<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Services;

class EmailService {
    public function send_customer_confirmation( object $booking, string $invoice_token ): bool {
        try {
            $company_name = get_option('opa_company_name', 'Opa Reklama');
            $invoice_link = get_site_url() . '?opa_invoice=' . $invoice_token;
            $headers = ['Content-Type: text/html; charset=UTF-8'];
            
            // --- 1. Customer Email ---
            $enable_customer = get_option('opa_enable_customer_emails', 'yes');
            $sent_customer = false;
            
            if ($enable_customer === 'yes') {
                $to = $booking->customer_email;
                $subject = get_option('opa_customer_subject', 'Your Booking Confirmation - {company_name}');
                $body_template = get_option('opa_customer_body', "Thank you for booking with us! Your booking has been successfully received.\n\nWe will process it shortly.");
                
                // Replace variables
                $subject = str_replace(
                    ['{company_name}', '{booking_id}'], 
                    [$company_name, $booking->booking_number], 
                    $subject
                );
                
                $body_text = str_replace(
                    ['{company_name}', '{booking_id}', '{customer_name}'], 
                    [$company_name, $booking->booking_number, explode('@', $booking->customer_email)[0]], 
                    $body_template
                );
                
                // Build HTML
                ob_start();
                require OPA_BOOKING_PLUGIN_DIR . 'views/emails/customer-confirmation.php';
                $customer_html = ob_get_clean();
                
                $sent_customer = wp_mail( $to, $subject, $customer_html, $headers );
            }
            
            // --- 2. Admin Notification ---
            $admin_email = get_option('opa_admin_email', '');
            if (!empty($admin_email)) {
                $admin_subject = get_option('opa_admin_subject', 'New Booking Alert: #{booking_id}');
                $admin_body_template = get_option('opa_admin_body', "A new booking has just been submitted.\n\nPlease check the dashboard or view the invoice.");
                
                // Replace variables
                $admin_subject = str_replace('{booking_id}', $booking->booking_number, $admin_subject);
                
                $admin_body_text = str_replace(
                    ['{booking_id}', '{customer_email}'], 
                    [$booking->booking_number, $booking->customer_email], 
                    $admin_body_template
                );
                
                // Build HTML
                ob_start();
                require OPA_BOOKING_PLUGIN_DIR . 'views/emails/admin-notification.php';
                $admin_html = ob_get_clean();
                
                wp_mail( $admin_email, $admin_subject, $admin_html, $headers );
            }
            
            return $sent_customer;
        } catch ( \Exception $e ) {
            error_log( "Email failed for booking {$booking->id}: " . $e->getMessage() );
            return false;
        }
    }
}
