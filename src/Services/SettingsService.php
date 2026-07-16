<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Services;

class SettingsService {
    /**
     * Get a setting from the opa_settings table.
     *
     * @param string $key The setting key.
     * @param mixed $default Default value if not found.
     * @return mixed
     */
    public static function get(string $key, $default = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'opa_settings';
        
        $row = $wpdb->get_row($wpdb->prepare("SELECT setting_value FROM {$table} WHERE setting_key = %s", $key));
        
        if ($row) {
            $val = json_decode($row->setting_value, true);
            if (json_last_error() === JSON_ERROR_NONE && (is_array($val) || is_object($val))) {
                return $val;
            }
            return $row->setting_value;
        }
        
        return $default;
    }

    /**
     * Update or insert a setting in the opa_settings table.
     *
     * @param string $key The setting key.
     * @param mixed $value The value to store (will be JSON encoded if array).
     * @return bool
     */
    public static function set(string $key, $value): bool {
        global $wpdb;
        $table = $wpdb->prefix . 'opa_settings';
        
        $val_str = is_array($value) || is_object($value) ? wp_json_encode($value) : (string)$value;
        
        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE setting_key = %s", $key));
        
        if ($exists) {
            $result = $wpdb->update(
                $table,
                ['setting_value' => $val_str],
                ['setting_key' => $key],
                ['%s'],
                ['%s']
            );
        } else {
            $result = $wpdb->insert(
                $table,
                ['setting_key' => $key, 'setting_value' => $val_str],
                ['%s', '%s']
            );
        }
        
        return $result !== false;
    }
}
