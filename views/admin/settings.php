<div class="wrap">
    <h1>Plugin Settings</h1>
    <p>Configure company details and global settings for the Opa Booking Engine.</p>
    
    <?php
    if ( isset( $_POST['opa_save_settings'] ) && wp_verify_nonce( $_POST['opa_settings_nonce'] ?? '', 'opa_save_settings' ) ) {
        update_option( 'opa_company_name', sanitize_text_field( $_POST['company_name'] ?? '' ) );
        update_option( 'opa_company_address', sanitize_textarea_field( $_POST['company_address'] ?? '' ) );
        update_option( 'opa_vat_number', sanitize_text_field( $_POST['vat_number'] ?? '' ) );
        update_option( 'opa_admin_email', sanitize_email( $_POST['admin_email'] ?? '' ) );
        update_option( 'opa_enable_emails', isset( $_POST['enable_emails'] ) ? 'yes' : 'no' );
        update_option( 'opa_email_body', wp_kses_post( $_POST['email_body'] ?? '' ) );
        echo '<div class="updated notice"><p>Settings saved successfully.</p></div>';
    }
    
    $company_name = get_option( 'opa_company_name', 'Opa Reklama' );
    $company_address = get_option( 'opa_company_address', '' );
    $vat_number = get_option( 'opa_vat_number', '' );
    $admin_email = get_option( 'opa_admin_email', get_option('admin_email') );
    $enable_emails = get_option( 'opa_enable_emails', 'yes' );
    $email_body = get_option( 'opa_email_body', "Thank you for booking with us! Your booking has been successfully received.\n\nWe will process it shortly." );
    ?>
    
    <h2 class="nav-tab-wrapper" style="margin-top: 20px;">
        <a href="#tab-general" class="nav-tab nav-tab-active" onclick="opaSwitchTab(event, 'tab-general')">General & Invoice</a>
        <a href="#tab-email" class="nav-tab" onclick="opaSwitchTab(event, 'tab-email')">Email Settings</a>
    </h2>

    <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-top: none; box-shadow: 0 1px 1px rgba(0,0,0,.04); max-width: 800px;">
        <form method="post" action="">
            <?php wp_nonce_field( 'opa_save_settings', 'opa_settings_nonce' ); ?>
            
            <div id="tab-general" class="opa-tab-content">
                <table class="form-table">
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
                </table>
            </div>

            <div id="tab-email" class="opa-tab-content" style="display: none;">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="enable_emails">Enable Emails</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="enable_emails" id="enable_emails" value="1" <?php checked( $enable_emails, 'yes' ); ?>>
                                Send automated confirmation emails to customers
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="admin_email">Admin Notification Email</label></th>
                        <td><input name="admin_email" type="email" id="admin_email" value="<?php echo esc_attr( $admin_email ); ?>" class="regular-text"><br><small>Email where admin booking notifications should be sent.</small></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="email_body">Customer Email Message</label></th>
                        <td>
                            <textarea name="email_body" id="email_body" class="large-text" rows="5"><?php echo esc_textarea( $email_body ); ?></textarea>
                            <br><small>This message will be shown at the top of the customer's email.</small>
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
