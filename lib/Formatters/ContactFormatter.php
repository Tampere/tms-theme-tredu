<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

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
        if ( empty( $data['contacts'] ) ) {
            return $data;
        }

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

        if ( ! $the_query->have_posts() ) {
            return $data;
        }

        $field_keys              = $data['fields'];
        $data['filled_contacts'] = $this->map_keys(
            $the_query->posts,
            $field_keys,
            Settings::get_setting( 'contacts_default_image' )
        );

        return $data;
    }

    /**
     * Map keys to posts
     *
     * @param array $posts         Array of WP_Post instances.
     * @param array $field_keys    Array of field keys.
     * @param null  $default_image Default image.
     *
     * @return array
     */
    public function map_keys( array $posts, array $field_keys, $default_image = null ) : array {
        return array_map( function ( $id ) use ( $field_keys, $default_image ) {
            $fields = [];

            foreach ( $field_keys as $field_key ) {
                $fields[ $field_key ] = get_field( $field_key, $id );

                if ( $field_key === 'image' && empty( $fields[ $field_key ] ) && ! empty( $default_image ) ) {
                    $fields[ $field_key ] = $default_image;
                }
            }

            if ( isset( $fields['phone_repeater'] ) ) {
                $fields['phone_repeater'] = array_filter( $fields['phone_repeater'], function ( $item ) {
                    return ! empty( $item['phone_text'] ) || ! empty( $item['phone_number'] );
                } );
            }

            return $fields;
        }, $posts );
    }
}
