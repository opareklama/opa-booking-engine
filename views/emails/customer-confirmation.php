<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        h1 { color: #0073aa; margin: 0; }
        .content { font-size: 16px; color: #333; line-height: 1.6; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #0073aa; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo esc_html($company_name); ?></h1>
        </div>
        <div class="content">
            <p><?php echo nl2br(esc_html($body_text)); ?></p>
            
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Booking ID:</strong></td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;"><?php echo esc_html($booking->booking_number); ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Service Date:</strong></td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;"><?php echo esc_html($booking->booking_date); ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Total Price:</strong></td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;"><?php echo esc_html(get_option('opa_currency_symbol', '$')); ?><?php echo number_format((float)$booking->total_price, 2); ?></td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="<?php echo esc_url($invoice_link); ?>" class="btn">View & Download Invoice</a>
            </div>
        </div>
        <div class="footer">
            <p>If you have any questions, please reply to this email.</p>
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html($company_name); ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
