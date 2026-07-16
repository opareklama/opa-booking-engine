<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Bootstrap;

/**
 * Main application bootstrap class.
 * Responsible for initializing the plugin and registering services.
 */
class Application {

    /**
     * Boot the application.
     */
    public static function boot(): void {
        $app = new self();
        $app->run();
    }

    /**
     * Run the application.
     */
    public function run(): void {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_ajax_hooks();
    }

    /**
     * Plugin activation hook callback.
     */
    public static function activate(): void {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        
        $migration = new \OpaReklama\Booking\Database\MigrationManager();
        $migration->migrate();

        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation hook callback.
     */
    public static function deactivate(): void {
        // Will flush rewrite rules or clean up scheduled crons.
    }

    /**
     * @var Container
     */
    private Container $container;

    /**
     * Load all required dependencies.
     */
    private function load_dependencies(): void {
        $this->container = new Container();
        $this->container->singleton( self::class, $this );
        $this->container->singleton( Container::class, $this->container );
    }

    /**
     * Register all of the hooks related to the admin area functionality.
     */
    private function define_admin_hooks(): void {
        $admin_controller = $this->container->get( \OpaReklama\Booking\Controllers\AdminController::class );
        add_action( 'admin_menu', [ $admin_controller, 'register_menus' ] );
    }

    private function define_public_hooks(): void {
        $frontend_controller = $this->container->get( \OpaReklama\Booking\Controllers\FrontendController::class );
        add_action( 'init', [ $frontend_controller, 'register_shortcodes' ] );
        add_action( 'init', function() {
            load_plugin_textdomain( 'opa-booking', false, dirname( plugin_basename( OPA_BOOKING_PLUGIN_DIR . 'opa-booking-engine.php' ) ) . '/languages' );
        } );
    }

    /**
     * Register all AJAX hooks.
     */
    private function define_ajax_hooks(): void {
        $ajax_controller = $this->container->get( \OpaReklama\Booking\Controllers\AjaxController::class );
        $ajax_controller->register_hooks();
    }
}
