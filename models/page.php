<?php
/**
 * Define the generic Page class.
 */

use TMS\Theme\Tredu\PostType\Program;
use TMS\Theme\Tredu\Formatters\ProgramFormatter;

/**
 * The Page class.
 */
class Page extends PageExtend {

    /**
     * Define methods that are allowed to be used by Ajax.
     *
     * @var array
     */
    public $api = [
        'Programslist',
    ];

    /**
     * Query programs to be loaded with ajax load more
     *
     * @return array
     */
    public function Programslist() {
        $programformatter = new ProgramFormatter();
        $js_args          = $this->get_args()->ajax_params ?? false;
        $posts_per_page   = 4;
        $offset           = $posts_per_page;

        if ( $js_args ) {
            $current_set     = (int) $js_args->currentSet; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
            $posts_per_page  = $js_args->postsPerPage; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
            $apply_start     = $js_args->applyStart; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
            $offset          = ( $current_set - 1 ) * $posts_per_page;
            $data_attributes = [
                'apply_method'           => $js_args->applyMethod ?? '', // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'program_type'           => $js_args->programType ?? '', // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'profession'             => $js_args->profession ?? '',
                'location'               => $js_args->location ?? '',
                'educational_background' => $js_args->educationalBackground ?? '', // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
            ];
        }

        // Initial arguments for wp query
        $args = [
            'post_type'              => Program::SLUG,
            'update_post_meta_cache' => false,
            'orderby'                => [ 'start_date' => 'ASC', 'title' => 'ASC' ],
            'posts_per_page'         => $posts_per_page ?? 4,
            'offset'                 => $offset,
        ];

        $date_query = [];

        // Check if start date is set and make a date query
        if ( ! empty( $apply_start ) ) {
            $date_query[] = [
                'key'     => 'apply_start',
                'compare' => '>=',
                'type'    => 'DATE',
                'value'   => $apply_start,
            ];
        }

        if ( ! empty( $date_query ) ) {
            $args['update_post_meta_cache'] = true;
            $args['meta_query']             = $date_query;
        }

        $taxonomies = $programformatter->get_tax_map();

        $tax_query = [];

        // Go through taxonomies and check if data-attributes have them selected
        foreach ( $taxonomies as $field_key => $taxonomy ) {
            if ( empty( $data_attributes[ $field_key ] ) ) {
                continue;
            }

            // Add found taxonomy filters to meta query
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $data_attributes[ $field_key ],
            ];
        }

        if ( ! empty( $tax_query ) ) {
            array_unshift(
                $tax_query,
                [
                    'relation' => 'AND',
                ]
            );
        }

        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }

        $programs = ( new \WP_Query( $args ) )->get_posts();

        if ( ! empty( $programs ) ) {
            $programs = Program::format_posts( $programs, $programformatter->get_tax_map() );
        }

        $data['posts'] = $programs;

        return $data;
    }

}
