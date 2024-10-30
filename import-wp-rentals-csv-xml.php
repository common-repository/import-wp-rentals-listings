<?php
/*
 * Plugin Name: Import WP Rentals Listings
 * Plugin URI: http://www.wpallimport.com/
 * Description: Easily import Listings and Owners into the WP Rentals theme from any CSV or XML file.
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.0.0
 * Author: Soflyy
 * Text Domain: import-wp-rentals-csv-xml
*/

namespace PMXI_WP_Rentals_Import_Add_On;

/**
 * The main Add-On class.
 * 
 * This class adds all of our constants and includes the other classes to build the import fields in the UI. Note
 *  that the only way to access this class is by using the get_instance() method.
 * 
 * @author Soflyy <support@wpallimport.com>
 * 
 * @since 1.0
 */
final class Add_On {
    
    protected static $instance;
    
    protected $add_on;

    protected $addon_name = 'WP Rentals Add-On';

    protected $addon_slug = 'pmxi_wprentals_addon';
    
    public $post_type = '';
    
    /**
     * The method used to get the add-on instance.
     */
    static public function get_instance() {
        if ( self::$instance == NULL ) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * The construct method, which initializes the add-on and sets up constants & includes.
     */
    protected function __construct() {
        add_action( 'init', array( $this, 'pmxi_init' ) );
    }
    
    /**
     * The init method.
     * 
     * This method sets up the import fields for each post type.
     */
    public function pmxi_init() {

        if(!class_exists('PMXI_Plugin') || !class_exists('PMXI_RapidAddon')){
            $this->pmxi_display_admin_notice($this->addon_name, $this->addon_slug);
            return;
        }

        $this->pmxi_constants();
        $this->pmxi_includes();

	    $this->add_on = new RapidAddon( $this->addon_name, $this->addon_slug );

        // Helper functions to get post type and other things.
        $helper = new Helper();
        $this->post_type = $helper->pmxi_get_post_type();
        
        // We have to check the post type to output different fields.
        switch ( $this->post_type ) {
            // Importing 'Agents'
            case 'estate_agent':
                $this->pmxi_owner_fields();
                break;
                
            case 'estate_property':
                $this->pmxi_listing_fields();
                break;
        }
                
        $this->add_on->set_import_function( array( $this, 'pmxi_import' ) );
                
        $this->add_on->run( array(
            'themes'     => array( 'WpRentals' ),
            'post_types' => array( 'estate_agent', 'estate_property', 'wpestate_booking' )
        ) );
                    
        $notice_message = esc_html__('The WPRentals Add-On requires WP All Import <a href="http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wprentals" target="_blank">Pro</a> or <a href="https://wordpress.org/plugins/wp-all-import/">Free</a>, and the <a href="https://themeforest.net/item/wp-rentals-booking-accommodation-wordpress-theme/12921802" target="_blank">WPRentals Theme</a>.', 'import-wp-rentals-csv-xml');
                    
        $this->add_on->admin_notice( $notice_message, array( 'themes' => array( 'WpRentals' ) ) );
                    
    }
                
    /**
     * Method for setting up constants.
     */
    public function pmxi_constants() {
        if ( ! defined( 'PMXI_WPRENTALS_PLUGIN_DIR_PATH' ) ) {
            // Dir path
            define( 'PMXI_WPRENTALS_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
        }
        
        if ( ! defined( 'PMXI_WPRENTALS_ROOT_DIR' ) ) {
            // Root directory for the plugin.
            define( 'PMXI_WPRENTALS_ROOT_DIR', str_replace( '\\', '/', dirname( __FILE__ ) ) );
        }
        
        if ( ! defined( 'PMXI_WPRENTALS_PLUGIN_PATH' ) ) {
            // Path to the main plugin file.
            define( 'PMXI_WPRENTALS_PLUGIN_PATH', PMXI_WPRENTALS_ROOT_DIR . '/' . basename( __FILE__ ) );
        }
        
        if ( ! defined( 'PMXI_WPRENTALS_ADDON_FIELD_PREFIX' ) ) {
            define( 'PMXI_WPRENTALS_ADDON_FIELD_PREFIX', 'pmxi_wpres_addon_' );
        }
    }
     
    /** 
     * Method for including the Rapid Add-On API and our other classes for importing.
     */
    public function pmxi_includes() {
        include_once( PMXI_WPRENTALS_PLUGIN_DIR_PATH . 'classes/class-rapid-addon.php' );
        include_once( PMXI_WPRENTALS_PLUGIN_DIR_PATH . 'classes/class-importer-listings.php' );
        include_once( PMXI_WPRENTALS_PLUGIN_DIR_PATH . 'classes/class-importer-owners.php' );            
        include_once( PMXI_WPRENTALS_PLUGIN_DIR_PATH . 'classes/class-importer-location.php' );
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        include_once( PMXI_WPRENTALS_PLUGIN_DIR_PATH . 'classes/class-field-factory-owners.php' );            
        include_once( PMXI_WPRENTALS_PLUGIN_DIR_PATH . 'classes/class-field-factory-listings.php' );
        include_once( PMXI_WPRENTALS_PLUGIN_DIR_PATH . 'classes/class-helper.php' ); 

    }
             
    /**
     * Method that adds fields to "Owners" imports.
     */
    public function pmxi_owner_fields() {
        $fields = new Field_Factory_Owners( $this->add_on );
        
        $fields->pmxi_add_field( 'all_main_fields' );    
        $fields->pmxi_add_field( 'advanced_settings' );

    }
                
    /**
     * Method that adds fields to "Listings" imports.
     */
    public function pmxi_listing_fields() {
        $fields = new Field_Factory_Listings( $this->add_on );
        
        $fields->pmxi_add_field( 'all_main_fields' );
        $fields->pmxi_add_field( 'text_image_custom_details' );
    } 
                
    /**
     * The method that actually imports the data into each post type.
     */
    public function pmxi_import( $post_id, $data, $import_options, $article ) { 

        switch( $this->post_type ) {
            case 'estate_property':
                $importer = new Importer_Listings( $this->add_on, $this->post_type );
                $importer->pmxi_import( $post_id, $data, $import_options, $article );
                break;

            case 'estate_agent':
                $importer = new Importer_Owners( $this->add_on, $this->post_type );
                $importer->pmxi_import( $post_id, $data, $import_options, $article );
                break;
        }

    }

    public function pmxi_display_admin_notice($addon_name, $addon_slug, $notice_text = false) {
        if (!$notice_text) {
            $notice_text = $addon_name.' requires the latest version of WP All Import <a href="http://www.wpallimport.com/" target="_blank">Pro</a> or <a href="http://wordpress.org/plugins/wp-all-import" target="_blank">Free</a>.';
        }

        if (!get_option(sanitize_key($addon_slug).'_notice_ignore')) {
            ?>
            <div class="error notice is-dismissible wpallimport-dismissible" style="margin-top: 10px;" rel="<?php echo esc_attr(sanitize_key($addon_slug)); ?>">
                <p><?php echo \wp_kses_post( $notice_text ); ?></p>
            </div>
            <?php
        }
    }
}

Add_On::get_instance();
