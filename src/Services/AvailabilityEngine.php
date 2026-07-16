<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Services;

class AvailabilityEngine {
    
    /**
     * Get rules for frontend rendering.
     * 
     * @return array
     */
    public static function getFrontendRules(): array {
        $min_advance_days = (int) SettingsService::get('opa_min_advance_days', 1);
        $max_advance_days = (int) SettingsService::get('opa_max_advance_days', 365);
        $closed_weekdays = (array) SettingsService::get('opa_closed_weekdays', []);
        $custom_blocked_dates = (array) SettingsService::get('opa_custom_blocked_dates', []);
        $public_holidays = (array) SettingsService::get('opa_public_holidays', []);
        
        $tz = wp_timezone();
        $now = new \DateTimeImmutable('now', $tz);
        
        $min_date = $now->modify("+{$min_advance_days} days")->format('Y-m-d');
        $max_date = $now->modify("+{$max_advance_days} days")->format('Y-m-d');
        
        return [
            'min_date' => $min_date,
            'max_date' => $max_date,
            'closed_weekdays' => $closed_weekdays,
            'custom_blocked_dates' => $custom_blocked_dates,
            'public_holidays' => $public_holidays,
            'min_advance_days' => $min_advance_days,
            'max_advance_days' => $max_advance_days
        ];
    }
    
    /**
     * Strictly validate if a specific date is available for booking.
     * Validates in specific priority order.
     * 
     * @param string $date (YYYY-MM-DD)
     * @return array ['available' => bool, 'reason' => string]
     */
    public static function isDateAvailable(string $date): array {
        $tz = wp_timezone();
        try {
            $target = new \DateTimeImmutable($date, $tz);
        } catch (\Exception $e) {
            return ['available' => false, 'reason' => 'Invalid date format.'];
        }
        
        $target_date = $target->format('Y-m-d');
        
        $now = new \DateTimeImmutable('now', $tz);
        $today = $now->format('Y-m-d');
        
        // 1. Past Date
        if ($target_date < $today) {
            return ['available' => false, 'reason' => 'Cannot book in the past.'];
        }
        
        // 2. Minimum Advance Booking Days
        $min_advance_days = (int) SettingsService::get('opa_min_advance_days', 1);
        $min_date = $now->modify("+{$min_advance_days} days")->format('Y-m-d');
        if ($target_date < $min_date) {
            return ['available' => false, 'reason' => sprintf(__('Booking requires at least %d days notice.', 'opa-booking'), $min_advance_days)];
        }
        
        // 3. Maximum Advance Booking Days
        $max_advance_days = (int) SettingsService::get('opa_max_advance_days', 365);
        $max_date = $now->modify("+{$max_advance_days} days")->format('Y-m-d');
        if ($target_date > $max_date) {
            return ['available' => false, 'reason' => sprintf(__('Cannot book more than %d days in advance.', 'opa-booking'), $max_advance_days)];
        }
        
        // 4. Weekly Closed Days
        $closed_weekdays = (array) SettingsService::get('opa_closed_weekdays', []);
        $day_of_week = (int) $target->format('w'); // 0 (for Sunday) through 6 (for Saturday)
        if (in_array($day_of_week, $closed_weekdays)) {
            $day_names = [0 => 'Sundays', 1 => 'Mondays', 2 => 'Tuesdays', 3 => 'Wednesdays', 4 => 'Thursdays', 5 => 'Fridays', 6 => 'Saturdays'];
            $day_name = $day_names[$day_of_week] ?? 'this day';
            return ['available' => false, 'reason' => sprintf(__('Closed on %s.', 'opa-booking'), $day_name)];
        }
        
        // 5. Custom Blocked Dates
        if (self::isBlockedDate($target_date)) {
            return ['available' => false, 'reason' => __('Date unavailable.', 'opa-booking')];
        }
        
        // 6. Public Holidays
        if (self::isHoliday($target_date)) {
            return ['available' => false, 'reason' => __('Public Holiday.', 'opa-booking')];
        }
        
        // 7. Future Capacity Rules (placeholder check)
        try {
            if (!self::hasCapacity($target_date)) {
                return ['available' => false, 'reason' => __('Fully booked.', 'opa-booking')];
            }
        } catch (\Exception $e) {
            // Ignore NotImplementedException for now, assume available
        }
        
        return ['available' => true, 'reason' => ''];
    }
    
    /**
     * Check if date is a Public Holiday.
     */
    public static function isHoliday(string $date): bool {
        $public_holidays = (array) SettingsService::get('opa_public_holidays', []);
        return in_array($date, $public_holidays);
    }
    
    /**
     * Check if date is a Custom Blocked Date.
     */
    public static function isBlockedDate(string $date): bool {
        $custom_blocked_dates = (array) SettingsService::get('opa_custom_blocked_dates', []);
        return in_array($date, $custom_blocked_dates);
    }
    
    /**
     * Check if date has remaining capacity.
     */
    public static function hasCapacity(string $date): bool {
        throw new \Exception('Not Implemented');
    }
    
    /**
     * Get remaining capacity for a date.
     */
    public static function getCapacity(string $date): int {
        throw new \Exception('Not Implemented');
    }
}
