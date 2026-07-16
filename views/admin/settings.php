<div class="wrap">
    <h1>Plugin Settings</h1>
    <p>Configure advanced options for the Opa Booking Engine.</p>
    
    <?php
    if ( isset( $_POST['opa_save_settings'] ) && wp_verify_nonce( $_POST['opa_settings_nonce'] ?? '', 'opa_save_settings' ) ) {
        // Invoice
        update_option( 'opa_company_name', sanitize_text_field( $_POST['company_name'] ?? '' ) );
        update_option( 'opa_company_logo', esc_url_raw( $_POST['company_logo'] ?? '' ) );
        update_option( 'opa_company_address', sanitize_textarea_field( $_POST['company_address'] ?? '' ) );
        update_option( 'opa_vat_number', sanitize_text_field( $_POST['vat_number'] ?? '' ) );
        update_option( 'opa_tax_rate', (float) ( $_POST['tax_rate'] ?? 0 ) );
        update_option( 'opa_currency_symbol', sanitize_text_field( $_POST['currency_symbol'] ?? '$' ) );
        update_option( 'opa_invoice_notes', sanitize_textarea_field( $_POST['invoice_notes'] ?? '' ) );
        update_option( 'opa_default_city', absint( $_POST['default_city'] ?? 0 ) );
        update_option( 'opa_terms_html', wp_kses_post( wp_unslash( $_POST['terms_html'] ?? '' ) ) );
        
        // Emails
        update_option( 'opa_enable_customer_emails', isset( $_POST['enable_customer_emails'] ) ? 'yes' : 'no' );
        update_option( 'opa_customer_subject', sanitize_text_field( $_POST['customer_subject'] ?? 'Booking Confirmation' ) );
        update_option( 'opa_customer_body', wp_kses_post( $_POST['customer_body'] ?? '' ) );
        
        update_option( 'opa_admin_email', sanitize_email( $_POST['admin_email'] ?? '' ) );
        update_option( 'opa_admin_subject', sanitize_text_field( $_POST['admin_subject'] ?? 'New Booking Received' ) );
        update_option( 'opa_admin_body', wp_kses_post( $_POST['admin_body'] ?? '' ) );
        
        // Booking Rules (SettingsService)
        if (class_exists('\\OpaReklama\\Booking\\Services\\SettingsService')) {
            $settings_service = '\\OpaReklama\\Booking\\Services\\SettingsService';
            
            $settings_service::set('opa_min_advance_days', absint($_POST['min_advance_days'] ?? 1));
            $settings_service::set('opa_max_advance_days', absint($_POST['max_advance_days'] ?? 365));
            
            $closed_weekdays = isset($_POST['closed_weekdays']) && is_array($_POST['closed_weekdays']) ? array_map('absint', $_POST['closed_weekdays']) : [];
            $settings_service::set('opa_closed_weekdays', $closed_weekdays);
            
            // Helper to parse dates
            $parse_dates = function($input) {
                $lines = explode("\n", $input);
                $dates = [];
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    // Validate YYYY-MM-DD
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $line)) {
                        $dates[] = $line;
                    }
                }
                return array_unique($dates);
            };
            
            $custom_blocked_dates = $parse_dates($_POST['custom_blocked_dates'] ?? '');
            $settings_service::set('opa_custom_blocked_dates', $custom_blocked_dates);
            
            $public_holidays = $parse_dates($_POST['public_holidays'] ?? '');
            $settings_service::set('opa_public_holidays', $public_holidays);
        }

        echo '<div class="updated notice"><p>Settings saved successfully.</p></div>';
    }
    
    // Load existing
    $company_name = get_option( 'opa_company_name', 'Opa Reklama' );
    $company_logo = get_option( 'opa_company_logo', '' );
    $company_address = get_option( 'opa_company_address', '' );
    $vat_number = get_option( 'opa_vat_number', '' );
    $tax_rate = get_option( 'opa_tax_rate', 0 );
    $currency_symbol = get_option( 'opa_currency_symbol', '$' );
    $invoice_notes = get_option( 'opa_invoice_notes', '' );
    $default_city = get_option( 'opa_default_city', 0 );
    $terms_html = get_option( 'opa_terms_html', '<a href="/taisykles-ir-salygos/" target="_blank">Sutinku su taisyklėmis</a>' );
    
    $enable_customer = get_option( 'opa_enable_customer_emails', 'yes' );
    $customer_subject = get_option( 'opa_customer_subject', 'Your Booking Confirmation - {company_name}' );
    $customer_body = get_option( 'opa_customer_body', "Thank you for booking with us! Your booking has been successfully received.\n\nWe will process it shortly." );
    
    $admin_email = get_option( 'opa_admin_email', get_option('admin_email') );
    $admin_subject = get_option( 'opa_admin_subject', 'New Booking Alert: #{booking_id}' );
    $admin_body = get_option( 'opa_admin_body', "A new booking has just been submitted.\n\nPlease check the dashboard or view the invoice." );
    
    // Load Booking Rules
    $min_advance_days = 1;
    $max_advance_days = 365;
    $closed_weekdays = [];
    $custom_blocked_dates = [];
    $public_holidays = [];
    
    if (class_exists('\\OpaReklama\\Booking\\Services\\SettingsService')) {
        $settings_service = '\\OpaReklama\\Booking\\Services\\SettingsService';
        $min_advance_days = (int) $settings_service::get('opa_min_advance_days', 1);
        $max_advance_days = (int) $settings_service::get('opa_max_advance_days', 365);
        $closed_weekdays = (array) $settings_service::get('opa_closed_weekdays', []);
        $custom_blocked_dates = (array) $settings_service::get('opa_custom_blocked_dates', []);
        $public_holidays = (array) $settings_service::get('opa_public_holidays', []);
    }
    ?>
    
    <h2 class="nav-tab-wrapper" style="margin-top: 20px;">
        <a href="#tab-general" class="nav-tab nav-tab-active" onclick="opaSwitchTab(event, 'tab-general')">General Settings</a>
        <a href="#tab-rules" class="nav-tab" onclick="opaSwitchTab(event, 'tab-rules')">Booking Rules</a>
        <a href="#tab-invoice" class="nav-tab" onclick="opaSwitchTab(event, 'tab-invoice')">Invoice Settings</a>
        <a href="#tab-email" class="nav-tab" onclick="opaSwitchTab(event, 'tab-email')">Email Settings</a>
    </h2>

    <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-top: none; box-shadow: 0 1px 1px rgba(0,0,0,.04); max-width: 800px;">
        <form method="post" action="">
            <?php wp_nonce_field( 'opa_save_settings', 'opa_settings_nonce' ); ?>
            
            <div id="tab-general" class="opa-tab-content">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="default_city">Default City</label></th>
                        <td>
                            <select name="default_city" id="default_city">
                                <option value="0">-- None --</option>
                                <?php
                                global $wpdb;
                                $cities = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}opa_cities WHERE status = 'active' ORDER BY priority ASC, name ASC");
                                foreach($cities as $city) {
                                    echo '<option value="'.esc_attr($city->id).'" '.selected($default_city, $city->id, false).'>'.esc_html($city->name).'</option>';
                                }
                                ?>
                            </select>
                            <br><small>If selected, this city will be auto-selected when users open the booking form.</small>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="terms_html">Terms & Conditions HTML</label></th>
                        <td>
                            <textarea name="terms_html" id="terms_html" class="large-text" rows="3"><?php echo esc_textarea( $terms_html ); ?></textarea>
                            <br><small>HTML allowed (e.g. <code>&lt;a href="..."&gt;Sutinku...&lt;/a&gt;</code>). This will appear next to a required checkbox before submission.</small>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="tab-rules" class="opa-tab-content" style="display: none;">
                <h2>Availability Engine</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="min_advance_days">Min Advance Booking Days</label></th>
                        <td>
                            <input name="min_advance_days" type="number" id="min_advance_days" value="<?php echo esc_attr( $min_advance_days ); ?>" class="small-text" min="0">
                            <br><small>Minimum number of days before a booking can be made.</small>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="max_advance_days">Max Advance Booking Days</label></th>
                        <td>
                            <input name="max_advance_days" type="number" id="max_advance_days" value="<?php echo esc_attr( $max_advance_days ); ?>" class="small-text" min="1">
                            <br><small>Maximum number of days in the future a booking can be made.</small>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Weekly Closed Days</label></th>
                        <td>
                            <?php 
                            $weekdays = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday'];
                            foreach ($weekdays as $day_num => $day_name): 
                                $checked = in_array($day_num, $closed_weekdays) ? 'checked' : '';
                            ?>
                                <label style="display: block; margin-bottom: 5px;">
                                    <input type="checkbox" name="closed_weekdays[]" value="<?php echo esc_attr($day_num); ?>" <?php echo $checked; ?>> <?php echo esc_html($day_name); ?>
                                </label>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="custom_blocked_dates">Custom Blocked Dates</label></th>
                        <td>
                            <textarea name="custom_blocked_dates" id="custom_blocked_dates" class="large-text" rows="5" placeholder="YYYY-MM-DD"><?php echo esc_textarea( implode("\n", $custom_blocked_dates) ); ?></textarea>
                            <br><small>Enter one date per line in YYYY-MM-DD format. These dates will be disabled.</small>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="public_holidays">Public Holidays</label></th>
                        <td>
                            <textarea name="public_holidays" id="public_holidays" class="large-text" rows="5" placeholder="YYYY-MM-DD"><?php echo esc_textarea( implode("\n", $public_holidays) ); ?></textarea>
                            <br><small>Enter one date per line in YYYY-MM-DD format. These will be marked as Public Holidays.</small>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="tab-invoice" class="opa-tab-content" style="display: none;">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="company_logo">Logo URL</label></th>
                        <td><input name="company_logo" type="url" id="company_logo" value="<?php echo esc_url( $company_logo ); ?>" class="regular-text"><br><small>Absolute URL to your logo image (e.g., PNG/JPG).</small></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_name">Company Name</label></th>
                        <td><input name="company_name" type="text" id="company_name" value="<?php echo esc_attr( $company_name ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_address">Company Address</label></th>
                        <td><textarea name="company_address" id="company_address" class="large-text" rows="3"><?php echo esc_textarea( $company_address ); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vat_number">VAT Number</label></th>
                        <td><input name="vat_number" type="text" id="vat_number" value="<?php echo esc_attr( $vat_number ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="currency_symbol">Currency Symbol</label></th>
                        <td><input name="currency_symbol" type="text" id="currency_symbol" value="<?php echo esc_attr( $currency_symbol ); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="tax_rate">Tax / VAT Rate (%)</label></th>
                        <td><input name="tax_rate" type="number" step="0.01" id="tax_rate" value="<?php echo esc_attr( $tax_rate ); ?>" class="small-text"><br><small>If > 0, tax will be added to the base price.</small></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="invoice_notes">Invoice Footer Notes</label></th>
                        <td><textarea name="invoice_notes" id="invoice_notes" class="large-text" rows="3"><?php echo esc_textarea( $invoice_notes ); ?></textarea><br><small>Additional notes to display at the bottom of the PDF (e.g., Payment Terms, Bank Details).</small></td>
                    </tr>
                </table>
            </div>

            <div id="tab-email" class="opa-tab-content" style="display: none;">
                <h2>Customer Notification (Booking Confirmation)</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="enable_customer_emails">Enable Emails</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="enable_customer_emails" id="enable_customer_emails" value="1" <?php checked( $enable_customer, 'yes' ); ?>>
                                Send automated confirmation emails to customers
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="customer_subject">Email Subject</label></th>
                        <td><input name="customer_subject" type="text" id="customer_subject" value="<?php echo esc_attr( $customer_subject ); ?>" class="large-text"><br><small>Variables: {company_name}, {booking_id}</small></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="customer_body">Message Body</label></th>
                        <td>
                            <textarea name="customer_body" id="customer_body" class="large-text" rows="5"><?php echo esc_textarea( $customer_body ); ?></textarea>
                            <br><small>Variables: {company_name}, {booking_id}, {customer_name}. The invoice link will be appended automatically.</small>
                        </td>
                    </tr>
                </table>

                <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

                <h2>Admin Notification (New Booking Alert)</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="admin_email">Admin Email Address</label></th>
                        <td><input name="admin_email" type="email" id="admin_email" value="<?php echo esc_attr( $admin_email ); ?>" class="regular-text"><br><small>Leave blank to disable admin notifications.</small></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="admin_subject">Email Subject</label></th>
                        <td><input name="admin_subject" type="text" id="admin_subject" value="<?php echo esc_attr( $admin_subject ); ?>" class="large-text"><br><small>Variables: {booking_id}</small></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="admin_body">Message Body</label></th>
                        <td>
                            <textarea name="admin_body" id="admin_body" class="large-text" rows="5"><?php echo esc_textarea( $admin_body ); ?></textarea>
                            <br><small>Variables: {booking_id}, {customer_email}. The invoice link will be appended automatically.</small>
                        </td>
                    </tr>
                </table>
            </div>
            
            <p class="submit">
                <button type="submit" name="opa_save_settings" class="button button-primary">Save Changes</button>
            </p>
        </form>
    </div>
</div>

<script>
function opaSwitchTab(evt, tabId) {
    evt.preventDefault();
    // Hide all contents
    document.querySelectorAll('.opa-tab-content').forEach(function(el) {
        el.style.display = 'none';
    });
    // Remove active class from all tabs
    document.querySelectorAll('.nav-tab').forEach(function(el) {
        el.classList.remove('nav-tab-active');
    });
    // Show current content
    document.getElementById(tabId).style.display = 'block';
    // Add active class to current tab
    evt.currentTarget.classList.add('nav-tab-active');
}
</script>
