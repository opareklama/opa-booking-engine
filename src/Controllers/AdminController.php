<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Controllers;

/**
 * Handles WordPress Admin Menu registration and rendering.
 */
class AdminController {

    public function __construct() {
        add_action('admin_menu', [$this, 'register_menus']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

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
            __( 'Dashboard', 'opa-booking' ),
            __( 'Dashboard', 'opa-booking' ),
            'manage_options',
            'opa-booking',
            [ $this, 'render_dashboard' ]
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

    public function enqueue_assets(string $hook): void {
        // Only load on our plugin's pages
        if ( strpos( $hook, 'opa-booking' ) === false ) {
            return;
        }

        // Native WP Media Uploader
        wp_enqueue_media();

        // Design System CSS
        wp_enqueue_style(
            'opa-admin-design-system',
            plugin_dir_url( dirname(__DIR__) ) . 'assets/admin/css/admin-design-system.css',
            [],
            '1.0.0'
        );

        // Design System JS
        wp_enqueue_script(
            'opa-admin-design-system',
            plugin_dir_url( dirname(__DIR__) ) . 'assets/admin/js/admin-design-system.js',
            [],
            '1.0.0',
            true
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
