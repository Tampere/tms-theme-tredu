<?php
/**
 * Class Strings
 * UI Strings
 */

/**
 * Class Strings
 */
class Strings extends \DustPress\Model {

    /**
     * Constructor
     *
     * @param array $args   Model arguments.
     * @param mixed $parent Set model parent.
     */
    public function __construct( $args = [], $parent = null ) {
        parent::__construct( $args, $parent );

        $this->hooks();
    }

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'dustpress/pagination/data',
            \Closure::fromCallable( [ $this, 'add_pagination_translations' ] )
        );
    }

    /**
     * Translated strings
     *
     * @return array
     */
    public function s() : array {
        return [
            'header'             => [
                'skip_to_content'           => _x( 'Skip to content', 'theme-frontend', 'tms-theme-tredu' ),
                'main_navigation'           => _x( 'Main navigation', 'theme-frontend', 'tms-theme-tredu' ),
                'frequently_searched_pages' => _x( 'Frequently searched pages', 'theme-frontend', 'tms-theme-tredu' ),
                'open_menu'                 => _x( 'Open menu', 'theme-frontend', 'tms-theme-tredu' ),
                'close_menu'                => _x( 'Close menu', 'theme-frontend', 'tms-theme-tredu' ),
                'language_navigation'       => _x( 'Language navigation', 'theme-frontend', 'tms-theme-tredu' ),
                'open_search'               => _x( 'Open search form', 'theme-frontend', 'tms-theme-tredu' ),
                'open_lang_nav'             => _x( 'Open language navigation', 'theme-frontend', 'tms-theme-tredu' ),
                'current_lang'              => _x( 'Current language: ', 'theme-frontend', 'tms-theme-tredu' ),
                'search'                    => _x( 'Search', 'theme-frontend', 'tms-theme-tredu' ),
                'search_from_site'          => _x( 'Search from site', 'theme-frontend', 'tms-theme-tredu' ),
                'search_title'              => _x( 'Search from site', 'theme-frontend', 'tms-theme-tredu' ),
                'search_input_label'        => _x( 'Search from site', 'theme-frontend', 'tms-theme-tredu' ),
                'search_input_placeholder'  => _x( 'Search from site', 'theme-frontend', 'tms-theme-tredu' ),
                'exception_close_button'    => _x( 'Close', 'theme-frontend', 'tms-theme-tredu' ),
                'home'                      => _x( 'To home page', 'theme-frontend', 'tms-theme-tredu' ),
                'breadcrumbs'               => _x( 'Breadcrumbs', 'theme-frontend', 'tms-theme-tredu' ),
                'translate'                 => _x( 'Translate', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            '404'                => [
                'title'         => _x( 'Page not found', 'theme-frontend', 'tms-theme-tredu' ),
                'subtitle'      => _x(
                    'The content were looking for was not found',
                    'theme-frontend',
                    'tms-theme-tredu',
                ),
                'home_link_txt' => _x( 'To home page', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'video'              => [
                'skip_embed' => _x( 'Skip video embed', 'theme-frontend', 'tms-theme-tredu' ),
                'play'       => _x( 'Play video', 'theme-frontend', 'tms-theme-tredu' ),
                'pause'      => _x( 'Pause video', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'social_media'       => [
                'skip_embed' => _x( 'Skip social media embed', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'share'              => [
                'share_article'         => _x( 'Share Article', 'theme-frontend', 'tms-theme-tredu' ),
                'share_event'           => _x( 'Share Event', 'theme-frontend', 'tms-theme-tredu' ),
                'share_to_social_media' => _x( 'Share to social media', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'gallery'            => [
                'close'           => _x( 'Close', 'theme-frontend', 'tms-theme-tredu' ),
                'next'            => _x( 'Next', 'theme-frontend', 'tms-theme-tredu' ),
                'open'            => _x( 'Open', 'theme-frontend', 'tms-theme-tredu' ),
                'previous'        => _x( 'Previous', 'theme-frontend', 'tms-theme-tredu' ),
                'goto'            => _x( 'Go to slide', 'theme-frontend', 'tms-theme-tredu' ),
                'centered'        => _x( 'Centered', 'theme-frontend', 'tms-theme-tredu' ),
                'slide'           => _x( 'Slide', 'theme-frontend', 'tms-theme-tredu' ),
                'image_carousel'  => _x( 'Image carousel', 'theme-frontend', 'tms-theme-tredu' ),
                'modal_carousel'  => _x( 'Modal image carousel', 'theme-frontend', 'tms-theme-tredu' ),
                'browsing_images' => _x( 'Browsing images', 'theme-frontend', 'tms-theme-tredu' ),
                'main_carousel'   => _x( 'Main image carousel', 'theme-frontend', 'tms-theme-tredu' ),
                'expand'          => _x( 'Expand', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'footer'             => [
                'to_main_site' => _x( 'Move to tampere.fi', 'theme-frontend', 'tms-theme-tredu' ),
                'back_to_top'  => _x( 'Back to top', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'common'             => [
                'target_blank'  => _x( 'Opens in a new window', 'theme-frontend', 'tms-theme-tredu' ),
                'external_link' => _x( 'The link takes you to an external website', 'theme-frontend', 'tms-theme-tredu' ),
                'all'           => _x( 'All', 'theme-frontend', 'tms-theme-tredu' ),
                'read_more'     => _x( 'Read more', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'single'             => [
                'image_credits'   => _x( 'Image:', 'theme-frontend', 'tms-theme-tredu' ),
                'writing_credits' => _x( 'Text:', 'theme-frontend', 'tms-theme-tredu' ),
                'article_type'    => _x( 'Articletype:', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'home'               => [
                'month'              => _x( 'Month', 'theme-frontend', 'tms-theme-tredu' ),
                'year'               => _x( 'Year', 'theme-frontend', 'tms-theme-tredu' ),
                'no_results'         => _x( 'No results', 'theme-frontend', 'tms-theme-tredu' ),
                'filter_by_category' => _x( 'Filter by Category', 'theme-frontend', 'tms-theme-tredu' ),
                'description'        => _x( 'The page reloads after the selection.', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'months'             => [
                'january'   => _x( 'January', 'theme-frontend', 'tms-theme-tredu' ),
                'february'  => _x( 'February', 'theme-frontend', 'tms-theme-tredu' ),
                'march'     => _x( 'March', 'theme-frontend', 'tms-theme-tredu' ),
                'april'     => _x( 'April', 'theme-frontend', 'tms-theme-tredu' ),
                'may'       => _x( 'May', 'theme-frontend', 'tms-theme-tredu' ),
                'june'      => _x( 'June', 'theme-frontend', 'tms-theme-tredu' ),
                'july'      => _x( 'July', 'theme-frontend', 'tms-theme-tredu' ),
                'august'    => _x( 'August', 'theme-frontend', 'tms-theme-tredu' ),
                'september' => _x( 'September', 'theme-frontend', 'tms-theme-tredu' ),
                'october'   => _x( 'October', 'theme-frontend', 'tms-theme-tredu' ),
                'november'  => _x( 'November', 'theme-frontend', 'tms-theme-tredu' ),
                'december'  => _x( 'December', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'password_protected' => [
                'input_label' => _x( 'Password:', 'theme-frontend', 'tms-theme-tredu' ),
                'button_text' => _x( 'Enter', 'theme-frontend', 'tms-theme-tredu' ),
                'message'     => _x(
                    'This content is password protected. To view it please enter your password below:',
                    'theme-frontend',
                    'tms-theme-tredu'
                ),
            ],
            'sibling_navigation' => [
                'sibling_navigation'         => _x( 'Sibling pages', 'theme-frontend', 'tms-theme-tredu' ),
                'sibling_navigation_heading' => _x( 'You might also be interested in', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'comments'           => [
                'comments_title' => _x( 'Comments', 'theme-frontend', 'tms-theme-tredu' ),
                'close_notice'   => _x( 'Close', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'blog_article'       => [
                'toggle_details'    => _x( 'Show description', 'theme-frontend', 'tms-theme-tredu' ),
                'archive_link_text' => _x( 'All articles', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'event'              => [
                'date'     => _x( 'Event date', 'theme-frontend', 'tms-theme-tredu' ),
                'time'     => _x( 'Event time', 'theme-frontend', 'tms-theme-tredu' ),
                'location' => _x( 'Event location', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'sitemap'            => [
                'open'  => _x( 'Open', 'theme-frontend', 'tms-theme-tredu' ),
                'close' => _x( 'Close', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'contact_search'     => [
                'label'             => _x( 'Search contacts', 'theme-frontend', 'tms-theme-tredu' ),
                'input_placeholder' => _x( 'Search', 'theme-frontend', 'tms-theme-tredu' ),
                'submit_value'      => _x( 'Search', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'artist'             => [
                'open'            => _x( 'Open', 'theme-frontend', 'tms-theme-tredu' ),
                'close'           => _x( 'Close', 'theme-frontend', 'tms-theme-tredu' ),
                'related_artwork' => _x( 'Related artwork', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'artwork'            => [
                'artist_link'     => _x( 'Show artist', 'theme-frontend', 'tms-theme-tredu' ),
                'related_art'     => _x( 'Artwork by the same artist', 'theme-frontend', 'tms-theme-tredu' ),
                'related_artwork' => _x( 'Related artwork', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'program' => [
                'search'                  => [
                    'search_studies'                     => _x( 'Search studies', 'theme-frontend', 'tms-theme-tredu' ),
                    'results_shown'                      => _x( 'Results shown', 'theme-frontend', 'tms-theme-tredu' ),
                    'commit_new_search'                  => _x( 'Commit new search', 'theme-frontend', 'tms-theme-tredu' ),
                    'clear_search_terms'                 => _x( 'Clear the search terms', 'theme-frontend', 'tms-theme-tredu' ),
                    'show_only_ongoing'                  => _x( 'Show only programs that can be applied now', 'theme-frontend', 'tms-theme-tredu' ),
                    'select'                             => _x( 'Select', 'theme-frontend', 'tms-theme-tredu' ),
                    'results'                            => _x( 'Results', 'theme-frontend', 'tms-theme-tredu' ),
                    'result'                             => _x( 'Result', 'theme-frontend', 'tms-theme-tredu' ),
                    'remove_filter'                      => _x( 'Remove filter', 'theme-frontend', 'tms-theme-tredu' ),
                    'key_to_navigate'                    => _x( 'Use arrow key to navigate', 'theme-frontend', 'tms-theme-tredu' ),
                    'add_filter'                         => _x( 'Add filter', 'theme-frontend', 'tms-theme-tredu' ),
                    'commits_new_search'                 => _x( 'Selecting filter commits new search', 'theme-frontend', 'tms-theme-tredu' ),
                    'write_term_profession_program_name' => _x( 'Write search term, profession or program name', 'theme-frontend', 'tms-theme-tredu' ),
                    'sr_autocomplete_instructions'       => _x( 'Write three or more letter to get results. When results are available, use up and down arrows to inspect and select. For touchscreen users, inspect by touching or swiping.', 'theme-frontend', 'tms-theme-tredu' ),
                    'more_search_filters'                => _x( 'More search filters', 'theme-frontend', 'tms-theme-tredu' ),
                    'choose_apply_method'                => _x( 'Choose apply method', 'theme-frontend', 'tms-theme-tredu' ),
                ],
                'profession'              => _x( 'Profession', 'theme-frontend', 'tms-theme-tredu' ),
                'program-type'            => _x( 'Program type', 'theme-frontend', 'tms-theme-tredu' ),
                'location'                => _x( 'Location', 'theme-frontend', 'tms-theme-tredu' ),
                'educational-background'  => _x( 'Educational background', 'theme-frontend', 'tms-theme-tredu' ),
                'delivery-method'         => _x( 'Delivery method', 'theme-frontend', 'tms-theme-tredu' ),
                'application-period-ends' => _x( 'Application period ends', 'theme-frontend', 'tms-theme-tredu' ),
                'start-date'              => _x( 'Starts', 'theme-frontend', 'tms-theme-tredu' ),
                'print'                   => _x( 'Print page', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'program_type' => [
                'scope'         => _x( 'Scope', 'theme-frontend', 'tms-theme-tredu' ),
                'who_can_apply' => _x( 'Who can apply?', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'program_favorite' => [
                'favorites_title'              => _x( 'Favorites', 'theme-frontend', 'tms-theme-tredu' ),
                'no_favorites_text'            => _x( 'No favorites found', 'theme-frontend', 'tms-theme-tredu' ),
                'open_or_close_favorites_text' => _x( 'Open or close favorites', 'theme-frontend', 'tms-theme-tredu' ),
                'close_favorites_text'         => _x( 'Close favorites', 'theme-frontend', 'tms-theme-tredu' ),
                'add_to_favorites_aria_label'  => _x( 'Add or remove from favorites', 'theme-frontend', 'tms-theme-tredu' ),
                'add_to_favorites_text'        => _x( 'Add to favorites', 'theme-frontend', 'tms-theme-tredu' ),
                'remove_from_favorites_text'   => _x( 'Remove from favorites', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'search'             => [
                'filter_by_post_type' => _x( 'Filter by type', 'theme-frontend', 'tms-theme-tredu' ),
                'filter_by_date'      => _x( 'Publish date', 'theme-frontend', 'tms-theme-tredu' ),
                'write_search_terms'  => _x( 'Write search terms', 'theme-frontend', 'tms-theme-tredu' ),
                'breadcrumbs'         => _x( 'Page location:', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'page_materials'     => [
                'filter'        => _x( 'Filter', 'theme-frontend', 'tms-theme-tredu' ),
                'title_sr_text' => _x( 'Clicking the link will download file', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            // Use the Duet Date Picker keys for strings
            'datepicker' => [
                'buttonLabel'         => _x( 'Pick a date', 'theme-frontend', 'tms-theme-tredu' ),
                'placeholder'         => _x( 'dd.mm.yyyy', 'theme-frontend', 'tms-theme-tredu' ),
                'selectedDateMessage' => _x( 'The chosen date is', 'theme-frontend', 'tms-theme-tredu' ),
                'prevMonthLabel'      => _x( 'Previous month', 'theme-frontend', 'tms-theme-tredu' ),
                'nextMonthLabel'      => _x( 'Next month', 'theme-frontend', 'tms-theme-tredu' ),
                'monthSelectLabel'    => _x( 'Month', 'theme-frontend', 'tms-theme-tredu' ),
                'yearSelectLabel'     => _x( 'Year', 'theme-frontend', 'tms-theme-tredu' ),
                'closeLabel'          => _x( 'Close window', 'theme-frontend', 'tms-theme-tredu' ),
                'calendarHeading'     => _x( 'Pick a date', 'theme-frontend', 'tms-theme-tredu' ),
                'dayNames'            => [
                    _x( 'Sunday', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Monday', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Tuesday', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Wednesday', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Thursday', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Friday', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Saturday', 'theme-frontend', 'tms-theme-tredu' ),
                ],
                'monthNames'          => [
                    _x( 'January', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'February', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'March', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'April', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'May', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'June', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'July', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'August', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'September', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'October', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'November', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'December', 'theme-frontend', 'tms-theme-tredu' ),
                ],
                'monthNamesShort'     => [
                    _x( 'Jan', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Feb', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Mar', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Apr', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'May', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Jun', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Jul', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Aug', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Sept', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Oct', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Nov', 'theme-frontend', 'tms-theme-tredu' ),
                    _x( 'Dec', 'theme-frontend', 'tms-theme-tredu' ),
                ],
            ],
            'modaal' => [
                'accessible_title' => _x( 'Enlarged image', 'theme-frontend', 'tms-theme-tredu' ),
                'close'            => _x( 'Close', 'theme-frontend', 'tms-theme-tredu' ),
            ],
            'load_more' => [
                'load_more_text'          => _x( 'Load more', 'theme-frontend', 'tms-theme-tredu' ),
                'load_more_programs_text' => _x( 'Load more programs', 'theme-frontend', 'tms-theme-tredu' ),
                'load_more_loaded'        => _x( 'More results loaded', 'theme-frontend', 'tms-theme-tredu' ),
            ],
        ];
    }

    /**
     * Add translations to pagination
     *
     * @param object $data Pagination data.
     *
     * @return object
     */
    public function add_pagination_translations( $data ) {
        $data->S->aria_label = __( 'Pagination', 'tms-theme-tredu' ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

        return $data;
    }
}
