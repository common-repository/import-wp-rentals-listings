<?php

namespace PMXI_WP_Rentals_Import_Add_On;

class Importer_Listings {
    protected $add_on;
    public $helper;
    public $logger = null;
    public $post_type;

    public function __construct( RapidAddon $addon_object, $post_type = 'listing' ) {
        $this->add_on    = $addon_object;
        $this->helper    = new Helper();
        $this->post_type = sanitize_text_field( $post_type );
    }

    public function pmxi_import( $post_id, $data, $import_options, $article ) {
        $post_id = absint( $post_id );

        $field_factory = new Field_Factory_Listings( $this->add_on );
        $fields        = $field_factory->pmxi_get_all_fields( 'all' );

        // Property General Details fields
        $property_general_details_fields = $fields['Property General Details'];

        foreach ( $property_general_details_fields as $field_label => $field_info ) {
            $field_info['name'] = sanitize_text_field( $field_info['name'] );
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_info['name'], $import_options ) ) {
                $this->helper->pmxi_update_meta( $post_id, $field_info['name'], sanitize_text_field( $data[ $field_info['name'] ] ) );
            }
        }

        // Property Price fields
        $property_price_fields = $fields['Property Price'];

        foreach ( $property_price_fields as $field_label => $field_info ) {
            $field_info['name'] = sanitize_text_field( $field_info['name'] );
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_info['name'], $import_options ) ) {
                $this->helper->pmxi_update_meta( $post_id, $field_info['name'], sanitize_text_field( $data[ $field_info['name'] ] ) );
            }
        }

        // Property Media Fields
        $property_media_fields = $fields['Property Media'];

        foreach ( $property_media_fields as $field_label => $field_info ) {
            $field_info['name'] = sanitize_text_field( $field_info['name'] );
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_info['name'], $import_options ) ) {
                $this->helper->pmxi_update_meta( $post_id, $field_info['name'], sanitize_text_field( $data[ $field_info['name'] ] ) );
            }
        }

        // Property Specific Details
        $property_specific_details_fields = $fields['Property Specific Details'];

        foreach ( $property_specific_details_fields as $field_label => $field_info ) {
            $field_info['name'] = sanitize_text_field( $field_info['name'] );
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_info['name'], $import_options ) ) {
                $this->helper->pmxi_update_meta( $post_id, $field_info['name'], sanitize_text_field( $data[ $field_info['name'] ] ) );
            }
        }

        // Map fields
        $map_fields = $fields['Map'];

        if ( empty( $article['ID'] ) || $this->pmxi_can_update_location_fields( $import_options ) ) {
            $location_importer = new Importer_Location( $this->add_on, $this->post_type );
            $location_importer->pmxi_import( $post_id, $data, $import_options, $article );
            unset( $map_fields['Latitude'] );
            unset( $map_fields['Longitude'] );
        }

        foreach ( $map_fields as $field_label => $field_info ) {
            $field_info['name'] = sanitize_text_field( $field_info['name'] );
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_info['name'], $import_options ) ) {
                $this->helper->pmxi_update_meta( $post_id, $field_info['name'], sanitize_text_field( $data[ $field_info['name'] ] ) );
            }
        }

        // Owner Fields
        $owner_fields = $fields['Owner'];

        foreach ( $owner_fields as $field_label => $field_info ) {
            $field_info['name'] = sanitize_text_field( $field_info['name'] );
            if ( $field_info['name'] == 'property_agent' ) {
                if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_info['name'], $import_options ) ) {
                    $this->pmxi_import_agent( $post_id, $field_info['name'], sanitize_text_field( $data[ $field_info['name'] ] ) );
                }
            }
        }

        // Advanced Options
        $fields = array(
            'sidebar_option',
            'sidebar_select'
        );

        foreach ( $fields as $field_name ) {
            $field_name = sanitize_text_field( $field_name );
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_name, $import_options ) ) {
                $this->helper->pmxi_update_meta( $post_id, $field_name, sanitize_text_field( $data[ $field_name ] ) );
            }
        }

    }

    public function pmxi_import_agent( $post_id, $field, $agent_data ) {
        $post_id = absint( $post_id );
        $field = sanitize_text_field( $field );
        $agent_data = sanitize_text_field( $agent_data );
    
        $user = get_user_by( 'ID', $agent_data ) ?: 
                get_user_by( 'slug', $agent_data ) ?: 
                get_user_by( 'email', $agent_data ) ?: 
                get_user_by( 'login', $agent_data );
    
        if ( $user === false ) {
            // translators: %s: agent data
            $this->helper->pmxi_log( sprintf( wp_kses_post( __( '<strong>WP Rentals Add-On:</strong> WARNING Couldn\'t import owner/property_agent. User not found with this data: %s', 'import-wp-rentals-csv-xml' ) ), esc_html( $agent_data ) ) );
            return;
        }
                
    
        if ( $post = get_post( $post_id ) ) {
            $post->post_author = $user->ID;
            if ( wp_update_post( $post ) ) {
                // translators: %d: user ID
                $this->helper->pmxi_log( sprintf( wp_kses_post(__( '<strong>WP Rentals Add-On:</strong> Successfully set post owner to user ID %d', 'import-wp-rentals-csv-xml' ) ), $user->ID ) );
            }
        }
        $this->helper->pmxi_update_meta( $post_id, $field, $user->ID );
    }
    

    public function pmxi_can_update_location_fields( $import_options ) {
        $fields = apply_filters( 'wpai_wp_rentals_location_fields_update_check', array(
            'property_latitude',
            'property_longitude',
            'property_address',
            'property_zip',
            'property_country'
        ) );

        $can_update = false;

        foreach ( $fields as $field ) {
            $field = sanitize_text_field( $field );
            if ( $this->add_on->can_update_meta( $field, $import_options ) ) {
                $can_update = true;
                break;
            }
        }

        $can_update = apply_filters( 'wpai_wp_rentals_can_update_location', $can_update );
        return $can_update;
    }

    function pmxi_image_importer( $post_id, $attachment_id, $image_filepath, $import_options ) {
        $post_id = absint( $post_id );
        $attachment_id = absint( $attachment_id );
        $image_filepath = sanitize_text_field( $image_filepath );        
        
        $wpresidence_addon = new Helper();
        
        $current_images = get_post_meta( $post_id, 'image_to_attach', true );
        $current_images = explode( ",", $current_images );
        $current_images[] = $attachment_id;
        $current_images = array_filter($current_images);
        
        $count = 1;
    
        $logger = function($m) {
            printf("<div class='progress-msg'>[%s] %s</div>\n", esc_html(gmdate("H:i:s")), esc_html($m));
            flush();
        };
        call_user_func($logger, esc_html__( "<b>Images Attached to this property:</b> Each image can only be attached to one property.  Uncheck 'Search through the Media Library for existing images before importing new images' if you need to import the same image for multiple properties.", 'import-wp-rentals-csv-xml' ));
    
        $set_menu_order = apply_filters( 'wpai_wp_rentals_is_set_menu_order', true );
        if ( $set_menu_order === true ) {
            foreach ( $current_images as $image ) {
                $gallery_post = wp_update_post( array(
                    'ID'            => $image,
                    'post_parent'   => $post_id,
                    'menu_order'    => $count,
                ), true );
                if( is_wp_error( $gallery_post ) ) {
                        error_log( print_r( $gallery_post, 1 ) );
                }
                $count++;                
            }
        }
    
        $current_images = implode( ",", $current_images );
    
        $wpresidence_addon->pmxi_update_meta( $post_id, 'image_to_attach', trim( $current_images, "," ) );
    }
}
