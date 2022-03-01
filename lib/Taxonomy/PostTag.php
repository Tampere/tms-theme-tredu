<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Taxonomy;

use \TMS\Theme\Tredu\Interfaces\Taxonomy;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Tredu\Taxonomy
 */
class PostTag implements Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'post_tag';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {}

    /**
     * Get post categories
     *
     * @param int $post_id WP_Post ID.
     *
     * @return array
     */
    public static function get_post_tags( int $post_id ) : array {
        $categories = wp_get_post_terms( $post_id, static::SLUG );

        if ( empty( $categories ) ) {
            return [];
        }

        return array_map( function ( $item ) {
            $item->permalink = get_term_link( $item, static::SLUG );

            return $item;
        }, $categories );
    }
}
