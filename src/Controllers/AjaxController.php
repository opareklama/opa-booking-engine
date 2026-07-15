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
        add_action( 'wp_ajax_opa_save_city', [ $this, 'ajax_save_city' ] );
        add_action( 'wp_ajax_opa_get_cities', [ $this, 'ajax_get_cities' ] );

        add_action( 'wp_ajax_opa_save_waste_type', [ $this, 'ajax_save_waste_type' ] );
        add_action( 'wp_ajax_opa_get_waste_types', [ $this, 'ajax_get_waste_types' ] );

        add_action( 'wp_ajax_opa_save_container', [ $this, 'ajax_save_container' ] );
        add_action( 'wp_ajax_opa_get_containers', [ $this, 'ajax_get_containers' ] );

        add_action( 'wp_ajax_opa_save_pricing_rule', [ $this, 'ajax_save_pricing_rule' ] );
        add_action( 'wp_ajax_opa_get_pricing_rules', [ $this, 'ajax_get_pricing_rules' ] );
        add_action( 'wp_ajax_opa_archive_pricing_rule', [ $this, 'ajax_archive_pricing_rule' ] );
        add_action( 'wp_ajax_opa_delete_pricing_rule', [ $this, 'ajax_delete_pricing_rule' ] );

        // Bookings Management
        add_action( 'wp_ajax_opa_get_bookings', [ $this, 'ajax_get_bookings' ] );
        add_action( 'wp_ajax_opa_update_booking_status', [ $this, 'ajax_update_booking_status' ] );

        // Frontend Hooks
        add_action( 'wp_ajax_nopriv_opa_front_get_cities', [ $this, 'ajax_front_get_cities' ] );
        add_action( 'wp_ajax_opa_front_get_cities', [ $this, 'ajax_front_get_cities' ] );

        add_action( 'wp_ajax_nopriv_opa_front_get_waste', [ $this, 'ajax_front_get_waste' ] );
        add_action( 'wp_ajax_opa_front_get_waste', [ $this, 'ajax_front_get_waste' ] );

        add_action( 'wp_ajax_nopriv_opa_front_get_containers', [ $this, 'ajax_front_get_containers' ] );
        add_action( 'wp_ajax_opa_front_get_containers', [ $this, 'ajax_front_get_containers' ] );

        add_action( 'wp_ajax_nopriv_opa_front_get_price', [ $this, 'ajax_front_get_price' ] );
        add_action( 'wp_ajax_opa_front_get_price', [ $this, 'ajax_front_get_price' ] );

        add_action( 'wp_ajax_nopriv_opa_front_get_blocked_dates', [ $this, 'ajax_front_get_blocked_dates' ] );
        add_action( 'wp_ajax_opa_front_get_blocked_dates', [ $this, 'ajax_front_get_blocked_dates' ] );

        add_action( 'wp_ajax_nopriv_opa_front_submit_booking', [ $this, 'ajax_front_submit_booking' ] );
        add_action( 'wp_ajax_opa_front_submit_booking', [ $this, 'ajax_front_submit_booking' ] );
    }

    public function ajax_save_city(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }

            $id = isset( $_POST['city_id'] ) ? (int) $_POST['city_id'] : 0;
            $name = sanitize_text_field( $_POST['name'] ?? '' );
            $slug = sanitize_text_field( $_POST['slug'] ?? '' );
            if ( empty($slug) && !empty($name) ) {
                $slug = sanitize_title( $name );
            }

            $country = sanitize_text_field( $_POST['country'] ?? '' );
            $postcode_regex = sanitize_text_field( $_POST['postcode_regex'] ?? '' );
            $priority = isset( $_POST['priority'] ) ? (int) $_POST['priority'] : 0;
            $status = sanitize_text_field( $_POST['status'] ?? 'active' );
            $internal_notes = sanitize_textarea_field( $_POST['internal_notes'] ?? '' );

            if ( empty( $name ) ) {
                wp_send_json_error( 'City name is required.' );
            }

            $data = [
                'name' => $name,
                'slug' => $slug,
                'country' => $country,
                'postcode_regex' => $postcode_regex,
                'priority' => $priority,
                'status' => $status,
                'internal_notes' => $internal_notes
            ];
            $format = ['%s', '%s', '%s', '%s', '%d', '%s', '%s'];

            if ( $id > 0 ) {
                $this->city_repo->update($id, $data, $format);
            } else {
                $id = $this->city_repo->insert($data, $format);
            }

            // Log action
            $logger = new \OpaReklama\Booking\Services\AuditLogger();
            $logger->log('master_data', isset($_POST['city_id']) && (int)$_POST['city_id'] > 0 ? 'update' : 'create', 'City', $id, "Saved City: $name");

            wp_send_json_success([
                'id' => $id,
                'name' => $name,
                'status' => $status
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
            global $wpdb;
            $table = $wpdb->prefix . 'opa_waste_types';
            $sql = "SELECT id, title, slug, status, featured_image_id, short_description, full_description 
                    FROM {$table} ORDER BY sort_order ASC, id DESC";
            $results = $wpdb->get_results( $sql );

            // Append featured image URLs
            foreach ($results as $row) {
                if ($row->featured_image_id) {
                    $row->featured_image_url = wp_get_attachment_url($row->featured_image_id);
                } else {
                    $row->featured_image_url = null;
                }
            }

            wp_send_json_success( $results );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_save_waste_type(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            
            $id = isset( $_POST['waste_id'] ) ? (int) $_POST['waste_id'] : 0;
            $title = sanitize_text_field( $_POST['title'] ?? '' );
            $status = sanitize_text_field( $_POST['status'] ?? 'active' );
            $featured_image_id = isset( $_POST['featured_image_id'] ) && $_POST['featured_image_id'] !== '' ? (int) $_POST['featured_image_id'] : null;
            $short_description = sanitize_textarea_field( $_POST['short_description'] ?? '' );
            $full_description = wp_kses_post( $_POST['full_description'] ?? '' ); // Allow safe HTML from rich editor

            if ( empty( $title ) ) {
                wp_send_json_error( 'Title is required.' );
            }

            $repo = new \OpaReklama\Booking\Repositories\WasteTypeRepository();
            $slug = sanitize_title( $title );

            $data = [
                'title' => $title,
                'slug' => $slug,
                'status' => $status,
                'featured_image_id' => $featured_image_id,
                'short_description' => $short_description,
                'full_description' => $full_description
            ];
            $format = ['%s', '%s', '%s', '%d', '%s', '%s'];

            if ($id > 0) {
                $repo->update($id, $data, $format);
            } else {
                $id = $repo->insert($data, $format);
            }

            wp_send_json_success([ 'id' => $id, 'title' => $title, 'status' => $status ]);
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

    public function ajax_save_container(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            
            $id = isset( $_POST['container_id'] ) ? (int) $_POST['container_id'] : 0;
            $name = sanitize_text_field( $_POST['name'] ?? $_POST['title'] ?? '' );
            $display_name = sanitize_text_field( $_POST['display_name'] ?? '' );
            $volume = sanitize_text_field( $_POST['volume'] ?? $_POST['size'] ?? '' );
            $length = sanitize_text_field( $_POST['length'] ?? '' );
            $width = sanitize_text_field( $_POST['width'] ?? '' );
            $height = sanitize_text_field( $_POST['height'] ?? '' );
            $status = sanitize_text_field( $_POST['status'] ?? 'active' );
            $featured_image_id = isset( $_POST['featured_image_id'] ) && $_POST['featured_image_id'] !== '' ? (int) $_POST['featured_image_id'] : null;

            if ( empty( $name ) ) {
                wp_send_json_error( 'Internal Name is required.' );
            }
            if ( empty( $display_name ) ) {
                $display_name = $name;
            }

            $repo = new \OpaReklama\Booking\Repositories\ContainerRepository();

            $data = [
                'name' => $name,
                'display_name' => $display_name,
                'volume' => $volume,
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'status' => $status,
                'featured_image_id' => $featured_image_id
            ];
            $format = ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'];

            if ($id > 0) {
                $repo->update($id, $data, $format);
            } else {
                $id = $repo->insert($data, $format);
            }

            wp_send_json_success([ 'id' => $id, 'name' => $name, 'status' => $status ]);
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
            $table = $wpdb->prefix . 'opa_service_rules';
            $cities_tbl = $wpdb->prefix . 'opa_cities';
            $waste_tbl = $wpdb->prefix . 'opa_waste_types';
            $containers_tbl = $wpdb->prefix . 'opa_containers';

            // Pagination
            $page = max( 1, (int) ( $_GET['page'] ?? 1 ) );
            $limit = max( 1, (int) ( $_GET['limit'] ?? 10 ) );
            $offset = ( $page - 1 ) * $limit;

            // Filters
            $where = ["r.status != 'archived'"];
            $params = [];

            if ( ! empty( $_GET['city_id'] ) ) {
                $where[] = "r.city_id = %d";
                $params[] = (int) $_GET['city_id'];
            }
            if ( ! empty( $_GET['waste_id'] ) ) {
                $where[] = "r.waste_type_id = %d";
                $params[] = (int) $_GET['waste_id'];
            }
            if ( ! empty( $_GET['container_id'] ) ) {
                $where[] = "r.container_id = %d";
                $params[] = (int) $_GET['container_id'];
            }
            if ( ! empty( $_GET['status'] ) ) {
                if ( $_GET['status'] === 'archived' ) {
                    // Replace the first where clause
                    $where[0] = "r.status = 'archived'";
                } else {
                    $where[] = "r.status = %s";
                    $params[] = $_GET['status'];
                }
            }

            if ( ! empty( $_GET['search'] ) ) {
                $search = '%' . $wpdb->esc_like( $_GET['search'] ) . '%';
                $where[] = "(c.name LIKE %s OR w.title LIKE %s OR cont.title LIKE %s OR cont.name LIKE %s)";
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }

            $where_sql = implode( ' AND ', $where );
            
            // Total Count
            $count_sql = "SELECT COUNT(r.id) FROM $table r 
                          JOIN $cities_tbl c ON r.city_id = c.id
                          JOIN $waste_tbl w ON r.waste_type_id = w.id
                          JOIN $containers_tbl cont ON r.container_id = cont.id
                          WHERE $where_sql";
                          
            $total_records = (int) ( empty($params) ? $wpdb->get_var($count_sql) : $wpdb->get_var( $wpdb->prepare( $count_sql, ...$params ) ) );
            $total_pages = ceil( $total_records / $limit );

            // Fetch Data
            $sql = "SELECT r.id, r.city_id, r.waste_type_id, r.container_id, r.base_price, r.status, r.created_at,
                           c.name as city_name, w.title as waste_name, 
                           COALESCE(NULLIF(cont.display_name, ''), cont.title, cont.name) as container_name
                    FROM $table r
                    JOIN $cities_tbl c ON r.city_id = c.id
                    JOIN $waste_tbl w ON r.waste_type_id = w.id
                    JOIN $containers_tbl cont ON r.container_id = cont.id
                    WHERE $where_sql
                    ORDER BY r.id DESC 
                    LIMIT %d OFFSET %d";

            $query_params = array_merge($params, [$limit, $offset]);
            $rules = $wpdb->get_results( $wpdb->prepare( $sql, ...$query_params ) );
            
            wp_send_json_success( [
                'data' => $rules,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total_records' => $total_records,
                    'total_pages' => $total_pages
                ]
            ] );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_save_pricing_rule(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            
            $id = isset( $_POST['rule_id'] ) ? (int) $_POST['rule_id'] : 0;
            $city_id = (int) ( $_POST['city_id'] ?? 0 );
            $waste_type_id = (int) ( $_POST['waste_type_id'] ?? 0 );
            $container_id = (int) ( $_POST['container_id'] ?? 0 );
            $base_price = (float) ( $_POST['base_price'] ?? 0.0 );
            $status = sanitize_text_field( $_POST['status'] ?? 'active' );
            
            if ( ! $city_id || ! $waste_type_id || ! $container_id ) {
                wp_send_json_error( 'City, Waste Type, and Container are required.' );
            }
            
            $repo = new \OpaReklama\Booking\Repositories\ServiceRuleRepository();
            
            // Check for duplicates
            global $wpdb;
            $table = $wpdb->prefix . 'opa_service_rules';
            $duplicate_check = $wpdb->get_var( $wpdb->prepare(
                "SELECT id FROM $table WHERE city_id = %d AND waste_type_id = %d AND container_id = %d AND id != %d",
                $city_id, $waste_type_id, $container_id, $id
            ) );

            if ( $duplicate_check ) {
                wp_send_json_error( 'A pricing rule for this combination already exists.' );
            }

            $data = [
                'city_id' => $city_id,
                'waste_type_id' => $waste_type_id,
                'container_id' => $container_id,
                'base_price' => $base_price,
                'status' => $status
            ];
            $format = ['%d', '%d', '%d', '%f', '%s'];

            if ($id > 0) {
                $repo->update($id, $data, $format);
            } else {
                $id = $repo->insert($data, $format);
            }
            
            // Log action
            $logger = new \OpaReklama\Booking\Services\AuditLogger();
            $logger->log('master_data', isset($_POST['rule_id']) && (int)$_POST['rule_id'] > 0 ? 'update' : 'create', 'PricingRule', $id, "Saved Rule ID: $id");

            wp_send_json_success( ['id' => $id] );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_archive_pricing_rule(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            $id = (int) ( $_POST['id'] ?? 0 );
            if (!$id) wp_send_json_error('Invalid ID');
            
            $repo = new \OpaReklama\Booking\Repositories\ServiceRuleRepository();
            $repo->archive($id);

            // Log action
            $logger = new \OpaReklama\Booking\Services\AuditLogger();
            $logger->log('master_data', 'archive', 'PricingRule', $id, "Archived Rule ID: $id");

            wp_send_json_success('Rule archived successfully.');
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_delete_pricing_rule(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            $id = (int) ( $_POST['id'] ?? 0 );
            if (!$id) wp_send_json_error('Invalid ID');
            
            $repo = new \OpaReklama\Booking\Repositories\ServiceRuleRepository();
            $repo->delete($id);

            // Log action
            $logger = new \OpaReklama\Booking\Services\AuditLogger();
            $logger->log('master_data', 'delete', 'PricingRule', $id, "Deleted Rule ID: $id");

            wp_send_json_success('Rule deleted successfully.');
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
                SELECT DISTINCT w.id, w.title, w.featured_image_id, w.short_description, w.full_description 
                FROM {$wpdb->prefix}opa_waste_types w
                JOIN {$wpdb->prefix}opa_service_rules r ON w.id = r.waste_type_id
                WHERE r.city_id = %d AND r.status = 'active' AND w.status = 'active'
                ORDER BY w.title ASC
            ", $city_id );
            
            $results = $wpdb->get_results( $sql );
            foreach ($results as $row) {
                if ($row->featured_image_id) {
                    $row->featured_image_url = wp_get_attachment_url($row->featured_image_id);
                } else {
                    $row->featured_image_url = null;
                }
            }
            
            wp_send_json_success( $results );
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
                SELECT DISTINCT c.id, COALESCE(NULLIF(c.display_name, ''), c.title, c.name) as title, c.volume as size, c.featured_image_id 
                FROM {$wpdb->prefix}opa_containers c
                JOIN {$wpdb->prefix}opa_service_rules r ON c.id = r.container_id
                WHERE r.city_id = %d AND r.waste_type_id = %d AND r.status = 'active' AND c.status = 'active'
                ORDER BY c.title ASC
            ", $city_id, $waste_id );
            
            $results = $wpdb->get_results( $sql );
            foreach ($results as $row) {
                if ($row->featured_image_id) {
                    $row->featured_image_url = wp_get_attachment_url($row->featured_image_id);
                } else {
                    $row->featured_image_url = null;
                }
            }
            
            wp_send_json_success( $results );
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

    public function ajax_front_get_blocked_dates(): void {
        try {
            global $wpdb;
            $container_id = (int) ( $_GET['container_id'] ?? 0 );
            
            // We assume a container is fully booked if there is any active booking for that container on a given date.
            // Exclude cancelled/refunded bookings.
            $sql = $wpdb->prepare( "
                SELECT booking_date FROM {$wpdb->prefix}opa_bookings 
                WHERE container_id = %d AND status NOT IN ('cancelled', 'refunded')
            ", $container_id );
            
            $results = $wpdb->get_col( $sql );
            wp_send_json_success( $results );
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
            
            $invoice_id = $invoice_service->generate_invoice( $booking_id, (float) $booking_data->total_price );
            $invoice_record = $invoice_repo->find( $invoice_id );
            
            // Dispatch Email
            $email_service = new \OpaReklama\Booking\Services\EmailService();
            $email_service->send_customer_confirmation( $booking_data, $invoice_record->invoice_token );
            
            wp_send_json_success( [
                'booking_number' => $booking_data->booking_number,
                'invoice_token' => $invoice_record->invoice_token
            ] );
            
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    // --- Admin Bookings Management ---

    public function ajax_get_bookings(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            
            global $wpdb;
            $sql = "SELECT b.*, c.name as city, w.title as waste_type, cont.title as container
                    FROM {$wpdb->prefix}opa_bookings b
                    JOIN {$wpdb->prefix}opa_cities c ON b.city_id = c.id
                    JOIN {$wpdb->prefix}opa_waste_types w ON b.waste_type_id = w.id
                    JOIN {$wpdb->prefix}opa_containers cont ON b.container_id = cont.id
                    ORDER BY b.id DESC";
            
            wp_send_json_success( $wpdb->get_results( $sql ) );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }

    public function ajax_update_booking_status(): void {
        try {
            $this->security->verify_nonce( 'opa_admin_nonce' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'Unauthorized' );
            }
            
            $booking_id = (int) ( $_POST['booking_id'] ?? 0 );
            $status = sanitize_text_field( $_POST['status'] ?? '' );
            
            if ( ! $booking_id || ! $status ) {
                wp_send_json_error( 'Invalid parameters.' );
            }
            
            $repo = new \OpaReklama\Booking\Repositories\BookingRepository();
            $repo->update( $booking_id, ['status' => $status], ['%s'] );
            
            wp_send_json_success( 'Status updated.' );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }
}
