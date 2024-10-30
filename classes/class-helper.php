<?php

namespace PMXI_WP_Rentals_Import_Add_On;

/**
 * Helper class for various import functionality.
 */
class Helper {

    /**
     * Method to get CLI arguments.
     *
     * @return array
     */
    private function pmxi_get_cli_args() {
        global $argv;
        return $argv;
    }

    /**
     * Method used to find the post type that's being imported.
     * 
     * This is important because we need to output and import different fields based on whether it's
     * an "Owners" import or a "Listings" import.
     * 
     * @param mixed $import_id
     * 
     * @return string the custom post type that's being imported.
     */
    public function pmxi_get_post_type( $import_id = 'new' ) {
        if ( $import_id == 'new' ) {
            $import_id = $this->pmxi_get_import_id();
        }
    
        $cache_key = 'import_options_' . $import_id;
        $custom_type = false;
    
        // Attempt to get from cache
        $import_options = wp_cache_get( $cache_key );
    
        if ( $import_options === false ) {
            // Declaring $wpdb as global to access database
            global $wpdb;
    
            // Get values from import data table
            $imports_table = $wpdb->prefix . 'pmxi_imports';
    
            // Get import session from database based on import ID or 'new'
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $import_options = $wpdb->get_row( $wpdb->prepare(
                "SELECT options FROM {$wpdb->prefix}pmxi_imports WHERE id = %d", 
                $import_id 
            ), ARRAY_A );
    
            // Set cache for future use
            if ( $import_options ) {
                wp_cache_set( $cache_key, $import_options );
            }
        }
    
        // If this is an existing import load the custom post type from the array
        if ( ! empty($import_options) ) {
            $import_options_arr = unserialize($import_options['options']);
            $custom_type = $import_options_arr['custom_type'];
        } else {
            // If this is a new import get the custom post type data from the current session
            $import_options = wp_cache_get( '_wpallimport_session_' . $import_id . '_' );
    
            if ( $import_options === false ) {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
                $import_options = $wpdb->get_row( $wpdb->prepare(
                    "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name = %s", 
                    '_wpallimport_session_' . $import_id . '_'
                ), ARRAY_A );
    
                if ( $import_options ) {
                    wp_cache_set( '_wpallimport_session_' . $import_id . '_', $import_options );
                }
            }
    
            $import_options_arr = empty($import_options) ? array() : unserialize($import_options['option_value']);
            $custom_type = empty($import_options_arr['custom_type']) ? '' : $import_options_arr['custom_type'];
        }
    
        return $custom_type;
    }

    /**
     * This method is basically a copy of the wp_all_import_get_import_id() function.
     * 
     * @return string|int the import ID that's currently running.
     */
    public function pmxi_get_import_id() {
        $argv = $this->pmxi_get_cli_args();
        $import_id = 'new';
            
        if ( ! empty( $argv ) ) {

            // First check for the ID set by the WP_CLI code.
            $temp_id = apply_filters( 'wp_all_import_cli_import_id', false );

            if( $temp_id !== false && is_numeric( $temp_id ) ) {
                $import_id = $temp_id;
            } else {

                // Try to get the ID from the CLI arguments if it's not found otherwise.
                $import_id_arr = array_filter( $argv, function ( $a ) {
                    return ( is_numeric( $a ) ) ? true : false;
                } );

                if ( ! empty( $import_id_arr ) ) {
                    $import_id = reset( $import_id_arr );
                }
            }
        }
        
        if ( $import_id == 'new' ) {
            if ( isset( $_GET['import_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
                $import_id = filter_var( $_GET['import_id'], FILTER_SANITIZE_NUMBER_INT ); // phpcs:ignore WordPress.Security.NonceVerification
            } elseif ( isset( $_GET['id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
                $import_id = filter_var( $_GET['id'], FILTER_SANITIZE_NUMBER_INT ); // phpcs:ignore WordPress.Security.NonceVerification
            }
        }

        return $import_id;
    }

    /**
     * Method that outputs log entries into WP All Import's history logs.
     * 
     * @param string $message
     */
    public function pmxi_log( $message = 'empty' ){
        $message = sanitize_text_field($message);
    
        if ( $message !== 'empty' ) {            
            $logger = function( $m = '' ) {
                $date = gmdate("H:i:s");
                $m = str_replace( '%', '%%', $m );
                printf( "<div class='progress-msg'>[%s] " . esc_html( $m ) . "</div>\n", esc_html( $date ) ); 
                flush(); 
            };
            call_user_func( $logger, $message );
        }
    }
    

    /**
     * Method that updates the post meta for the item being imported.
     * 
     * We use our own method so that we can output useful log entries.
     * 
     * @param string|int $id
     * @param string $field
     * @param array $data
     * @param string $type
     */
    public function pmxi_update_meta( $id, $field, $data, $type = 'post' ) {
        $enable_logs_settings = array( 
            'enable'                 => true, 
            'include_empty_updates'  => false,
            'include_failed_updates' => false 
        );

        $enable_logs = apply_filters( 'wpai_wprentals_addon_enable_logs', $enable_logs_settings );

        if ( $type == 'post' ) {
            $id    = absint( $id );
            $field = sanitize_text_field( $field );

            if ( strpos( $data, "\n" ) !== false ) {
                $data = sanitize_textarea_field( $data );
            } else {
                $data = sanitize_text_field( $data );
            }

            $update = update_post_meta( $id, $field, $data );
            $data   = maybe_serialize( $data );
            $data   = wp_strip_all_tags( $data );

            if ( $update !== false ) {
                if ( $enable_logs['enable'] === true ) {
                    if ( $enable_logs['include_empty_updates'] === false ) {
                        if ( $field !== '' && $data !== '' ) {                                
                            $this->pmxi_log( '<strong>' . esc_html__( 'WP Rentals Add-On:', 'import-wp-rentals-csv-xml' ) . '</strong> ' . esc_html__( 'Successfully imported value', 'import-wp-rentals-csv-xml' ) . ' "<em>' . esc_html( $data ) . '</em>" ' . esc_html__( 'into field:', 'import-wp-rentals-csv-xml' ) . ' <em>' . esc_html( $field ) . '</em>.' );
                        }
                    } else {
                        $this->pmxi_log( '<strong>' . esc_html__( 'WP Rentals Add-On:', 'import-wp-rentals-csv-xml' ) . '</strong> ' . esc_html__( 'Successfully imported value', 'import-wp-rentals-csv-xml' ) . ' "<em>' . esc_html( $data ) . '</em>" ' . esc_html__( 'into field:', 'import-wp-rentals-csv-xml' ) . ' <em>' . esc_html( $field ) . '</em>.' );
                    }
                }
            } else {
                if ( $enable_logs['enable'] === true && $enable_logs['include_failed_updates'] === true )  {
                    $this->pmxi_log( '<strong>' . esc_html__( 'WP Rentals Add-On:', 'import-wp-rentals-csv-xml' ) . '</strong> ' . esc_html__( 'Failed to import value', 'import-wp-rentals-csv-xml' ) . ' "<em>' . esc_html( $data ) . '</em>" ' . esc_html__( 'into field:', 'import-wp-rentals-csv-xml' ) . ' <em>' . esc_html( $field ) . '</em>.' );
                }
            }
        }
    }
}
