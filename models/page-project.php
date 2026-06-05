<?php
/**
 * Template Name: Projektilistaus
 */

use DustPress\Query;
use TMS\Theme\Tredu\Traits;
use TMS\Theme\Tredu\PostType\Project;
use TMS\Theme\Tredu\Taxonomy\Portfolio;

/**
 * Archive for Project CPT
 */
class PageProject extends BaseModel {

    use Traits\Pagination;
    use Traits\Components;

    /**
     * Template
     */
    const TEMPLATE = 'models/page-project.php';

    /**
     * Search input name.
     */
    const SEARCH_QUERY_VAR = 'project-search';

    /**
     * Project category filter name.
     */
    const PORTFOLIO_QUERY_VAR = 'portfolio';

    /**
     * Project category active only input name.
     */
    const SHOW_ALL_PROJECTS_QUERY_VAR = 'show-all-projects';

    /**
     * Pagination data.
     *
     * @var object
     */
    protected object $pagination;

    /**
     * This holds the summary text.
     *
     * @var string
     */
    private static $summary = '';

    /**
     * Hooks
     */
    public function hooks() : void {
        \add_filter( 'tms/theme/breadcrumbs/show_breadcrumbs_in_header', fn() => false );
        \add_filter( 'the_seo_framework_title_from_generation', Closure::fromCallable( [ __CLASS__, 'alter_title' ] ) );
    }

    /**
     * Alter head section title.
     */
    private static function alter_title() : string {
        return self::$summary;
    }

    /**
     * Return Hero (post thumbnail) image ID.
     *
     * @return false|int
     */
    public function hero() {
        return \has_post_thumbnail() ? \get_post_thumbnail_id() : false;
    }

    /**
     * Content
     *
     * @return array|object|WP_Post|null
     * @throws Exception If global $post is not available or $id param is not defined.
     */
    public function content() {
        return Query::get_acf_post( get_queried_object_id() );
    }

    /**
     * Get search query var value
     *
     * @return mixed
     */
    protected static function get_search_query_var() {
        return \get_query_var( self::SEARCH_QUERY_VAR, false );
    }

    /**
     * Get active only query var value
     *
     * @return mixed
     */
    protected static function get_show_all_projects_query_var() {
        return \get_query_var( self::SHOW_ALL_PROJECTS_QUERY_VAR, false );
    }

    /**
     * Get filter query var value
     *
     * @return int|null
     */
    protected static function get_filter_query_var() {
        $value = \get_query_var( self::PORTFOLIO_QUERY_VAR, false );

        return ! $value
            ? null
            : intval( $value );
    }

    /**
     * Page title
     *
     * @return string
     */
    public function page_title() : string {
        return \get_the_title();
    }

    /**
     * Page description
     *
     * @return string
     */
    public function page_description() : string {
        return get_field( 'description' ) ?? '';
    }

    /**
     * Return translated strings.
     *
     * @return array[]
     */
    public function strings() : array {
        return [
            'search'     => [
                'title'             => __( 'Search for projects', 'tms-theme-tredu' ),
                'label'             => __( 'Search for project', 'tms-theme-tredu' ),
                'submit_value'      => __( 'Search', 'tms-theme-tredu' ),
                'input_placeholder' => __( 'Search query', 'tms-theme-tredu' ),
                'portfolio_label'   => __( 'Choose a portfolio', 'tms-theme-tredu' ),
                'show_all_projects' => __( 'Also show completed projects', 'tms-theme-tredu' ),
            ],
            'terms'      => [
                'show_all' => __( 'Show All', 'tms-theme-tredu' ),
            ],
            'no_results' => __( 'No results', 'tms-theme-tredu' ),
            'filter'     => __( 'Filter', 'tms-theme-tredu' ),
            'sort'       => __( 'Sort', 'tms-theme-tredu' ),

        ];
    }

    /**
     * Return current search data.
     *
     * @return string[]
     */
    public function search() : array {
        $this->search_data        = new stdClass();
        $this->search_data->query = \get_query_var( self::SEARCH_QUERY_VAR );

        return [
            'input_search_name' => self::SEARCH_QUERY_VAR,
            'current_search'    => $this->search_data->query,
            'action'            => \get_permalink( get_the_ID() ),
            'show_all_projects' => self::get_show_all_projects_query_var(),
        ];
    }

    /**
     * Filters
     *
     * @return array
     */
    public function filters() {
        $portfolios = \get_terms( [
            'taxonomy'   => Portfolio::SLUG,
            'hide_empty' => true,
        ] );

        if ( empty( $portfolios ) || \is_wp_error( $portfolios ) ) {
            return [];
        }

        $active_filter = self::get_filter_query_var();

        $portfolios = array_map( function ( $portfolio ) use ( $active_filter ) {
            if ( $portfolio->term_id === $active_filter ) {
                $portfolio->selected = true;
            }

            return $portfolio;
        }, $portfolios );

        array_unshift(
            $portfolios,
            [
                'name'  => __( 'Choose a portfolio', 'tms-theme-tredu' ),
                'value' => '',
            ]
        );

        return $portfolios;
    }

    /**
     * View results
     *
     * @return array
     */
    public function results() {
        $args = [
            'post_type'      => Project::SLUG,
            'paged'          => ( \get_query_var( 'paged' ) ) ? \get_query_var( 'paged' ) : 1,
            'posts_per_page' => 12,
            'order'          => 'ASC',
            'orderby'        => 'title',
        ];

        $show_all_projects = self::get_show_all_projects_query_var();

        // Show only active projects on default
        if ( $show_all_projects === false ) {
            $args['meta_query'] = [
                'active_project_clause' => [
                    [
                        'key'   => 'is_active',
                        'value' => '1',
                    ],
                ],
            ];
        }

        $portfolio = self::get_filter_query_var();

        if ( ! empty( $portfolio ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => Portfolio::SLUG,
                    'terms'    => $portfolio,
                ],
            ];
        }

        $s = self::get_search_query_var();

        if ( ! empty( $s ) ) {
            $args['s'] = $s;
        }

        $the_query = new WP_Query( $args );

        $this->set_pagination_data( $the_query );

        $is_filtered = $s || $portfolio;

        return [
            'posts'   => $this->format_posts( $the_query->posts ),
            'summary' => $is_filtered ? $this->results_summary( $the_query->found_posts, $s ) : false,
        ];
    }

    /**
     * Format posts for view
     *
     * @param array $posts Array of WP_Post instances.
     *
     * @return array
     */
    protected function format_posts( array $posts ) : array {
        return array_map( function ( $item ) {
            $item->permalink  = \get_the_permalink( $item->ID );
            $item->duration   = \get_field( 'duration', $item->ID );
            $item->title_lang = \get_field( 'title_lang', $item->ID );

            $terms = \wp_get_post_terms( $item->ID, Portfolio::SLUG );

            if ( ! empty( $terms ) && ! \is_wp_error( $terms ) ) {
                $item->portfolio = $terms[0];
            }

            return $item;
        }, $posts );
    }

    /**
     * Set pagination data
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    protected function set_pagination_data( $wp_query ) : void {
        $per_page = '12';
        $paged    = ( \get_query_var( 'paged' ) ) ? \get_query_var( 'paged' ) : 1;

        $this->pagination           = new stdClass();
        $this->pagination->page     = $paged;
        $this->pagination->per_page = $per_page;
        $this->pagination->items    = $wp_query->found_posts;
        $this->pagination->max_page = (int) ceil( $wp_query->found_posts / $per_page );
    }

    /**
     * Get results summary text.
     *
     * @param int    $result_count  Result count.
     * @param string $search_clause Search clause.
     *
     * @return string|bool
     */
    protected function results_summary( $result_count, $search_clause ) {
        if ( ! empty( $search_clause ) ) {
            $results_text = sprintf(
            // translators: 1. placeholder is number of search results, 2. placeholder contains the search term(s).
                _nx(
                    '%1$1s result found for "%2$2s"',
                    '%1$1s results found for "%2$2s"',
                    $result_count,
                    'filter with search clause results summary',
                    'tms-theme-tredu'
                ),
                $result_count,
                $search_clause
            );
        }
        else {
            $results_text = sprintf(
            // translators: 1. placeholder is number of search results
                _nx(
                    '%1$1s result found',
                    '%1$1s results found',
                    $result_count,
                    'filter results summary',
                    'tms-theme-tredu'
                ),
                $result_count,
                $search_clause
            );
        }

        self::$summary = $results_text;

        return $results_text;
    }
}
