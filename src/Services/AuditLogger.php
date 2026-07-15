<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Services;

class AuditLogger {
    private \wpdb $db;
    private string $table;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $this->db->prefix . 'opa_logs';
    }

    /**
     * Log an action to the audit trail.
     *
     * @param string $log_type   General category (e.g., 'master_data', 'booking')
     * @param string $action     Specific action (e.g., 'update', 'archive', 'create')
     * @param string $entity_type The type of entity (e.g., 'City', 'Container')
     * @param int|null $entity_id The ID of the affected entity
     * @param string $message    Human readable message
     * @param array $before      State of the entity before change
     * @param array $after       State of the entity after change
     */
    public function log(
        string $log_type,
        string $action,
        string $entity_type,
        ?int $entity_id,
        string $message,
        array $before = [],
        array $after = []
    ): void {
        $user_id = get_current_user_id();
        $user_info = get_userdata($user_id);
        $user_name = $user_info ? $user_info->user_login : 'System';
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

        $data = [
            'log_type' => $log_type,
            'action' => $action,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'reference_id' => $entity_id, // Legacy compatibility
            'user_id' => $user_id ?: null,
            'user_name' => $user_name,
            'ip_address' => $ip_address,
            'message' => $message,
            'before_json' => empty($before) ? null : wp_json_encode($before),
            'after_json' => empty($after) ? null : wp_json_encode($after),
            'payload' => null, // Legacy compatibility
            'created_at' => current_time('mysql', true)
        ];

        $format = [
            '%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
        ];

        $this->db->insert($this->table, $data, $format);
    }
}
