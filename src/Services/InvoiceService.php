<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Services;

use OpaReklama\Booking\Repositories\InvoiceRepository;

class InvoiceService {
    private InvoiceRepository $invoice_repo;

    public function __construct( InvoiceRepository $invoice_repo ) {
        $this->invoice_repo = $invoice_repo;
    }

    public function generate_invoice( int $booking_id, float $total_price ): int {
        // Logic to generate invoice number, snapshot company data, and save
        $invoice_number = "INV-" . date("Y") . "-" . str_pad((string)$booking_id, 4, "0", STR_PAD_LEFT);
        $invoice_token = wp_generate_password( 64, false );
        
        $snapshot = [
            "company_name" => get_option("opa_company_name", "Opa Reklama"),
            "company_logo" => get_option("opa_company_logo", ""),
            "company_address" => get_option("opa_company_address", ""),
            "vat_number" => get_option("opa_vat_number", "")
        ];

        return $this->invoice_repo->insert([
            "booking_id" => $booking_id,
            "invoice_number" => $invoice_number,
            "invoice_token" => $invoice_token,
            "subtotal" => $total_price,
            "tax_total" => 0.00,
            "grand_total" => $total_price,
            "snapshot_data" => wp_json_encode( $snapshot )
        ], ["%d", "%s", "%s", "%f", "%f", "%f", "%s"]);
    }
}
