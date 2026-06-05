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
        $selected_contacts = \get_field( 'contacts' );
        if ( empty( $selected_contacts ) ) {
            return [];
        }

        $s = \get_query_var( self::SEARCH_QUERY_VAR, false );

        // Return all selected contacts if no search was performed.
        if ( empty( $s ) ) {
            return $selected_contacts;
        }

        $args = [
            'post_type'      => Contact::SLUG,
            'posts_per_page' => 200, // phpcs:ignore
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'post__in'       => array_map( 'absint', $selected_contacts ),
            'no_found_rows'  => true,
            'meta_key'       => 'last_name',
            'orderby'        => [
                'menu_order' => 'ASC',
                'meta_value' => 'ASC', // phpcs:ignore
            ],
            's'              => $s,
        ];

        $the_query = new \WP_Query( $args );

        return $the_query->posts;
    }

    /**
     * Get contacts
     */
    public function contacts() : array {
        $contacts      = $this->get_contacts();
        $api_contacts  = \get_field( 'api_contacts' ) ?? [];
        $default_image = Settings::get_setting( 'contacts_default_image' );
        $formatter     = new ContactFormatter();

        $contacts = $formatter->map_keys(
            $contacts,
            \get_field( 'fields' ) ?? [],
            $default_image
        );

        $api_contacts = $formatter->map_api_contacts(
            $api_contacts,
            \get_field( 'fields' ) ?? [],
            $default_image
        );

        return array_merge(
            $contacts ?? [],
            $api_contacts ?? []
        );
    }
}
