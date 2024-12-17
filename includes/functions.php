<?php

// Add a new tab to WooCommerce settings
add_filter('woocommerce_settings_tabs_array', 'add_cif_settings_tab', 50);

function add_cif_settings_tab($tabs) {
    $tabs['woo_cif'] = __('Checkout Invoice Fields Settings', 'cif');
    return $tabs;
}

// Get the settings fields
function get_cif_settings() {
    return array(
        array(
            'title' => __('Checkout invoice fields settings', 'cif'),
            'type'  => 'title',
            'id'    => 'cif_email_settings'
        ),
        array(
            'title'    => __('Invoice information emails', 'cif'),
            'desc'     => __('Enter emails separated by commas', 'cif'),
            'id'       => 'cif_email_addresses',
            'type'     => 'text',
            'default'  => '',
        ),
        array(
            'title'    => __('Send Email', 'cif'),
            'desc'     => __('Enable sending email for the invoice information', 'cif'),
            'id'       => 'cif_send_emails',
            'type'     => 'checkbox',
            'default'  => 'no', // Unchecked by default
        ),
        array(
            'type' => 'sectionend',
            'id'   => 'cif_email_settings'
        ),
    );
}

// Display the settings fields on the new tab
add_action('woocommerce_settings_woo_cif', 'display_cif_settings');

function display_cif_settings() {
    $settings = get_cif_settings();
    WC_Admin_Settings::output_fields($settings);
}

// Save the settings when the user clicks "Save changes"
add_action('woocommerce_update_options_woo_cif', 'save_cif_settings');

function save_cif_settings() {
    $settings = get_cif_settings();
    WC_Admin_Settings::save_fields($settings);
}

if ( !function_exists( 'get_order_meta' ) ) {
	function get_order_meta( $order, $key = '', $single = true, $context = 'edit' ) {
		if(is_int($order)){
			$order = wc_get_order((int)$order);
		}
		
		$value = $order->get_meta( $key, $single, $context );
        return $value;
	}
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'edit_invoice_fields', 9, 1 );    
function edit_invoice_fields( $order ) {
?>
    <div class="invoice_details">
        <h3><?php _e('Invoice info:', 'cif') ?><a href="#" class="edit_invoice">Edit</a></h3>
        <?php if(get_order_meta($order, '_billing_is_invoice', true) == 1){ ?>     
            <strong> <?php _e('Company name', 'cif') ?>: </strong><?php echo get_order_meta($order, '_billing_invoice_company', true) ?><br/>
            <strong> <?php _e('MOL', 'cif') ?>: </strong><?php echo get_order_meta($order, '_billing_invoice_mol', true) ?><br/>
            <strong> <?php _e('VAT', 'cif') ?>: </strong><?php echo get_order_meta($order, '_billing_invoice_eik', true) ?><br/>
            <strong> <?php _e('DDS', 'cif') ?>: </strong><?php echo get_order_meta($order, '_billing_invoice_vatnum', true) ?><br/>
            <strong> <?php _e('Address', 'cif') ?>: </strong><?php echo get_order_meta($order, '_billing_invoice_address', true) ?><br/>
            <strong> <?php _e('City', 'cif') ?>: </strong><?php echo get_order_meta($order, '_billing_invoice_town', true) ?><br/>
        <?php } ?>
    </div>
    <div class="edit_invoice_details">
        <h3><?php _e('Invoice info:', 'cif') ?></h3>
        <p class="form-row form-row-wide validate-required" id="billing_invoice_company_field" data-priority=""><label for="billing_invoice_company" class=""><?php _e('Company name', 'cif') ?>: <abbr class="required" title="задължително">*</abbr></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="billing_invoice_company" id="billing_invoice_company" value='<?php echo str_replace("'", '"', get_order_meta($order, '_billing_invoice_company', true)) ?>'>
        </span></p>
        <p class="form-row form-row-wide validate-required" id="billing_invoice_mol_field" data-priority=""><label for="billing_invoice_mol" class=""><?php _e('MOL', 'cif') ?>: <abbr class="required" title="задължително">*</abbr></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="billing_invoice_mol" id="billing_invoice_mol" value='<?php echo str_replace("'", '"', get_order_meta($order, '_billing_invoice_mol', true)) ?>'>
        </span></p>
        <p class="form-row form-row-wide validate-required" id="billing_invoice_eik_field" data-priority=""><label for="billing_invoice_eik" class=""><?php _e('VAT', 'cif') ?>: <abbr class="required" title="задължително">*</abbr></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="billing_invoice_eik" id="billing_invoice_eik" value='<?php echo str_replace("'", '"', get_order_meta($order, '_billing_invoice_eik', true)) ?>'>
        </span></p>
        <p class="form-row form-row-wide validate-required" id="billing_invoice_vatnum_field" data-priority=""><label for="billing_invoice_vatnum" class=""><?php _e('DDS', 'cif') ?>: </label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="billing_invoice_vatnum" id="billing_invoice_vatnum" value='<?php echo str_replace("'", '"', get_order_meta($order, '_billing_invoice_vatnum', true)) ?>'>
        </span></p>
        <p class="form-row form-row-wide validate-required" id="billing_invoice_address_field" data-priority=""><label for="billing_invoice_address" class=""><?php _e('Address', 'cif') ?>: <abbr class="required" title="задължително">*</abbr></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="billing_invoice_address" id="billing_invoice_address" value='<?php echo str_replace("'", '"', get_order_meta($order, '_billing_invoice_address', true)) ?>'>
        </span></p>
        <p class="form-row form-row-wide validate-required" id="billing_invoice_town_field" data-priority=""><label for="billing_invoice_town" class=""><?php _e('City', 'cif') ?>: <abbr class="required" title="задължително">*</abbr></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="billing_invoice_town" id="billing_invoice_town" value='<?php echo str_replace("'", '"', get_order_meta($order, '_billing_invoice_town', true)) ?>'>
        </span></p>
        <p>
        <a href="javascript:void(0);" class="save_invoice_details button-primary button" id="save_invoice_details" title="<?php _e('save','cif') ?>"><?php _e('save','cif') ?></a>
        </p>
    </div>
    <script>
        jQuery("#save_invoice_details").click(function(e){
            var order_id = <?php echo $order->get_id(); ?>;
            edit_invoice_details(order_id);
        });
    </script>
<?php 
}

add_filter( 'woocommerce_checkout_fields' , 'override_checkout_fields', 1, 1);

function override_checkout_fields( $fields ) {
    $fields['billing']['billing_is_invoice'] = array(
        'label'     => __('I want invoice', 'cif'),
        'type'          => 'checkbox',
        //'required'  => true,
        'class'     => array('form-row-wide'),
        'clear'     => true,
        'priority' => 120,
    );

    $fields['billing']['billing_invoice_company'] = array(
        'label'     => __('Company name', 'cif') . '<abbr class="required" title="Required">*</abbr>',
        'placeholder'  => __('', 'cif'),
        //'required'  => true,
        'class'     => array('form-row-wide cif-company-field', 'invoice'),
        'clear'     => true,
        'priority' => 130,
    );

    $fields['billing']['billing_invoice_mol'] = array(
        'label'     => __('MOL', 'cif') . '<abbr class="required" title="Required">*</abbr>',
        'placeholder'   => __('', 'cif'),
        //'required'  => true,
        'class'     => array('form-row-wide cif-mol-field', 'invoice'),
        'clear'     => true,
        'priority' => 140,
    );

    $fields['billing']['billing_invoice_eik'] = array(
        'label'     => __('VAT', 'cif') . '<abbr class="required" title="Required">*</abbr>',
        'placeholder'   => __('', 'cif'),
        //'required'  => true,
        'class'     => array('form-row-wide cif-eik-field', 'invoice'),
        'clear'     => true,
        'priority' => 150,
    );

    $fields['billing']['billing_invoice_vatnum'] = array(
        'label'     => __('DDS', 'cif'),
        'placeholder'   => __('', 'cif'),
        //'required'  => true,
        'class'     => array('form-row-wide cif-bulstat-field', 'invoice'),
        'clear'     => true,
        'priority' => 160,
    );

    $fields['billing']['billing_invoice_town'] = array(
        'label'     => __('City', 'cif') . '<abbr class="required" title="Required">*</abbr>',
        'placeholder'   => __('', 'cif'),
        //'required'  => true,
        'class'     => array('form-row-wide cif-city-field', 'invoice'),
        //'clear'     => true,
        'priority' => 170,
    );

    $fields['billing']['billing_invoice_address'] = array(
        'label'     => __('Address', 'cif') . '<abbr class="required" title="Required">*</abbr>',
        'placeholder'   => __('', 'cif'),
        //'required'  => true,
        'class'     => array('form-row-wide cif-address-field', 'invoice'),
        'clear'     => true,
        'priority' => 180,
    );
        
    return $fields;
}

add_action( 'wp_ajax_nopriv_notasuptoinvoice_handle_ajax', 'cif_handle_ajax' );
add_action( 'wp_ajax_cif_handle_ajax', 'cif_handle_ajax' );

function cif_handle_ajax(){
    $result = array();
		
    if( (int)$_GET['order_id'] > 0 ){
        $order = wc_get_order( (int)$_GET['order_id'] );
    } else {
        return;
    }
		
    if($_GET['action2'] == 'edit_invoice_details'){
        if(!empty($_GET['billing_invoice_company'])){
            $order->update_meta_data('_billing_is_invoice', 1);
        }else{
            $order->update_meta_data('_billing_is_invoice', 0);
        }
        $order->update_meta_data('_billing_invoice_company', sanitize_text_field($_GET['billing_invoice_company']));
        $order->update_meta_data('_billing_invoice_mol', sanitize_text_field($_GET['billing_invoice_mol']));
        $order->update_meta_data('_billing_invoice_eik', sanitize_text_field($_GET['billing_invoice_eik']));
        $order->update_meta_data('_billing_invoice_vatnum', sanitize_text_field($_GET['billing_invoice_vatnum']));
        $order->update_meta_data('_billing_invoice_town', sanitize_text_field($_GET['billing_invoice_town']));
        $order->update_meta_data('_billing_invoice_address', sanitize_text_field($_GET['billing_invoice_address']));
        
        $current_user = wp_get_current_user();
        $note = __('Invoice details has been changed by ', 'cif') . $current_user->display_name;
        $order->add_order_note( $note );
        $order->save();
                
    }

    $response = json_encode($result);

    echo $response;
    exit();
}

add_action( 'woocommerce_checkout_process', 'cif_checkout_field_process' );

function cif_checkout_field_process() {
   // write_log($_POST);
    global $woocommerce;
    $lang = get_bloginfo("language");

    if ( $lang == 'bg-BG' ) {
        $to_check = array(
            'billing_invoice_company' => __('Company name is required field', 'cif'),
            'billing_invoice_mol' => __('MOL is required field', 'cif'),
            'billing_invoice_eik' => __('VAT is required field', 'cif'),
            'billing_invoice_town' => __('Company city is required field', 'cif'),
            'billing_invoice_address' => __('Company address is required field', 'cif'),
        );
    } else {
        $to_check = array(
            'billing_invoice_company' => __('Company name is required field', 'cif'),
            //'billing_invoice_mol' => __('MOL is required field', 'cif'),
            'billing_invoice_eik' => __('VAT is required field', 'cif'),
            'billing_invoice_town' => __('Company city is required field', 'cif'),
            'billing_invoice_address' => __('Company address is required field', 'cif'),
        );
    }

    if ( !empty($_POST['billing_is_invoice']) ) {      
        foreach ( $to_check as $key => $value ) {
            if( empty($_POST[$key]) ) {
                if ( function_exists( 'wc_add_notice' ) ) {
                    wc_add_notice($value, 'error');
                }else{
                    $woocommerce->add_error(sprintf($value));
                }
            }
        }
    }
}

add_action('woocommerce_email_before_order_table', 'cif_display_email_data', 10, 3);

function cif_display_email_data($order, $sent_to_admin, $plain_text = false) {
    if(!empty(get_order_meta($order, '_billing_is_invoice', true))){
        ?>
        <table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
            <tr>
                <td style="text-align:left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
                    <h2><?php esc_html_e( 'Invoice info', 'cif' ); ?></h2>
                    <address class="address">
                        <strong><?php esc_html_e('Company name:', 'cif'); ?></strong>
                        <?php echo get_order_meta($order, '_billing_invoice_company', true); ?>
                        <br/>
                        <?php if( ! empty(get_order_meta($order, '_billing_invoice_mol', true)) ) : ?>
                        <strong><?php esc_html_e('MOL:', 'cif'); ?></strong>
                        <?php echo get_order_meta($order, '_billing_invoice_mol', true); ?>
                        <br/>
                        <?php endif; ?>
                        <strong><?php esc_html_e('VAT:', 'cif'); ?></strong>
                        <?php echo get_order_meta($order, '_billing_invoice_eik', true); ?>
                        <br/>
                        <?php if( ! empty(get_order_meta($order, '_billing_invoice_vatnum', true)) ) : ?>
                        <strong><?php esc_html_e('DDS:', 'cif'); ?></strong>
                        <?php echo get_order_meta($order, '_billing_invoice_vatnum', true); ?>
                        <br/>
                        <?php endif; ?>
                        <strong><?php esc_html_e('City:', 'cif'); ?></strong>
                        <?php echo get_order_meta($order, '_billing_invoice_town', true); ?>
                        <br/>
                        <strong><?php esc_html_e('Address:', 'cif'); ?></strong>
                        <?php echo get_order_meta($order, '_billing_invoice_address', true); ?>
                    </address>
                </td>
            </tr>
        </table>
        <?php
    }
}

add_action('woocommerce_thankyou', 'cif_display_order_data', 20);

function cif_display_order_data( $order_id ) {
	
	if( is_int($order_id) ){
		$order = wc_get_order((int)$order_id);
	}
	
	$billing_is_invoice = get_order_meta($order, '_billing_is_invoice', true);
	
    if(!empty($billing_is_invoice)){
		ob_start();
        ?>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th scope="col" colspan="2"><?php _e('Invoice info:', 'cif'); ?></th>
                    </tr>
                </thead>
                <tbody>              
                    <tr>
                        <td data-label=""><strong><?php
                        _e('Company name:', 'cif'); ?></strong></td>
                        <td data-label=""><?php
                        echo get_order_meta($order, '_billing_invoice_company', true); ?></td>
                    </tr>
                    <?php if( ! empty(get_order_meta($order, '_billing_invoice_mol', true)) ) : ?>
                        <tr>
                            <td data-label=""><strong><?php
                            _e('MOL:', 'cif'); ?></strong></td>
                            <td data-label=""><?php
                            echo get_order_meta($order, '_billing_invoice_mol', true); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td data-label=""><strong><?php
                        _e('VAT:', 'cif'); ?></strong></td>
                        <td data-label=""><?php
                        echo get_order_meta($order, '_billing_invoice_eik', true); ?></td>
                    </tr>
                    <?php if( ! empty(get_order_meta($order, '_billing_invoice_vatnum', true)) ) : ?>
                    <tr>
                        <td data-label=""><strong><?php
                        _e('DDS:', 'cif'); ?></strong></td>
                        <td data-label=""><?php
                        echo get_order_meta($order, '_billing_invoice_vatnum', true); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td data-label=""><strong><?php
                        _e('City:', 'cif'); ?></strong></td>
                        <td data-label=""><?php
                        echo get_order_meta($order, '_billing_invoice_town', true); ?></td>
                    </tr>
                    <tr>
                        <td data-label=""><strong><?php
                        _e('Address:', 'cif'); ?></strong></td>
                        <td data-label=""><?php
                        echo get_order_meta($order, '_billing_invoice_address', true); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php
		$table = ob_get_clean();
		echo $table;

        $send_emails = get_option('cif_send_emails', 'no');

        if ($send_emails === 'yes') {
            $blog_name = get_bloginfo('name');
            $blog_url = get_bloginfo('url');
            // Retrieve the email addresses saved in the settings
            $email_addresses = get_option('woo_cif_email_addresses', '');
    
            // Split the email addresses into an array
            $to = array_map('trim', explode(',', $email_addresses));
    
            $subject = __('Invoice information for order from ', 'cif') . $blog_name;
    
            $order_url = $blog_url . "/wp-admin/admin.php?page=wc-orders&action=edit&id=" . (int)$order_id;
            
            $edit_order_html = "<a href='{$order_url}'>" . __('See the order', 'cif') . "</a><br><br>";
            
            $message = "<h3>" . __('Client email: ', 'cif') . $order->get_billing_email() . "</h3>" . $edit_order_html . $table;
            $headers = array('Content-Type: text/html; charset=UTF-8');	
    
            add_filter('wp_mail_from_name', 'change_from_name');
            function change_from_name($original_name) {
                $blog_name = get_bloginfo('name');
                return $blog_name; // Change this to your desired From Name
            }
            
            wp_mail( $to, $subject, $message, $headers );
    
            // Remove the custom "From Name" filter to avoid affecting other emails
            remove_filter('wp_mail_from_name', 'change_from_name');
        }
    }
}  