<?php

defined('ABSPATH') or die('You are not authorized to access!');



add_action('plugins_loaded', 'wan_load_textdomain');
function wan_load_textdomain() {
	load_plugin_textdomain( 'dutch-address-autocomplete', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}


//add_action( 'woocommerce_order_status_processing', 'add_user_data_to_profile' );
function add_user_data_to_profile( $order_id ) {
	$user = get_user_by( 'email', $_POST['billing_email'] );
	update_user_meta( $user->ID, 'billing_address_2', sanitize_text_field( $_POST['billing_address_2_1'] ).' '.sanitize_text_field( $_POST['billing_address_3'] ) );
	update_user_meta( $user->ID, 'shipping_address_2', sanitize_text_field( $_POST['shipping_address_2_1'] ).' '.sanitize_text_field( $_POST['shipping_address_3'] ) );
}





/*Database creation and installing data */

global $life_db_version;
$life_db_version = '1.0';


//register_activation_hook( __FILE__, 'ew_install_zipcode_data' );
//register_deactivation_hook(__FILE__, 'ew_remove_zipcode_data');
//register_deactivation_hook (__FILE__, '');






