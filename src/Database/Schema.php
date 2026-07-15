<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Database;

/**
 * Defines the database schema for the Opa Booking Engine.
 */
class Schema {
    /**
     * Get the array of CREATE TABLE SQL statements.
     *
     * @return array<string>
     */
    public function get_tables(): array {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $prefix = $wpdb->prefix . 'opa_';

        return [
            // Settings Table
            "CREATE TABLE {$prefix}settings (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                setting_key varchar(100) NOT NULL,
                setting_value longtext NULL,
                PRIMARY KEY  (id),
                UNIQUE KEY setting_key (setting_key)
            ) $charset_collate;",

            // Cities Table
            "CREATE TABLE {$prefix}cities (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                name varchar(100) NOT NULL,
                postcode_regex varchar(255) NULL,
                status varchar(20) NOT NULL DEFAULT 'active',
                PRIMARY KEY  (id)
            ) $charset_collate;",

            // Waste Types Table
            "CREATE TABLE {$prefix}waste_types (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                title varchar(150) NOT NULL,
                description text NULL,
                allowed_items text NULL,
                forbidden_items text NULL,
                status varchar(20) NOT NULL DEFAULT 'active',
                PRIMARY KEY  (id)
            ) $charset_collate;",

            // Containers Table
            "CREATE TABLE {$prefix}containers (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                title varchar(100) NOT NULL,
                size varchar(50) NOT NULL,
                image_url varchar(255) NULL,
                status varchar(20) NOT NULL DEFAULT 'active',
                PRIMARY KEY  (id)
            ) $charset_collate;",

            // Service Rules (Pricing Engine)
            "CREATE TABLE {$prefix}service_rules (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                city_id bigint(20) unsigned NOT NULL,
                waste_type_id bigint(20) unsigned NOT NULL,
                container_id bigint(20) unsigned NOT NULL,
                base_price decimal(10,2) NOT NULL DEFAULT 0.00,
                status varchar(20) NOT NULL DEFAULT 'active',
                PRIMARY KEY  (id),
                UNIQUE KEY rule_mapping (city_id, waste_type_id, container_id)
            ) $charset_collate;",

            // Bookings Table
            "CREATE TABLE {$prefix}bookings (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                booking_uid varchar(32) NOT NULL,
                booking_number varchar(50) NOT NULL,
                city_id bigint(20) unsigned NOT NULL,
                waste_type_id bigint(20) unsigned NOT NULL,
                container_id bigint(20) unsigned NOT NULL,
                booking_date date NOT NULL,
                customer_email varchar(100) NOT NULL,
                customer_phone varchar(50) NOT NULL,
                address_line text NOT NULL,
                status varchar(20) NOT NULL DEFAULT 'pending',
                payment_status varchar(20) NOT NULL DEFAULT 'unpaid',
                total_price decimal(10,2) NOT NULL DEFAULT 0.00,
                created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (id),
                UNIQUE KEY booking_uid (booking_uid),
                UNIQUE KEY booking_number (booking_number),
                KEY booking_date (booking_date)
            ) $charset_collate;",

            // Invoices Table
            "CREATE TABLE {$prefix}invoices (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                booking_id bigint(20) unsigned NOT NULL,
                invoice_number varchar(50) NOT NULL,
                invoice_token varchar(64) NOT NULL,
                subtotal decimal(10,2) NOT NULL DEFAULT 0.00,
                tax_total decimal(10,2) NOT NULL DEFAULT 0.00,
                grand_total decimal(10,2) NOT NULL DEFAULT 0.00,
                snapshot_data longtext NULL,
                created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (id),
                UNIQUE KEY booking_id (booking_id),
                UNIQUE KEY invoice_number (invoice_number),
                UNIQUE KEY invoice_token (invoice_token)
            ) $charset_collate;",

            // Calendar Rules Table
            "CREATE TABLE {$prefix}calendar_rules (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                target_date date NOT NULL,
                reason varchar(255) NULL,
                status varchar(20) NOT NULL DEFAULT 'blocked',
                PRIMARY KEY  (id),
                UNIQUE KEY target_date (target_date)
            ) $charset_collate;",

            // Logs Table
            "CREATE TABLE {$prefix}logs (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                log_type varchar(50) NOT NULL,
                reference_id bigint(20) unsigned NULL,
                message text NOT NULL,
                payload longtext NULL,
                created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (id),
                KEY log_type (log_type),
                KEY reference_id (reference_id)
            ) $charset_collate;"
        ];
    }
}
