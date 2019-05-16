<?php
/*
Plugin Name: EDD - Midtrans Gateway
Plugin URL: 
Description: Midtrans Payment Gateway plugin for Easy Digital Downloads
Version: 2.3.0
Author: Midtrans
Author URI: 

*/
//exit if opened directly
if ( ! defined( 'ABSPATH' ) ) exit;
DEFINE ('EDD_MIDTRANS_PLUGIN_VERSION', get_file_data(__FILE__, array('Version' => 'Version'), false)['Version'] );

/*
|--------------------------------------------------------------------------
| CONSTANTS
|--------------------------------------------------------------------------
*/
define( 'EDDMIDTRANS_DIR', plugin_dir_path( __FILE__ ) );

/*
|--------------------------------------------------------------------------
| INCLUDES
|--------------------------------------------------------------------------
*/
include_once( EDDMIDTRANS_DIR . 'includes/edd-midtrans.php' );
include_once( EDDMIDTRANS_DIR . 'includes/edd-midtrans-installment.php' );
include_once( EDDMIDTRANS_DIR . 'includes/edd-midtrans-installmentoff.php' );
include_once( EDDMIDTRANS_DIR . 'includes/edd-midtrans-promo.php' );
require_once plugin_dir_path( __FILE__ ) . '/lib/Veritrans.php';

#To add currency Rp and IDR
#
function midtrans_gateway_rupiah_currencies( $currencies ) {
	if(!array_key_exists('Rp', $currencies)){
		$currencies['Rp'] = __('Indonesian Rupiah ( Rp )', 'edd-midtrans');
	}
	return $currencies;	
}
add_filter( 'edd_currencies', 'midtrans_gateway_rupiah_currencies');


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

	if ( isset( $_GET['confirmation_page'] ) && $_GET['confirmation_page'] == 'midtrans'  && wp_verify_nonce($_GET['nonce'], 'edd_midtrans_gateway' . $_REQUEST['order_id'] )) {
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

// remove_action( 'edd_checkout_form_top', 'edd_discount_field', -1 );

/**
 * Applies filters to the success page content.
 *
 * @param string $content Content before filters
 * @return string $content Filtered content
 *
 * TODO : handle status changed from pending to settlemen
 */
function edd_midtrans_page_content( $content ) {
	// Check if we're on the success page
	if (edd_is_success_page()) {
		if ($_SESSION['pdf']){
    		$message  = '<div class="edd-midtrans">';
    		$message .= '<h3>Payment Instruction</h3>';
    		$message .= '<p><a href="' . $_SESSION['pdf'] . '" target="_blank">' . $_SESSION['pdf'] . '</a></p>' ;
   			$message .= '</div>';
			if (has_filter('edd_payment_confirm_' . $_GET['payment-confirmation'])) {
            	$content = apply_filters('edd_payment_confirm_' . $_GET['payment-confirmation'], $content);
        	}
        	$_SESSION['pdf'] = "";
			return $content . $message;
		}
		else return $content;
	}
	// Fallback to returning the default content
	else
		return $content;
}
add_filter( 'the_content', 'edd_midtrans_page_content' );
