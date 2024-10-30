<?php

namespace PMXI_WP_Rentals_Import_Add_On;

/**
 * Class that imports data into "Owners" fields.
 */
class Importer_Owners {
    protected $add_on;
    public $helper;
    public $logger = null;

    /**
     * Construct class that sets up the add-on object and helper object.
     * 
     * @param RapidAddon $addon_object
     * @param string $post_type
     */
    public function __construct( RapidAddon $addon_object, $post_type = 'owner' ) {
        $this->add_on = $addon_object;
        $this->helper = new Helper();
    }

    /**
     * Main import method for fields in an "Owners" import.
     * 
     * @param string|int $post_id
     * @param array $data
     * @param array $import_options
     * @param array $article
     */
    public function pmxi_import( $post_id, $data, $import_options, $article ) {
        $post_id = absint( $post_id );

        $field_factory = new Field_Factory_Owners( $this->add_on );
        $fields        = $field_factory->pmxi_get_all_fields( 'all' );

        // Import Owner Details fields
        $owner_fields = $fields['Owner Details'];
        foreach ( $owner_fields as $field_label => $field_info ) {
            $field_name = sanitize_text_field( $field_info['name'] );
            $field_value = isset( $data[ $field_name ] ) ? sanitize_text_field( $data[ $field_name ] ) : '';
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_name, $import_options ) ) {
                $this->helper->pmxi_update_meta( $post_id, $field_name, $field_value );
            }
        }

        // Import Advanced Settings regular fields
        $advanced_settings_regular_fields = $fields['Advanced Settings'];
        foreach ( $advanced_settings_regular_fields as $field_name ) {
            $field_name = sanitize_text_field( $field_name );
            $field_value = isset( $data[ $field_name ] ) ? sanitize_text_field( $data[ $field_name ] ) : '';
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_name, $import_options ) ) {
                $this->helper->pmxi_update_meta( $post_id, $field_name, $field_value );
            }
        }

        // Import Advanced Settings image and file fields
        $advanced_settings_image_file_fields = $fields['Advanced Settings Image File Fields'];
        foreach ( $advanced_settings_image_file_fields as $field_name ) {
            $field_name = sanitize_text_field( $field_name );
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field_name, $import_options ) ) {
                $id = isset( $data[ $field_name ]['attachment_id'] ) ? absint( $data[ $field_name ]['attachment_id'] ) : 0;
                $url = wp_get_attachment_url( $id );
                if ( $url ) {
                    $this->helper->pmxi_update_meta( $post_id, $field_name, esc_url_raw( $url ) );
                }
            }
        }
    }
}