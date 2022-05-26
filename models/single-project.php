<?php

use DustPress\Query;
use TMS\Theme\Tredu\Traits;

/**
 * The SingleProject class.
 */
class SingleProject extends BaseModel {

    use Traits\Sharing;
    use Traits\Components;

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter( 'tms/theme/breadcrumbs/show_breadcrumbs_in_header', fn() => false );
    }

    /**
     * Content
     *
     * @return array|object|WP_Post|null
     * @throws Exception If global $post is not available or $id param is not defined.
     */
    public function content() {
        $single = Query::get_acf_post( get_queried_object_id() );

        return $single;
    }

    /**
     * Return Hero (post thumbnail) image ID.
     *
     * @return false|int
     */
    public function hero() {
        return has_post_thumbnail() ? get_post_thumbnail_id() : false;
    }

    /**
     * Logo cloud.
     *
     * @return array|void
     */
    public function logo_cloud() {
        $logos = get_field( 'logos' );

        if ( empty( $logos ) ) {
            return;
        }

        return [
            'logos'       => $logos,
            'title'       => get_field( 'logo_cloud_title' ),
            'description' => get_field( 'logo_cloud_description' ),
        ];
    }

    /**
     * Logo cloud.
     *
     * @return array|void
     */
    public function sidebar() {
        return [
            'website'   => get_field( 'website' ),
            'duration'  => get_field( 'duration' ),
            'portfolio' => get_field( 'portfolio' ),
            'contacts'  => get_field( 'contacts' ),
        ];
    }
}
