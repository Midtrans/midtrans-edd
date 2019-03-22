<?php
/*
Plugin Name: Easy Digital Downloads - Midtrans Gateway
Plugin URL: 
Description: Midtrans Payment Gateway plugin for Easy Digital Downloads
Version: 2.1.2
Author: Wendy kurniawan Soesanto, Rizda Dwi Prasetya, Alexander Kevin
Author URI: 
Contributors: wendy0402, rizdaprasetya, aalexanderkevin

*/
//exit if opened directly
if ( ! defined( 'ABSPATH' ) ) exit;
DEFINE ('MT_PLUGIN_VERSION', get_file_data(__FILE__, array('Version' => 'Version'), false)['Version'] );

/**
 * Add new section for payment option
 */
// Section for Midtrans Payment
	function edd_midtrans_settings_section( $sections ) {
		$sections['midtrans'] = __( 'Midtrans', 'midtrans' );
		return $sections;
	}
	add_filter( 'edd_settings_sections_gateways', 'edd_midtrans_settings_section' );

// Section for Midtrans Installment Payment
	function edd_midtrans_installment_settings_section( $sections ) {
		$sections['midtrans_installment'] = __( 'Midtrans Online Installment', 'midtrans_installment' );
		return $sections;
	}
	add_filter( 'edd_settings_sections_gateways', 'edd_midtrans_installment_settings_section' );

// Section for Midtrans Offline Installment Payment
	function edd_midtrans_offinstallment_settings_section( $sections ) {
		$sections['midtrans_offinstallment'] = __( 'Midtrans Offline Installment', 'midtrans_offinstallment' );
		return $sections;
	}
	add_filter( 'edd_settings_sections_gateways', 'edd_midtrans_offinstallment_settings_section' );

// Section for Midtrans Promo Payment
	function edd_midtrans_promo_settings_section( $sections ) {
		$sections['midtrans_promo'] = __( 'Midtrans Promo', 'midtrans_promo' );
		return $sections;
	}
	add_filter( 'edd_settings_sections_gateways', 'edd_midtrans_promo_settings_section' );


// registers midtrans gateway
	function midtrans_register_gateway($gateways) {
		global $edd_options;
		$checkout_label = 'Online Payment via Midtrans';
		//check checkout label field from backend, then set if not null and not empty string
		if(isset($edd_options['mt_checkout_label']) and $edd_options['mt_checkout_label'] != ''){
			$checkout_label = $edd_options['mt_checkout_label'];
		}
		$gateways['midtrans'] = array(
			'admin_label' => 'Midtrans',
			'checkout_label' => __($checkout_label, 'midtrans')
		);
		return $gateways;
	}
	add_filter('edd_payment_gateways', 'midtrans_register_gateway');

// register midtrans installment gateway
function midtrans_installment_register_gateway($gateways) {
	global $edd_options;
	$checkout_label_installment = 'Credit Card Installment via Midtrans';
	//check checkout label field from backend, then set if not null and not empty string
	if(isset($edd_options['mt_checkout_label_installment']) and $edd_options['mt_checkout_label_installment'] != ''){
		$checkout_label_installment = $edd_options['mt_checkout_label_installment'];
	}
	$gateways['midtrans_installment'] = array(
		'admin_label' => 'Midtrans Online Installment',
		'checkout_label' => __($checkout_label_installment, 'midtrans')
	);
	return $gateways;
}
add_filter('edd_payment_gateways', 'midtrans_installment_register_gateway');

// register midtrans Offline installment gateway
function midtrans_offinstallment_register_gateway($gateways) {
	global $edd_options;
	$checkout_label_offinstallment = 'Credit Card Installment for any Bank via Midtrans';
	//check checkout label field from backend, then set if not null and not empty string
	if(isset($edd_options['mt_checkout_label_offinstallment']) and $edd_options['mt_checkout_label_offinstallment'] != ''){
		$checkout_label_offinstallment = $edd_options['mt_checkout_label_offinstallment'];
	}
	$gateways['midtrans_offinstallment'] = array(
		'admin_label' => 'Midtrans Offline Installment',
		'checkout_label' => __($checkout_label_offinstallment, 'midtrans_offinstallment')
	);
	return $gateways;
}
add_filter('edd_payment_gateways', 'midtrans_offinstallment_register_gateway');

// register midtrans promo gateway
function midtrans_promo_register_gateway($gateways) {
	global $edd_options;
	$checkout_label_promo = 'Promo Discount via Midtrans';
	//check checkout label field from backend, then set if not null and not empty string
	if(isset($edd_options['mt_checkout_label_promo']) and $edd_options['mt_checkout_label_promo'] != ''){
		$checkout_label_promo = $edd_options['mt_checkout_label_promo'];
	}
	$gateways['midtrans_promo'] = array(
		'admin_label' => 'Midtrans Promo',
		'checkout_label' => __($checkout_label_promo, 'midtrans')
	);
	return $gateways;
}
add_filter('edd_payment_gateways', 'midtrans_promo_register_gateway');

#To add currency Rp and IDR
#
function rupiah_currencies( $currencies ) {
	if(!array_key_exists('Rp', $currencies)){
		$currencies['Rp'] = __('Indonesian Rupiah ( Rp )', 'midtrans');
	}
	return $currencies;	
}
add_filter( 'edd_currencies', 'rupiah_currencies');

function midtrans_gateway_cc_form($purchase_data) {	
	global $edd_options;	
	if(isset($edd_options['mt_promo_code']) and $edd_options['mt_promo_code'] != ''){
				$promo = $edd_options['mt_promo_code'];
	}
	else {
		$promo = "onlinepromo";
	}
	edd_unset_cart_discount( $promo );
	return;
}
add_action('edd_midtrans_cc_form', 'midtrans_gateway_cc_form');

function midtrans_installment_gateway_cc_form($purchase_data) {
	global $edd_options;	
	if(isset($edd_options['mt_promo_code']) and $edd_options['mt_promo_code'] != ''){
		$promo = $edd_options['mt_promo_code'];
	}
	else {
		$promo = "onlinepromo";
	}	
	edd_unset_cart_discount( $promo );
	return;
}
add_action('edd_midtrans_installment_cc_form', 'midtrans_installment_gateway_cc_form');

function midtrans_offinstallment_gateway_cc_form($purchase_data) {
	global $edd_options;	
	if(isset($edd_options['mt_promo_code']) and $edd_options['mt_promo_code'] != ''){
		$promo = $edd_options['mt_promo_code'];
	}
	else {
		$promo = "onlinepromo";
	}	
	edd_unset_cart_discount( $promo );
	return;
}
add_action('edd_midtrans_offinstallment_cc_form', 'midtrans_offinstallment_gateway_cc_form');

function midtrans_promo_gateway_cc_form($purchase_data) { 
	global $edd_options;	
	if(isset($edd_options['mt_promo_code']) and $edd_options['mt_promo_code'] != ''){
		$promo = $edd_options['mt_promo_code'];
	}
	else {
		$promo = "onlinepromo";
	}		
	edd_set_cart_discount( $promo );

return;
}
add_action('edd_midtrans_promo_cc_form', 'midtrans_promo_gateway_cc_form');

// add form to display payment method images **unused**
function mt_payment_images_form() {
	ob_start(); ?>

	<fieldset id="edd_cc_fields" class="edd-midtrans-fields">
		<p class="edd-midtrans-profile-wrapper">
			<div>adadwadawdad</div>	
			<img src= <?php echo '"'.plugins_url( 'assets/logo/mandiri.png', __FILE__ ).'"'; ?> alt="Mandiri" style='width:64px; height:64px'>
			<img src="http://docs.veritrans.co.id/images/cc_icon.jpg" alt="CC">
			<div>dadd</div>
			<span class="edd-midtrans-profile-name"><?php echo $profile['name']; ?></span>
		</p>
cf3
		<div id="edd-midtrans-address-box"></div>
	</fieldset>

	<?php
	$form = ob_get_clean();
	echo $form;
}
// adds the settings to the Payment Gateways section
function midtrans_add_settings($settings) {
        $sandbox_key_url = 'https://dashboard.sandbox.midtrans.com/settings/config_info';
        $production_key_url = 'https://dashboard.midtrans.com/settings/config_info';

	$midtrans_settings = array(
		array(
			'id' => 'edd_midtrans_gateway_settings',
			'name' => '<strong>'.__('Midtrans Gateway Settings', 'midtrans').'</strong>',
			'desc' => __('Configure the gateway settings', 'midtrans'),
			'type' => 'header'
		),
		array(
			'id' => 'mt_checkout_label',
			'name' => __('Checkout Label', 'midtrans'),
			'desc' => __('<br>Payment gateway text label that will be shown as payment options to your customers (Default = "Online Payment via Midtrans")'),
			'type' => 'text',
		),
		array(
			'id' => 'mt_merchant_id',
			'name' => __('Merchant ID', 'midtrans'),
			'desc' => sprintf(__('<br>Input your Midtrans Merchant ID (e.g M012345). Get the ID <a href="%s" target="_blank">here</a>', 'midtrans' ),$sandbox_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_production_server_key',
			'name' => __('Production Server Key', 'midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans' ),$production_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_production_client_key',
			'name' => __('Production Client Key', 'midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans' ),$production_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_sandbox_server_key',
			'name' => __('Sandbox Server Key', 'midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans' ),$sandbox_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_sandbox_client_key',
			'name' => __('Sandbox Client Key', 'midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans' ),$sandbox_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_3ds',
			'name' => __('Enable 3D Secure', 'midtrans'),
			'desc' => __('You must enable 3D Secure. Please contact us if you wish to disable this feature in the Production environment.'),
			'type' => 'checkbox',
		),
		array(
			'id' => 'mt_save_card',
			'name' => __('Enable Save Card', 'midtrans'),
			'desc' => __('This will allow your customer to save their card on the payment popup, for faster payment flow on the following purchase'),
			'type' => 'checkbox',
		),
		array(
			'id' => 'mt_enable_redirect',
			'name' => __('Enable Payment Page Redirection', 'midtrans'),
			'desc' => __('This will redirect customer to Midtrans hosted payment page instead of popup payment page on your website. <br> <b>Leave it disabled if you are not sure</b>'),
			'type' => 'checkbox',	
		),	
		array(
			'id' => 'mt_enabled_payment',
			'name' => __('Allowed Payment Method', 'midtrans'),
			'desc' => __('<br>Customize allowed payment method, separate payment method code with coma. e.g: bank_transfer,credit_card.<br> <b>Leave it default if you are not sure</b>'),
			'type' => 'text',
		),					
		array(
			'id' => 'mt_custom_expiry',
			'name' => __('Custom Expiry', 'midtrans'),
			'desc' => __('<br>This will allow you to set custom duration on how long the transaction available to be paid.<br> example: 45 minutes'),
			'type' => 'text',
		),
		array(
			'id' => 'mt_custom_field',
			'name' => __('Custom fields', 'midtrans'),
			'desc' => __('<br>This will allow you to set custom fields that will be displayed on Midtrans dashboard. <br>Up to 3 fields are available, separate by coma (,) <br> Example:  Order from web, Processed', 'midtrans'),
			'type' => 'text',
		),				
	);
    if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
        $midtrans_settings = array( 'midtrans' => $midtrans_settings );
    }
	return array_merge($settings, $midtrans_settings);	
}
add_filter('edd_settings_gateways', 'midtrans_add_settings');

// adds the settings to the Midtrans Installment section
function midtrans_installment_add_settings($settings) {
        $sandbox_key_url = 'https://dashboard.sandbox.midtrans.com/settings/config_info';
        $production_key_url = 'https://dashboard.midtrans.com/settings/config_info';

	$midtrans_installment_settings = array(
		array(
			'id' => '_edd_midtrans_installment_gateway_settings',
			'name' => '<strong>'.__('Midtrans Online Installment Settings', 'midtrans').'</strong>',
			'type' => 'header'
		),
		array(
			'id' => 'mt_checkout_label_installment',
			'name' => __('Checkout Label Installment', 'midtrans_installment'),
			'desc' => __('<br>Payment gateway text label that will be shown as payment options to your customers (Default = "Credit Card Installment via Midtrans")'),
			'type' => 'text',
		),
		array(
			'id' => 'mt_installment_merchant_id',
			'name' => __('Merchant ID', 'midtrans'),
			'desc' => sprintf(__('<br>Input your Midtrans Merchant ID (e.g M012345). Get the ID <a href="%s" target="_blank">here</a>', 'midtrans' ),$sandbox_key_url),
			'type' => 'text',
		),			
		array(
			'id' => 'mt_installment_production_server_key',
			'name' => __('Production Server Key', 'midtrans_installment'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_installment' ),$production_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_installment_production_client_key',
			'name' => __('Production Client Key', 'midtrans_installment'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_installment' ),$production_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_installment_sandbox_server_key',
			'name' => __('Sandbox Server Key', 'midtrans_installment'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_installment' ),$sandbox_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_installment_sandbox_client_key',
			'name' => __('Sandbox Client Key', 'midtrans_installment'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_installment' ),$sandbox_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_installment_min_amount',
			'name' => __('Minimal Transaction Amount', 'midtrans_installment'),
			'desc' => __('<br>Minimal transaction amount allowed to be paid with installment. (amount in IDR, without comma or period) example: 500000 </br> if the transaction amount is below this value, customer will be redirected to Credit Card fullpayment page'),
			'type' => 'text',
		),	
		array(
			'id' => 'mt_installment_3ds',
			'name' => __('Enable 3D Secure', 'midtrans_installment'),
			'desc' => __('You must enable 3D Secure. Please contact us if you wish to disable this feature in the Production environment.'),
			'type' => 'checkbox',
		),
		array(
			'id' => 'mt_installment_save_card',
			'name' => __('Enable Save Card', 'midtrans_installment'),
			'desc' => __('This will allow your customer to save their card on the payment popup, for faster payment flow on the following purchase'),
			'type' => 'checkbox',
		),	
		array(
			'id' => 'mt_installment_enable_redirect',
			'name' => __('Enable Payment Page Redirection', 'midtrans_installment'),
			'desc' => __('This will redirect customer to Midtrans hosted payment page instead of popup payment page on your website. <br> <b>Leave it disabled if you are not sure</b>'),
			'type' => 'checkbox',	
		),				
		array(
			'id' => 'mt_installment_custom_field',
			'name' => __('Custom fields', 'midtrans_installment'),
			'desc' => __('<br>This will allow you to set custom fields that will be displayed on Midtrans dashboard. <br>Up to 3 fields are available, separate by coma (,) <br> Example:  Order from web, Processed', 'midtran_installment'),
			'type' => 'text',
		),				
	);
    if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
        $midtrans_installment_settings = array( 'midtrans_installment' => $midtrans_installment_settings );
    }
	return array_merge($settings, $midtrans_installment_settings);	
}
add_filter('edd_settings_gateways', 'midtrans_installment_add_settings');

function midtrans_offinstallment_add_settings($settings) {
        $sandbox_key_url = 'https://dashboard.sandbox.midtrans.com/settings/config_info';
        $production_key_url = 'https://dashboard.midtrans.com/settings/config_info';

	$midtrans_offinstallment_settings = array(
		array(
			'id' => '_edd_midtrans_offinstallment_gateway_settings',
			'name' => '<strong>'.__('Midtrans Online Offline Installment Settings', 'midtrans').'</strong>',
			'type' => 'header'
		),
		array(
			'id' => 'mt_checkout_label_offinstallment',
			'name' => __('Checkout Label Offline Installment', 'midtrans_offinstallment'),
			'desc' => __('<br>Payment gateway text label that will be shown as payment options to your customers (Default = "Credit Card Installment for any Bank via Midtrans")'),
			'type' => 'text',
		),
		array(
			'id' => 'mt_offinstallment_merchant_id',
			'name' => __('Merchant ID', 'midtrans'),
			'desc' => sprintf(__('<br>Input your Midtrans Merchant ID (e.g M012345). Get the ID <a href="%s" target="_blank">here</a>', 'midtrans' ),$sandbox_key_url),
			'type' => 'text',
		),	
		array(
			'id' => 'mt_offinstallment_production_server_key',
			'name' => __('Production Server Key', 'midtrans_offinstallment'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_offinstallment' ),$production_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_offinstallment_production_client_key',
			'name' => __('Production Client Key', 'midtrans_offinstallment'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_offinstallment' ),$production_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_offinstallment_sandbox_server_key',
			'name' => __('Sandbox Server Key', 'midtrans_offinstallment'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_offinstallment' ),$sandbox_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_offinstallment_sandbox_client_key',
			'name' => __('Sandbox Client Key', 'midtrans_offinstallment'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_offinstallment' ),$sandbox_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_offinstallment_min_amount',
			'name' => __('Minimal Transaction Amount', 'midtrans_offinstallment'),
			'desc' => __('<br>Minimal transaction amount allowed to be paid with installment. (amount in IDR, without comma or period) example: 500000 </br> if the transaction amount is below this value, customer will be redirected to Credit Card fullpayment page'),
			'type' => 'text',
		),	
		array(
			'id' => 'mt_offinstallment_bin_number',
			'name' => __('Allowed CC BINs', 'midtrans_offinstallment'),
			'desc' => __('<br>Fill with CC BIN numbers (or bank name) that you want to allow to use this payment button. </br>Separate BIN number with coma Example: 4,5,4811,bni,mandiri'),
			'type' => 'text',
		),			
		array(
			'id' => 'mt_offinstallment_3ds',
			'name' => __('Enable 3D Secure', 'midtrans_offinstallment'),
			'desc' => __('You must enable 3D Secure. Please contact us if you wish to disable this feature in the Production environment.'),
			'type' => 'checkbox',
		),
		array(
			'id' => 'mt_offinstallment_save_card',
			'name' => __('Enable Save Card', 'midtrans_offinstallment'),
			'desc' => __('This will allow your customer to save their card on the payment popup, for faster payment flow on the following purchase'),
			'type' => 'checkbox',
		),		
		array(
			'id' => 'mt_offinstallment_enable_redirect',
			'name' => __('Enable Payment Page Redirection', 'midtrans_offinstallment'),
			'desc' => __('This will redirect customer to Midtrans hosted payment page instead of popup payment page on your website. <br> <b>Leave it disabled if you are not sure</b>'),
			'type' => 'checkbox',	
		),		
		array(
			'id' => 'mt_offinstallment_custom_field',
			'name' => __('Custom fields', 'midtrans_offinstallment'),
			'desc' => __('<br>This will allow you to set custom fields that will be displayed on Midtrans dashboard. <br>Up to 3 fields are available, separate by coma (,) <br> Example: Order from web, Processed', 'midtran_offinstallment'),
			'type' => 'text',
		),				
	);
    if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
        $midtrans_offinstallment_settings = array( 'midtrans_offinstallment' => $midtrans_offinstallment_settings );
    }
	return array_merge($settings, $midtrans_offinstallment_settings);	
}
add_filter('edd_settings_gateways', 'midtrans_offinstallment_add_settings');

// adds the settings to the Midtrans Promo section
function midtrans_promo_add_settings($settings) {
        $sandbox_key_url = 'https://dashboard.sandbox.midtrans.com/settings/config_info';
        $production_key_url = 'https://dashboard.midtrans.com/settings/config_info';
	$midtrans_promo_settings = array(
		array(
			'id' => '_edd_midtrans_promo_gateway_settings',
			'name' => '<strong>'.__('Midtrans Promo Settings', 'midtrans_promo').'</strong>',
			'type' => 'header'
		),
		array(
			'id' => 'mt_checkout_label_promo',
			'name' => __('Checkout Label Promo', 'midtrans_promo'),
			'desc' => __('<br>Payment gateway text label that will be shown as payment options to your customers (Default = "Promo Discount via Midtrans")'),
			'type' => 'text',
		),	
		array(
			'id' => 'mt_promo_merchant_id',
			'name' => __('Merchant ID', 'midtrans'),
			'desc' => sprintf(__('<br>Input your Midtrans Merchant ID (e.g M012345). Get the ID <a href="%s" target="_blank">here</a>', 'midtrans' ),$sandbox_key_url),
			'type' => 'text',
		),			
		array(
			'id' => 'mt_promo_production_server_key',
			'name' => __('Production Server Key', 'midtrans_promo'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_promo' ),$production_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_promo_production_client_key',
			'name' => __('Production Client Key', 'midtrans_promo'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_promo' ),$production_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_promo_sandbox_server_key',
			'name' => __('Sandbox Server Key', 'midtrans_promo'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_promo' ),$sandbox_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_promo_sandbox_client_key',
			'name' => __('Sandbox Client Key', 'midtrans_promo'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_promo' ),$sandbox_key_url),
			'type' => 'text',
		),						
		array(
			'id' => 'mt_promo_enabled_payment',
			'name' => __('Allowed Payment Method for Promo', 'midtrans_promo'),
			'desc' => __('<br>Customize allowed payment method, separate payment method code with coma. e.g: bank_transfer,credit_card.<br>Leave it default if you are not sure.'),
			'type' => 'text',
		),	
		array(
			'id' => 'mt_promo_bin_number',
			'name' => __('Allowed CC BINs', 'midtrans_promo'),
			'desc' => __('<br>Fill with CC BIN numbers (or bank name) that you want to allow to use this payment button. </br>Separate BIN number with coma Example: 4,5,4811,bni,mandiri'),
			'type' => 'text',
		),	
		array(
			'id' => 'mt_promo_3ds',
			'name' => __('Enable 3D Secure', 'midtrans_promo'),
			'desc' => __('You must enable 3D Secure. Please contact us if you wish to disable this feature in the Production environment.'),
			'type' => 'checkbox',
		),			
		array(
			'id' => 'mt_promo_save_card',
			'name' => __('Enable Save Card', 'midtrans_promo'),
			'desc' => __('This will allow your customer to save their card on the payment popup, for faster payment flow on the following purchase'),
			'type' => 'checkbox',
		),	
		array(
			'id' => 'mt_promo_enable_redirect',
			'name' => __('Enable Payment Page Redirection', 'midtrans_promo'),
			'desc' => __('This will redirect customer to Midtrans hosted payment page instead of popup payment page on your website. <br> <b>Leave it disabled if you are not sure</b>'),
			'type' => 'checkbox',	
		),			
		array(
			'id' => 'mt_promo_code',
			'name' => __('Promo Code', 'midtrans_promo'),
			'desc' => __('<br>Promo Code that would be used for discount. Leave blank if you are not sure.'),
			'type' => 'text',
		),
		array(
			'id' => 'mt_promo_custom_field',
			'name' => __('Custom fields', 'midtrans_promo'),
			'desc' => __('<br>This will allow you to set custom fields that will be displayed on Midtrans dashboard. <br>Up to 3 fields are available, separate by coma (,) <br> Example:  Order from web, Processed', 'midtran_promo'),
			'type' => 'text',
		),			
	);
    if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
        $midtrans_promo_settings = array( 'midtrans_promo' => $midtrans_promo_settings );
    }
	return array_merge($settings, $midtrans_promo_settings);	
}
add_filter('edd_settings_gateways', 'midtrans_promo_add_settings');

function edd_midtrans_plugin_action_links( $links ) {

    $settings_link = array(
        'settings' => '<a href="' . admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=midtrans' ) . '" title="Settings">Settings</a>'
    );

    return array_merge( $settings_link, $links );

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'edd_midtrans_plugin_action_links' );

function edd_midtrans_installment_plugin_action_links( $links ) {

    $settings_link = array(
        'settings' => '<a href="' . admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=midtrans_installment' ) . '" title="Settings">Settings</a>'
    );

    return array_merge( $settings_link, $links );

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'edd_midtrans_installment_plugin_action_links' );

function edd_midtrans_offinstallment_plugin_action_links( $links ) {

    $settings_link = array(
        'settings' => '<a href="' . admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=midtrans_offinstallment' ) . '" title="Settings">Settings</a>'
    );

    return array_merge( $settings_link, $links );

}

function edd_midtrans_promo_plugin_action_links( $links ) {

    $settings_link = array(
        'settings' => '<a href="' . admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=midtrans_promo' ) . '" title="Settings">Settings</a>'
    );

    return array_merge( $settings_link, $links );

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'edd_midtrans_promo_plugin_action_links' );

// processes the payment-mode
function edd_midtrans_payment($purchase_data) {
	global $edd_options;
	require_once plugin_dir_path( __FILE__ ) . '/lib/Veritrans.php';
	/**********************************
	* set transaction mode
	**********************************/
	if(edd_is_test_mode()) {
		// set Sandbox credentials here
		Veritrans_Config::$isProduction = false;
		Veritrans_Config::$serverKey = $edd_options['mt_sandbox_server_key'];
		$client_key = $edd_options['mt_sandbox_client_key'];
		$snap_script_url = "https://app.sandbox.midtrans.com/snap/snap.js";
		$mixpanel_key = "9dcba9b440c831d517e8ff1beff40bd9";
	} else {
		// set Production credentials here
		Veritrans_Config::$isProduction = true;
		Veritrans_Config::$serverKey = $edd_options['mt_production_server_key'];
		$client_key = $edd_options['mt_production_client_key'];
		$snap_script_url = "https://app.midtrans.com/snap/snap.js";
		$mixpanel_key = "17253088ed3a39b1e2bd2cbcfeca939a";
	}
 
	// check for any stored errors
	$errors = edd_get_errors();
	if(!$errors) {
		$purchase_summary = edd_get_purchase_summary($purchase_data);
		/**********************************
		* setup the payment details
		**********************************/
		$payment = array( 
			'price' => $purchase_data['price'], 
			'date' => $purchase_data['date'], 
			'user_email' => $purchase_data['user_email'],
			'purchase_key' => $purchase_data['purchase_key'],
			'currency' => $edd_options['currency'],
			'downloads' => $purchase_data['downloads'],
			'cart_details' => $purchase_data['cart_details'],
			'user_info' => $purchase_data['user_info'],
			'status' => 'pending'
		);
 
		// record the pending payment
		$payment = edd_insert_payment($payment);
		// create item
		$transaction_details = array();
		foreach($purchase_data['cart_details'] as $item){
			$mt_item = array(
				'id' => $item['id'],
				'price' => $item['price'],
				'quantity' => $item['quantity'],
				'name' => $item['name']
			);
			array_push($transaction_details, $mt_item);
		};
        if (strlen($edd_options['mt_enabled_payment']) > 0){
          $enable_payment = explode(',', $edd_options['mt_enabled_payment']);
        }  		
		$edd_get_base_url = home_url( '/');
		$finish_url = esc_url_raw( add_query_arg( array( 'confirmation_page' => 'midtrans' ), home_url( 'index.php' ) ) );	
		$mt_params = array(
			'transaction_details' => array(
				'order_id' 			=> $payment,
				'gross_amount' 		=> $purchase_data['price']
				),
			'customer_details' 	=> array(
				'first_name' 		=> $purchase_data['user_info']['first_name'],
				'last_name' 		=> $purchase_data['user_info']['last_name'],
				'email' 			=> $purchase_data['user_info']['email'],
		        'phone'       		=> $purchase_data['post_data']['edd_phone'],		
				'billing_address' 	=> array(
					'first_name' 		=> $purchase_data['user_info']['first_name'],
					'last_name' 		=> $purchase_data['user_info']['last_name'],
					),
				),
			'enabled_payments' => $enable_payment,			
			'credit_card' => array(
        		'secure' => $edd_options['mt_3ds'] ? true : false,
    			),
			'callbacks' => array(
				'finish' => $finish_url
			),
			'item_details' => $transaction_details
		);

		//set custom expiry
        $custom_expiry_params = explode(" ",$edd_options['mt_custom_expiry']);
        if ( !empty($custom_expiry_params[1]) && !empty($custom_expiry_params[0]) ){
          $mt_params['expiry'] = array(
            'unit' => $custom_expiry_params[1], 
            'duration'  => (int)$custom_expiry_params[0],
          );
        }					
        if ($edd_options['mt_save_card'] && is_user_logged_in()){
          $mt_params['user_id'] = crypt( $purchase_data['user_info']['email'].$purchase_data['post_data']['edd_phone'] , Veritrans_Config::$serverKey );
          $mt_params['credit_card']['save_card'] = true;          
        }

        // add custom fields params
        $custom_fields_params = explode(",",$edd_options["mt_custom_field"]);
        if ( !empty($custom_fields_params[0]) ){
          $mt_params['custom_field1'] = $custom_fields_params[0];
          $mt_params['custom_field2'] = !empty($custom_fields_params[1]) ? $custom_fields_params[1] : null;
          $mt_params['custom_field3'] = !empty($custom_fields_params[2]) ? $custom_fields_params[2] : null;
        }
		// error_log('midtrans'.print_r($mt_params,true)); //debugan
   		// get rid of cart contents
		edd_empty_cart();
		// Snap Request Process
			try{          
				$snapResponse = Veritrans_Snap::createTransaction($mt_params);
				$snapRedirectUrl = $snapResponse->redirect_url;
				$snapToken = $snapResponse->token;
			}
			catch(Exception $e) {
  				echo 'Error: ' .$e->getMessage();
  				exit;
			}
		get_header();

		if ($edd_options["mt_enable_redirect"]){
			wp_redirect($snapRedirectUrl);
		}
		else{
		try{
		?>		
		<!-- start Mixpanel -->
		<script type="text/javascript">(function(c,a){if(!a.__SV){var b=window;try{var d,m,j,k=b.location,f=k.hash;d=function(a,b){return(m=a.match(RegExp(b+"=([^&]*)")))?m[1]:null};f&&d(f,"state")&&(j=JSON.parse(decodeURIComponent(d(f,"state"))),"mpeditor"===j.action&&(b.sessionStorage.setItem("_mpcehash",f),history.replaceState(j.desiredHash||"",c.title,k.pathname+k.search)))}catch(n){}var l,h;window.mixpanel=a;a._i=[];a.init=function(b,d,g){function c(b,i){var a=i.split(".");2==a.length&&(b=b[a[0]],i=a[1]);b[i]=function(){b.push([i].concat(Array.prototype.slice.call(arguments,0)))}}var e=a;"undefined"!==typeof g?e=a[g]=[]:g="mixpanel";e.people=e.people||[];e.toString=function(b){var a="mixpanel";"mixpanel"!==g&&(a+="."+g);b||(a+=" (stub)");return a};e.people.toString=function(){return e.toString(1)+".people (stub)"};l="disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(" ");for(h=0;h<l.length;h++)c(e,l[h]);var f="set set_once union unset remove delete".split(" ");e.get_group=function(){function a(c){b[c]=function(){call2_args=arguments;call2=[c].concat(Array.prototype.slice.call(call2_args,0));e.push([d,call2])}}for(var b={},d=["get_group"].concat(Array.prototype.slice.call(arguments,0)),c=0;c<f.length;c++)a(f[c]);return b};a._i.push([b,d,g])};a.__SV=1.2;b=c.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===c.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";d=c.getElementsByTagName("script")[0];d.parentNode.insertBefore(b,d)}})(document,window.mixpanel||[]);mixpanel.init("<?php echo $mixpanel_key ?>");</script> 
		<!-- TODO replace above with real mixpanel key -->
		<!-- end Mixpanel -->			
        	<script src="<?php echo $snap_script_url;?>" data-client-key="<?php echo $client_key;?>"></script>
        	<center><p><b><h2 class="alert alert-info">Please complete your payment...</h2></b></p>
        	<p>Continue payment via payment popup window.<br>Or click button below: </p>
	    	<button id="pay-button">Proceed to Payment</button> </center>
        	<script type="text/javascript">
        		function MixpanelTrackResult(snap_token, merchant_id, cms_name, cms_version, plugin_name, plugin_version, status, result) {
  					var eventNames = {
    					pay: 'pg-pay',
    					success: 'pg-success',
    					pending: 'pg-pending',
    					error: 'pg-error',
    					close: 'pg-close'
  					};
  					mixpanel.track(
    					eventNames[status], {
      						merchant_id: merchant_id,
      						cms_name: cms_name,
      						cms_version: cms_version,
      						plugin_name: plugin_name,
      						plugin_version: plugin_version,
      						snap_token: snap_token,
      						payment_type: result ? result.payment_type: null,
      						order_id: result ? result.order_id: null,
      						status_code: result ? result.status_code: null,
      						gross_amount: result && result.gross_amount ? Number(result.gross_amount) : null,
    					}
  					);
				}
				var MID_SNAP_TOKEN = "<?=$snapToken?>";
				var MID_MERCHANT_ID = "<?=$edd_options["mt_merchant_id"];?>";
				var MID_CMS_NAME = "easy digital downloads";
				var MID_CMS_VERSION = "<?php echo EDD_VERSION;?>";
				var MID_PLUGIN_NAME = "fullpayment";
				var MID_PLUGIN_VERSION = "<?php echo MT_PLUGIN_VERSION;?>";
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
        		var retryCount = 0;
        		var snapExecuted = false;
        		var intervalFunction = 0;
      			document.getElementById('pay-button').onclick = function(){
      				popup();
      			}	
      			popup();
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
      		function popup(){
        		intervalFunction = setInterval(function() {
        			try{
            			snap.pay(MID_SNAP_TOKEN,{
    						onSuccess: function(result){
      							MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'success', result);
      							window.location = result.finish_redirect_url; 
    						},
    						onPending: function(result){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pending', result);
      						if(result.hasOwnProperty("pdf_url")){
                				var PDF = "&pdf="+result.pdf_url;
              				}
              				else {var PDF = "";}
      						window.location = result.finish_redirect_url + PDF;
    						},
    						onError: function(result){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'error', result);
    						},
    						onClose: function(){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'close', null);
    						}
    					});
            			snapExecuted = true; // if SNAP popup executed, change flag to stop the retry.
         			}
          			catch (e){ 
            			retryCount++;
            			if(retryCount >= 10){
              				location.reload(); 
              				return;
            			}
          				console.log(e);
          				console.log("Snap not ready yet... Retrying in 1000ms!");
          			}
          			finally {
            			if (snapExecuted) {
              			 clearInterval(intervalFunction);
            			 // record 'pay' event to Mixpanel
      					 MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pay', null);	
           			}
          			}
        		}, 1000);
        	}
        	</script>        
			<?php          
      	}
      	catch (Exception $e) {
        error_log($e->getMessage());
      	}
      	} 
		get_footer();
	}
	else {
		$fail = true;
		// if errors are present, send the user back to the purchase page so they can be corrected
		edd_send_back_to_checkout('?payment-mode=' . $purchase_data['post_data']['edd-gateway']);
	}
}
add_action('edd_gateway_midtrans', 'edd_midtrans_payment');

// installment procces
function edd_midtrans_installment_payment($purchase_data) {
	global $edd_options;
	require_once plugin_dir_path( __FILE__ ) . '/lib/Veritrans.php';
	/**********************************
	* set transaction mode
	**********************************/
	if(edd_is_test_mode()) {
		// set Sandbox credentials here
		Veritrans_Config::$isProduction = false;
		Veritrans_Config::$serverKey = $edd_options['mt_installment_sandbox_server_key'];
		$client_key = $edd_options['mt_installment_sandbox_client_key'];
		$snap_script_url = "https://app.sandbox.midtrans.com/snap/snap.js";
		$mixpanel_key = "9dcba9b440c831d517e8ff1beff40bd9";		
	} else {
		// set Prouction credentials here
		Veritrans_Config::$isProduction = true;
		Veritrans_Config::$serverKey = $edd_options['mt_installment_production_server_key'];
		$client_key = $edd_options['mt_installment_production_client_key'];
		$snap_script_url = "https://app.midtrans.com/snap/snap.js";
		$mixpanel_key = "17253088ed3a39b1e2bd2cbcfeca939a";
	}
 
	// check for any stored errors
	$errors = edd_get_errors();
	if(!$errors) {
		$purchase_summary = edd_get_purchase_summary($purchase_data);
		/**********************************
		* setup the payment details
		**********************************/
		$payment = array( 
			'price' => $purchase_data['price'], 
			'date' => $purchase_data['date'], 
			'user_email' => $purchase_data['user_email'],
			'purchase_key' => $purchase_data['purchase_key'],
			'currency' => $edd_options['currency'],
			'downloads' => $purchase_data['downloads'],
			'cart_details' => $purchase_data['cart_details'],
			'user_info' => $purchase_data['user_info'],
			'status' => 'pending'
		);
 
		// record the pending payment
		$payment = edd_insert_payment($payment);
		// create item
		$transaction_details = array();
		foreach($purchase_data['cart_details'] as $item){
			$mt_item = array(
				'id' => $item['id'],
				'price' => $item['price'],
				'quantity' => $item['quantity'],
				'name' => $item['name']
			);
			array_push($transaction_details, $mt_item);
		};
		$edd_get_base_url = home_url( '/');
		$finish_url = esc_url_raw( add_query_arg( array( 'confirmation_page' => 'midtrans' ), home_url( 'index.php' ) ) );	
		$mt_params = array(
			'transaction_details' => array(
				'order_id' 			=> $payment,
				'gross_amount' 		=> $purchase_data['price']
				),
			'customer_details' 	=> array(
				'first_name' 		=> $purchase_data['user_info']['first_name'],
				'last_name' 		=> $purchase_data['user_info']['last_name'],
				'email' 			=> $purchase_data['user_info']['email'],
        		'phone'       		=> $purchase_data['post_data']['edd_phone'],
				'billing_address' 	=> array(
					'first_name' 		=> $purchase_data['user_info']['first_name'],
					'last_name' 		=> $purchase_data['user_info']['last_name'],
					),
				),
			'enabled_payments' => ['credit_card'],
			'credit_card' => array(
        		'secure' => $edd_options['mt_installment_3ds'] ? true : false,
    		),
			'callbacks' => array(
				'finish' => $finish_url,
			),
			'item_details' => $transaction_details
		);
        if ($edd_options['mt_installment_save_card'] && is_user_logged_in()){
          $mt_params['user_id'] = crypt( $purchase_data['user_info']['email'].$purchase_data['post_data']['edd_phone'] , Veritrans_Config::$serverKey );
        }
    	if ($edd_options['mt_installment_save_card']){
          $mt_params['credit_card']['save_card'] = true;
      	}          
        if($mt_params['transaction_details']['gross_amount'] >= $edd_options['mt_installment_min_amount'])
        {
          $terms      = array(3,6,9,12,15,18,21,24,27,30,33,36);
          $mt_params['credit_card']['installment']['required'] = true;
          $mt_params['credit_card']['installment']['terms'] = array(
              'bri' => $terms, 
              'maybank' => $terms,
              'bri' => $terms,
              'bni' => $terms, 
              'mandiri' => $terms, 
              'cimb' => $terms,
              'bca' => $terms
            );
        }
        // add custom fields params
        $custom_fields_params = explode(",",$edd_options["mt_installment_custom_field"]);
        if ( !empty($custom_fields_params[0]) ){
          $mt_params['custom_field1'] = $custom_fields_params[0];
          $mt_params['custom_field2'] = !empty($custom_fields_params[1]) ? $custom_fields_params[1] : null;
          $mt_params['custom_field3'] = !empty($custom_fields_params[2]) ? $custom_fields_params[2] : null;
        }                       
   		// get rid of cart contents
		edd_empty_cart();
		// Snap Request Process
			try{          
				$snapResponse = Veritrans_Snap::createTransaction($mt_params);
				$snapRedirectUrl = $snapResponse->redirect_url;
				$snapToken = $snapResponse->token;
			}
			catch(Exception $e) {
  				echo 'Error: ' .$e->getMessage();
  				exit;
			}
		get_header();

		if ($edd_options["mt_installment_enable_redirect"]){
			wp_redirect($snapRedirectUrl);
		}
		else{
		try{
		?>				
		<!-- start Mixpanel -->
		<script type="text/javascript">(function(c,a){if(!a.__SV){var b=window;try{var d,m,j,k=b.location,f=k.hash;d=function(a,b){return(m=a.match(RegExp(b+"=([^&]*)")))?m[1]:null};f&&d(f,"state")&&(j=JSON.parse(decodeURIComponent(d(f,"state"))),"mpeditor"===j.action&&(b.sessionStorage.setItem("_mpcehash",f),history.replaceState(j.desiredHash||"",c.title,k.pathname+k.search)))}catch(n){}var l,h;window.mixpanel=a;a._i=[];a.init=function(b,d,g){function c(b,i){var a=i.split(".");2==a.length&&(b=b[a[0]],i=a[1]);b[i]=function(){b.push([i].concat(Array.prototype.slice.call(arguments,0)))}}var e=a;"undefined"!==typeof g?e=a[g]=[]:g="mixpanel";e.people=e.people||[];e.toString=function(b){var a="mixpanel";"mixpanel"!==g&&(a+="."+g);b||(a+=" (stub)");return a};e.people.toString=function(){return e.toString(1)+".people (stub)"};l="disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(" ");for(h=0;h<l.length;h++)c(e,l[h]);var f="set set_once union unset remove delete".split(" ");e.get_group=function(){function a(c){b[c]=function(){call2_args=arguments;call2=[c].concat(Array.prototype.slice.call(call2_args,0));e.push([d,call2])}}for(var b={},d=["get_group"].concat(Array.prototype.slice.call(arguments,0)),c=0;c<f.length;c++)a(f[c]);return b};a._i.push([b,d,g])};a.__SV=1.2;b=c.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===c.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";d=c.getElementsByTagName("script")[0];d.parentNode.insertBefore(b,d)}})(document,window.mixpanel||[]);mixpanel.init("<?php echo $mixpanel_key ?>");</script> 
		<!-- TODO replace above with real mixpanel key -->
		<!-- end Mixpanel -->			
        	<script src="<?php echo $snap_script_url;?>" data-client-key="<?php echo $client_key;?>"></script>
        	<center><p><b><h2 class="alert alert-info">Please complete your payment...</h2></b></p>
        	<p>Continue payment via payment popup window.<br>Or click button below: </p>
	    	<button id="pay-button">Proceed to Payment</button> </center>
        	<script type="text/javascript">
        		function MixpanelTrackResult(snap_token, merchant_id, cms_name, cms_version, plugin_name, plugin_version, status, result) {
  					var eventNames = {
    					pay: 'pg-pay',
    					success: 'pg-success',
    					pending: 'pg-pending',
    					error: 'pg-error',
    					close: 'pg-close'
  					};
  					mixpanel.track(
    					eventNames[status], {
      						merchant_id: merchant_id,
      						cms_name: cms_name,
      						cms_version: cms_version,
      						plugin_name: plugin_name,
      						plugin_version: plugin_version,
      						snap_token: snap_token,
      						payment_type: result ? result.payment_type: null,
      						order_id: result ? result.order_id: null,
      						status_code: result ? result.status_code: null,
      						gross_amount: result && result.gross_amount ? Number(result.gross_amount) : null,
    					}
  					);
				}
				var MID_SNAP_TOKEN = "<?=$snapToken?>";
				var MID_MERCHANT_ID = "<?=$edd_options["mt_installment_merchant_id"];?>";
				var MID_CMS_NAME = "easy digital downloads";
				var MID_CMS_VERSION = "<?php echo EDD_VERSION;?>";
				var MID_PLUGIN_NAME = "online installment";
				var MID_PLUGIN_VERSION = "<?php echo MT_PLUGIN_VERSION;?>";
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
        		var retryCount = 0;
        		var snapExecuted = false;
        		var intervalFunction = 0;
      			document.getElementById('pay-button').onclick = function(){
      				popup();
      			}	
      			popup();
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
      		function popup(){
        		intervalFunction = setInterval(function() {
        			try{
            			snap.pay(MID_SNAP_TOKEN,{
    						onSuccess: function(result){
      							MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'success', result);
      							window.location = result.finish_redirect_url; 
    						},
    						onPending: function(result){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pending', result);
      						window.location = result.finish_redirect_url;
    						},
    						onError: function(result){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'error', result);
    						},
    						onClose: function(){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'close', null);
    						}
    					});
            			snapExecuted = true; // if SNAP popup executed, change flag to stop the retry.
         			}
          			catch (e){ 
            			retryCount++;
            			if(retryCount >= 10){
              				location.reload(); 
              				return;
            			}
          				console.log(e);
          				console.log("Snap not ready yet... Retrying in 1000ms!");
          			}
          			finally {
            			if (snapExecuted) {
              			 clearInterval(intervalFunction);
            			 // record 'pay' event to Mixpanel
      					 MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pay', null);	
           			}
          			}
        		}, 1000);
        	}
        	</script>        
			<?php          
      	}
      	catch (Exception $e) {
        error_log($e->getMessage());
      	}
      	} 
		get_footer();
	}
	else {
		$fail = true;
		// if errors are present, send the user back to the purchase page so they can be corrected
		edd_send_back_to_checkout('?payment-mode=' . $purchase_data['post_data']['edd-gateway']);
	}
}
add_action('edd_gateway_midtrans_installment', 'edd_midtrans_installment_payment');

// Offline Installment Procces
function edd_midtrans_offinstallment_payment($purchase_data) {
	global $edd_options;
	require_once plugin_dir_path( __FILE__ ) . '/lib/Veritrans.php';
	/**********************************
	* set transaction mode
	**********************************/
	if(edd_is_test_mode()) {
		// set Sandbox credentials here
		Veritrans_Config::$isProduction = false;
		Veritrans_Config::$serverKey = $edd_options['mt_offinstallment_sandbox_server_key'];
		$client_key = $edd_options['mt_offinstallment_sandbox_client_key'];
		$snap_script_url = "https://app.sandbox.midtrans.com/snap/snap.js";
		$mixpanel_key = "9dcba9b440c831d517e8ff1beff40bd9";		
	} else {
		// set Production credentials here
		Veritrans_Config::$isProduction = true;
		Veritrans_Config::$serverKey = $edd_options['mt_offinstallment_production_server_key'];
		$client_key = $edd_options['mt_offinstallment_production_client_key'];
		$snap_script_url = "https://app.midtrans.com/snap/snap.js";
		$mixpanel_key = "17253088ed3a39b1e2bd2cbcfeca939a";
	}
 
	// check for any stored errors
	$errors = edd_get_errors();
	if(!$errors) { 
		$purchase_summary = edd_get_purchase_summary($purchase_data);
		/**********************************
		* setup the payment details
		**********************************/
		$payment = array( 
			'price' => $purchase_data['price'], 
			'date' => $purchase_data['date'], 
			'user_email' => $purchase_data['user_email'],
			'purchase_key' => $purchase_data['purchase_key'],
			'currency' => $edd_options['currency'],
			'downloads' => $purchase_data['downloads'],
			'cart_details' => $purchase_data['cart_details'],
			'user_info' => $purchase_data['user_info'],
			'status' => 'pending'
		);
 
		// record the pending payment
		$payment = edd_insert_payment($payment);
		// create item
		$transaction_details = array();
		foreach($purchase_data['cart_details'] as $item){
			$mt_item = array(
				'id' => $item['id'],
				'price' => $item['price'],
				'quantity' => $item['quantity'],
				'name' => $item['name']
			);
			array_push($transaction_details, $mt_item);
		};
		$edd_get_base_url = home_url( '/');
		$finish_url = esc_url_raw( add_query_arg( array( 'confirmation_page' => 'midtrans' ), home_url( 'index.php' ) ) );	

         // add bin params    
        if (strlen($edd_options['mt_offinstallment_bin_number']) > 0){
          $bins = explode(',', $edd_options['mt_offinstallment_bin_number']);
          }		

		$mt_params = array(
			'transaction_details' => array(
				'order_id' 			=> $payment,
				'gross_amount' 		=> $purchase_data['price']
				),
			'customer_details' 	=> array(
				'first_name' 		=> $purchase_data['user_info']['first_name'],
				'last_name' 		=> $purchase_data['user_info']['last_name'],
				'email' 			=> $purchase_data['user_info']['email'],
        		'phone'       		=> $purchase_data['post_data']['edd_phone'],
				'billing_address' 	=> array(
					'first_name' 		=> $purchase_data['user_info']['first_name'],
					'last_name' 		=> $purchase_data['user_info']['last_name'],
					),
				),
			'enabled_payments' => ['credit_card'],
			'credit_card' => array(
        		'secure' => $edd_options['mt_offinstallment_3ds'] ? true : false,
        		'whitelist_bins' => $bins,        
    		),
			'callbacks' => array(
				'finish' => $finish_url,
			),
			'item_details' => $transaction_details
		);
        if ($edd_options['mt_offinstallment_save_card'] && is_user_logged_in()){
          $mt_params['user_id'] = crypt( $purchase_data['user_info']['email'].$purchase_data['post_data']['edd_phone'] , Veritrans_Config::$serverKey );
          $mt_params['credit_card']['save_card'] = true;
      	}          
        if($mt_params['transaction_details']['gross_amount'] >= $edd_options['mt_offinstallment_min_amount'])
        {
          $terms      = array(3,6,9,12,15,18,21,24,27,30,33,36);
          $mt_params['credit_card']['installment']['required'] = true;
          $mt_params['credit_card']['installment']['terms'] = array(
              'offline' => $terms
            );
         }
        // add custom fields params
        $custom_fields_params = explode(",",$edd_options["mt_offinstallment_custom_field"]);
        if ( !empty($custom_fields_params[0]) ){
          $mt_params['custom_field1'] = $custom_fields_params[0];
          $mt_params['custom_field2'] = !empty($custom_fields_params[1]) ? $custom_fields_params[1] : null;
          $mt_params['custom_field3'] = !empty($custom_fields_params[2]) ? $custom_fields_params[2] : null;
        }                       
   		// get rid of cart contents
		edd_empty_cart();
		// Snap Request Process
			try{          
				$snapResponse = Veritrans_Snap::createTransaction($mt_params);
				$snapRedirectUrl = $snapResponse->redirect_url;
				$snapToken = $snapResponse->token;
			}
			catch(Exception $e) {
  				echo 'Error: ' .$e->getMessage();
  				exit;
			}
		get_header();

		if ($edd_options["mt_offinstallment_enable_redirect"]){
			wp_redirect($snapRedirectUrl);
		}
		else{
		try{
		?>		
		<!-- start Mixpanel -->
		<script type="text/javascript">(function(c,a){if(!a.__SV){var b=window;try{var d,m,j,k=b.location,f=k.hash;d=function(a,b){return(m=a.match(RegExp(b+"=([^&]*)")))?m[1]:null};f&&d(f,"state")&&(j=JSON.parse(decodeURIComponent(d(f,"state"))),"mpeditor"===j.action&&(b.sessionStorage.setItem("_mpcehash",f),history.replaceState(j.desiredHash||"",c.title,k.pathname+k.search)))}catch(n){}var l,h;window.mixpanel=a;a._i=[];a.init=function(b,d,g){function c(b,i){var a=i.split(".");2==a.length&&(b=b[a[0]],i=a[1]);b[i]=function(){b.push([i].concat(Array.prototype.slice.call(arguments,0)))}}var e=a;"undefined"!==typeof g?e=a[g]=[]:g="mixpanel";e.people=e.people||[];e.toString=function(b){var a="mixpanel";"mixpanel"!==g&&(a+="."+g);b||(a+=" (stub)");return a};e.people.toString=function(){return e.toString(1)+".people (stub)"};l="disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(" ");for(h=0;h<l.length;h++)c(e,l[h]);var f="set set_once union unset remove delete".split(" ");e.get_group=function(){function a(c){b[c]=function(){call2_args=arguments;call2=[c].concat(Array.prototype.slice.call(call2_args,0));e.push([d,call2])}}for(var b={},d=["get_group"].concat(Array.prototype.slice.call(arguments,0)),c=0;c<f.length;c++)a(f[c]);return b};a._i.push([b,d,g])};a.__SV=1.2;b=c.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===c.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";d=c.getElementsByTagName("script")[0];d.parentNode.insertBefore(b,d)}})(document,window.mixpanel||[]);mixpanel.init("<?php echo $mixpanel_key ?>");</script> 
		<!-- TODO replace above with real mixpanel key -->
		<!-- end Mixpanel -->			
        	<script src="<?php echo $snap_script_url;?>" data-client-key="<?php echo $client_key;?>"></script>
        	<center><p><b><h2 class="alert alert-info">Please complete your payment...</h2></b></p>
        	<p>Continue payment via payment popup window.<br>Or click button below: </p>
	    	<button id="pay-button">Proceed to Payment</button> </center>
        	<script type="text/javascript">
        		function MixpanelTrackResult(snap_token, merchant_id, cms_name, cms_version, plugin_name, plugin_version, status, result) {
  					var eventNames = {
    					pay: 'pg-pay',
    					success: 'pg-success',
    					pending: 'pg-pending',
    					error: 'pg-error',
    					close: 'pg-close'
  					};
  					mixpanel.track(
    					eventNames[status], {
      						merchant_id: merchant_id,
      						cms_name: cms_name,
      						cms_version: cms_version,
      						plugin_name: plugin_name,
      						plugin_version: plugin_version,
      						snap_token: snap_token,
      						payment_type: result ? result.payment_type: null,
      						order_id: result ? result.order_id: null,
      						status_code: result ? result.status_code: null,
      						gross_amount: result && result.gross_amount ? Number(result.gross_amount) : null,
    					}
  					);
				}
				var MID_SNAP_TOKEN = "<?=$snapToken?>"; 
				var MID_MERCHANT_ID = "<?=$edd_options["mt_offinstallment_merchant_id"];?>"; 
				var MID_CMS_NAME = "easy digital downloads"; 
				var MID_CMS_VERSION = "<?php echo EDD_VERSION;?>"; 
				var MID_PLUGIN_NAME = "offline installment"; 
				var MID_PLUGIN_VERSION = "<?php echo MT_PLUGIN_VERSION;?>";      		
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
        		var retryCount = 0;
        		var snapExecuted = false;
        		var intervalFunction = 0;
      			document.getElementById('pay-button').onclick = function(){
      				popup();
      			}	
      			popup();
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
      		function popup(){
        		intervalFunction = setInterval(function() {
        			try{
            			snap.pay(MID_SNAP_TOKEN,{
    						onSuccess: function(result){
      							MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'success', result);
      							window.location = result.finish_redirect_url; 
    						},
    						onPending: function(result){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pending', result);
      						window.location = result.finish_redirect_url;
    						},
    						onError: function(result){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'error', result);
    						},
    						onClose: function(){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'close', null);
    						}
    					});
            			snapExecuted = true; // if SNAP popup executed, change flag to stop the retry.
         			}
          			catch (e){ 
            			retryCount++;
            			if(retryCount >= 10){
              				location.reload(); 
              				return;
            			}
          				console.log(e);
          				console.log("Snap not ready yet... Retrying in 1000ms!");
          			}
          			finally {
            			if (snapExecuted) {
              			 clearInterval(intervalFunction);
            			 // record 'pay' event to Mixpanel
      					 MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pay', null);	
           			}
          			}
        		}, 1000);
        	}
        	</script>        
			<?php          
      	}
      	catch (Exception $e) {
        error_log($e->getMessage());
      	}
      	} 
		get_footer();
	}
	else {
		$fail = true;
		// if errors are present, send the user back to the purchase page so they can be corrected
		edd_send_back_to_checkout('?payment-mode=' . $purchase_data['post_data']['edd-gateway']);
	}
}
add_action('edd_gateway_midtrans_offinstallment', 'edd_midtrans_offinstallment_payment');

// promo procces
function edd_midtrans_promo_payment($purchase_data) {
	global $edd_options;
	require_once plugin_dir_path( __FILE__ ) . '/lib/Veritrans.php';
	/**********************************
	* set transaction mode
	**********************************/
	if(edd_is_test_mode()) {
		// set Sandbox credentials here
		Veritrans_Config::$isProduction = false;
		Veritrans_Config::$serverKey = $edd_options['mt_promo_sandbox_server_key'];
		$client_key = $edd_options['mt_promo_sandbox_client_key'];
		$snap_script_url = "https://app.sandbox.midtrans.com/snap/snap.js";		
		$mixpanel_key = "9dcba9b440c831d517e8ff1beff40bd9";		
	} else {
		// set Production credentials here
		Veritrans_Config::$isProduction = true;
		Veritrans_Config::$serverKey = $edd_options['mt_promo_production_server_key'];
		$client_key = $edd_options['mt_promo_production_client_key'];
		$snap_script_url = "https://app.midtrans.com/snap/snap.js";		
		$mixpanel_key = "17253088ed3a39b1e2bd2cbcfeca939a";
	}

		// $discount_code = 'onlinepromo';
		// $result = edd_set_cart_discount( $discount_code );	
		// do_action( 'edd_cart_discounts_updated', $result );  
	// check for any stored errors
	$errors = edd_get_errors();
	if(!$errors) {
		$purchase_summary = edd_get_purchase_summary($purchase_data);
		/**********************************
		* setup the payment details
		**********************************/
		$payment = array( 
			'price' => $purchase_data['price'], 
			'date' => $purchase_data['date'], 
			'user_email' => $purchase_data['user_email'],
			'purchase_key' => $purchase_data['purchase_key'],
			'currency' => $edd_options['currency'],
			'downloads' => $purchase_data['downloads'],
			'cart_details' => $purchase_data['cart_details'],
			'user_info' => $purchase_data['user_info'],
			'status' => 'pending'
		);

		// record the pending payment
		$payment = edd_insert_payment($payment);
		// create item
		$transaction_details = array();
		foreach($purchase_data['cart_details'] as $item){
			$mt_item = array(
				'id' => $item['id'],
				'price' => $item['price'],
				'quantity' => $item['quantity'],
				'name' => $item['name']
			);
			array_push($transaction_details, $mt_item);
		};
		$edd_get_base_url = home_url( '/');
		$finish_url = esc_url_raw( add_query_arg( array( 'confirmation_page' => 'midtrans' ), home_url( 'index.php' ) ) );	

         // add bin params		
        if (strlen($edd_options['mt_promo_bin_number']) > 0){
          $bins = explode(',', $edd_options['mt_promo_bin_number']);
        }
        if (strlen($edd_options['mt_promo_enabled_payment']) > 0){
          $enable_payment = explode(',', $edd_options['mt_promo_enabled_payment']);
        }        

		$mt_params = array(
			'transaction_details' => array(
				'order_id' 			=> $payment,
				'gross_amount' 		=> $purchase_data['price']
				),
			'customer_details' 	=> array(
				'first_name' 		=> $purchase_data['user_info']['first_name'],
				'last_name' 		=> $purchase_data['user_info']['last_name'],
				'email' 			=> $purchase_data['user_info']['email'],
				'phone'				=> $purchase_data['post_data']['edd_phone'],
				'billing_address' 	=> array(
					'first_name' 		=> $purchase_data['user_info']['first_name'],
					'last_name' 		=> $purchase_data['user_info']['last_name'],
					),
				),
			'enabled_payments' => $enable_payment,
			'credit_card' => array(
				'secure' => $edd_options['mt_promo_3ds'] ? true : false,
				'required' => false,
				'whitelist_bins' => $bins,				
    		),
			'callbacks' => array(
				'finish' => $finish_url,
			),
			'item_details' => $transaction_details,
		);
        if ($edd_options["mt_promo_save_card"] && is_user_logged_in()){
          $mt_params['user_id'] = crypt( $purchase_data['user_info']['email'].$purchase_data['post_data']['edd_phone'] , Veritrans_Config::$serverKey );
        }
		if ($edd_options['mt_promo_save_card']){
      	  $mt_params['credit_card']['save_card'] = true;
    	}          
        // add custom fields params
        $custom_fields_params = explode(",",$edd_options["mt_promo_custom_field"]);
        if ( !empty($custom_fields_params[0]) ){
          $mt_params['custom_field1'] = $custom_fields_params[0];
          $mt_params['custom_field2'] = !empty($custom_fields_params[1]) ? $custom_fields_params[1] : null;
          $mt_params['custom_field3'] = !empty($custom_fields_params[2]) ? $custom_fields_params[2] : null;
        } 			      
// error_log('hehe '.print_r($mt_params,true)); //debugan
//    		edd_set_cart_discount( 'onlinepromo' )
// edd_cart_items_before();	

// function remove_edd_discount_code_display( $html, $discounts, $rate, $remove_url ) {
// 	error_log('diskon 1'.print_r($discounts,true)); //debugan
// 	$discounts = 'Discount applied - ' . $rate;
// 	return $discounts;	
// }
// add_filter( 'edd_get_cart_discounts_html', 'remove_edd_discount_code_display', 10, 4 );
// 	do_action('remove_edd_discount_code_display');

		edd_empty_cart();
		// Snap Request Process
			try{          
				$snapResponse = Veritrans_Snap::createTransaction($mt_params);
				$snapRedirectUrl = $snapResponse->redirect_url;
				$snapToken = $snapResponse->token;
			}
			catch(Exception $e) {
  				echo 'Error: ' .$e->getMessage();
  				exit;
			}
		get_header();

		if ($edd_options["mt_promo_enable_redirect"]){
			wp_redirect($snapRedirectUrl);
		}
		else{
		try{
		?>			
		<!-- start Mixpanel -->
		<script type="text/javascript">(function(c,a){if(!a.__SV){var b=window;try{var d,m,j,k=b.location,f=k.hash;d=function(a,b){return(m=a.match(RegExp(b+"=([^&]*)")))?m[1]:null};f&&d(f,"state")&&(j=JSON.parse(decodeURIComponent(d(f,"state"))),"mpeditor"===j.action&&(b.sessionStorage.setItem("_mpcehash",f),history.replaceState(j.desiredHash||"",c.title,k.pathname+k.search)))}catch(n){}var l,h;window.mixpanel=a;a._i=[];a.init=function(b,d,g){function c(b,i){var a=i.split(".");2==a.length&&(b=b[a[0]],i=a[1]);b[i]=function(){b.push([i].concat(Array.prototype.slice.call(arguments,0)))}}var e=a;"undefined"!==typeof g?e=a[g]=[]:g="mixpanel";e.people=e.people||[];e.toString=function(b){var a="mixpanel";"mixpanel"!==g&&(a+="."+g);b||(a+=" (stub)");return a};e.people.toString=function(){return e.toString(1)+".people (stub)"};l="disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(" ");for(h=0;h<l.length;h++)c(e,l[h]);var f="set set_once union unset remove delete".split(" ");e.get_group=function(){function a(c){b[c]=function(){call2_args=arguments;call2=[c].concat(Array.prototype.slice.call(call2_args,0));e.push([d,call2])}}for(var b={},d=["get_group"].concat(Array.prototype.slice.call(arguments,0)),c=0;c<f.length;c++)a(f[c]);return b};a._i.push([b,d,g])};a.__SV=1.2;b=c.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===c.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";d=c.getElementsByTagName("script")[0];d.parentNode.insertBefore(b,d)}})(document,window.mixpanel||[]);mixpanel.init("<?php echo $mixpanel_key ?>");</script> 
		<!-- TODO replace above with real mixpanel key -->
		<!-- end Mixpanel -->			
        	<script src="<?php echo $snap_script_url;?>" data-client-key="<?php echo $client_key;?>"></script>
        	<center><p><b><h2 class="alert alert-info">Please complete your payment...</h2></b></p>
        	<p>Continue payment via payment popup window.<br>Or click button below: </p>
	    	<button id="pay-button">Proceed to Payment</button> </center>
        	<script type="text/javascript">
        		function MixpanelTrackResult(snap_token, merchant_id, cms_name, cms_version, plugin_name, plugin_version, status, result) {
  					var eventNames = {
    					pay: 'pg-pay',
    					success: 'pg-success',
    					pending: 'pg-pending',
    					error: 'pg-error',
    					close: 'pg-close'
  					};
  					mixpanel.track(
    					eventNames[status], {
      						merchant_id: merchant_id,
      						cms_name: cms_name,
      						cms_version: cms_version,
      						plugin_name: plugin_name,
      						plugin_version: plugin_version,
      						snap_token: snap_token,
      						payment_type: result ? result.payment_type: null,
      						order_id: result ? result.order_id: null,
      						status_code: result ? result.status_code: null,
      						gross_amount: result && result.gross_amount ? Number(result.gross_amount) : null,
    					}
  					);
				}
				var MID_SNAP_TOKEN = "<?=$snapToken?>"; 
				var MID_MERCHANT_ID = "<?=$edd_options["mt_promo_merchant_id"];?>"; 
				var MID_CMS_NAME = "easy digital downloads"; 
				var MID_CMS_VERSION = "<?php echo EDD_VERSION;?>"; 
				var MID_PLUGIN_NAME = "bin promo"; 
				var MID_PLUGIN_VERSION = "<?php echo MT_PLUGIN_VERSION;?>";      		
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
        		var retryCount = 0;
        		var snapExecuted = false;
        		var intervalFunction = 0;
      			document.getElementById('pay-button').onclick = function(){
      				popup();
      			}	
      			popup();
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
      		function popup(){
        		intervalFunction = setInterval(function() {
        			try{
            			snap.pay(MID_SNAP_TOKEN,{
    						onSuccess: function(result){
      							MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'success', result);
      							window.location = result.finish_redirect_url; 
    						},
    						onPending: function(result){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pending', result);
      						window.location = result.finish_redirect_url;
    						},
    						onError: function(result){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'error', result);
    						},
    						onClose: function(){
      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'close', null);
    						}
    					});
            			snapExecuted = true; // if SNAP popup executed, change flag to stop the retry.
         			}
          			catch (e){ 
            			retryCount++;
            			if(retryCount >= 10){
              				location.reload(); 
              				return;
            			}
          				console.log(e);
          				console.log("Snap not ready yet... Retrying in 1000ms!");
          			}
          			finally {
            			if (snapExecuted) {
              			 clearInterval(intervalFunction);
            			 // record 'pay' event to Mixpanel
      					 MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pay', null);	
           			}
          			}
        		}, 1000);
        	}
        	</script>        
			<?php          
      	}
      	catch (Exception $e) {
        error_log($e->getMessage());
      	}
      	} 
		get_footer();
	}
	else {
		$fail = true;
		// if errors are present, send the user back to the purchase page so they can be corrected
		edd_send_back_to_checkout('?payment-mode=' . $purchase_data['post_data']['edd-gateway']);
	}
}
add_action('edd_gateway_midtrans_promo', 'edd_midtrans_promo_payment');

// to get notification from veritrans
function edd_midtrans_notification(){
	global $edd_options;
	require_once plugin_dir_path( __FILE__ ) . '/lib/Veritrans.php';
	if(edd_is_test_mode()){
		// set Sandbox credentials here
		Veritrans_Config::$serverKey = $edd_options['mt_sandbox_server_key'];
		Veritrans_Config::$isProduction = false;
	}else {
		// set Production credentials here
		Veritrans_Config::$serverKey = $edd_options['mt_production_server_key'];
		Veritrans_Config::$isProduction = true;
	}
	$notif = new Veritrans_Notification();
	$transaction = $notif->transaction_status;
	$fraud = $notif->fraud_status;
	$order_id = $notif->order_id;
	
	if ($transaction == 'capture') {
		if ($fraud == 'challenge') {
			edd_insert_payment_note( $order_id, __( 'Midtrans Challenged Payment', 'edd-midtrans' ) );			
			edd_update_payment_status($order_id, 'pending');
		}
		else if ($fraud == 'accept') {
			edd_insert_payment_note( $order_id, __( 'Midtrans Payment Completed', 'edd-midtrans' ) );			
		 	edd_update_payment_status($order_id, 'complete');
		}
	}
	else if ($notif->transaction_status != 'credit_card' && $transaction == 'settlement') {
		edd_insert_payment_note( $order_id, __( 'Midtrans Payment Completed', 'edd-midtrans' ) );		
		edd_update_payment_status($order_id, 'complete');
	}
	else if ($transaction == 'pending') {
		edd_insert_payment_note( $order_id, __( 'Midtrans Awaiting Payment', 'edd-midtrans' ) );
		edd_update_payment_status($order_id, 'pending');
	}	
	else if ($transaction == 'cancel') {
		edd_insert_payment_note( $order_id, __( 'Midtrans Cancelled Payment', 'edd-midtrans' ) );
		edd_update_payment_status($order_id, 'failed');
	}
	else if ($transaction == 'expire') {
		edd_insert_payment_note( $order_id, __( 'Midtrans Expired Payment', 'edd-midtrans' ) );
	 	edd_update_payment_status($order_id, 'failed');
	}
	else if ($transaction == 'deny') {
		edd_insert_payment_note( $order_id, __( 'Midtrans Expired Payment', 'edd-midtrans' ) );
	 	edd_update_payment_status($order_id, 'failed');
	}
};
add_action( 'edd_midtrans_notification', 'edd_midtrans_notification' );

function edd_listen_for_midtrans_notification() {
	global $edd_options;
	// check if payment url http://site.com/?edd-listener=veritrans
	if ( isset( $_GET['edd-listener'] ) && $_GET['edd-listener'] == 'midtrans' ) {
		// error_log('masuk edd_listen_for_veritrans_notification, '.$_GET['edd-listener']); //debugan
		do_action( 'edd_midtrans_notification' );
	}
	if ( isset( $_GET['confirmation_page'] ) && $_GET['confirmation_page'] == 'midtrans' ) {
		$order = $_REQUEST['order_id'];
		$status = $_REQUEST['transaction_status'];
		if (isset( $_GET['edd-listener'])){
			edd_send_to_success_page();
		}
		else{
			if ($status == 'capture'){
				$_SESSION['pdf'] = "";
 				edd_send_to_success_page();
 			}
 			else if ($status == 'pending'){
				if ($_REQUEST['pdf']){
				$_SESSION['pdf'] = $_REQUEST['pdf'];
				error_log('pdf nih' .  $_SESSION['pdf']);	
				}
				else{
					$_SESSION['pdf'] = "";
				}				
 				edd_send_to_success_page();
 			}	
 		}	
	}
}
add_action( 'init', 'edd_listen_for_midtrans_notification' );

function mid_edd_display_checkout_fields() {
?>
    <p id="edd-phone-wrap">
        <label class="edd-label" for="edd-phone">Phone Number</label>
        <span class="edd-description">
        	Enter your phone number so we can get in touch with you.
        </span>
        <input class="edd-input" type="text" name="edd_phone" id="edd-phone" placeholder="Phone Number" />
    </p>
    <?php
}
add_action( 'edd_purchase_form_user_info_fields', 'mid_edd_display_checkout_fields' );

/**
 * Make phone number required
 * Add more required fields here if you need to
 */
function mid_edd_required_checkout_fields( $required_fields ) {
    $required_fields['edd_phone'] = array(
        'error_id' => 'invalid_phone_number',
        'error_message' => 'Please enter a valid Phone number'
    );
    return $required_fields;
}
add_filter( 'edd_purchase_form_required_fields', 'mid_edd_required_checkout_fields' );

/**
 * Set error if phone number field is empty
 * You can do additional error checking here if required
 */
function mid_edd_validate_checkout_fields( $valid_data, $data ) {
    if ( empty( $data['edd_phone'] ) ) {
        edd_set_error( 'invalid_phone', 'Please enter your phone number.' );
    }
}
add_action( 'edd_checkout_error_checks', 'mid_edd_validate_checkout_fields', 10, 2 );

/**
 * Store the custom field data into EDD's payment meta
 */
function mid_edd_store_custom_fields( $payment_meta ) {

	if( did_action( 'edd_purchase' ) ) {
		$payment_meta['phone'] = isset( $_POST['edd_phone'] ) ? sanitize_text_field( $_POST['edd_phone'] ) : '';
	}

	return $payment_meta;
}
add_filter( 'edd_payment_meta', 'mid_edd_store_custom_fields');


/**
 * Add the phone number to the "View Order Details" page
 */
function mid_edd_view_order_details( $payment_meta, $user_info ) {
	$phone = isset( $payment_meta['phone'] ) ? $payment_meta['phone'] : 'none';
?>
    <div class="column-container">
    	<div class="column">
    		<strong>Phone: </strong>
    		 <?php echo $phone; ?>
    	</div>
    </div>
<?php
}
add_action( 'edd_payment_personal_details_list', 'mid_edd_view_order_details', 10, 2 );

/**
 * Add a {phone} tag for use in either the purchase receipt email or admin notification emails
 */
function mid_edd_add_email_tag() {

	edd_add_email_tag( 'phone', 'Customer\'s phone number', 'mid_edd_email_tag_phone' );
}
add_action( 'edd_add_email_tags', 'mid_edd_add_email_tag' );

/**
 * The {phone} email tag
 */
function mid_edd_email_tag_phone( $payment_id ) {
	$payment_data = edd_get_payment_meta( $payment_id );
	return $payment_data['phone'];
}

remove_action( 'edd_checkout_form_top', 'edd_discount_field', -1 );

/**
 * Applies filters to the success page content.
 *
 * @param string $content Content before filters
 * @return string $content Filtered content
 *
 * TODO : handle status changed from pending to settlemen
 */
function edd_midtrans_page_content( $content ) {
    error_log('masuk custom');
	// Check if we're on the success page
	if (edd_is_success_page()) {
		if ($_SESSION['pdf']){
    		$message  = '<div class="edd-midtrans">';
    		$message .= '<h3>Payment Instruction</h3>';
    		$message .= '<p><a href="' . $_SESSION['pdf'] . '" target="_blank">' . $_SESSION['pdf'] . '</a></p>' ;
   			$message .= '</div>';
			error_log('masuk isset dan pdf'. $_SESSION['pdf'].'yeye');
			if (has_filter('edd_payment_confirm_' . $_GET['payment-confirmation'])) {
            	$content = apply_filters('edd_payment_confirm_' . $_GET['payment-confirmation'], $content);
        	}
			return $content . $message;
		}
		else return $content;
	}
	// Fallback to returning the default content
	else
		return $content;
}
add_filter( 'the_content', 'edd_midtrans_page_content' );
