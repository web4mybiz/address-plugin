<?php
/*
Plugin Name: Dutch Address Autocomplete
Plugin URI: http://onlinemarketingagency.eu/
Description: Address plugin with auto fill feature for Netherlands.
Author: Online Marketing Agency
Version: 1.0
Author URI: http://onlinemarketingagency.eu/
Text Domain: dutch-address-autocomplete
WC tested up to: 3.9.0
*/

defined('ABSPATH') or die('You are not authorised to view this page!');

require_once( dirname( __FILE__ ) .'/class.dutch-address-autocomplete-table.php' );
require_once( dirname( __FILE__ ) .'/admin.php' );

class DutchAddressAutocomplete
{
    public $plugin;

    public function __construct()
    {
        $this->plugin=plugin_basename(__FILE__);
    }
    function enqueue(){
        add_action('wp_enqueue_scripts', array($this,'scripts'));
    }

    function scripts(){
        //enqueue all our scripts
        wp_register_style( 'daa-style', plugins_url('css/style.css', __FILE__) );
        wp_enqueue_style( 'daa-style' );

        if (!is_admin() && is_page('checkout')) {
            wp_register_script ( 'postcode_script', plugins_url( '/js/validate.js', __FILE__ ), array('jquery'),1.61, false);
            wp_enqueue_script( 'postcode_script');	
        }
        
        wp_localize_script( 'postcode_script', 'postpostcode', array('ajax_url' => admin_url( 'admin-ajax.php' )));
    }

    function text_domain() {
        load_plugin_textdomain( 'dutch-address-autocomplete', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
    }


    function register(){
        add_filter("plugin_action_links_$this->plugin", array($this,'settings_link' ));
    }
    function settings_link($links){
        $settings_link='<a href="options-general.php?page=daac_settings_page">Settings</a>';
        array_push($links,$settings_link);
        return $links;
    }

//end class
}


    if(class_exists('DutchAddressAutocomplete')){
        $DutchAddressAutocomplete = new DutchAddressAutocomplete();
        $DutchAddressAutocomplete->enqueue();
        $DutchAddressAutocomplete->register();
    }

    if(class_exists('DaacOverride')){
        $DaacOverride = new DaacOverride();
        if (strpos($_SERVER['HTTP_HOST'], 'addresschechnl') !== false) {
            $DaacOverride->ajax();
            $DaacOverride->override();
        }
    }
   
    if(class_exists('DutchAddressAutocomplete_Table')){
        $DutchAddressAutocomplete_Table = new DutchAddressAutocomplete_Table();
        $DutchAddressAutocomplete_Table->activate();
    } 

    if(class_exists('DutchAddressAutocompleteAdmin')){
        new DutchAddressAutocompleteAdmin();
    }


    

