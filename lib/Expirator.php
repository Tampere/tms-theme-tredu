<?php
/**
 * This class is used to move expirated posts to trash.
 */

namespace TMS\Theme\Tredu;

use TMS\Theme\Tredu\PostType\TreduEvent;

/**
 *
 * Class to make expirated posts to drafts.
 *
 * @package TMS\Theme\Tredu
 */
class Expirator {

    /**
     * Make expired posts to have 'draft' state.
     *
     * @return void
     */
    public static function expirate() {
        $args = [
            'post_type'              => TreduEvent::SLUG,
            'post_status'            => 'publish',
            'update_post_term_cache' => false,
            'posts_per_page'         => -1,
        ];

        $query = new \WP_Query( $args );
        $ids = \wp_list_pluck( $query->posts, 'ID' );

        // Current time.
        $now           = \current_time( 'mysql' );
        $timestamp_now = \strtotime( $now );

        array_map( function ( $post ) use ( $timestamp_now ) {

            // End time of the post.
            $end_time = \get_post_meta( $post, 'end_date', true );

            // If end_date field is empty, use start_date + 1 day as the expiration date
            if ( empty( $end_time ) ) {
                $end_time = \get_post_meta( $post, 'start_date', true );
                $end_time = date( 'Ymd', strtotime( $end_time . ' +1 days' ) );
            }

            if ( ! empty( $end_time ) ) {

                $timestamp_end = \strtotime( $end_time );

                // Set post as 'draft' if end time was found and the end time is passed.
                if ( $timestamp_now > $timestamp_end ) {
                    $post_object = \get_post( $post );
                    $post_object->post_status = 'draft';
                    \wp_update_post( $post_object );
                }
            }
        }, $ids );
    }
}

