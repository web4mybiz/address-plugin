<?php 
class DaacOverride{

    function override(){
        add_filter('woocommerce_default_address_fields', array($this,'override_default_address_fields'), 20,1);
        add_filter( 'woocommerce_checkout_fields' , array($this,'override_billing_fields') );
        add_filter( 'woocommerce_checkout_fields' , array($this,'override_shipping_fields') );
        add_filter( 'woocommerce_default_address_fields', array($this,'customize_checkout_fields') );
        add_filter( 'woocommerce_localisation_address_formats', array($this,'custom_address_format'), 20 );
        add_action( 'woocommerce_checkout_update_order_meta', array($this,'update_order_meta') );
        add_action( 'personal_options_update', array($this,'save_extra_user_profile_fields') );
        add_action( 'edit_user_profile_update', array($this,'save_extra_user_profile_fields') );
        add_action( 'woocommerce_checkout_order_processed', array($this,'save_address2_profile_fields') );
        add_action('plugins_loaded', array($this,'text_domain'));
    }

    function ajax(){
        add_action( 'wp_ajax_nopriv_post_postcode', array($this,'post_postcode') ); //guest
        add_action( 'wp_ajax_post_postcode', array($this,'post_postcode')  );//loggged usrs
    }

    function textDomain(){

    }

    function override_default_address_fields($address_fields){

        $address_fields['address_1']['label'] =  __( 'Street', 'dutch-address-autocomplete');
        $address_fields['address_1']['placeholder'] = __( 'Street', 'dutch-address-autocomplete');
        $address_fields['address_1']['required'] = true;
        return $address_fields;

    }

    function override_billing_fields( $fields ) {
        
            //E-MAILADRES
        $fields['billing']['billing_email'] = array(
            'type'          => 'email',
            'label'         => __( 'Email Address', 'dutch-address-autocomplete' ),
            'placeholder'   => _x( '', 'placeholder', 'dutch-address-autocomplete' ),
            'required'      => true,
            'class'         => array(' '),
            'clear'         => true
        );
        $fields['billing']['billing_address_2'] = array(
            'type'          => 'hidden',
            'class'         => array(' '),
            'clear'         => true
            );
        $fields['billing']['billing_address_2_1']['placeholder'] = '';
        $fields['billing']['billing_address_2_1']['label'] = __( 'House Number', 'dutch-address-autocomplete'); 
        $fields['billing']['billing_address_2_1']['required'] = true;
        
        $fields['billing']['billing_address_3']['placeholder'] = '';
        $fields['billing']['billing_address_3']['label'] = __( 'Ext', 'dutch-address-autocomplete'); 
        $fields['billing']['billing_address_3']['required'] = false;
        

        $fields['billing']['billing_city']['placeholder'] = '';
        $fields['billing']['billing_city']['label'] =__( 'City', 'dutch-address-autocomplete');
        $fields['billing']['billing_city']['required'] = true;
        $fields['billing']['billing_city']['class'] = array('form-row-last');
        $fields['billing']['billing_company']['class'] = array('form-row-first');
        $fields['billing']['billing_phone']['class'] = array('form-row-last');
        
        $fields['billing']['billing_first_name']['autofocus'] = false;
        $fields['billing']['billing_email']['autofocus'] = true;		
        
        $fields['order']['order_comments']['type']='text';
        $fields['order']['order_comments']['placeholder']='';
        $fields['order']['order_comments']['label']='Order referentie';

        
        // Reordering billing fields
        $order = array(
            "billing_email",
            "billing_first_name",
            "billing_last_name",
            "billing_postcode",
            "billing_address_2_1",
            "billing_address_3",
            "billing_address_1",
            "billing_address_2",
            "billing_city",
            "billing_country",
            "billing_company",
            "billing_phone"
        );

        foreach($order as $field)
        {
            $ordered_fields[$field] = $fields["billing"][$field];
        }

        $fields["billing"] = $ordered_fields;

        return $fields;
    }


    function override_shipping_fields( $fields ) {
            
            //E-MAILADRES
        $fields['shipping']['shipping_email'] = array(
            'type'          => 'email',
            'label'         => __( 'Email Address', 'dutch-address-autocomplete' ),
            'placeholder'   => _x( 'Email Address', 'placeholder', 'dutch-address-autocomplete' ),
            'required'      => true,
            'class'         => array(' '),
            'clear'         => true
        );
        $fields['shipping']['shipping_address_2'] = array(
                'type'          => 'hidden',
                'class'         => array(' '),
                'clear'         => true
                );

        $fields['shipping']['shipping_address_2_1']['placeholder'] = '';
        $fields['shipping']['shipping_address_2_1']['label'] = __( 'House Number', 'dutch-address-autocomplete'); 
        $fields['shipping']['shipping_address_2_1']['required'] = true;
        
        $fields['shipping']['shipping_address_3']['placeholder'] = '';
        $fields['shipping']['shipping_address_3']['label'] = __( 'Ext', 'dutch-address-autocomplete'); 
        $fields['shipping']['shipping_address_3']['required'] = false;
        
        $fields['shipping']['shipping_city']['placeholder'] = '';
        $fields['shipping']['shipping_city']['label'] =__( 'City', 'dutch-address-autocomplete');
        $fields['shipping']['shipping_city']['required'] = true;
        $fields['shipping']['shipping_city']['class'] = array('form-row-last');
        unset($fields['shipping']['shipping_phone']);	
            
        $fields['shipping']['shipping_first_name']['autofocus'] = false;	
        
        
        
            // Reordering shipping fields
        $order = array(
            "shipping_company",
            "shipping_first_name",
            "shipping_last_name",
            "shipping_postcode",
            "shipping_address_2_1",
            "shipping_address_3",
            "shipping_address_1",
            "shipping_address_2",
            "shipping_city",
            "shipping_country"
        );

        foreach($order as $field)
        {
            $ordered_fields[$field] = $fields["shipping"][$field];
        }

        $fields["shipping"] = $ordered_fields;

        return $fields;
    }

    function customize_checkout_fields( $fields ) {
        // just assign priority less than 10
        $fields['postcode']['priority'] = 81;
        $fields['address_2_1']['priority'] = 82;
        $fields['address_3']['priority'] = 83;
        $fields['address_1']['priority'] = 84;
        $fields['city']['priority'] = 86;
        $fields['country']['priority'] = 88;
        $fields['company']['priority'] = 90;
        $fields['address_2']['priority'] = 92;
        return $fields;
    }

    function update_order_meta( $order_id ) {
        if ( ! empty( $_POST['billing_address_2_1'] ) ) {
            update_post_meta( $order_id, 'billing_address_2_1', sanitize_text_field( $_POST['billing_address_2_1'] ) );
            update_post_meta( $order_id, '_billing_address_2', sanitize_text_field( $_POST['billing_address_2_1'] )." ".sanitize_text_field( $_POST['billing_address_3'] ) );
            
            update_user_meta( $user_id, 'billing_address_2', sanitize_text_field( $_POST['billing_address_2_1'] ).' '.sanitize_text_field( $_POST['billing_address_3'] ) );
        }
        //when shipping address is same as billing
        if(!$_POST['ship_to_different_address']){
            update_post_meta( $order_id, 'shipping_address_2_1', sanitize_text_field( $_POST['billing_address_2_1'] ) );
            update_post_meta( $order_id, '_shipping_address_2', sanitize_text_field( $_POST['billing_address_2_1'] )." ".sanitize_text_field( $_POST['billing_address_3'] ) );
            
            update_user_meta( $user_id, 'shipping_address_2', sanitize_text_field( $_POST['billing_address_2_1'] ).' '.sanitize_text_field( $_POST['billing_address_3'] ) );
            update_post_meta( $order_id, 'shipping_address_3', sanitize_text_field( $_POST['billing_address_3'] ) );
        }
            
        if ( ! empty( $_POST['shipping_address_2_1'] ) ) {
            update_post_meta( $order_id, 'shipping_address_2_1', sanitize_text_field( $_POST['shipping_address_2_1'] ) );
            update_post_meta( $order_id, '_shipping_address_2', sanitize_text_field( $_POST['shipping_address_2_1'] )." ".sanitize_text_field( $_POST['shipping_address_3'] ) );
            
            update_user_meta( $user_id, 'shipping_address_2', sanitize_text_field( $_POST['shipping_address_2_1'] ).' '.sanitize_text_field( $_POST['shipping_address_3'] ) );
        }
        
        
        if ( ! empty( $_POST['billing_address_3'] ) ) {
            update_post_meta( $order_id, 'billing_address_3', sanitize_text_field( $_POST['billing_address_3'] ) );
        }
        
        if ( ! empty( $_POST['shipping_address_3'] ) ) {
            update_post_meta( $order_id, 'shipping_address_3', sanitize_text_field( $_POST['shipping_address_3'] ) );
        }
        
    }

    function custom_address_format( $formats ) {
        $formats[ 'BE' ]  = "{company}\n{name}\n{address_1} {address_2}\n{postcode} {city}\n{country}\n{vat_number}";
        $formats[ 'NL' ]  = "{company}\n{name}\n{address_1} {address_2}\n{postcode} {city}\n{country}\n{vat_number}";
        //$formats[ 'LU' ]  = "{company}\n{name}\n{address_1} {address_2}\n{postcode} {city}\n{country}\n{vat_number}";
        
        return $formats;
    }

    function post_postcode() {
        global $wpdb;
        $table = $wpdb->prefix . 'address_database_nl';
        $address_data = $wpdb->get_row( "SELECT * FROM $table WHERE zipcode='".$_POST['postcode']."' AND streetnumber='".$_POST['house_number']."' ",ARRAY_A );//AND min_house_number house_number
        //AND CONCAT(':', streetnumbers, ':') like '%,".$_POST['house_number'].",%'
        if(isset($address_data)){
            echo json_encode($address_data);
        }else{
            $address_data=array("failure");
            echo json_encode($address_data);
        }
        die();
    }


    function save_extra_user_profile_fields( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) ) { 
            return false; 
        }
        
        $address2=explode(" ",$_POST['billing_address_2']);
        $saddress2=explode(" ",$_POST['shipping_address_2']);
        
        update_user_meta( $user_id, 'billing_address_2_1', $address2[0] );
        update_user_meta( $user_id, 'billing_address_3', $address2[1] );
        
        update_user_meta( $user_id, 'shipping_address_2_1', $saddress2[0] );
        update_user_meta( $user_id, 'shipping_address_3', $saddress2[1] );
    }

    function save_address2_profile_fields( $order_id ) {        
        $user_id=get_post_meta( $order_id, '_customer_user', true );
        $billing_address_2=get_post_meta( $order_id, 'billing_address_2_1', true )." ".get_post_meta( $order_id, 'billing_address_3', true );
        $shipping_address_2=get_post_meta( $order_id, 'shipping_address_2_1', true )." ".get_post_meta( $order_id, 'shipping_address_3', true );
        
        update_user_meta( $user_id, 'billing_address_2', $billing_address_2);
        update_user_meta( $user_id, 'shipping_address_2', $shipping_address_2);
    }
}