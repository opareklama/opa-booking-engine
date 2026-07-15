<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Controllers;

use OpaReklama\Booking\Repositories\CityRepository;
use OpaReklama\Booking\Services\SecurityService;

/**
 * Handles WordPress AJAX requests.
 */
class AjaxController {
    private CityRepository $city_repo;
    private SecurityService $security;

    public function __construct( CityRepository $city_repo, SecurityService $security ) {
        $this->city_repo = $city_repo;
        $this->security = $security;
    }

    public function register_hooks(): void {
        add_action( 'wp_ajax_opa_add_city', [ $this, 'ajax_add_city' ] );
        add_action( 'wp_ajax_opa_get_cities', [ $this, 'ajax_get_cities' ] );

        add_action( 'wp_ajax_opa_add_waste_type', [ $this, 'ajax_add_waste_type' ] );
        add_action( 'wp_ajax_opa_get_waste_types', [ $this, 'ajax_get_waste_types' ] );

        add_action( 'wp_ajax_opa_add_container', [ $this, 'ajax_add_container' ] );
        add_action( 'wp_ajax_opa_get_containers', [ $this, 'ajax_get_containers' ] );

        add_action( 'wp_ajax_opa_add_pricing_rule', [ $this, 'ajax_add_pricing_rule' ] );
        add_action( 'wp_ajax_opa_get_pricing_rules', [ $this, 'ajax_get_pricing_rules' ] );

        // Frontend Hooks
        add_action( 'wp_ajax_nopriv_opa_front_get_cities', [ $this, 'ajax_front_get_cities' ] );
        add_action( 'wp_ajax_opa_front_get_cities', [ $this, 'ajax_front_get_cities' ] );

        add_action( 'wp_ajax_nopriv_opa_front_get_waste', [ $this, 'ajax_front_get_waste' ] );
        add_action( 'wp_ajax_opa_front_get_waste', [ $this, 'ajax_front_get_waste' ] );

        add_action( 'wp_ajax_nopriv_opa_front_get_containers', [ $this, 'ajax_front_get_containers' ] );
        add_action( 'wp_ajax_opa_front_get_containers', [ $this, 'ajax_front_get_containers' ] );

        add_action( 'wp_ajax_nopriv_opa_front_get_price', [ $this, 'ajax_front_get_price' ] );
        add_action( 'wp_ajax_opa_front_get_price', [ $this, 'ajax_front_get_price' ] );

        add_action( 'wp_ajax_nopriv_opa_front_submit_booking', [ $this, 'ajax_front_submit_booking' ] );
        add_action( 'wp_ajax_opa_front_submit_booking', [ $this, 'ajax_front_submit_booking' ] );
    }

    public function ajax_add_city(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }

            $name = sanitize_text_field( $_POST['city_name'] ?? '' );
            if ( empty( $name ) ) {
                wp_send_json_error( 'City name is required.' );
            }

            $id = $this->city_repo->insert([
                'name' => $name,
                'status' => 'active'
            ], ['%s', '%s']);

            wp_send_json_success([
                'id' => $id,
                'name' => $name,
                'status' => 'active'
            ]);

        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_get_cities(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }

            $cities = $this->city_repo->all();
            wp_send_json_success( $cities );

        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    // --- Waste Types ---
    
    public function ajax_get_waste_types(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            $repo = new \OpaReklama\Booking\Repositories\WasteTypeRepository();
            wp_send_json_success( $repo->all() );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_add_waste_type(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            $title = sanitize_text_field( $_POST['title'] ?? '' );
            if ( empty( $title ) ) {
                wp_send_json_error( 'Title is required.' );
            }
            $repo = new \OpaReklama\Booking\Repositories\WasteTypeRepository();
            $id = $repo->insert(['title' => $title, 'status' => 'active'], ['%s', '%s']);
            wp_send_json_success([ 'id' => $id, 'title' => $title, 'status' => 'active' ]);
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    // --- Containers ---

    public function ajax_get_containers(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            $repo = new \OpaReklama\Booking\Repositories\ContainerRepository();
            wp_send_json_success( $repo->all() );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_add_container(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            $title = sanitize_text_field( $_POST['title'] ?? '' );
            $size = sanitize_text_field( $_POST['size'] ?? '' );
            if ( empty( $title ) || empty( $size ) ) {
                wp_send_json_error( 'Title and Size are required.' );
            }
            $repo = new \OpaReklama\Booking\Repositories\ContainerRepository();
            $id = $repo->insert(['title' => $title, 'size' => $size, 'status' => 'active'], ['%s', '%s', '%s']);
            wp_send_json_success([ 'id' => $id, 'title' => $title, 'size' => $size, 'status' => 'active' ]);
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }
    // --- Pricing Rules (Service Rules) ---

    public function ajax_get_pricing_rules(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            
            global $wpdb;
            $sql = "SELECT r.id, c.name as city, w.title as waste_type, cont.title as container, r.base_price, r.status 
                    FROM {$wpdb->prefix}opa_service_rules r
                    JOIN {$wpdb->prefix}opa_cities c ON r.city_id = c.id
                    JOIN {$wpdb->prefix}opa_waste_types w ON r.waste_type_id = w.id
                    JOIN {$wpdb->prefix}opa_containers cont ON r.container_id = cont.id
                    ORDER BY r.id DESC";
            
            $rules = $wpdb->get_results( $sql );
            wp_send_json_success( $rules );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_add_pricing_rule(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            
            $city_id = (int) ( $_POST['city_id'] ?? 0 );
            $waste_type_id = (int) ( $_POST['waste_type_id'] ?? 0 );
            $container_id = (int) ( $_POST['container_id'] ?? 0 );
            $base_price = (float) ( $_POST['base_price'] ?? 0.0 );
            
            if ( ! $city_id || ! $waste_type_id || ! $container_id || ! $base_price ) {
                wp_send_json_error( 'All fields are required.' );
            }
            
            $repo = new \OpaReklama\Booking\Repositories\ServiceRuleRepository();
            $id = $repo->insert([
                'city_id' => $city_id,
                'waste_type_id' => $waste_type_id,
                'container_id' => $container_id,
                'base_price' => $base_price,
                'status' => 'active'
            ], ['%d', '%d', '%d', '%f', '%s']);
            
            wp_send_json_success( ['id' => $id] );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    // --- Frontend Public Endpoints ---

    public function ajax_front_get_cities(): void {
        try {
            global $wpdb;
            $cities = $wpdb->get_results( "SELECT id, name FROM {$wpdb->prefix}opa_cities WHERE status = 'active' ORDER BY name ASC" );
            wp_send_json_success( $cities );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_front_get_waste(): void {
        try {
            global $wpdb;
            $city_id = (int) ( $_GET['city_id'] ?? 0 );
            $sql = $wpdb->prepare( "
                SELECT DISTINCT w.id, w.title 
                FROM {$wpdb->prefix}opa_waste_types w
                JOIN {$wpdb->prefix}opa_service_rules r ON w.id = r.waste_type_id
                WHERE r.city_id = %d AND r.status = 'active' AND w.status = 'active'
                ORDER BY w.title ASC
            ", $city_id );
            
            wp_send_json_success( $wpdb->get_results( $sql ) );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_front_get_containers(): void {
        try {
            global $wpdb;
            $city_id = (int) ( $_GET['city_id'] ?? 0 );
            $waste_id = (int) ( $_GET['waste_id'] ?? 0 );
            $sql = $wpdb->prepare( "
                SELECT DISTINCT c.id, c.title, c.size 
                FROM {$wpdb->prefix}opa_containers c
                JOIN {$wpdb->prefix}opa_service_rules r ON c.id = r.container_id
                WHERE r.city_id = %d AND r.waste_type_id = %d AND r.status = 'active' AND c.status = 'active'
                ORDER BY c.title ASC
            ", $city_id, $waste_id );
            
            wp_send_json_success( $wpdb->get_results( $sql ) );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_front_get_price(): void {
        try {
            global $wpdb;
            $city_id = (int) ( $_GET['city_id'] ?? 0 );
            $waste_id = (int) ( $_GET['waste_id'] ?? 0 );
            $container_id = (int) ( $_GET['container_id'] ?? 0 );
            
            $sql = $wpdb->prepare( "
                SELECT base_price FROM {$wpdb->prefix}opa_service_rules 
                WHERE city_id = %d AND waste_type_id = %d AND container_id = %d AND status = 'active'
            ", $city_id, $waste_id, $container_id );
            
            $price = $wpdb->get_var( $sql );
            if ( $price === null ) {
                wp_send_json_error( 'Pricing not found' );
            }
            wp_send_json_success( ['price' => number_format( (float) $price, 2 )] );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_front_submit_booking(): void {
        try {
            if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'opa_frontend_nonce' ) ) {
                wp_send_json_error( 'Session expired. Please refresh the page.' );
            }
            
            // Honeypot check
            if ( ! empty( $_POST['opa_website'] ) ) {
                wp_send_json_error( 'Spam detected.' );
            }
            
            $repo = new \OpaReklama\Booking\Repositories\BookingRepository();
            $rule_repo = new \OpaReklama\Booking\Repositories\ServiceRuleRepository();
            $pricing_service = new \OpaReklama\Booking\Services\PricingService( $rule_repo );
            $booking_service = new \OpaReklama\Booking\Services\BookingService( $repo, $pricing_service );
            $validator = new \OpaReklama\Booking\Services\BookingValidator();
            
            $validated = $validator->validate( $_POST );
            $booking_id = $booking_service->create_booking( $validated );
            
            $invoice_repo = new \OpaReklama\Booking\Repositories\InvoiceRepository();
            $invoice_service = new \OpaReklama\Booking\Services\InvoiceService( $invoice_repo );
            $booking_data = $repo->find( $booking_id );
            
            $invoice_service->generate_invoice( $booking_id, (float) $booking_data->total_price );
            
            wp_send_json_success( ['booking_number' => $booking_data->booking_number] );
            
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }
}
