<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Controllers;

class FrontendController {
    public function register_shortcodes(): void {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_shortcode( "opa_booking_engine", [ $this, "render_booking_form" ] );
        add_action( 'init', [ $this, 'handle_invoice_download' ] );
    }

    public function register_assets(): void {
        wp_register_style(
            'opa-booking-wizard',
            OPA_BOOKING_PLUGIN_URL . 'assets/frontend/css/booking-wizard.css',
            [],
            time()
        );

        wp_register_script(
            'opa-booking-wizard',
            OPA_BOOKING_PLUGIN_URL . 'assets/frontend/js/booking-wizard.js',
            ['jquery'],
            time(),
            true // Load in footer
        );
    }

    public function handle_invoice_download(): void {
        if ( isset( $_GET['opa_invoice'] ) && ! empty( $_GET['opa_invoice'] ) ) {
            $token = sanitize_text_field( $_GET['opa_invoice'] );
            $format = isset( $_GET['format'] ) ? sanitize_text_field( $_GET['format'] ) : 'html';
            
            global $wpdb;
            $invoice = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}opa_invoices WHERE invoice_token = %s", $token ) );
            
            if ( $invoice ) {
                $booking = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}opa_bookings WHERE id = %d", $invoice->booking_id ) );
                
                // Build HTML
                ob_start();
                require OPA_BOOKING_PLUGIN_DIR . "views/frontend/live-invoice.php";
                $html = ob_get_clean();
                
                if ( $format === 'pdf' ) {
                    $options = new \Dompdf\Options();
                    $options->set('defaultFont', 'Courier');
                    $dompdf = new \Dompdf\Dompdf($options);
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();
                    
                    // Attachment => true forces download
                    $dompdf->stream("invoice-{$invoice->invoice_number}.pdf", ["Attachment" => true]);
                    exit;
                } else {
                    // Show HTML Preview
                    echo $html;
                    exit;
                }
            } else {
                wp_die("Invoice not found or invalid token.");
            }
        }
    }

    public function render_booking_form( $atts ): string {
        wp_enqueue_style('opa-booking-wizard');
        wp_enqueue_script('opa-booking-wizard');
        
        wp_localize_script('opa-booking-wizard', 'opaBookingObj', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('opa_frontend_nonce')
        ]);

        ob_start();
        require OPA_BOOKING_PLUGIN_DIR . "views/frontend/booking-form.php";
        return ob_get_clean();
    }
}
