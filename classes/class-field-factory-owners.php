<?php

namespace PMXI_WP_Rentals_Import_Add_On;

/**
 * Class that handles outputting import fields for "Owners" imports.
 * 
 * This adds all of the "Owners" import field to the UI.
 * 
 * @author Soflyy <support@wpallimport.com>
 * 
 * @since 1.0
 */
class Field_Factory_Owners {
    protected $add_on;

    public $helper;

    /**
     * Construct function that sets up the add-on object and helper object.
     *  
     * @param \PMXI_RapidAddon $addon_object
     */
    public function __construct( \PMXI_RapidAddon $addon_object ) {
        $this->add_on = $addon_object;
        $this->helper = new Helper();
    }

    /**
     * Method to add fields to the import UI for "Owners" imports.
     * 
     * @param string $field_type
     */
    public function pmxi_add_field( $field_type ) {
        $field_type = sanitize_text_field($field_type);
        switch( $field_type ) {
            case 'all_main_fields':
                $this->pmxi_all_main_fields();
                break;
            case 'advanced_settings':
                $this->pmxi_advanced_settings();
                break;
            case 'images':
                $this->pmxi_images();
                break;
        }
    }

    /**
     * Method used to get all of the import fields.
     * 
     * This method is also used by the importer class.
     * 
     * @param string $section
     */
    public function pmxi_get_all_fields( $section = '' ) {
        $section = sanitize_text_field($section);
        if ( empty( $section ) ) {
            return array();
        }

        $fields = array(
            esc_html__( 'Owner Details', 'import-wp-rentals-csv-xml' ) => array(
                esc_html__( 'Email', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'agent_email',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the owner\'s email address', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Phone', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'agent_phone',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the owner\'s phone number', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Mobile', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'agent_mobile',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the owner\'s mobile number', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Skype', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'agent_skype',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the owner\'s Skype ID', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Facebook', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'agent_facebook',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the owner\'s Facebook profile URL', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Twitter', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'agent_twitter',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the owner\'s Twitter handle', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'LinkedIn', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'agent_linkedin',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the owner\'s LinkedIn profile URL', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Pinterest', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'agent_pinterest',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the owner\'s Pinterest profile URL', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'I Live In', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'live_in',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the location where the owner lives', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'I Speak', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'i_speak',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the languages the owner speaks', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Payment Info/Hidden Field', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'payment_info',
                    'type'    => 'textarea',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the owner\'s payment information', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'User Agent ID', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'user_agent_id',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the user agent ID for the owner', 'import-wp-rentals-csv-xml' )
                )
            ),
            esc_html__( 'Advanced Settings', 'import-wp-rentals-csv-xml' ) => array(
                'transparent_status',
                'header_type',
                'page_header_image_full_screen',
                'page_header_image_back_type',
                'page_header_title_over_image',
                'page_header_subtitle_over_image',
                'page_header_image_height',
                'page_header_overlay_color',
                'page_header_overlay_val',
                'rev_slider',
                'page_custom_lat',
                'page_custom_long',
                'page_custom_zoom',
                'min_height',
                'max_height',
                'keep_min',
                'bypass_fit_bounds',
                'page_header_video_full_screen',
                'page_header_title_over_video',
                'page_header_subtitle_over_video',
                'page_header_video_height',
                'page_header_overlay_color_video',
                'page_header_overlay_val_video',
                'sidebar_option',
                'sidebar_select'
            ),
            esc_html__( 'Advanced Settings Image File Fields', 'import-wp-rentals-csv-xml' ) => array(
                'page_custom_image',
                'page_custom_video',
                'page_custom_video_webbm',
                'page_custom_video_ogv',
                'page_custom_video_cover_image'
            )
        );

        if ( array_key_exists( $section, $fields ) ) {
            return $fields[ $section ];
        } elseif ( $section == 'all' ) {
            return $fields;
        } else {
            return array();
        }
    }

    /**
     * Method that outputs all of the main fields for an "Owners" import.
     */
    public function pmxi_all_main_fields() {
        /*****
        * Begin Owner Details section
        */
        $this->add_on->add_title( esc_html__( 'Owner Details', 'import-wp-rentals-csv-xml' ) );
        $fields = $this->pmxi_get_all_fields( 'Owner Details' );
        if ( ! empty( $fields ) && is_array( $fields ) ) {
            foreach ( $fields as $field_name => $field_data ) {
                $this->add_on->add_field( $field_data['name'], $field_name, $field_data['type'], $field_data['options'], esc_html( $field_data['tooltip'] ) );
            }
        }
        /*****
        * End Owner Details section
        */
    }

    /**
     * Method that outputs the "Advanced Settings" section in the import UI.
     */
    public function pmxi_advanced_settings() {
        $advanced_settings = array(
            $this->add_on->add_field( 'transparent_status', esc_html__( 'Use transparent header', 'import-wp-rentals-csv-xml' ), 'radio', array(
                'global' => esc_html__( 'Global', 'import-wp-rentals-csv-xml' ),
                'no'     => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                'yes'    => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
            ), esc_html__( 'Accepted values are \'global\', \'no\', or \'yes\'', 'import-wp-rentals-csv-xml' )
            ),
            $this->add_on->add_field( 'header_type', esc_html__( 'Select header type', 'import-wp-rentals-csv-xml' ), 'radio', array(
                '0' => esc_html__( 'Global', 'import-wp-rentals-csv-xml' ),
                '1' => esc_html__( 'None', 'import-wp-rentals-csv-xml' ),
                '2' => array(
                    esc_html__( 'Image', 'import-wp-rentals-csv-xml' ),
                    $this->add_on->add_field( 'page_custom_image', esc_html__( 'Header Image', 'import-wp-rentals-csv-xml' ), 'image' ),
                    $this->add_on->add_field( 'page_header_image_full_screen', esc_html__( 'Full Screen?', 'import-wp-rentals-csv-xml' ), 'radio', array(
                        'no'  => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                        'yes' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
                    ) ),
                    $this->add_on->add_field( 'page_header_image_back_type', esc_html__( 'Full Screen Background Type?', 'import-wp-rentals-csv-xml' ), 'radio', array(
                        'cover'   => esc_html__( 'Cover', 'import-wp-rentals-csv-xml' ),
                        'contain' => esc_html__( 'Contain', 'import-wp-rentals-csv-xml' )
                    ) ),
                    $this->add_on->add_field( 'page_header_title_over_image', esc_html__( 'Title Over Image', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'page_header_subtitle_over_image', esc_html__( 'SubTitle Over Image', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'page_header_image_height', esc_html__( 'Image Height (Ex:700, Default:580px)', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'page_header_overlay_color', esc_html__( 'Overlay Color', 'import-wp-rentals-csv-xml' ), 'text', null, esc_html__( 'Hex Code, e.g.: 4254d6', 'import-wp-rentals-csv-xml' ) ),
                    $this->add_on->add_field( 'page_header_overlay_val', esc_html__( 'Overlay Opacity (between 0 and 1, Ex:0.5, default 0.6)', 'import-wp-rentals-csv-xml' ), 'text' )
                ),
                '3' => esc_html__( 'Theme Slider', 'import-wp-rentals-csv-xml' ),
                '4' => array(
                    esc_html__( 'Revolution Slider', 'import-wp-rentals-csv-xml' ),
                    $this->add_on->add_field( 'rev_slider', esc_html__( 'Revolution Slider Name', 'import-wp-rentals-csv-xml' ), 'text' )
                ),
                '5' => array(
                    esc_html__( 'Google Map', 'import-wp-rentals-csv-xml' ),
                    $this->add_on->add_field( 'page_custom_lat', esc_html__( 'Map - Center point Latitude', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'page_custom_long', esc_html__( 'Map - Center point Longitude', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'page_custom_zoom', esc_html__( 'Zoom Level for map (1-20)', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'min_height', esc_html__( 'Height of the map when closed', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'max_height', esc_html__( 'Height of map when open', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'keep_min', esc_html__( 'Force map at the "closed" size?', 'import-wp-rentals-csv-xml' ), 'radio', array(
                        'yes' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' ),
                        'no'  => esc_html__( 'No', 'import-wp-rentals-csv-xml' )
                    ) ),
                    $this->add_on->add_field( 'bypass_fit_bounds', esc_html__( 'ByPass fit bounds (auto zoom and pan of the map around visible markers)', 'import-wp-rentals-csv-xml' ), 'radio', array(
                        '0' => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                        '1' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
                    ) )
                ),
                '6' => array(
                    esc_html__( 'Video Header', 'import-wp-rentals-csv-xml' ),
                    $this->add_on->add_field( 'page_custom_video', esc_html__( 'Video MP4 version', 'import-wp-rentals-csv-xml' ), 'file', null, esc_html__( 'Import the URL for the MP4 video', 'import-wp-rentals-csv-xml' ) ),
                    $this->add_on->add_field( 'page_custom_video_webbm', esc_html__( 'Video WEBM version', 'import-wp-rentals-csv-xml' ), 'file', null, esc_html__( 'Import the URL for the WEBM video', 'import-wp-rentals-csv-xml' ) ),
                    $this->add_on->add_field( 'page_custom_video_ogv', esc_html__( 'Video OGV version', 'import-wp-rentals-csv-xml' ), 'file', null, esc_html__( 'Import the URL for the OGV video', 'import-wp-rentals-csv-xml' ) ),
                    $this->add_on->add_field( 'page_custom_video_cover_image', esc_html__( 'Cover Image', 'import-wp-rentals-csv-xml' ), 'image' ),
                    $this->add_on->add_field( 'page_header_video_full_screen', esc_html__( 'Full Screen?', 'import-wp-rentals-csv-xml' ), 'radio', array(
                        'no'  => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                        'yes' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
                    ) ),
                    $this->add_on->add_field( 'page_header_title_over_video', esc_html__( 'Title Over Video', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'page_header_subtitle_over_video', esc_html__( 'SubTitle Over Video', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'page_header_video_height', esc_html__( 'Video Height (Ex:700, Default:580px)', 'import-wp-rentals-csv-xml' ), 'text' ),
                    $this->add_on->add_field( 'page_header_overlay_color_video', esc_html__( 'Overlay Color', 'import-wp-rentals-csv-xml' ), 'text', null, esc_html__( 'Hex code, e.g.: 00ccff', 'import-wp-rentals-csv-xml' ) ),
                    $this->add_on->add_field( 'page_header_overlay_val_video', esc_html__( 'Overlay Opacity (between 0 and 1, Ex:0.5, default 0.6)', 'import-wp-rentals-csv-xml' ), 'text' )
                )
            ) ),
            $this->add_on->add_field( 'sidebar_option', esc_html__( 'Where to show the sidebar', 'import-wp-rentals-csv-xml' ), 'radio', array(
                'right' => esc_html__( 'Right', 'import-wp-rentals-csv-xml' ),
                'left'  => esc_html__( 'Left', 'import-wp-rentals-csv-xml' ),
                'none'  => esc_html__( 'None', 'import-wp-rentals-csv-xml' )
            ), esc_html__( 'Accepted values are \'right\', \'left\', or \'none\'', 'import-wp-rentals-csv-xml' ) ),
            $this->add_on->add_field( 'sidebar_select', esc_html__( 'Select the sidebar', 'import-wp-rentals-csv-xml' ), 'radio', array(
                'primary-widget-area'                  => esc_html__( 'Primary Widget Area', 'import-wp-rentals-csv-xml' ),
                'secondary-widget-area'                => esc_html__( 'Secondary Widget Area', 'import-wp-rentals-csv-xml' ),
                'first-footer-widget-area'             => esc_html__( 'First Footer Widget Area', 'import-wp-rentals-csv-xml' ),
                'second-footer-widget-area'            => esc_html__( 'Second Footer Widget Area', 'import-wp-rentals-csv-xml' ),
                'third-footer-widget-area'             => esc_html__( 'Third Footer Widget Area', 'import-wp-rentals-csv-xml' ),
                'fourth-footer-widget-area'            => esc_html__( 'Fourth Footer Widget Area', 'import-wp-rentals-csv-xml' ),
                'top-bar-left-widget-area'             => esc_html__( 'Top Bar Left Widget Area', 'import-wp-rentals-csv-xml' ),
                'top-bar-right-widget-area'            => esc_html__( 'Top Bar Right Widget Area', 'import-wp-rentals-csv-xml' ),
                'owner-page-widget-area'               => esc_html__( 'Owner Page', 'import-wp-rentals-csv-xml' ),
                'splash-page_bottom-right-widget-area' => esc_html__( 'Splash Page Bottom Right Widget Area', 'import-wp-rentals-csv-xml' ),
                'splash-page_bottom-left-widget-area'  => esc_html__( 'Splash Page Bottom Left Widget Area', 'import-wp-rentals-csv-xml' )
            ), esc_html__( 'To see the possible import values, check Set with XPath › Field Options › Mapping', 'import-wp-rentals-csv-xml' ) )
        );

        $this->add_on->add_options( null, esc_html__( 'Advanced Options', 'import-wp-rentals-csv-xml' ), $advanced_settings );
    }
}