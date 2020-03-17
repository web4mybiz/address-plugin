<?php
global $life_db_version;
$life_db_version = '1.0';

class DutchAddressAutocomplete_Table{
    function __construct(){

    }

    function activate(){
        register_activation_hook( __FILE__, array($this,'install_table') );
    }

    function install_table() {
        global $wpdb;
        global $life_db_version;
     
        $table_name1 = $wpdb->prefix . 'address_database_nl2';
    
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql1 = "CREATE TABLE IF NOT EXISTS $table_name1 (
                id int(11) NOT NULL,
                country varchar(255) NOT NULL,
                city varchar(255) NOT NULL,
                zipcode varchar(40) NOT NULL,
                street varchar(255) NOT NULL,
                streetnumbers text NOT NULL,
                pc_count int(11) NOT NULL
        ) $charset_collate;";
    
     
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
         
        dbDelta( $sql1 );
    
        add_option( 'life_db_version', $life_db_version );
    }
}