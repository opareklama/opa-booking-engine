<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Services;

use OpaReklama\Booking\Repositories\ServiceRuleRepository;
use OpaReklama\Booking\Exceptions\ValidationException;

/**
 * The Rule Engine. Calculates prices based on the database rules.
 */
class PricingService {
    private ServiceRuleRepository $rule_repo;

    public function __construct( ServiceRuleRepository $rule_repo ) {
        $this->rule_repo = $rule_repo;
    }

    /**
     * Calculate the base price for a given combination.
     *
     * @param int $city_id
     * @param int $waste_type_id
     * @param int $container_id
     * @return float
     * @throws ValidationException
     */
    public function calculate_price( int $city_id, int $waste_type_id, int $container_id ): float {
        global $wpdb;
        
        // Custom query to find the specific rule using the unique mapping
        $sql = $wpdb->prepare(
            "SELECT base_price FROM {$wpdb->prefix}opa_service_rules 
            WHERE city_id = %d AND waste_type_id = %d AND container_id = %d AND status = 'active'",
            $city_id, $waste_type_id, $container_id
        );
        
        $price = $wpdb->get_var( $sql );
        
        if ( is_null( $price ) ) {
            throw new ValidationException( "No pricing rule found for the selected combination.", "ERR_NO_PRICING_RULE" );
        }
        
        return (float) $price;
    }
}
