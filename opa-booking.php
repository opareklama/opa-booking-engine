<?php
/**
 * Plugin Name: Opa Booking Engine
 * Plugin URI: https://github.com/opareklama/opa-booking-engine
 * Description: Enterprise-grade Booking Engine for Opa Reklama.
 * Version: 1.0.0
 * Author: Opa Reklama
 * Author URI: https://opareklama.com
 * License: Proprietary
 * Text Domain: opa-booking
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define Plugin Constants
define( 'OPA_BOOKING_VERSION', '1.0.0' );
define( 'OPA_BOOKING_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'OPA_BOOKING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'OPA_BOOKING_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Require Autoloader
if ( file_exists( OPA_BOOKING_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
    require_once OPA_BOOKING_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    // If autoloader is missing, do not crash the site, just notify admin.
    add_action( 'admin_notices', function () {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'Opa Booking Engine requires Composer to be installed. Please run `composer install` in the plugin directory.', 'opa-booking' ) . '</p></div>';
    } );
    return;
}

// Bootstrap the Application
use OpaReklama\Booking\Bootstrap\Application;

/**
 * Begins execution of the plugin.
 */
function run_opa_booking_engine() {
    $app = new Application();
    $app->run();
}

run_opa_booking_engine();
