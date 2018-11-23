<?php
/*
Plugin Name: Easy Digital Downloads - Midtrans Gateway
Plugin URL: 
Description: Midtrans Payment Gateway plugin for Easy Digital Downloads
Version: 1.0
Author: Wendy kurniawan Soesanto, Rizda Dwi Prasetya
Author URI: 
Contributors: wendy0402, rizdaprasetya

*/
//exit if opened directly
if ( ! defined( 'ABSPATH' ) ) exit;

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

#To add payment status challenge
function add_edd_payment_statuses( $payment_statuses ) {
    $payment_statuses['on_process']   = 'On Process';    
    $payment_statuses['challenge']   = 'Challenge';
    $payment_statuses['cancel']   = 'Cancel';
    return $payment_statuses;   
}
add_filter( 'edd_payment_statuses', 'add_edd_payment_statuses' );

/**
 * Registers challenge statuses as post statuses so we can use them in Payment History navigation
 */
function register_post_type_statuses() {
 
    // Payment Statuses
    register_post_status( 'challenge', array(
        'label'                     => _x( 'Challenge', 'challenge, payment status', 'edd' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Challange <span class="count">(%s)</span>', 'Challenge <span class="count">(%s)</span>', 'edd' )
    ) );
    register_post_status( 'cancel', array(
        'label'                     => _x( 'Cancel', 'cancel, payment status', 'edd' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Cancel <span class="count">(%s)</span>', 'Cancel <span class="count">(%s)</span>', 'edd' )
    ) );
    register_post_status( 'on_process', array(
        'label'                     => _x( 'On Process', 'on_process, payment status', 'edd' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'On Process <span class="count">(%s)</span>', 'On Process <span class="count">(%s)</span>', 'edd' )
    ) );    
}
add_action( 'init', 'register_post_type_statuses' );
 
/**
 * Adds challenge payment statuses to the Payment History navigation
 */
function edd_payments_new_views( $views ) {
     
    $views['challenge']  = sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'status' => 'challenge', 'paged' => FALSE ) ), 'Challenge' ); 
    $views['cancel']  = sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'status' => 'cancel', 'paged' => FALSE ) ), 'Cancel' );
    $views['on_process']  = sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'status' => 'on_process', 'paged' => FALSE ) ), 'On Process' );     
    return $views;
 
}
add_filter( 'edd_payments_table_views', 'edd_payments_new_views' );

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
			'id' => 'mt_production_api_key',
			'name' => __('Production Server Key', 'midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Production</b> Midtrans Server Key. Get the key <a href="%s" target="_blank">here</a>', 'midtrans' ),$production_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_sandbox_api_key',
			'name' => __('Sandbox Server Key', 'midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox</b> Midtrans Server Key. Get the key <a href="%s" target="_blank">here</a>', 'midtrans' ),$sandbox_key_url),
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
			'id' => 'mt_enabled_payment',
			'name' => __('Allowed Payment Method', 'midtrans'),
			'desc' => __('<br>Customize allowed payment method, separate payment method code with coma. e.g: bank_transfer,credit_card.<br>Leave it default if you are not sure.'),
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
			'id' => 'mt_installment_production_api_key',
			'name' => __('Production Server Key', 'midtrans_installment'),
			'desc' => sprintf(__('<br>Input your <b>Production</b> Midtrans Server Key. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_installment' ),$sandbox_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_installment_sandbox_api_key',
			'name' => __('Sandbox Server Key', 'midtrans_installment'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox</b> Midtrans Server Key. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_installment' ),$sandbox_key_url),
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
			'name' => __('Enable 3D Secure', 'midtrans_promo'),
			'desc' => __('You must enable 3D Secure. Please contact us if you wish to disable this feature in the Production environment.'),
			'type' => 'checkbox',
		),
		array(
			'id' => 'mt_installment_save_card',
			'name' => __('Enable Save Card', 'midtrans_promo'),
			'desc' => __('This will allow your customer to save their card on the payment popup, for faster payment flow on the following purchase'),
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
			'id' => 'mt_offinstallment_production_api_key',
			'name' => __('Production Server Key', 'midtrans_offinstallment'),
			'desc' => sprintf(__('<br>Input your <b>Production</b> Midtrans Server Key. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_offinstallment' ),$production_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_offinstallment_sandbox_api_key',
			'name' => __('Sandbox Server Key', 'midtrans_offinstallment'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox</b> Midtrans Server Key. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_offinstallment' ),$sandbox_key_url),
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
			'name' => __('Enable 3D Secure', 'midtrans_promo'),
			'desc' => __('You must enable 3D Secure. Please contact us if you wish to disable this feature in the Production environment.'),
			'type' => 'checkbox',
		),
		array(
			'id' => 'mt_offinstallment_save_card',
			'name' => __('Enable Save Card', 'midtrans_promo'),
			'desc' => __('This will allow your customer to save their card on the payment popup, for faster payment flow on the following purchase'),
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
			'id' => 'mt_promo_production_server_key',
			'name' => __('Production Server Key', 'midtrans_promo'),
			'desc' => sprintf(__('<br>Input your <b>Production</b> Midtrans Server Key. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_promo' ),$production_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_promo_sandbox_server_key',
			'name' => __('Sandbox Server Key', 'midtrans_promo'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox</b> Midtrans Server Key. Get the key <a href="%s" target="_blank">here</a>', 'midtrans_promo' ),$sandbox_key_url),
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
			'id' => 'mt_promo_save_card',
			'name' => __('Enable Save Card', 'midtrans_promo'),
			'desc' => __('This will allow your customer to save their card on the payment popup, for faster payment flow on the following purchase'),
			'type' => 'checkbox',
		),
		array(
			'id' => 'mt_promo_3ds',
			'name' => __('Enable 3D Secure', 'midtrans_promo'),
			'desc' => __('You must enable 3D Secure. Please contact us if you wish to disable this feature in the Production environment.'),
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
		// set test credentials here
		Veritrans_Config::$isProduction = false;
		Veritrans_Config::$serverKey = $edd_options['mt_sandbox_api_key'];
	} else {
		// set live credentials here
		Veritrans_Config::$isProduction = true;
		Veritrans_Config::$serverKey = $edd_options['mt_production_api_key'];
	}
 
	// check for any stored errors
	$errors = edd_get_errors();
	if(!$errors) {
 
		$purchase_summary = edd_get_purchase_summary($purchase_data);
 		// error_log('purchase data: '.print_r($purchase_data,true)); //debugan
 		// error_log('purchase summary: '.print_r($purchase_summary,true)); //debugan
 		// error_log('plugin_dir_path : '.plugin_dir_path(__FILE__)); //debugan
		/**********************************
		* setup the payment details
		**********************************/
 		// error_log(json_encode($purchase_data, true));
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
error_log('midtrans'.print_r($mt_params,true));                
		// error_log('mt_3ds '.$edd_options['mt_3ds']); //debugan
   		// get rid of cart contents
		edd_empty_cart();
		// Redirect to veritrans
		$snapToken = Veritrans_Snap::getSnapToken($mt_params);	
		// error_log('mt_params: '.print_r($mt_params,true)); //debugan
		wp_redirect( Veritrans_Snap::getRedirectUrl($mt_params) );

		exit;
	} else {
		$fail = true; // errors were detected
	}
 
	if( $fail !== false ) {
		// if errors are present, send the user back to the purchase page so they can be corrected
		edd_send_back_to_checkout('?payment-mode=' . $purchase_data['post_data']['edd-gateway']);
	}
}

// $merchant_payment_confirmed = true;		
 
// if($merchant_payment_confirmed) { // this is used when processing credit cards on site
 
// 	// // once a transaction is successful, set the purchase to complete
//	// edd_update_payment_status($payment, 'complete');
 
// 	// // go to the success page			
// edd_send_to_success_page();
 
// } else {
// 	$fail = true; // payment wasn't recorded
// }
add_action('edd_gateway_midtrans', 'edd_midtrans_payment');

// installment procces
function edd_midtrans_installment_payment($purchase_data) {
	global $edd_options;
	require_once plugin_dir_path( __FILE__ ) . '/lib/Veritrans.php';
	/**********************************
	* set transaction mode
	**********************************/
	if(edd_is_test_mode()) {
		// set test credentials here
		Veritrans_Config::$isProduction = false;
		Veritrans_Config::$serverKey = $edd_options['mt_installment_sandbox_api_key'];
	} else {
		// set live credentials here
		Veritrans_Config::$isProduction = true;
		Veritrans_Config::$serverKey = $edd_options['mt_installment_production_api_key'];
	}
 
	// check for any stored errors
	$errors = edd_get_errors();
	if(!$errors) {
 
		$purchase_summary = edd_get_purchase_summary($purchase_data);
 		// error_log('purchase data: '.print_r($purchase_data,true)); //debugan
 		// error_log('purchase summary: '.print_r($purchase_summary,true)); //debugan
 		// error_log('plugin_dir_path : '.plugin_dir_path(__FILE__)); //debugan
		/**********************************
		* setup the payment details
		**********************************/
 		// error_log(json_encode($purchase_data, true));
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
		// Redirect to veritrans
		$snapToken = Veritrans_Snap::getSnapToken($mt_params);	
		// error_log('mt_params: '.print_r($mt_params,true)); //debugan
		wp_redirect( Veritrans_Snap::getRedirectUrl($mt_params) );
		exit;
	} else {
		$fail = true; // errors were detected
	}
 
	if( $fail !== false ) {
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
		// set test credentials here
		Veritrans_Config::$isProduction = false;
		Veritrans_Config::$serverKey = $edd_options['mt_offinstallment_sandbox_api_key'];
	} else {
		// set live credentials here
		Veritrans_Config::$isProduction = true;
		Veritrans_Config::$serverKey = $edd_options['mt_offinstallment_production_api_key'];
	}
 
	// check for any stored errors
	$errors = edd_get_errors();
	if(!$errors) {
 
		$purchase_summary = edd_get_purchase_summary($purchase_data);
 		// error_log('purchase data: '.print_r($purchase_data,true)); //debugan
 		// error_log('purchase summary: '.print_r($purchase_summary,true)); //debugan
 		// error_log('plugin_dir_path : '.plugin_dir_path(__FILE__)); //debugan
		/**********************************
		* setup the payment details
		**********************************/
 		// error_log(json_encode($purchase_data, true));
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
		// Redirect to veritrans
		$snapToken = Veritrans_Snap::getSnapToken($mt_params);	
		// error_log('mt_params: '.print_r($mt_params,true)); //debugan
		wp_redirect( Veritrans_Snap::getRedirectUrl($mt_params) );
		exit;
	} else {
		$fail = true; // errors were detected
	}
	if( $fail !== false ) {
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
		// set test credentials here
		Veritrans_Config::$isProduction = false;
		Veritrans_Config::$serverKey = $edd_options['mt_promo_sandbox_server_key'];
	} else {
		// set live credentials here
		Veritrans_Config::$isProduction = true;
		Veritrans_Config::$serverKey = $edd_options['mt_promo_production_server_key'];
	}

		// $discount_code = 'onlinepromo';
		// $result = edd_set_cart_discount( $discount_code );	
		// do_action( 'edd_cart_discounts_updated', $result );  
	// check for any stored errors
	$errors = edd_get_errors();
	if(!$errors) {
 
		$purchase_summary = edd_get_purchase_summary($purchase_data);
 		// error_log('purchase data: '.print_r($purchase_data,true)); //debugan
 		// error_log('purchasem summary: '.print_r($purchase_summary,true)); //debugan
 		// error_log('plugin_dir_path : '.plugin_dir_path(__FILE__)); //debugan
		/**********************************
		* setup the payment details
		**********************************/
 		// error_log(json_encode($purchase_data, true));
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
error_log('hehe '.print_r($mt_params,true));
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
		// Redirect to veritrans
		$snapToken = Veritrans_Snap::getSnapToken($mt_params);	
		// error_log('mt_params: '.print_r($mt_params,true)); //debugan
		wp_redirect( Veritrans_Snap::getRedirectUrl($mt_params) );
		exit;
	} else {
		$fail = true; // errors were detected
	}
 
	if( $fail !== false ) {
		// if errors are present, send the user back to the purchase page so they can be corrected
		edd_send_back_to_checkout('?payment-mode=' . $purchase_data['post_data']['edd-gateway']);
	}
}
add_action('edd_gateway_midtrans_promo', 'edd_midtrans_promo_payment');

/**
 * Get Enabled Payment from backend settings
 * @return array $enabled_payment
 **/
function edd_get_mtpayment_ops()
{
	global $edd_options;
	//get 3ds opts from backend
	Veritrans_Config::$is3ds = $edd_options['mt_promo_3ds'] ? true : false;
	// error_log('mt_3ds '.$edd_options['mt_3ds']); //debugan
	// error_log('credit_card '.$edd_options['mt_credit_card']); //debugan
// error_log('enabled payments array'.print_r($enabled_payments,true)); //debugan
    return $enabled_payments;
}
// to get notification from veritrans
function edd_midtrans_notification(){
	global $edd_options;
	require_once plugin_dir_path( __FILE__ ) . '/lib/Veritrans.php';
	if(edd_is_test_mode()){
		// set test credentials here
		// error_log('masuk test mode');  //debugan
		Veritrans_Config::$serverKey = $edd_options['mt_sandbox_api_key'];
		Veritrans_Config::$isProduction = false;
	}else {
		// set test credentials here
		// error_log('masuk production mode'); //debugan
		Veritrans_Config::$serverKey = $edd_options['mt_production_api_key'];
		Veritrans_Config::$isProduction = true;
	}
	// error_log('serverKey: '.Veritrans_Config::$serverKey); //debugan
	// error_log('isProduction: '.Veritrans_Config::$isProduction); //debugan
	
	$notif = new Veritrans_Notification();
	// error_log('$notif '.print_r($notif)); //debugan
	$transaction = $notif->transaction_status;
	$fraud = $notif->fraud_status;
	$order_id = $notif->order_id;
	// error_log('$order_id '.$order_id); //debugan
	// error_log('$fraud '.$fraud); //debugan
	// error_log('$transaction '.$transaction); //debugan
	
	if ($transaction == 'capture') {
		if ($fraud == 'challenge') {
		 	// TODO Set payment status in merchant's database to 'challenge'
			edd_update_payment_status($order_id, 'challenge');
			// error_log('challenge gan!'); //debugan
		}
		else if ($fraud == 'accept') {
		 	edd_update_payment_status($order_id, 'complete');
			// error_log('accepted gan!'); //debugan
		}
	}
	else if ($notif->transaction_status != 'credit_card' && $transaction == 'settlement') {
		edd_update_payment_status($order_id, 'complete');
			// error_log('accepted gan!'); //debugan
	}
	else if ($transaction == 'cancel') {
		edd_update_payment_status($order_id, 'cancel');
			// error_log('cancelled gan!'); //debugan
	}
	else if ($transaction == 'deny') {
	 	edd_update_payment_status($order_id, 'failed');
			// error_log('denied gan!'); //debugan
	}
	else if ($transaction == 'deny') {
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
				edd_update_payment_status($order, 'pending');
 				edd_send_to_success_page();
				do_shortcode('[edd_receipt payment_method="0" error="error cuk"]');				
 			}
 			else if ($status == 'pending'){
				edd_update_payment_status($order, 'pending');
				$content = do_shortcode('[shortcode discount="0"]');
				edd_filter_success_page_content($content);
 				edd_send_to_success_page();
				do_shortcode('[edd_receipt payment_method="0" error="error cuk"]');				
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

function midtrans_edd_thank_customer()
{
    if (function_exists('edd_is_success_page') && !edd_is_success_page()) {
        return;
    }
    $message = '<h2>Your purchase was successful</h2>';
    if ($message) {
        return $message;
    }
    return null;
}