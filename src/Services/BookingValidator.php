<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Services;

use OpaReklama\Booking\Exceptions\ValidationException;

/**
 * Validates booking payloads with Zero Trust to frontend input.
 */
class BookingValidator {
    /**
     * Validate the incoming booking payload.
     *
     * @param array $payload
     * @return array Sanitized and validated data.
     * @throws ValidationException
     */
    public function validate( array $payload ): array {
        $validated = [];

        // Required fields
        $required = ['city_id', 'waste_type_id', 'container_id', 'booking_date', 'customer_email', 'customer_phone', 'address_line'];
        foreach ( $required as $field ) {
            if ( empty( $payload[ $field ] ) ) {
                throw new ValidationException( "Missing required field: {$field}", "ERR_MISSING_FIELD" );
            }
        }

        $validated['city_id'] = (int) $payload['city_id'];
        $validated['waste_type_id'] = (int) $payload['waste_type_id'];
        $validated['container_id'] = (int) $payload['container_id'];
        
        // Date validation
        $date = \DateTime::createFromFormat( 'Y-m-d', $payload['booking_date'] );
        if ( ! $date || $date->format( 'Y-m-d' ) !== $payload['booking_date'] ) {
            throw new ValidationException( "Invalid booking date format. Expected YYYY-MM-DD.", "ERR_INVALID_DATE" );
        }
        $validated['booking_date'] = $payload['booking_date'];

        // Email validation
        if ( ! is_email( $payload['customer_email'] ) ) {
            throw new ValidationException( "Invalid email address.", "ERR_INVALID_EMAIL" );
        }
        $validated['customer_email'] = sanitize_email( $payload['customer_email'] );

        $validated['customer_phone'] = sanitize_text_field( $payload['customer_phone'] );
        $validated['address_line']   = sanitize_text_field( $payload['address_line'] );

        // Idempotency token
        if ( empty( $payload['idempotency_key'] ) ) {
            throw new ValidationException( "Missing idempotency key.", "ERR_MISSING_IDEMPOTENCY" );
        }
        $validated['idempotency_key'] = sanitize_text_field( $payload['idempotency_key'] );

        return $validated;
    }
}
