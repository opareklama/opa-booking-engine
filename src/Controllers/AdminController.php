<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Controllers;

/**
 * Handles WordPress Admin Menu registration and rendering.
 */
class AdminController {

    public function register_menus(): void {
        add_menu_page(
            __( 'Opa Booking Engine', 'opa-booking' ),
            __( 'Opa Booking', 'opa-booking' ),
            'manage_options',
            'opa-booking',
            [ $this, 'render_dashboard' ],
            'dashicons-calendar-alt',
            30
        );

        add_submenu_page(
            'opa-booking',
            __( 'Master Data', 'opa-booking' ),
            __( 'Master Data', 'opa-booking' ),
            'manage_options',
            'opa-booking-master-data',
            [ $this, 'render_master_data' ]
        );

        add_submenu_page(
            'opa-booking',
            __( 'Pricing Rules', 'opa-booking' ),
            __( 'Pricing Rules', 'opa-booking' ),
            'manage_options',
            'opa-booking-pricing',
            [ $this, 'render_pricing_rules' ]
        );

        add_submenu_page(
            'opa-booking',
            __( 'Settings', 'opa-booking' ),
            __( 'Settings', 'opa-booking' ),
            'manage_options',
            'opa-booking-settings',
            [ $this, 'render_settings' ]
        );
    }

    public function render_dashboard(): void {
        require_once OPA_BOOKING_PLUGIN_DIR . 'views/admin/dashboard.php';
    }

    public function render_master_data(): void {
        require_once OPA_BOOKING_PLUGIN_DIR . 'views/admin/master-data.php';
    }

    public function render_pricing_rules(): void {
        require_once OPA_BOOKING_PLUGIN_DIR . 'views/admin/pricing-rules.php';
    }

    public function render_settings(): void {
        require_once OPA_BOOKING_PLUGIN_DIR . 'views/admin/settings.php';
    }
}
