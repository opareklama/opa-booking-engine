<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Services;

use OpaReklama\Booking\Repositories\BookingRepository;
use OpaReklama\Booking\Exceptions\BookingException;

/**
 * Handles the transactional creation of a booking.
 */
class BookingService {
    private BookingRepository $booking_repo;
    private PricingService $pricing_service;

    public function __construct( BookingRepository $booking_repo, PricingService $pricing_service ) {
        $this->booking_repo = $booking_repo;
        $this->pricing_service = $pricing_service;
    }

    /**
     * Process a booking transaction.
     *
     * @param array $validated_payload
     * @return int The new booking ID.
     * @throws BookingException
     */
    public function create_booking( array $validated_payload ): int {
        global $wpdb;

        // 1. Idempotency Check (prevent duplicate submissions)
        $idempotency_key = $validated_payload['idempotency_key'];
        // In a full implementation, we would check a transient or dedicated idempotency table here.
        if ( get_transient( 'opa_idemp_' . $idempotency_key ) ) {
            throw new BookingException( "Duplicate booking request detected.", "ERR_DUPLICATE_BOOKING" );
        }
        
        // 2. Calculate authoritative price
        $total_price = $this->pricing_service->calculate_price(
            $validated_payload['city_id'],
            $validated_payload['waste_type_id'],
            $validated_payload['container_id']
        );

        // 3. Generate secure tokens
        $booking_uid = wp_generate_password( 32, false );
        $booking_number = 'OBE-' . date( 'Ymd' ) . '-' . strtoupper( substr( uniqid(), -6 ) );

        // 4. Start Database Transaction
        $wpdb->query( 'START TRANSACTION' );

        try {
            $booking_data = [
                'booking_uid'    => $booking_uid,
                'booking_number' => $booking_number,
                'city_id'        => $validated_payload['city_id'],
                'waste_type_id'  => $validated_payload['waste_type_id'],
                'container_id'   => $validated_payload['container_id'],
                'booking_date'   => $validated_payload['booking_date'],
                'customer_email' => $validated_payload['customer_email'],
                'customer_phone' => $validated_payload['customer_phone'],
                'address_line'   => $validated_payload['address_line'],
                'status'         => 'pending',
                'payment_status' => 'unpaid',
                'total_price'    => $total_price,
            ];

            $booking_id = $this->booking_repo->insert( $booking_data, [
                '%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%f'
            ] );

            // 5. Commit Transaction
            $wpdb->query( 'COMMIT' );
            
            // Lock this idempotency key for 24 hours
            set_transient( 'opa_idemp_' . $idempotency_key, $booking_id, DAY_IN_SECONDS );

            return $booking_id;
            
        } catch ( \Exception $e ) {
            // Rollback on any failure
            $wpdb->query( 'ROLLBACK' );
            throw new BookingException( "Failed to create booking. Transaction rolled back.", "ERR_TRANSACTION_FAILED", 0, $e );
        }
    }
}
