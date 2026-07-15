<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Repositories;

use wpdb;
use OpaReklama\Booking\Exceptions\DatabaseException;

/**
 * Base Repository class to handle core database interactions.
 */
abstract class BaseRepository {
    protected wpdb $db;
    protected string $table;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $this->db->prefix . 'opa_' . $this->get_table_name();
    }

    /**
     * Get the base name of the table (without prefix).
     *
     * @return string
     */
    abstract protected function get_table_name(): string;

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @param bool $include_archived Whether to include archived records
     * @return object|null
     */
    public function find( int $id, bool $include_archived = false ): ?object {
        if ($include_archived) {
            $sql = $this->db->prepare( "SELECT * FROM {$this->table} WHERE id = %d", $id );
        } else {
            $sql = $this->db->prepare( "SELECT * FROM {$this->table} WHERE id = %d AND status != 'archived'", $id );
        }
        $result = $this->db->get_row( $sql );
        return $result ? clone $result : null;
    }

    /**
     * Get all records.
     *
     * @param bool $include_archived Whether to include archived records
     * @return array
     */
    public function all( bool $include_archived = false ): array {
        if ($include_archived) {
            return $this->db->get_results( "SELECT * FROM {$this->table}" );
        }
        return $this->db->get_results( "SELECT * FROM {$this->table} WHERE status != 'archived'" );
    }

    /**
     * Insert a new record.
     *
     * @param array $data
     * @param array $format
     * @return int The ID of the inserted record.
     * @throws DatabaseException
     */
    public function insert( array $data, array $format = [] ): int {
        $inserted = $this->db->insert( $this->table, $data, $format );
        if ( false === $inserted ) {
            throw new DatabaseException( "Failed to insert record into {$this->table}." );
        }
        return $this->db->insert_id;
    }

    /**
     * Update an existing record.
     *
     * @param int   $id
     * @param array $data
     * @param array $format
     * @return bool
     * @throws DatabaseException
     */
    public function update( int $id, array $data, array $format = [] ): bool {
        $updated = $this->db->update( $this->table, $data, ['id' => $id], $format, ['%d'] );
        if ( false === $updated ) {
            throw new DatabaseException( "Failed to update record ID {$id} in {$this->table}." );
        }
        return true;
    }

    /**
     * Delete a record (Soft Delete).
     *
     * @param int $id
     * @return bool
     * @throws DatabaseException
     */
    public function delete( int $id ): bool {
        $user_id = get_current_user_id();
        $data = [
            'status' => 'archived',
            'archived_at' => current_time('mysql', true), // UTC time
            'archived_by' => $user_id ?: null
        ];
        $format = ['%s', '%s', '%d'];

        $updated = $this->db->update( $this->table, $data, ['id' => $id], $format, ['%d'] );
        if ( false === $updated ) {
            throw new DatabaseException( "Failed to archive record ID {$id} in {$this->table}." );
        }
        return true;
    }

    /**
     * Hard Delete a record (Danger: only use for cleanup).
     *
     * @param int $id
     * @return bool
     * @throws DatabaseException
     */
    public function force_delete( int $id ): bool {
        $deleted = $this->db->delete( $this->table, ['id' => $id], ['%d'] );
        if ( false === $deleted ) {
            throw new DatabaseException( "Failed to force delete record ID {$id} in {$this->table}." );
        }
        return true;
    }
}
