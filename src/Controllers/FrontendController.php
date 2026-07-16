<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Controllers;

class FrontendController {
    public function register_shortcodes(): void {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_shortcode( "opa_booking_engine", [ $this, "render_booking_form" ] );
        
        // This is already running on 'init', so just execute it directly
        $this->handle_invoice_download();
    }

    public function register_assets(): void {
        wp_enqueue_style(
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
                    $options->set('defaultFont', 'DejaVu Sans');
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
            'nonce'    => wp_create_nonce('opa_frontend_nonce'),
            'tax_rate' => (float) get_option('opa_tax_rate', 0),
            'default_city' => absint( get_option('opa_default_city', 0) ),
            'terms_html' => get_option( 'opa_terms_html', '<a href="/taisykles-ir-salygos/" target="_blank">Sutinku su taisyklėmis</a>' ),
            'availability_rules' => \OpaReklama\Booking\Services\AvailabilityEngine::getFrontendRules(),
            'i18n'     => [
                'err_no_city' => __('Nepavyko nustatyti miesto pagal pasirinktą adresą. Prašome pasirinkti rankiniu būdu.', 'opa-booking'),
                'err_no_service' => __('Atsiprašome, šiuo metu neaptarnaujame šios vietovės:', 'opa-booking'),
                'select_city_first' => __('Pirmiausia pasirinkite miestą.', 'opa-booking'),
                'select_waste_first' => __('Pirmiausia pasirinkite atliekų tipą.', 'opa-booking'),
                'loading_options' => __('Įkeliamos parinktys...', 'opa-booking'),
                'details' => __('Išsami informacija', 'opa-booking'),
                'no_waste' => __('Šioje vietoje nėra atliekų tipų.', 'opa-booking'),
                'loading_containers' => __('Įkeliami konteineriai...', 'opa-booking'),
                'volume' => __('Tūris:', 'opa-booking'),
                'no_containers' => __('Konteinerių nėra.', 'opa-booking'),
                'container_details' => __('Konteinerio informacija', 'opa-booking'),
                'waste_rules_for' => __('Atliekų taisyklės:', 'opa-booking'),
                'standard_rules' => __('Taikomos standartinės atliekų šalinimo taisyklės.', 'opa-booking'),
                'order_details' => __('Užsakymo informacija', 'opa-booking'),
                'fully_booked' => __('Pilnai užsakyta', 'opa-booking'),
                'complete_all' => __('Prieš pateikdami užpildykite visus skyrius.', 'opa-booking'),
                'processing' => __('Apdorojama...', 'opa-booking'),
                'confirm_booking' => __('Patvirtinti užsakymą', 'opa-booking'),
                'error_occured' => __('Įvyko klaida.', 'opa-booking'),
                'network_error' => __('Tinklo klaida.', 'opa-booking'),
                'months' => [
                    __('Sausis', 'opa-booking'), __('Vasaris', 'opa-booking'), __('Kovas', 'opa-booking'),
                    __('Balandis', 'opa-booking'), __('Gegužė', 'opa-booking'), __('Birželis', 'opa-booking'),
                    __('Liepa', 'opa-booking'), __('Rugpjūtis', 'opa-booking'), __('Rugsėjis', 'opa-booking'),
                    __('Spalis', 'opa-booking'), __('Lapkritis', 'opa-booking'), __('Gruodis', 'opa-booking')
                ]
            ]
        ]);

        ob_start();
        require OPA_BOOKING_PLUGIN_DIR . "views/frontend/booking-form.php";
        return ob_get_clean();
    }
}
