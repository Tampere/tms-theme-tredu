<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu;

use TMS\Theme\Tredu\Taxonomy\ApplyMethod;
use TMS\Theme\Tredu\Taxonomy\BlogCategory;
use TMS\Theme\Tredu\Taxonomy\BlogTag;
use TMS\Theme\Tredu\Taxonomy\DeliveryMethod;
use TMS\Theme\Tredu\Taxonomy\Location;
use TMS\Theme\Tredu\Taxonomy\Profession;

/**
 * Class Localization
 *
 * @package TMS\Theme\Tredu
 */
class Localization implements Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        $this->load_theme_textdomains();

        \add_filter(
            'pll_get_post_types',
            \Closure::fromCallable( [ $this, 'add_cpts_to_polylang' ] ),
            10,
            2
        );

        \add_filter(
            'pll_get_taxonomies',
            \Closure::fromCallable( [ $this, 'add_tax_to_polylang' ] ),
            10,
            2
        );
    }

    /**
     * Load theme translations.
     */
    public function load_theme_textdomains() {
        \load_theme_textdomain(
            'tms-theme-tredu',
            get_template_directory() . '/lang'
        );
    }

    /**
     * This returns the current language either by using
     * PLL or WP's locale.
     *
     * @return string|bool The language slug or false if used in
     *                     admin and 'all languages' are chosen from PLL top bar filter.
     */
    public static function get_current_language() {
        if ( DPT_PLL_ACTIVE && function_exists( 'pll_current_language' ) ) {
            return \pll_current_language() ?? get_locale();
        }

        return get_locale();
    }

    /**
     * Get data for the language navigation.
     *
     * @return array|null|void Returns an array with two items; 'current' and 'others'. Current is the current,
     *                          language, and others is an array of the other available languages.
     *                          Returns null if Polylang not active. Returns void if no language data found.
     */
    public static function get_language_nav() {
        if ( ! DPT_PLL_ACTIVE || ! function_exists( 'pll_the_languages' ) ) {
            return null;
        }

        $args      = [ 'raw' => 1 ];
        $languages = \pll_the_languages( $args );
        $lang_data = [];

        foreach ( $languages as $lang ) {
            if ( ! empty( $lang['current_lang'] ) ) {
                $lang_data['current'] = $lang;
            }
            else {
                $lang_data['others'][] = $lang;
            }
        }

        if ( ! empty( $lang_data ) ) {
            return $lang_data;
        }
    }

    /**
     * This adds the CPTs that are not public to Polylang translation.
     *
     * @param array   $post_types  The post type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified post_types -array.
     */
    protected function add_cpts_to_polylang( $post_types, $is_settings ) { // phpcs:ignore
        if ( ! DPT_PLL_ACTIVE ) {
            return $post_types;
        }

        $post_types[ PostType\Settings::SLUG ]    = PostType\Settings::SLUG;
        $post_types[ PostType\Contact::SLUG ]     = PostType\Contact::SLUG;
        $post_types[ PostType\BlogArticle::SLUG ] = PostType\BlogArticle::SLUG;
        $post_types[ PostType\BlogAuthor::SLUG ]  = PostType\BlogAuthor::SLUG;
        $post_types[ PostType\Program::SLUG ]     = PostType\Program::SLUG;

        return $post_types;
    }

    /**
     * This adds the taxonomies that are not public to Polylang translation.
     *
     * @param array   $tax_types   The taxonomy type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified tax_types -array.
     */
    protected function add_tax_to_polylang( $tax_types, $is_settings ) : array { // phpcs:ignore
        $tax_types[ BlogCategory::SLUG ]   = BlogCategory::SLUG;
        $tax_types[ BlogTag::SLUG ]        = BlogTag::SLUG;
        $tax_types[ Location::SLUG ]       = Location::SLUG;
        $tax_types[ Profession::SLUG ]     = Profession::SLUG;
        $tax_types[ DeliveryMethod::SLUG ] = DeliveryMethod::SLUG;
        $tax_types[ ApplyMethod::SLUG ]    = ApplyMethod::SLUG;

        return $tax_types;
    }
}
