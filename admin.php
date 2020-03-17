<?php 

class DutchAddressAutocompleteAdmin{
    function __construct(){
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array($this,'daac_register_settings') );
    }

    public $options;

    function add_plugin_page(){
        add_options_page( 'Dutch Address Autocomplete Settings', 'Dutch Address Auto Complete', 'manage_options', 'daac_settings_page',array($this,'display_settings_page'));
    }

    function daac_register_settings(){
        register_setting('daac_option_group', 'daac_option_name');
        add_settings_section('daac_section', __( '', 'dutch-address-autocomplete' ), array($this,'daac_sections_cb'), 'daac_settings_page');
        add_settings_field('daac_field_key', __( 'Key ', 'dutch-address-autocomplete' ), array($this,'daac_render_form'), 'daac_settings_page','daac_section',array('label_for' => 'daac_field_key'));
        //add_settings_field('daac_key_domain', __( 'Domain ', 'dutch-address-autocomplete' ), array($this,'daac_render_form'), 'daac_settings_page','daac_section',array('label_for' => 'daac_key_domain'));

    }


    function daac_sections_cb(){
        //echo "cb cb";
    }

    function daac_render_form(){
        $options = get_option( 'daac_option_name' );
        //echo "ooo";
        echo "<input type='text' name='daac_option_name[daac_field_key]' value='".$options['daac_field_key']."' maxlength='100'>";
        echo "<input type='text' name='daac_option_name[daac_key_domain]' value='".$options['daac_key_domain']."' maxlength='100'>";

     }

     function display_settings_page(){?>
        <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "daac_settings"
        settings_fields( 'daac_option_group' );
        // output setting sections and their fields
        // (sections are registered for "daac_settings", each field is registered to a specific section)
        do_settings_sections( 'daac_settings_page' );
        // output save settings button
        submit_button( 'Save Settings' );
        ?>
        </form>
        </div>
  <?php
     }

}