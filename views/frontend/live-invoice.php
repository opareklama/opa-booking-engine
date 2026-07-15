<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice <?php echo esc_html($invoice->invoice_number); ?></title>
    <style>
        body { font-family: 'Courier', monospace; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .company-info h1 { margin: 0; color: #0073aa; font-size: 24px; }
        .company-logo { max-height: 80px; margin-bottom: 10px; }
        .invoice-details { text-align: right; }
        .bill-to { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f8f8; }
        .total-row td { border-top: 2px solid #333; }
        .grand-total td { font-weight: bold; font-size: 16px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        .footer-notes { margin-top: 30px; font-size: 12px; text-align: left; padding: 15px; background: #f9f9f9; border-left: 3px solid #0073aa; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <?php 
            $snapshot = json_decode($invoice->snapshot_data, true); 
            $currency = get_option('opa_currency_symbol', '$');
            $tax_rate = (float) get_option('opa_tax_rate', 0);
            
            $base_price = (float) $booking->total_price;
            $tax_amount = ($base_price * $tax_rate) / 100;
            $grand_total = $base_price + $tax_amount;
        ?>
        
        <div class="header">
            <div class="company-info">
                <?php if (!empty($snapshot['company_logo'])): ?>
                    <img src="<?php echo esc_url($snapshot['company_logo']); ?>" class="company-logo" alt="Logo">
                <?php endif; ?>
                <h1><?php echo esc_html($snapshot['company_name']); ?></h1>
                <p><?php echo nl2br(esc_html($snapshot['company_address'])); ?><br>
                VAT: <?php echo esc_html($snapshot['vat_number']); ?></p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <p><strong>Invoice #:</strong> <?php echo esc_html($invoice->invoice_number); ?><br>
                <strong>Date:</strong> <?php echo esc_html(date('F j, Y', strtotime($invoice->created_at))); ?></p>
            </div>
        </div>

        <div class="bill-to">
            <h3>Bill To:</h3>
            <p><strong><?php echo esc_html($booking->customer_email); ?></strong><br>
            <?php echo esc_html($booking->customer_phone); ?><br>
            <?php echo esc_html($booking->address_line); ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Container Booking (Ref: <?php echo esc_html($booking->booking_number); ?>)<br>
                    <small>Date: <?php echo esc_html($booking->booking_date); ?></small></td>
                    <td>1</td>
                    <td><?php echo esc_html($currency) . number_format($base_price, 2); ?></td>
                    <td style="text-align:right;"><?php echo esc_html($currency) . number_format($base_price, 2); ?></td>
                </tr>
                
                <?php if ($tax_rate > 0): ?>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right;">Subtotal:</td>
                    <td style="text-align:right;"><?php echo esc_html($currency) . number_format($base_price, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">Tax/VAT (<?php echo esc_html($tax_rate); ?>%):</td>
                    <td style="text-align:right;"><?php echo esc_html($currency) . number_format($tax_amount, 2); ?></td>
                </tr>
                <?php endif; ?>
                
                <tr class="<?php echo ($tax_rate > 0) ? '' : 'total-row'; ?> grand-total">
                    <td colspan="3" style="text-align:right;">Grand Total:</td>
                    <td style="text-align:right;"><?php echo esc_html($currency) . number_format($grand_total, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <?php $notes = get_option('opa_invoice_notes', ''); ?>
        <?php if (!empty($notes)): ?>
        <div class="footer-notes">
            <strong>Notes:</strong><br>
            <?php echo nl2br(esc_html($notes)); ?>
        </div>
        <?php endif; ?>

        <div class="footer">
            <p>Thank you for your business!</p>
            
            <?php if ( ! isset($_GET['format']) || $_GET['format'] !== 'pdf' ): ?>
                <div style="margin-top: 30px;">
                    <a href="<?php echo esc_url(add_query_arg('format', 'pdf')); ?>" style="display:inline-block; padding: 10px 20px; background: #0073aa; color: #fff; text-decoration: none; border-radius: 4px; font-family: sans-serif;">
                        Download PDF
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
