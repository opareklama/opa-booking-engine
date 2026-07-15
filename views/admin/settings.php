<div class="wrap">
    <h1>Plugin Settings</h1>
    <p>Configure company details and global settings for the Opa Booking Engine.</p>
    
    <?php
    if ( isset( $_POST['opa_save_settings'] ) && wp_verify_nonce( $_POST['opa_settings_nonce'] ?? '', 'opa_save_settings' ) ) {
        update_option( 'opa_company_name', sanitize_text_field( $_POST['company_name'] ?? '' ) );
        update_option( 'opa_company_address', sanitize_textarea_field( $_POST['company_address'] ?? '' ) );
        update_option( 'opa_vat_number', sanitize_text_field( $_POST['vat_number'] ?? '' ) );
        update_option( 'opa_admin_email', sanitize_email( $_POST['admin_email'] ?? '' ) );
        echo '<div class="updated notice"><p>Settings saved successfully.</p></div>';
    }
    
    $company_name = get_option( 'opa_company_name', 'Opa Reklama' );
    $company_address = get_option( 'opa_company_address', '' );
    $vat_number = get_option( 'opa_vat_number', '' );
    $admin_email = get_option( 'opa_admin_email', get_option('admin_email') );
    ?>
    
    <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin-top: 20px; max-width: 600px;">
        <form method="post" action="">
            <?php wp_nonce_field( 'opa_save_settings', 'opa_settings_nonce' ); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="company_name">Company Name</label></th>
                    <td><input name="company_name" type="text" id="company_name" value="<?php echo esc_attr( $company_name ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="company_address">Company Address</label></th>
                    <td><textarea name="company_address" id="company_address" class="large-text" rows="4"><?php echo esc_textarea( $company_address ); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="vat_number">VAT Number</label></th>
                    <td><input name="vat_number" type="text" id="vat_number" value="<?php echo esc_attr( $vat_number ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="admin_email">Notification Email</label></th>
                    <td><input name="admin_email" type="email" id="admin_email" value="<?php echo esc_attr( $admin_email ); ?>" class="regular-text"><br><small>Email where booking notifications should be sent.</small></td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" name="opa_save_settings" class="button button-primary">Save Changes</button>
            </p>
        </form>
    </div>
</div>
