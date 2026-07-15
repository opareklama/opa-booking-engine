<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Services;

class EmailService {
    public function send_customer_confirmation( object $booking, string $invoice_token ): bool {
        try {
            $enable_emails = get_option('opa_enable_emails', 'yes');
            if ($enable_emails !== 'yes') {
                return false; // Emails disabled
            }
            
            $to = $booking->customer_email;
            $company_name = get_option('opa_company_name', 'Opa Reklama');
            $subject = 'Your Booking Confirmation - ' . $company_name;
            $email_body_text = get_option('opa_email_body', "Thank you for booking with us! Your booking has been successfully received.\n\nWe will process it shortly.");
            
            $invoice_link = get_site_url() . '?opa_invoice=' . $invoice_token;
            
            ob_start();
            require OPA_BOOKING_PLUGIN_DIR . 'views/emails/customer-confirmation.php';
            $body = ob_get_clean();
            
            $headers = ['Content-Type: text/html; charset=UTF-8'];
            
            // Send to Customer
            $sent_customer = wp_mail( $to, $subject, $body, $headers );
            
            // Notify Admin
            $admin_email = get_option('opa_admin_email', get_option('admin_email'));
            if ($admin_email) {
                $admin_subject = 'New Booking Received - ' . $booking->booking_number;
                $admin_body = "A new booking has been placed.<br><br>Booking ID: {$booking->booking_number}<br>Customer: {$booking->customer_email}<br><a href='{$invoice_link}'>View Invoice</a>";
                wp_mail( $admin_email, $admin_subject, $admin_body, $headers );
            }
            
            return $sent_customer;
        } catch ( \Exception $e ) {
            error_log( "Email failed for booking {$booking->id}: " . $e->getMessage() );
            return false;
        }
    }
}
