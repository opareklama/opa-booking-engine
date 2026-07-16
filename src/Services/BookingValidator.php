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
        $required = ['city_id', 'waste_type_id', 'container_id', 'booking_date', 'customer_email', 'customer_phone', 'address_line', 'customer_name', 'customer_type'];
        foreach ( $required as $field ) {
            if ( empty( $payload[ $field ] ) ) {
                throw new ValidationException( "Missing required field: {$field}", "ERR_MISSING_FIELD" );
            }
        }
        
        if ( empty( $payload['terms_accepted'] ) ) {
            throw new ValidationException( "You must accept the terms and conditions.", "ERR_TERMS_NOT_ACCEPTED" );
        }

        $validated['city_id'] = (int) $payload['city_id'];
        $validated['waste_type_id'] = (int) $payload['waste_type_id'];
        $validated['container_id'] = (int) $payload['container_id'];
        
        $validated['customer_type'] = in_array( $payload['customer_type'], ['natural', 'legal'] ) ? $payload['customer_type'] : 'natural';
        $validated['customer_name'] = sanitize_text_field( $payload['customer_name'] );
        
        if ( $validated['customer_type'] === 'legal' ) {
            if ( empty( $payload['company_code'] ) || empty( $payload['person_in_charge'] ) ) {
                throw new ValidationException( "Company code and person in charge are required for legal entities.", "ERR_MISSING_LEGAL_FIELD" );
            }
            $validated['company_code'] = sanitize_text_field( $payload['company_code'] );
            $validated['person_in_charge'] = sanitize_text_field( $payload['person_in_charge'] );
        } else {
            $validated['company_code'] = null;
            $validated['person_in_charge'] = null;
        }
        
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
