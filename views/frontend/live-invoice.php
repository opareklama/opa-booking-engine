<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice <?php echo esc_html($invoice->invoice_number); ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; font-size: 14px; color: #334155; background: #f8fafc; margin: 0; padding: 40px 20px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 50px; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #f1f5f9; padding-bottom: 30px; margin-bottom: 30px; }
        .company-info h1 { margin: 0 0 8px 0; color: #0f172a; font-size: 28px; font-weight: 700; letter-spacing: -0.5px; }
        .company-info p { margin: 0; color: #64748b; line-height: 1.6; }
        .company-logo { max-height: 80px; margin-bottom: 15px; }
        .invoice-details { text-align: right; }
        .invoice-details h2 { margin: 0 0 10px 0; color: #4f46e5; font-size: 32px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; }
        .invoice-details p { margin: 0; color: #64748b; font-size: 14px; line-height: 1.6; }
        .invoice-details strong { color: #0f172a; }
        .bill-to { margin-bottom: 40px; background: #f8fafc; padding: 25px; border-radius: 12px; }
        .bill-to h3 { margin: 0 0 10px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
        .bill-to p { margin: 0; color: #334155; line-height: 1.6; font-size: 15px; }
        .bill-to strong { color: #0f172a; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 16px 20px; text-align: left; }
        th { background-color: #f1f5f9; color: #475569; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 8px 8px 0 0; }
        td { border-bottom: 1px solid #e2e8f0; color: #1e293b; }
        .total-row td { border-top: 2px solid #e2e8f0; padding-top: 20px; color: #475569; font-weight: 500; }
        .grand-total td { font-weight: 700; font-size: 18px; color: #0f172a; border-bottom: none; }
        .footer { margin-top: 50px; text-align: center; }
        .footer p { font-size: 15px; color: #64748b; font-weight: 500; }
        .footer-notes { margin-top: 40px; font-size: 14px; text-align: left; padding: 20px; background: #eff6ff; border-radius: 8px; border-left: 4px solid #3b82f6; color: #1e3a8a; }
        .download-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 28px; background: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 50px; font-weight: 600; font-size: 15px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); }
        .download-btn:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4); }
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
                <div style="margin-top: 40px;">
                    <a href="<?php echo esc_url(add_query_arg('format', 'pdf')); ?>" class="download-btn">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                        Download PDF
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
