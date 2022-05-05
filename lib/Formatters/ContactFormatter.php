<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

use TMS\Theme\Tredu\Integrations\Tampere\PersonApiController;
use TMS\Theme\Tredu\Integrations\Tampere\PersonFacade;
use TMS\Theme\Tredu\PostType\Contact;
use TMS\Theme\Tredu\Settings;

/**
 * Class ContactFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class ContactFormatter implements \TMS\Theme\Tredu\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'Contact';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/block/contacts/data',
            [ $this, 'format' ]
        );

        add_filter(
            'tms/acf/layout/contacts/data',
            [ $this, 'format' ]
        );
    }

    /**
     * Format view data
     *
     * @param array $data ACF data.
     *
     * @return array
     */
    public function format( array $data ) {
        if ( empty( $data['contacts'] ) && empty( $data['api_contacts'] ) ) {
            return $data;
        }

        $field_keys    = $data['fields'];
        $default_image = Settings::get_setting( 'contacts_default_image' );

        if ( ! empty( $data['contacts'] ) ) {
            $the_query = new \WP_Query( [
                'post_type'      => Contact::SLUG,
                'posts_per_page' => 100,
                'fields'         => 'ids',
                'post__in'       => array_map( 'absint', $data['contacts'] ),
                'no_found_rows'  => true,
                'meta_key'       => 'last_name',
                'orderby'        => [
                    'menu_order' => 'ASC',
                    'meta_value' => 'ASC', // phpcs:ignore
                ],
            ] );

            $filled_contacts = $this->map_keys(
                $the_query->posts,
                $field_keys,
                $default_image
            );
        }

        if ( ! empty( $data['api_contacts'] ) ) {
            $filled_api_contacts = $this->map_api_contacts(
                $data['api_contacts'],
                $field_keys,
                $default_image
            );
        }

        $data['filled_contacts'] = array_merge(
            $filled_contacts ?? [],
            $filled_api_contacts ?? []
        );

        $data['column_class'] = 'is-10-mobile is-offset-1-mobile is-6-tablet is-offset-0-tablet';

        if ( ! in_array( 'image', $field_keys, true ) ) {
            $data['column_class'] .= ' is-3-desktop';
        }

        return $data;
    }

    /**
     * Map api contacts to post like arrays
     *
     * @param array    $ids           Array of API ID's.
     * @param array    $field_keys    Array of field keys to be displayed.
     * @param int|null $default_image Default image.
     *
     * @return array|array[]
     */
    public function map_api_contacts( array $ids = [], array $field_keys = [], $default_image = null ) {
        if ( empty( $ids ) ) {
            return [];
        }

        $api      = new PersonApiController();
        $contacts = $api->validate_result_set( $api->get() );

        if ( empty( $contacts ) ) {
            return [];
        }

        $contacts = array_map(
            fn( $contact ) => ( new PersonFacade( $contact ) )->to_contact( $default_image ),
            $contacts
        );
        $contacts = array_filter( $contacts, function ( $contact ) use ( $ids ) {
            return in_array( $contact['id'], $ids, true );
        } );

        return array_map( function ( $contact ) use ( $field_keys ) {
            $fields = [];

            foreach ( $field_keys as $field_key ) {
                $fields[ $field_key ] = $contact[ $field_key ] ?? '';
            }

            return $fields;
        }, $contacts );
    }

    /**
     * Map keys to posts
     *
     * @param array $posts         Array of WP_Post instances.
     * @param array $field_keys    Array of field keys to be displayed.
     * @param null  $default_image Default image.
     *
     * @return array
     */
    public function map_keys( array $posts, array $field_keys, $default_image = null ) : array {
        return array_map( function ( $id ) use ( $field_keys, $default_image ) {
            $fields = [];

            foreach ( $field_keys as $field_key ) {
                $fields[ $field_key ] = get_field( $field_key, $id );
                $fields               = $this->append_image( $fields, $field_key, $default_image );
            }

            if ( isset( $fields['phone_repeater'] ) ) {
                $fields['phone_repeater'] = array_filter( $fields['phone_repeater'], function ( $item ) {
                    return ! empty( $item['phone_text'] ) || ! empty( $item['phone_number'] );
                } );
            }

            return $fields;
        }, $posts );
    }

    /**
     * Append image to contact fields
     *
     * @param array    $fields        Post fields.
     * @param string   $field_key     Array key.
     * @param int|null $default_image Default image id.
     *
     * @return array
     */
    protected function append_image( array $fields, string $field_key, ?int $default_image ) : array {
        if ( $field_key !== 'image' ) {
            return $fields;
        }

        $image_id = empty( $fields[ $field_key ] ) && ! empty( $default_image )
            ? $default_image
            : $fields[ $field_key ];

        if ( $image_id ) {
            $fields[ $field_key ] = wp_get_attachment_image_url( $image_id, 'medium' );
        }

        return $fields;
    }
}
