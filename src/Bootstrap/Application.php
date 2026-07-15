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
    }

    /**
     * Plugin activation hook callback.
     */
    public static function activate(): void {
        // Will trigger database migrations and flush rewrite rules.
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
        // e.g. add_action( 'admin_menu', ... )
    }

    /**
     * Register all of the hooks related to the public-facing functionality.
     */
    private function define_public_hooks(): void {
        // e.g. add_shortcode( 'opa_booking_engine', ... )
    }
}
