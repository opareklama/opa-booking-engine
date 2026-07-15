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
        .invoice-details { text-align: right; }
        .bill-to { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f8f8; }
        .total-row td { font-weight: bold; border-top: 2px solid #333; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <?php $snapshot = json_decode($invoice->snapshot_data, true); ?>
        
        <div class="header">
            <div class="company-info">
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
                    <td>Container Booking (Ref: <?php echo esc_html($booking->booking_number); ?>) - For <?php echo esc_html($booking->booking_date); ?></td>
                    <td>1</td>
                    <td>$<?php echo number_format((float)$booking->total_price, 2); ?></td>
                    <td style="text-align:right;">$<?php echo number_format((float)$booking->total_price, 2); ?></td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right;">Grand Total:</td>
                    <td style="text-align:right;">$<?php echo number_format((float)$invoice->grand_total, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>
