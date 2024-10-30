<?php

namespace PMXI_WP_Rentals_Import_Add_On;

/**
 * The field factory class for listings.
 * 
 * This class adds all of the fields available for "Listings" imports. In some cases, you can edit the 
 * array in the "get_all_fields" function to add new fields and they will automatically import.
 * 
 * @author Soflyy <support@wpallimport.com>
 * 
 * @since 1.0
 */
class Field_Factory_Listings {
    
    protected $add_on;
    
    public $helper;
    
    public $post_type;
    
    /**
     * @param \PMXI_RapidAddon $addon_object
     */
    public function __construct( \PMXI_RapidAddon $addon_object ) {
        $this->add_on    = $addon_object;
        $this->helper    = new Helper();
        $this->post_type = $this->helper->pmxi_get_post_type();
    }
    
    /**
     * @param string $field_type
     */
    public function pmxi_add_field( $field_type ) {
        $field_type = sanitize_text_field($field_type);
        switch( $field_type ) {
            case 'property_images':
                $this->pmxi_image_field();
                break;
                
            case 'text_image_custom_details':
                $this->pmxi_text_image_custom_details();
                break;
        }
    }

    /**
     * Method used to fetch import fields.
     * 
     * The $fields array contains all of the information needed to add those fields to the UI. If WP Rentals
     * adds a new field that we need to implement, simply add that field's information to the array. The import
     * login also uses this array to import the data, so that's the only change that should be needed (unless 
     * the field needs special functionality, like looking up data before returning it).
     * 
     * @param string $section The section name that you need fields for.
     * 
     * @return array $fields An array containing all of the fields from the section requested.
     */
    public function pmxi_get_all_fields( $section = '' ) {
        $section = sanitize_text_field($section);
        if ( empty( $section ) ) {
            return array();
        }

        $fields = array(
            esc_html__( 'Property General Details', 'import-wp-rentals-csv-xml' )  => array(
                esc_html__( 'Guest Number', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'guest_no',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the number of guests allowed', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property Address', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_address',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the property address', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property County', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_county',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the property county', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property State', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_state',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the property state', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property Zip', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_zip',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the property zip code', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property Country', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_country',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the property country', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Featured', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'prop_featured',
                    'type'    => 'radio',
                    'options' => array(
                        '0' => esc_html__( 'Not Featured', 'import-wp-rentals-csv-xml' ),
                        '1' => esc_html__( 'Featured', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'1\' for Featured or \'0\' for Not Featured.', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Affiliate Link', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_affiliate',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the affiliate link', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Private Notes', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'private_notes',
                    'type'    => 'textarea',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter any private notes', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Allow instant booking?', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'instant_booking',
                    'type'    => 'radio',
                    'options' => array(
                        '0' => esc_html__( 'Do not allow', 'import-wp-rentals-csv-xml' ),
                        '1' => esc_html__( 'Allow', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'0\' for Do not allow or \'1\' for Allow.', 'import-wp-rentals-csv-xml' )
                )
            ),
            esc_html__( 'Property Price', 'import-wp-rentals-csv-xml' ) => array(
                esc_html__( 'Booking Type', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'local_booking_type',
                    'type'    => 'radio',
                    'options' => array(
                        '1' => esc_html__( 'Per Day/Night', 'import-wp-rentals-csv-xml' ),
                        '2' => esc_html__( 'Per Hour', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'1\' for Per Day/Night or \'2\' for Per Hour', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property Price', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_price',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the property price', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Before Label', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_price_before_label',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the before label for the property price', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'After Label', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_price_after_label',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the after label for the property price', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property Taxes in %', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_taxes',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the property taxes in percentage', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Price per night (7d+)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_price_per_week',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the price per night for stays of 7 days or more', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Price per night (30d+)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_price_per_month',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the price per night for stays of 30 days or more', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Price per weekend', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'price_per_weekeend',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the price per weekend', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Cleaning Fee', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'cleaning_fee',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the cleaning fee', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Cleaning Fee Calculation', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'cleaning_fee_per_day',
                    'type'    => 'radio',
                    'options' => array(
                        '0' => esc_html__( 'Single Fee', 'import-wp-rentals-csv-xml' ),
                        '1' => esc_html__( 'Per night', 'import-wp-rentals-csv-xml' ),
                        '2' => esc_html__( 'Per Guest', 'import-wp-rentals-csv-xml' ),
                        '3' => esc_html__( 'Per night per Guest', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'0\' for Single Fee, \'1\' for Per night, \'2\' for Per Guest, \'3\' for Per night per Guest', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'City Fee', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'city_fee',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the city fee', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'City Fee calculation', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'city_fee_per_day',
                    'type'    => 'radio',
                    'options' => array(
                        '0' => esc_html__( 'Single Fee', 'import-wp-rentals-csv-xml' ),
                        '1' => esc_html__( 'Per night', 'import-wp-rentals-csv-xml' ),
                        '2' => esc_html__( 'Per Guest', 'import-wp-rentals-csv-xml' ),
                        '3' => esc_html__( 'Per night per Guest', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'0\' for Single Fee, \'1\' for Per night, \'2\' for Per Guest, \'3\' for Per night per Guest', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Minimum Days of booking', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'min_days_booking',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the minimum days of booking', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Security Deposit', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'security_deposit',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the security deposit amount', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Early bird discount', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'early_bird_percent',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the early bird discount percentage', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Early bird days before', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'early_bird_days',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the number of days before for early bird discount', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Extra Price per Guest', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'extra_price_per_guest',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the extra price per guest', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Allow guests above capacity?', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'overload_guest',
                    'type'    => 'radio',
                    'options' => array(
                        '0' => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                        '1' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'0\' for No or \'1\' for Yes', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Maximum extra guests above capacity (if extra guest are allowed)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'max_extra_guest_no',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the maximum number of extra guests allowed above capacity', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Pay by the no of guests (room prices will NOT be used anymore and billing will be done by guest no only)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'price_per_guest_from_one',
                    'type'    => 'radio',
                    'options' => array(
                        '0' => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                        '1' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'0\' for No or \'1\' for Yes', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Allow only bookings with the check-in/check-out on', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'checkin_checkout_change_over',
                    'type'    => 'radio',
                    'options' => array(
                        '0' => esc_html__( 'All', 'import-wp-rentals-csv-xml' ),
                        '7' => esc_html__( 'Sunday', 'import-wp-rentals-csv-xml' ),
                        '1' => esc_html__( 'Monday', 'import-wp-rentals-csv-xml' ),
                        '2' => esc_html__( 'Tuesday', 'import-wp-rentals-csv-xml' ),
                        '3' => esc_html__( 'Wednesday', 'import-wp-rentals-csv-xml' ),
                        '4' => esc_html__( 'Thursday', 'import-wp-rentals-csv-xml' ),
                        '5' => esc_html__( 'Friday', 'import-wp-rentals-csv-xml' ),
                        '6' => esc_html__( 'Saturday', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'0\' for All, \'1\' for Monday, \'2\' for Tuesday, \'3\' for Wednesday, \'4\' for Thursday, \'5\' for Friday, \'6\' for Saturday, \'7\' for Sunday', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Allow only bookings starting with the check-in on', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'checkin_change_over',
                    'type'    => 'radio',
                    'options' => array(
                        '0' => esc_html__( 'All', 'import-wp-rentals-csv-xml' ),
                        '1' => esc_html__( 'Monday', 'import-wp-rentals-csv-xml' ),
                        '2' => esc_html__( 'Tuesday', 'import-wp-rentals-csv-xml' ),
                        '3' => esc_html__( 'Wednesday', 'import-wp-rentals-csv-xml' ),
                        '4' => esc_html__( 'Thursday', 'import-wp-rentals-csv-xml' ),
                        '5' => esc_html__( 'Friday', 'import-wp-rentals-csv-xml' ),
                        '6' => esc_html__( 'Saturday', 'import-wp-rentals-csv-xml' ),
                        '7' => esc_html__( 'Sunday', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'0\' for All, \'1\' for Monday, \'2\' for Tuesday, \'3\' for Wednesday, \'4\' for Thursday, \'5\' for Friday, \'6\' for Saturday, \'7\' for Sunday', 'import-wp-rentals-csv-xml' )
                )
            ),
            esc_html__( 'Property Media', 'import-wp-rentals-csv-xml' ) => array(
                esc_html__( 'Video From', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'embed_video_type',
                    'type'    => 'radio',
                    'options' => array(
                        'vimeo'   => esc_html__( 'Vimeo', 'import-wp-rentals-csv-xml' ),
                        'youtube' => esc_html__( 'YouTube', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'vimeo\' or \'youtube\'', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Embed Video id', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'embed_video_id',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the video ID to embed', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Virtual Tour', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'virtual_tour',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Import the URL to the video.', 'import-wp-rentals-csv-xml' )
                )
            ),
            esc_html__( 'Property Specific Details', 'import-wp-rentals-csv-xml' ) => array(
                esc_html__( 'Property Size in', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_size',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the property size', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property Rooms', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_rooms',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the number of rooms in the property', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property Bedrooms', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_bedrooms',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the number of bedrooms in the property', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Property Bathrooms', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_bathrooms',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the number of bathrooms in the property', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Cancellation Policy', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'cancellation_policy',
                    'type'    => 'textarea',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the cancellation policy', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Other Rules', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'other_rules',
                    'type'    => 'textarea',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter any other rules', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Smoking Allowed', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'smoking_allowed',
                    'type'    => 'radio',
                    'options' => array(
                        'no'  => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                        'yes' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'yes\' or \'no\'', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Party Allowed', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'party_allowed',
                    'type'    => 'radio',
                    'options' => array(
                        'no'  => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                        'yes' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'yes\' or \'no\'', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Pets Allowed', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'pets_allowed',
                    'type'    => 'radio',
                    'options' => array(
                        'no'  => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                        'yes' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'yes\' or \'no\'', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Children Allowed', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'children_allowed',
                    'type'    => 'radio',
                    'options' => array(
                        'no'  => esc_html__( 'No', 'import-wp-rentals-csv-xml' ),
                        'yes' => esc_html__( 'Yes', 'import-wp-rentals-csv-xml' )
                    ),
                    'tooltip' => esc_html__( 'Accepted values are \'yes\' or \'no\'', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Check-in hour (*text)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'check-in-hour',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the check-in hour', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Check-Out hour (*text)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'check-out-hour',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the check-out hour', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Late Check-in (*text)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'late-check-in',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the details for late check-in', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Optional services (*text)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'optional-services',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter any optional services', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Outdoor facilities (*text)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'outdoor-facilities',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the outdoor facilities', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Extra People (*text)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'extra-people',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the details for extra people', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Cancellation (*text)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'cancellation',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the cancellation details', 'import-wp-rentals-csv-xml' )
                )
            ),
            esc_html__( 'Map', 'import-wp-rentals-csv-xml' )  => array(
                esc_html__( 'Latitude', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_latitude',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the latitude', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Longitude', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_longitude',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the longitude', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Google View Camera Angle', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'google_camera_angle',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Enter the Google View camera angle', 'import-wp-rentals-csv-xml' )
                ),
                esc_html__( 'Zoom Level for map (1-20)', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'page_custom_zoom',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Only accepts a number between 1 and 20', 'import-wp-rentals-csv-xml' )
                )
            ),
            esc_html__( 'Owner', 'import-wp-rentals-csv-xml' ) => array(
                esc_html__( 'Listing Owner', 'import-wp-rentals-csv-xml' ) => array(
                    'name'    => 'property_agent',
                    'type'    => 'text',
                    'options' => '',
                    'tooltip' => esc_html__( 'Import the owner username, user ID, or email.', 'import-wp-rentals-csv-xml' )
                )
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
     * Adds the "Property Images" section to the import UI.
     */
    public function pmxi_image_field() {
        $listings_importer = new Importer_Listings( $this->add_on, $this->post_type );
        $this->add_on->disable_default_images();
        $this->add_on->import_images( 'pmxi_wprentals_listing_images', esc_html__( 'Property Images', 'import-wp-rentals-csv-xml' ), 'images', [ $listings_importer, 'pmxi_image_importer' ] );
    }
    
    /**
     * Adds all of the regular text and image fields to the UI.
     */
    public function pmxi_text_image_custom_details() {
        // Images
        $this->pmxi_image_field();

        /*****
        * Property General Details section
        */
        $this->add_on->add_title( esc_html__( 'Property General Details', 'import-wp-rentals-csv-xml' ) );
        $fields = $this->pmxi_get_all_fields( 'Property General Details' );
        if ( ! empty( $fields ) && is_array( $fields ) ) {
            foreach ( $fields as $field_name => $field_data ) {
                $this->add_on->add_field( $field_data['name'], $field_name, $field_data['type'], $field_data['options'], esc_html( $field_data['tooltip'] ) );
            }
        }

        /*****
        * End Property General Details section
        */
        
        /***
        * Property Price section
        */
        
        $this->add_on->add_title( esc_html__( 'Property Price', 'import-wp-rentals-csv-xml' ) );
        $fields = $this->pmxi_get_all_fields( 'Property Price' );
        if ( ! empty( $fields ) && is_array( $fields ) ) {
            foreach ( $fields as $field_name => $field_data ) {
                $this->add_on->add_field( $field_data['name'], $field_name, $field_data['type'], $field_data['options'], esc_html( $field_data['tooltip'] ) );
            }
        }

        /*****
        * End Property Price section
        */
        
        /*****
        * Property Media section
        */
        
        $this->add_on->add_title( esc_html__( 'Property Media', 'import-wp-rentals-csv-xml' ) );
        $fields = $this->pmxi_get_all_fields( 'Property Media' );
        if ( ! empty( $fields ) && is_array( $fields ) ) {
            foreach ( $fields as $field_name => $field_data ) {
                $this->add_on->add_field( $field_data['name'], $field_name, $field_data['type'], $field_data['options'], esc_html( $field_data['tooltip'] ) );
            }
        }

        /*****
        * End Property Media section
        */
        
        /*****
        * Property Specific Details section
        */
        
        $this->add_on->add_title( esc_html__( 'Property Specific Details', 'import-wp-rentals-csv-xml' ) );
        $fields = $this->pmxi_get_all_fields( 'Property Specific Details' );
        if ( ! empty( $fields ) && is_array( $fields ) ) {
            foreach ( $fields as $field_name => $field_data ) {
                $this->add_on->add_field( $field_data['name'], $field_name, $field_data['type'], $field_data['options'], esc_html( $field_data['tooltip'] ) );
            }
        }
        
        /*****
        * End Property Specific Details section
        */
        
        /*****
        * Map section
        */
        $this->add_on->add_title( esc_html__( 'Map', 'import-wp-rentals-csv-xml' ) );
        $fields = $this->pmxi_get_all_fields( 'Map' );
        if ( ! empty( $fields ) && is_array( $fields ) ) {
            foreach ( $fields as $field_name => $field_data ) {
                $this->add_on->add_field( $field_data['name'], $field_name, $field_data['type'], $field_data['options'], esc_html( $field_data['tooltip'] ) );
            }
        }
        $this->pmxi_add_geocoding_options();
        $this->pmxi_add_google_section();
        
        /*****
        * End Map section
        */
        
        /*****
        * Owner section
        */
        
        $this->add_on->add_title( esc_html__( 'Owner', 'import-wp-rentals-csv-xml' ) );
        $fields = $this->pmxi_get_all_fields( 'Owner' );
        if ( ! empty( $fields ) && is_array( $fields ) ) {
            foreach ( $fields as $field_name => $field_data ) {
                $this->add_on->add_field( $field_data['name'], $field_name, $field_data['type'], $field_data['options'], esc_html( $field_data['tooltip'] ) );
            }
        }
        
        /*****
        * End Owner section
        */

        /*****
         * Advanced Options Section
         */

         $this->add_on->add_options( null, esc_html__( 'Advanced Options', 'import-wp-rentals-csv-xml' ), $this->pmxi_advanced_options() );

        /*****
         * End Advanced Options Section
         */
        
    }


    /**
     * Adds the "Advanced Settings" fields to the import UI.
     */
    public function pmxi_advanced_options() {
        $options = array(
            $this->add_on->add_field('sidebar_option', esc_html__( 'Where to show the sidebar', 'import-wp-rentals-csv-xml' ), 'radio', array(
                'right' => esc_html__( 'Right', 'import-wp-rentals-csv-xml' ),
                'left'  => esc_html__( 'Left', 'import-wp-rentals-csv-xml' ),
                'none'  => esc_html__( 'None', 'import-wp-rentals-csv-xml' )
            ), esc_html__( 'Accepted values are \'right\', \'left\', or \'none\'', 'import-wp-rentals-csv-xml' )),
            $this->add_on->add_field('sidebar_select', esc_html__( 'Select the sidebar', 'import-wp-rentals-csv-xml' ), 'radio', array(
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
            ), esc_html__( 'To see the possible import values, check Set with XPath › Field Options › Mapping', 'import-wp-rentals-csv-xml' ))
        );

        return $options;
    }

    /**
     * This adds the options necessary to import the map/location data.
     */
    public function pmxi_add_geocoding_options() {
        $this->add_on->add_field( 
            '_geocoding_options',
            esc_html__( 'Geocoding Options', 'import-wp-rentals-csv-xml' ),
            'radio',
            array(
                'search_by_address'     => esc_html__( 'Search by address from the Property General Details section', 'import-wp-rentals-csv-xml' ),
                'search_by_coords'      => array(
                    esc_html__( 'Enter coordinates', 'import-wp-rentals-csv-xml' ),
                    $this->add_on->add_field(
                        '_listing_lat',
                        esc_html__( 'Latitude', 'import-wp-rentals-csv-xml' ),
                        'text'
                    ),
                    $this->add_on->add_field(
                        '_listing_lng',
                        esc_html__( 'Longitude', 'import-wp-rentals-csv-xml' ),
                        'text'
                    )
                ),
                'import_address_as_is' => esc_html__( 'Import address from the Property General Details section as-is.', 'import-wp-rentals-csv-xml' ),
                'import_coords_as_is'  => esc_html__( 'Import coordinates (latitude/longitude) as-is.', 'import-wp-rentals-csv-xml' ),
                    ),
                    esc_html__( 'The "Search by address from the Property General Details section" and "Enter coordinates" options will use Google\'s Geocoding API to fetch the address data. The other options will import the data as-is with no request to the Geocoding API. A valid API key must be present in the Google Geocode API Settings section if you\'re using the API.', 'import-wp-rentals-csv-xml' )
            );
    }

    /**
     * This adds the Google Geocoding section to the import UI.
     */
    public function pmxi_add_google_section() {
        $this->add_on->add_options(null, esc_html__( 'Google Geocode API Settings', 'import-wp-rentals-csv-xml' ), array(
            $this->add_on->add_field(
                'address_geocode',
                esc_html__( 'Request Method', 'import-wp-rentals-csv-xml' ),
                'radio',
                array(
                    'address_google_developers' => array(
                        wp_kses(
                            __( 'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>', 'import-wp-rentals-csv-xml' ),
                            array(
                                'a' => array(
                                    'href' => array()
                                )
                            )
                        ),
                        $this->add_on->add_field(
                            'address_google_developers_api_key', 
                            esc_html__( 'API Key', 'import-wp-rentals-csv-xml' ),
                            'text'
                        ),
                        esc_html__( 'Up to 2,500 requests per day and 5 requests per second.', 'import-wp-rentals-csv-xml' )
                    ),
                    'address_google_for_work' => array(
                        wp_kses(
                            __( 'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>', 'import-wp-rentals-csv-xml' ),
                            array(
                                'a' => array(
                                    'href' => array()
                                )
                            )
                        ),
                        $this->add_on->add_field(
                            'address_google_for_work_client_id', 
                            esc_html__( 'Google for Work Client ID', 'import-wp-rentals-csv-xml' ),
                            'text'
                         ), 
                        $this->add_on->add_field(
                            'address_google_for_work_digital_signature', 
                            esc_html__( 'Google for Work Digital Signature', 'import-wp-rentals-csv-xml' ),
                            'text'
                        ),
                        esc_html__( 'Up to 100,000 requests per day and 10 requests per second', 'import-wp-rentals-csv-xml' )
                        )
                    ) // end Request Method options array
                ) // end Request Method nested radio field 
            )
        );
    }
}