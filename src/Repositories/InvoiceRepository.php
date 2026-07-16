<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Repositories;

class InvoiceRepository extends BaseRepository {
    protected function get_table_name(): string {
        return "invoices";
    }

    public function find( int $id, bool $include_archived = false ): ?object {
        $sql = $this->db->prepare( "SELECT * FROM {$this->table} WHERE id = %d", $id );
        $result = $this->db->get_row( $sql );
        return $result ? clone $result : null;
    }

    public function all( bool $include_archived = false ): array {
        return $this->db->get_results( "SELECT * FROM {$this->table}" );
    }
}
