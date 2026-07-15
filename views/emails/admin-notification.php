<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; background-color: #f9f9f9; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; padding: 30px; border-top: 4px solid #d63638; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .content { font-size: 16px; color: #333; line-height: 1.6; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #d63638; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <h2 style="margin-top:0;">New Booking Alert</h2>
            <p><?php echo nl2br(esc_html($admin_body_text)); ?></p>
            
            <div style="background:#f1f1f1; padding: 15px; margin-top: 20px;">
                <p style="margin:0 0 10px 0;"><strong>Booking ID:</strong> <?php echo esc_html($booking->booking_number); ?></p>
                <p style="margin:0 0 10px 0;"><strong>Customer:</strong> <?php echo esc_html($booking->customer_email); ?></p>
                <p style="margin:0 0 10px 0;"><strong>Total Value:</strong> <?php echo esc_html(get_option('opa_currency_symbol', '$')); ?><?php echo number_format((float)$booking->total_price, 2); ?></p>
            </div>

            <div style="text-align: center;">
                <a href="<?php echo esc_url($invoice_link); ?>" class="btn">View Customer Invoice</a>
            </div>
        </div>
    </div>
</body>
</html>
