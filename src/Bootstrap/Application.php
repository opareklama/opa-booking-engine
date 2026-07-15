<?php
namespace OpaReklama\Booking\Bootstrap;

/**
 * Main application bootstrap class.
 * Responsible for initializing the plugin and registering services.
 */
class Application {

    /**
     * Initializes the plugin.
     */
    public function run(): void {
        // Here we will eventually initialize the DI Container
        // and load hooks for Admin, API, Frontend, etc.
        
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load all required dependencies.
     */
    private function load_dependencies(): void {
        // Will initialize Container here in future milestones
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
