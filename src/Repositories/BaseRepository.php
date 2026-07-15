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
     * @return object|null
     */
    public function find( int $id ): ?object {
        $sql = $this->db->prepare( "SELECT * FROM {$this->table} WHERE id = %d", $id );
        $result = $this->db->get_row( $sql );
        return $result ? clone $result : null;
    }

    /**
     * Get all records.
     *
     * @return array
     */
    public function all(): array {
        return $this->db->get_results( "SELECT * FROM {$this->table}" );
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
     * Delete a record. Note: Hard deletes should generally be avoided per business rules,
     * but this method exists for exceptional cases.
     *
     * @param int $id
     * @return bool
     * @throws DatabaseException
     */
    public function delete( int $id ): bool {
        $deleted = $this->db->delete( $this->table, ['id' => $id], ['%d'] );
        if ( false === $deleted ) {
            throw new DatabaseException( "Failed to delete record ID {$id} in {$this->table}." );
        }
        return true;
    }
}
