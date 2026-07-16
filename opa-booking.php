<?php
/**
 * Plugin Name: Opa Booking Engine
 * Plugin URI:  https://opareklama.lt/booking-engine
 * Description: Enterprise-grade Booking Engine for Opa Reklama.
 * Version:     1.0.1
 * Author:      Opa Reklama
 * Author URI:  https://opareklama.lt
 * License:     GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: opa-booking
 * Domain Path: /languages
 */

declare(strict_types=1);

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Minimum Version Requirements
if ( ! defined( 'OPA_BOOKING_MIN_PHP_VERSION' ) ) {
    define( 'OPA_BOOKING_MIN_PHP_VERSION', '8.0' );
}
if ( ! defined( 'OPA_BOOKING_MIN_WP_VERSION' ) ) {
    define( 'OPA_BOOKING_MIN_WP_VERSION', '6.0' );
}

// Define Plugin Constants
if ( ! defined( 'OPA_BOOKING_VERSION' ) ) {
    define( 'OPA_BOOKING_VERSION', '1.0.1' );
}
if ( ! defined( 'OPA_BOOKING_PLUGIN_DIR' ) ) {
    define( 'OPA_BOOKING_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'OPA_BOOKING_PLUGIN_URL' ) ) {
    define( 'OPA_BOOKING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'OPA_BOOKING_PLUGIN_BASENAME' ) ) {
    define( 'OPA_BOOKING_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

// Validate PHP Version
if ( version_compare( PHP_VERSION, OPA_BOOKING_MIN_PHP_VERSION, '<' ) ) {
    add_action( 'admin_notices', function () {
        echo '<div class="notice notice-error"><p>' . esc_html( sprintf( __( 'Opa Booking Engine requires PHP version %s or higher.', 'opa-booking' ), OPA_BOOKING_MIN_PHP_VERSION ) ) . '</p></div>';
    } );
    return;
}

// Validate WordPress Version
global $wp_version;
if ( version_compare( $wp_version, OPA_BOOKING_MIN_WP_VERSION, '<' ) ) {
    add_action( 'admin_notices', function () {
        echo '<div class="notice notice-error"><p>' . esc_html( sprintf( __( 'Opa Booking Engine requires WordPress version %s or higher.', 'opa-booking' ), OPA_BOOKING_MIN_WP_VERSION ) ) . '</p></div>';
    } );
    return;
}

// Require Autoloader
if ( file_exists( OPA_BOOKING_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
    require_once OPA_BOOKING_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    add_action( 'admin_notices', function () {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'Opa Booking Engine requires Composer to be installed. Please run `composer install` in the plugin directory.', 'opa-booking' ) . '</p></div>';
    } );
    return;
}

use OpaReklama\Booking\Bootstrap\Application;

// Activation and Deactivation Hooks
register_activation_hook( __FILE__, [ Application::class, 'activate' ] );
register_deactivation_hook( __FILE__, [ Application::class, 'deactivate' ] );

// Bootstrap the Application
Application::boot();

// Initialize GitHub Update Checker
require_once __DIR__ . '/vendor/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
	'https://github.com/opareklama/opa-booking-engine',
	__FILE__,
	'opa-booking-engine'
);


