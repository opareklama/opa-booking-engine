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
}
