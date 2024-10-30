<?php

namespace PMXI_WP_Rentals_Import_Add_On;

class Importer_Location extends Importer_Listings {

    protected $add_on;
    public $helper;
    private $request_url;
    
    public function __construct( RapidAddon $addon_object, $post_type = 'listing' ) {
        $this->add_on = $addon_object;
        $this->helper = new Helper();
    }

    public function pmxi_import( $post_id, $data, $import_options, $article ) {
        $post_id = absint( $post_id );

        $address       = !empty( $data['property_address'] ) ? sanitize_text_field( $data['property_address'] ) : null;
        $county        = !empty( $data['property_county'] ) ? sanitize_text_field( $data['property_county'] ) : null;
        $state         = !empty( $data['property_state'] ) ? sanitize_text_field( $data['property_state'] ) : null;
        $street_name   = null;
        $street_number = null;
        $country       = !empty( $data['property_country'] ) ? sanitize_text_field( $data['property_country'] ) : null;
        $zip           = !empty( $data['property_zip'] ) ? sanitize_text_field( $data['property_zip'] ) : null;
        $api_key       = null;
        $lat           = !empty( $data['_listing_lat'] ) ? sanitize_text_field( $data['_listing_lat'] ) : null;
        $lng           = !empty( $data['_listing_lng'] ) ? sanitize_text_field( $data['_listing_lng'] ) : null;

        if ( $data['address_geocode'] == 'address_google_developers' && !empty( $data['address_google_developers_api_key'] ) ) {
            $api_key = sanitize_text_field( $data['address_google_developers_api_key'] );
        } elseif ( $data['address_geocode'] == 'address_google_for_work' && !empty( $data['address_google_for_work_client_id'] ) && !empty( $data['address_google_for_work_signature'] ) ) {
            $api_key = 'client=' . sanitize_text_field( $data['address_google_for_work_client_id'] ) . '&signature=' . sanitize_text_field( $data['address_google_for_work_signature'] );
        }

        $geocoding_data = null;

        switch ( $data['_geocoding_options'] ) {
            case 'search_by_address':
                $search = !empty( $address ) ? 'address=' . rawurlencode( $address ) : null;
                $geocoding_data = $this->pmxi_get_api_results( $search, $api_key );
                $this->pmxi_import_geocoding_data( $post_id, $data, $import_options, $article, $geocoding_data, $county, $state, $street_name, $street_number, $country, $zip );
                break;

            case 'search_by_coords':
                $search = !empty( $lat ) && !empty( $lng ) ? 'latlng=' . rawurlencode( $lat . ',' . $lng ) : null;
                $geocoding_data = $this->pmxi_get_api_results( $search, $api_key );
                $this->pmxi_import_geocoding_data( $post_id, $data, $import_options, $article, $geocoding_data, $county, $state, $street_name, $street_number, $country, $zip );
                break;

            case 'import_address_as_is':
            case 'import_coords_as_is':
                $this->pmxi_import_location_as_is( $post_id, $data, $import_options, $article );
                break;
        }
    }

    public function pmxi_import_location_as_is( $post_id, $data, $import_options, $article ) {
        $post_id = absint( $post_id );

        $fields = array(
            'property_latitude',
            'property_longitude',
            'property_address',
            'property_zip',
            'property_country',
            'property_county',
            'property_state',
        );

        foreach ( $fields as $field ) {
            $value = isset( $data[ $field ] ) ? sanitize_text_field( $data[ $field ] ) : '';
            if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field, $import_options ) ) {
                $this->helper->pmxi_update_meta( $post_id, $field, $value );
            }
        }
    }

    public function pmxi_import_geocoding_data( $post_id, $data, $import_options, $article, $details, $county, $state, $street_name, $street_number, $country, $zip ) {
        $post_id = absint( $post_id );

        if ( !empty( $details ) && ( empty( $article['ID'] ) || $this->pmxi_can_update_location_fields( $import_options ) ) ) {
            $lat        = sanitize_text_field( $details['results'][0]['geometry']['location']['lat'] );
            $long       = sanitize_text_field( $details['results'][0]['geometry']['location']['lng'] );
            $address    = sanitize_text_field( $details['results'][0]['formatted_address'] );
            $components = $details['results'][0]['address_components'];
    
            foreach ( $components as $key => $data ) {
                if ( $data['types'][0] == 'street_number' ) {
                    $street_number = sanitize_text_field( $data['short_name'] );
                    continue;
                }
                if ( $data['types'][0] == 'route' ) {
                    $street_name = sanitize_text_field( $data['short_name'] );
                    continue;
                }
                if ( $data['types'][0] == 'country' ) {
                    $country = sanitize_text_field( $data['long_name'] );
                    continue;
                }
                if ( $data['types'][0] == 'postal_code' ) {
                    $zip = sanitize_text_field( $data['short_name'] );
                    continue;
                }
                if ( $data['types'][0] == 'administrative_area_level_2' ) {
                    $county = sanitize_text_field( $data['long_name'] );
                }
                if ( $data['types'][0] === 'administrative_area_level_1' ) {
                    $state = sanitize_text_field( $data['short_name'] );
                }   
            }
            
            // Update location fields
            $fields = array(
                'property_latitude'  => $lat,
                'property_longitude' => $long,
                'property_address'   => $street_number . ' ' . $street_name,
                'property_zip'       => $zip,
                'property_country'   => $country,
                'property_county'    => $county,
                'property_state'     => $state
            );

            // Ensure property_address field is updated
            if (!empty($address)) {
                $fields['property_address'] = $address;
            }
    
            $this->helper->pmxi_log( esc_html__( '- Got location data from Geocoding API: ', 'import-wp-rentals-csv-xml' ) . esc_url( $this->request_url ) );
            $serialized_geocoding_data = wp_json_encode( $fields );
            $this->helper->pmxi_log( esc_html__( '- Geocoding data received: ', 'import-wp-rentals-csv-xml' ) . esc_html( $serialized_geocoding_data ) );
            $this->helper->pmxi_log( esc_html__( '- Updating latitude and longitude', 'import-wp-rentals-csv-xml' ) );
    
            foreach ( $fields as $key => $value ) {   
                if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $key, $import_options ) ) {
                    $this->helper->pmxi_update_meta( $post_id, $key, $value );    
                }
            }
        } else {
            $this->helper->pmxi_log( esc_html__( 'Skipping updates to the map/location fields. The location details are empty or the location fields are disabled in the import settings.', 'import-wp-rentals-csv-xml' ) );
        }
    }

    public function pmxi_get_api_results( $search, $api_key ) {
        if ( empty( $api_key ) ) {
            $this->pmxi_fail( 'empty_api_key' );
            return false;
        }
    
        $this->request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . '&key=' . $api_key;
        $response = wp_remote_get( $this->request_url );
    
        if ( is_wp_error( $response ) ) {
            $this->pmxi_fail( 'http_request_failed', $response->get_error_message() );
            return false;
        }
    
        $json = wp_remote_retrieve_body( $response );
    
        if ( ! empty( $json ) ) {
            $details = json_decode( $json, true );
    
            if ( array_key_exists( 'status', $details ) ) {
                if ( in_array( $details['status'], array( 'INVALID_REQUEST', 'ZERO_RESULTS', 'REQUEST_DENIED', 'OVER_QUERY_LIMIT' ) ) ) {
                    $this->pmxi_fail( 'error', $details );
                    return false;
                }
            }
    
            return $details;
        }
    
        return false;
    }

    public function pmxi_fail( $type, $details = array() ) {
        switch ( $type ) {
            case 'empty_api_key':
                $this->helper->pmxi_log( esc_html__( 'WARNING Geocoding failed because there is no API key in the import template.', 'import-wp-rentals-csv-xml' ) );
                break;

            case 'error':
                $this->helper->pmxi_log( esc_html__( 'WARNING Geocoding failed with status: ', 'import-wp-rentals-csv-xml' ) . esc_html( $details['status'] ) );
                if ( array_key_exists( 'error_message', $details ) ) {
                    $this->helper->pmxi_log( esc_html__( 'WARNING Geocoding error message: ', 'import-wp-rentals-csv-xml' ) . esc_html( $details['error_message'] ) );
                }
                break;
        }
    }
}
