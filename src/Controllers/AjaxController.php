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
}
