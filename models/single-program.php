<?php

use DustPress\Query;
use TMS\Theme\Tredu\Taxonomy\DeliveryMethod;
use TMS\Theme\Tredu\Taxonomy\Location;
use TMS\Theme\Tredu\Traits;
use TMS\Theme\Tredu\Taxonomy\ApplyMethod;
use TMS\Theme\Tredu\Taxonomy\Category;
use TMS\Theme\Tredu\Images;
use TMS\Theme\Tredu\Taxonomy\EducationalBackground;

/**
 * The SingleProgram class.
 */
class SingleProgram extends BaseModel {

    use Traits\Sharing;
    use Traits\Components;

    /**
     * Content
     *
     * @return array|object|WP_Post|null
     * @throws Exception If global $post is not available or $id param is not defined.
     */
    public function content() {
        $single = $this->get_post();

        $single->image = $single->image === 0
            ? false
            : $single->image;

        return $single;
    }

    /**
     * Get program info
     *
     * @return array
     */
    public function program_info() : array {
        $single = $this->get_post();
        $fields = $single->fields;
        $info   = [];

        $info[] = [
            'icon'  => 'prompt',
            'label' => _x( 'Apply period', 'program info', 'tms-theme-tredu' ),
            'text'  => $this->get_apply_period( $fields ),
        ];

        if ( ! isset( $fields['show_audience'] ) || ! empty( $fields['show_audience'] ) ) {

            $info[] = [
                'icon'  => 'learning',
                'label' => _x( 'Audience', 'program info', 'tms-theme-tredu' ),
                'text'  => $this->get_educational_background(),
            ];

        }

        $info[] = [
            'icon'  => 'backpack',
            'label' => _x( 'Delivery method', 'program info', 'tms-theme-tredu' ),
            'text'  => $this->get_delivery_method(),
        ];

        $info[] = [
            'icon'  => 'school',
            'label' => _x( 'Location', 'program info', 'tms-theme-tredu' ),
            'text'  => $this->get_location(),
        ];

        $info[] = [
            'icon'  => 'book',
            'label' => _x( 'Program starts', 'program info', 'tms-theme-tredu' ),
            'text'  => $this->get_start_date( $fields ),
        ];

        $info[] = [
            'icon'  => 'document',
            'label' => _x( 'Price', 'program info', 'tms-theme-tredu' ),
            'text'  => $fields['price'],
        ];

        return array_filter( $info, fn( $item ) => ! empty( $item['text'] ) );
    }

    /**
     * Get delivery method
     *
     * @return string|null
     */
    protected function get_delivery_method() : ?string {
        $terms = \get_the_terms( \get_queried_object(), DeliveryMethod::SLUG );

        if ( empty( $terms ) || \is_wp_error( $terms ) ) {
            return null;
        }

        return implode( ', ', array_map( fn( $item ) => $item->name, $terms ) );
    }

    /**
     * Get educational backgroud
     *
     * @return string|null
     */
    protected function get_educational_background() : ?string {
        $terms = \get_the_terms( \get_queried_object(), EducationalBackground::SLUG );

        if ( empty( $terms ) || \is_wp_error( $terms ) ) {
            return null;
        }

        return implode( ', ', array_map( fn( $item ) => $item->name, $terms ) );
    }

    /**
     * Get location
     *
     * @return string|null
     */
    protected function get_location() : ?string {
        $terms = \get_the_terms( \get_queried_object(), Location::SLUG );

        if ( empty( $terms ) || \is_wp_error( $terms ) ) {
            return null;
        }

        return implode( ', ', array_map( fn( $item ) => $item->name, $terms ) );
    }

    /**
     * Get start date
     *
     * @param array $fields Meta fields.
     *
     * @return string|null
     */
    protected function get_start_date( $fields ) : ?string {
        if ( ! empty( $fields['start_info'] ) ) {
            $start_info = $fields['start_info'];
        }
        elseif ( ! empty( $fields['start_date'] ) ) {
            $start_info = $fields['start_date'];
        }

        return $start_info ?? null;
    }

    /**
     * Get apply period
     *
     * @param array $fields Meta fields.
     *
     * @return string|null
     */
    protected function get_apply_period( array $fields ) : ?string {
        if ( ! empty( $fields['apply_info'] ) ) {
            return $fields['apply_info'];
        }

        if ( ! empty( $fields['apply_start'] ) ) {
            $start = new \DateTime( $fields['apply_start'] );
        }

        if ( ! empty( $fields['apply_end'] ) ) {
            $end = new \DateTime( $fields['apply_end'] );
        }

        $full_format  = 'd.m.Y';
        $short_format = 'd.m.';

        if ( ! empty( $start ) && ! empty( $end ) ) {
            if ( $start->format( 'Y' ) === $end->format( 'Y' ) ) {
                $apply_period = sprintf( '%s - %s', $start->format( $short_format ), $end->format( $full_format ) );
            }
            else {
                $apply_period = sprintf( '%s - %s', $start->format( $full_format ), $end->format( $full_format ) );
            }
        }
        elseif ( ! empty( $start ) && empty( $end ) ) {
            $apply_period = $start->format( $full_format );
        }

        return $apply_period ?? null;
    }

    /**
     * Get current post
     *
     * @return array|object|WP_Post|null
     */
    protected function get_post() {
        return Query::get_acf_post( \get_queried_object_id() );
    }

    /**
     * Get apply method taxonomy
     */
    public function apply_method_colors() {

        $colors = [
            'bg'   => 'blue',
            'text' => 'primary',
            'btn'  => 'is-primary',
        ];

        $this_post     = $this->get_post();
        $apply_methods = \get_the_terms( $this_post, ApplyMethod::SLUG );

        if ( ! empty( $apply_methods[0]->term_id ) ) {
            $apply_method_color = \get_term_meta( $apply_methods[0]->term_id, 'color', true ) ?? '';

            if ( ! empty( $apply_method_color ) ) {
                $colors['bg']   = $apply_method_color;
                $colors['text'] = $apply_method_color === 'primary' ? 'white' : 'primary';
                $colors['btn']  = $apply_method_color === 'primary' ? 'is-secondary' : 'is-primary';
            }
		}

        return $colors;
    }

    /**
     * Get apply method taxonomy
     */
    public function search_box_default_strs() {

        $single = $this->get_post();
        $fields = $single->fields;

		$strs = [
			'title'       => _x( 'Apply now', 'program info', 'tms-theme-tredu' ) . '!',
			'description' => _x( 'Apply period', 'program info', 'tms-theme-tredu' ) . ' ' . $this->get_apply_period( $fields ),
		];

        return $strs;
    }

    /**
     * Get selected category stories
     */
    public function stories() {

        $stories = [];

        $single = $this->get_post();
        $fields = $single->fields;

        $category = $fields['category'] ?? null;

        if ( empty( $category ) ) {
            return;
        }

        $stories['heading'] = _x( 'Graduation stories from Tredu', 'program info', 'tms-theme-tredu' );

        $amount               = $fields['stories_amount'] ?? 4;
        $stories['read_more'] = $fields['link'] ?? false;
        $args                 = [
            'post_type'      => 'post',
            'posts_per_page' => $amount,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'cat'            => [ implode( ', ', $category ) ],
		];

		$query = new WP_Query( $args );

        $posts = $query->query( $args );

        foreach ( $posts as $post ) {

            $image_id = \get_post_thumbnail_id( $post->ID ) ?? false;

            if ( ! $image_id || $image_id < 1 ) {
                $image_id = Images::get_default_image_id();
            }

            $stories['posts'][] = [
                'post_title'     => $post->post_title ?? '',
                'featured_image' => $image_id,
                'permalink'      => \get_permalink( $post->ID ),
                'post_date'      => $post->post_date ?? '',
                'excerpt'        => $post->post_excerpt ?? '',
            ];

        }

        return $stories;
    }
}
