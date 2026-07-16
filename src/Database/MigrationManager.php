<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Database;

/**
 * Handles database migrations and schema installation.
 * Uses WordPress dbDelta to safely create and update custom tables.
 */
class MigrationManager {
    /**
     * Run the database migrations.
     */
    public function migrate(): void {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $schema = new Schema();
        $tables = $schema->get_tables();

        foreach ( $tables as $sql ) {
            dbDelta( $sql );
        }

        global $wpdb;
        $table = $wpdb->prefix . 'opa_bookings';
        $column = 'delivery_notes';
        $result = $wpdb->get_results("SHOW COLUMNS FROM `$table` LIKE '$column'");
        if (empty($result)) {
            $wpdb->query("ALTER TABLE `$table` ADD COLUMN `$column` longtext NULL AFTER `address_line`");
        }

        // Update the installed version in options
        update_option( 'opa_booking_db_version', OPA_BOOKING_VERSION );
    }
}
