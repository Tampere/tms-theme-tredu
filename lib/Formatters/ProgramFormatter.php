<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

use TMS\Theme\Tredu\Interfaces\Formatter;
use TMS\Theme\Tredu\PostType\Post;
use TMS\Theme\Tredu\PostType\Program;
use TMS\Theme\Tredu\Taxonomy\ApplyMethod;
use TMS\Theme\Tredu\Taxonomy\EducationalBackground;
use TMS\Theme\Tredu\Taxonomy\Location;
use TMS\Theme\Tredu\Taxonomy\Profession;
use TMS\Theme\Tredu\Taxonomy\ProgramType;

/**
 * Class ProgramFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class ProgramFormatter implements Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'Program';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/program/data',
            [ $this, 'format' ]
        );
    }

    /**
     * Format layout data
     *
     * @param array $data ACF data.
     *
     * @return array
     */
    public function format( array $data ) : array {
        $program_query = $this->query_programs( $data );
        $programs = $program_query->posts;

        if ( ! empty( $programs ) ) {
            $programs = Program::format_posts( $programs, $this->get_tax_map() );
        }

        $data['posts']          = $programs;
        $data['found_posts']    = $program_query->found_posts;
        $data['posts_per_page'] = $program_query->post_count;

        return $data;
    }

    /**
     * Query Tredu Events.
     *
     * @param array $layout Layout.
     *
     * @return \WP_Query
     */
    public function query_programs( array $layout ) : \WP_Query {
        $args = [
            'post_type'              => Program::SLUG,
            'update_post_meta_cache' => false,
            'orderby'                => [ 'start_date' => 'ASC', 'title' => 'ASC' ],
            'posts_per_page'         => $layout['number'] ?? 4,
            'offset'                 => $layout['offset'] ?? 0,
        ];

        $date_query = $this->get_date_query( $layout );

        if ( ! empty( $date_query ) ) {
            $args['update_post_meta_cache'] = true;
            $args['meta_query']             = $date_query;
        }

        $tax_query = $this->get_tax_query( $layout );

        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }

        return new \WP_Query( $args );
    }

    /**
     * Get tax queries.
     *
     * @param array $layout Layout data.
     *
     * @return array
     */
    public function get_tax_query( array $layout ) : array {
        $taxonomies = $this->get_tax_map();

        $tax_query = [];

        foreach ( $taxonomies as $field_key => $taxonomy ) {
            if ( empty( $layout[ $field_key ] ) ) {
                continue;
            }

            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $layout[ $field_key ],
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

        return $tax_query;
    }

    /**
     * Get taxonomies.
     *
     * @return array
     */
    public function get_tax_map() : array {
        return [
            'apply_method'           => ApplyMethod::SLUG,
            'program_type'           => ProgramType::SLUG,
            'profession'             => Profession::SLUG,
            'location'               => Location::SLUG,
            'educational_background' => EducationalBackground::SLUG,
        ];
    }

    /**
     * Get date query.
     *
     * @param array $layout Layout data.
     *
     * @return array
     */
    public function get_date_query( array $layout ) : array {
        $meta_query = [];

        if ( ! empty( $layout['apply_start'] ) ) {
            $meta_query[] = [
                'key'     => 'apply_start',
                'compare' => '>=',
                'type'    => 'DATE',
                'value'   => $layout['apply_start'],
            ];
        }

        return $meta_query;
    }
}
