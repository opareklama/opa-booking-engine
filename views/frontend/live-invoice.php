<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sąskaita faktūra <?php echo esc_html($invoice->invoice_number); ?></title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; color: #334155; margin: 0; padding: 0; background: #ffffff; }
        .invoice-box { padding: 40px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 0; vertical-align: top; }
        
        /* Header */
        .header-table { margin-bottom: 30px; }
        .company-info h1 { margin: 0 0 5px 0; color: #0f172a; font-size: 24px; font-weight: bold; }
        .company-info p { margin: 0; color: #64748b; line-height: 1.5; font-size: 13px; }
        .company-logo { max-height: 70px; margin-bottom: 10px; }
        .invoice-details { text-align: right; }
        .invoice-details h2 { margin: 0 0 10px 0; color: #16a34a; font-size: 26px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
        .invoice-details p { margin: 0; color: #64748b; font-size: 13px; line-height: 1.5; }
        .invoice-details strong { color: #0f172a; }
        
        .divider { border-bottom: 2px solid #f1f5f9; margin-bottom: 30px; }
        
        /* Bill To */
        .bill-to-table { margin-bottom: 40px; }
        .bill-to { background: #f8fafc; padding: 20px; border-radius: 8px; border-left: 4px solid #16a34a; }
        .bill-to h3 { margin: 0 0 8px 0; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
        .bill-to p { margin: 0; color: #334155; line-height: 1.6; font-size: 14px; }
        .bill-to strong { color: #0f172a; font-weight: bold; }
        
        /* Items Table */
        .items-table { margin-bottom: 30px; }
        .items-table th, .items-table td { padding: 12px 15px; text-align: left; }
        .items-table th { background-color: #f8fafc; color: #475569; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; }
        .items-table td { border-bottom: 1px solid #f1f5f9; color: #1e293b; font-size: 13px; }
        
        /* Totals */
        .totals-table { width: 50%; float: right; margin-bottom: 40px; }
        .totals-table td { padding: 10px 15px; }
        .total-label { text-align: right; color: #64748b; font-size: 13px; }
        .total-value { text-align: right; color: #1e293b; font-size: 14px; font-weight: bold; }
        .grand-total .total-label { color: #0f172a; font-weight: bold; font-size: 15px; padding-top: 15px; border-top: 2px solid #e2e8f0; }
        .grand-total .total-value { color: #16a34a; font-weight: bold; font-size: 18px; padding-top: 15px; border-top: 2px solid #e2e8f0; }
        
        .clearfix { clear: both; }
        
        /* Footer */
        .footer-notes { margin-top: 30px; font-size: 12px; text-align: left; padding: 15px; background: #eff6ff; border-radius: 6px; border-left: 4px solid #3b82f6; color: #1e3a8a; line-height: 1.5; }
        .footer { margin-top: 50px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 20px; }
        .footer p { font-size: 14px; color: #94a3b8; font-weight: bold; margin: 0; }
        
        /* Web Preview Button */
        .html-download-btn { display: inline-block; padding: 10px 24px; background: #16a34a; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <?php 
            $snapshot = json_decode($invoice->snapshot_data, true); 
            $currency = get_option('opa_currency_symbol', '€');
            $tax_rate = (float) get_option('opa_tax_rate', 0);
            
            $base_price = (float) $invoice->subtotal;
            $tax_amount = (float) $invoice->tax_total;
            $grand_total = (float) $invoice->grand_total;
        ?>
        
        <table class="header-table">
            <tr>
                <td class="company-info" style="width: 50%;">
                    <?php if (!empty($snapshot['company_logo'])): ?>
                        <img src="<?php echo esc_url($snapshot['company_logo']); ?>" class="company-logo" alt="Logo">
                    <?php endif; ?>
                    <h1><?php echo esc_html($snapshot['company_name']); ?></h1>
                    <p><?php echo nl2br(esc_html($snapshot['company_address'])); ?><br>
                    VAT: <?php echo esc_html($snapshot['vat_number'] ?? ''); ?></p>
                </td>
                <td class="invoice-details" style="width: 50%;">
                    <h2>SĄSKAITA FAKTŪRA</h2>
                    <p><strong>Sąskaitos Nr.:</strong> <?php echo esc_html($invoice->invoice_number); ?><br>
                    <strong>Data:</strong> <?php echo esc_html(date('Y-m-d', strtotime($invoice->created_at))); ?></p>
                </td>
            </tr>
        </table>
        
        <div class="divider"></div>

        <table class="bill-to-table">
            <tr>
                <td style="width: 50%;">
                    <div class="bill-to">
                        <h3>Pirkėjas:</h3>
                        <p><strong><?php echo esc_html($booking->customer_email); ?></strong><br>
                        <?php echo esc_html($booking->customer_phone); ?><br>
                        <?php echo esc_html($booking->address_line); ?></p>
                    </div>
                </td>
                <td style="width: 50%;"></td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 55%;">Aprašymas</th>
                    <th style="width: 15%; text-align: center;">Kiekis</th>
                    <th style="width: 15%; text-align: right;">Kaina</th>
                    <th style="width: 15%; text-align: right;">Suma</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Konteinerio užsakymas (Nuoroda: <?php echo esc_html($booking->booking_number); ?>)</strong><br>
                        <span style="color: #64748b; font-size: 11px;">Pristatymo data: <?php echo esc_html($booking->booking_date); ?></span>
                    </td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right;"><?php echo esc_html($currency) . number_format($base_price, 2); ?></td>
                    <td style="text-align: right;"><?php echo esc_html($currency) . number_format($base_price, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <table class="totals-table">
            <?php if ($tax_rate > 0): ?>
            <tr>
                <td class="total-label">Tarpinė suma:</td>
                <td class="total-value"><?php echo esc_html($currency) . number_format($base_price, 2); ?></td>
            </tr>
            <tr>
                <td class="total-label">PVM (<?php echo esc_html($tax_rate); ?>%):</td>
                <td class="total-value"><?php echo esc_html($currency) . number_format($tax_amount, 2); ?></td>
            </tr>
            <?php endif; ?>
            <tr class="grand-total">
                <td class="total-label">Bendra suma:</td>
                <td class="total-value"><?php echo esc_html($currency) . number_format($grand_total, 2); ?></td>
            </tr>
        </table>
        
        <div class="clearfix"></div>

        <?php $notes = get_option('opa_invoice_notes', ''); ?>
        <?php if (!empty($notes)): ?>
        <div class="footer-notes">
            <strong>Pastabos:</strong><br>
            <?php echo nl2br(esc_html($notes)); ?>
        </div>
        <?php endif; ?>

        <div class="footer">
            <p>Dėkojame, kad renkatės mus!</p>
            
            <?php if ( ! isset($_GET['format']) || $_GET['format'] !== 'pdf' ): ?>
                <div style="margin-top: 30px;">
                    <a href="<?php echo esc_url(add_query_arg('format', 'pdf')); ?>" class="html-download-btn">
                        Atsisiųsti PDF
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
