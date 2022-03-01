<?php
/**
 * Copyright (c) 2021. Geniem Oy
 * Template Name: Yhteystiedot
 */

use TMS\Theme\Tredu\Formatters\ContactFormatter;
use TMS\Theme\Tredu\PostType\Contact;
use TMS\Theme\Tredu\Settings;
use TMS\Theme\Tredu\Traits\Components;

/**
 * The PageContacts class.
 */
class PageContacts extends BaseModel {

    use Components;

    /**
     * Template
     */
    const TEMPLATE = 'models/page-contacts.php';

    /**
     * Search input name.
     */
    const SEARCH_QUERY_VAR = 'contact_search';

    /**
     * Return current search data.
     *
     * @return string[]
     */
    public function search() : array {
        return [
            'input_search_name' => self::SEARCH_QUERY_VAR,
            'current_search'    => get_query_var( self::SEARCH_QUERY_VAR ),
            'action'            => get_the_permalink(),
        ];
    }

    /**
     * Get contacts
     *
     * @return array
     */
    protected function get_contacts() : array {
        $args = [
            'post_type'      => Contact::SLUG,
            'posts_per_page' => 200, // phpcs:ignore
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'meta_key'       => 'last_name',
            'orderby'        => [
                'menu_order' => 'ASC',
                'meta_value' => 'ASC', // phpcs:ignore
            ],
        ];

        $s = get_query_var( self::SEARCH_QUERY_VAR, false );

        if ( ! empty( $s ) ) {
            $args['s'] = $s;
        }

        $the_query = new WP_Query( $args );

        return $the_query->posts;
    }

    /**
     * Get contacts
     */
    public function contacts() : array {
        $contacts  = $this->get_contacts();
        $formatter = new ContactFormatter();

        return $formatter->map_keys(
            $contacts,
            get_field( 'fields' ),
            Settings::get_setting( 'contacts_default_image' )
        );
    }

    /**
     * Get column class
     *
     * @return string
     */
    public function column_class() : string {
        $field_keys   = get_field( 'fields' );
        $column_class = 'is-10-mobile is-offset-1-mobile is-6-tablet is-offset-0-tablet';

        if ( ! in_array( 'image', $field_keys, true ) ) {
            $column_class .= ' is-3-desktop';
        }

        return $column_class;
    }
}
