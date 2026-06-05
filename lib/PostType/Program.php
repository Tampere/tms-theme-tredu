<?php

namespace TMS\Theme\Tredu\PostType;

use Strings;
use \TMS\Theme\Tredu\Interfaces\PostType;
use TMS\Theme\Tredu\Images;
use TMS\Theme\Tredu\Taxonomy\ApplyMethod;

/**
 * Program CPT
 *
 * @package TMS\Theme\Tredu\PostType
 */
class Program implements PostType {

    /**
     * This defines the slug of this post type.
     */
    public const SLUG = 'program';

    /**
     * This defines what is shown in the url. This can
     * be different than the slug which is used to register the post type.
     *
     * @var string
     */
    private $url_slug = 'program';

    /**
     * Define the CPT description
     *
     * @var string
     */
    private $description = '';

    /**
     * This is used to position the post type menu in admin.
     *
     * @var int
     */
    private $menu_order = 40;

    /**
     * This defines the CPT icon.
     *
     * @var string
     */
    private $icon = 'dashicons-heart';

    /**
     * Constructor
     */
    public function __construct() {
        $this->description = \_x( 'program', 'theme CPT', 'tms-theme-tredu' );
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        \add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * Get post type slug
     *
     * @return string
     */
    public function get_post_type() : string {
        return static::SLUG;
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => 'Koulutukset',
            'singular_name'         => 'Koulutus',
            'menu_name'             => 'Koulutukset',
            'name_admin_bar'        => 'Koulutukset',
            'archives'              => 'Arkistot',
            'attributes'            => 'Ominaisuudet',
            'parent_item_colon'     => 'Vanhempi:',
            'all_items'             => 'Kaikki',
            'add_new_item'          => 'Lisää uusi',
            'add_new'               => 'Lisää uusi',
            'new_item'              => 'Uusi',
            'edit_item'             => 'Muokkaa',
            'update_item'           => 'Päivitä',
            'view_item'             => 'Näytä',
            'view_items'            => 'Näytä kaikki',
            'search_items'          => 'Etsi',
            'not_found'             => 'Ei löytynyt',
            'not_found_in_trash'    => 'Ei löytynyt roskakorista',
            'featured_image'        => 'Kuva',
            'set_featured_image'    => 'Aseta kuva',
            'remove_featured_image' => 'Poista kuva',
            'use_featured_image'    => 'Käytä kuvana',
            'insert_into_item'      => 'Aseta julkaisuun',
            'uploaded_to_this_item' => 'Lisätty tähän julkaisuun',
            'items_list'            => 'Listaus',
            'items_list_navigation' => 'Listauksen navigaatio',
            'filter_items_list'     => 'Suodata listaa',
        ];

        $rewrite = [
            'slug'       => $this->url_slug,
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        ];

        $args = [
            'label'               => $labels['name'],
            'description'         => '',
            'labels'              => $labels,
            'supports'            => [ 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ],
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => $this->menu_order,
            'menu_icon'           => $this->icon,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'program',
            'map_meta_cap'        => true,
            'show_in_rest'        => true,
        ];

        \register_post_type( static::SLUG, $args );
    }

    /**
     * Format posts for view
     *
     * @param array $posts      Array of WP_Post instances.
     * @param array $taxonomies Array of related taxonomies.
     *
     * @return array
     */
    public static function format_posts( array $posts, array $taxonomies ) : array {

        return array_map( function ( $item ) use ( $taxonomies ) {
            if ( \has_post_thumbnail( $item->ID ) ) {
                $item->image = \get_post_thumbnail_id( $item->ID );
            }
            else {
                $item->image = Images::get_default_image_id();
            }

            $item->permalink = \get_the_permalink( $item->ID );
            $item->fields    = \get_fields( $item->ID );

            if ( ! empty( $item->fields ) ) {

                if ( ! empty( $item->fields['start_info'] ) ) {
                    $item->fields['start_date'] = $item->fields['start_info'];
                }

                if ( ! empty( $item->fields['apply_info'] ) ) {
                    $item->fields['apply_end'] = $item->fields['apply_info'];
                }
                elseif ( ! empty( $item->fields['apply_end'] ) ) {
                    $item->fields['apply_end'] = ( new Strings() )->s()['program']['application-period-ends'] . ' ' . date( 'd.m.Y', strtotime( $item->fields['apply_end'] ) ); // phpcs:ignore
                }
            }

            foreach ( $taxonomies as $tax_slug ) {

                $primary_term_id = \get_post_meta( $item->ID, '_primary_term_' . $tax_slug, true );
                $terms           = \wp_get_post_terms( $item->ID, $tax_slug );

                $term_id = 0;
                if ( ! empty( $primary_term_id ) ) {
                    $primary_term      = \get_term( $primary_term_id );
                    $item->{$tax_slug} = $primary_term->name;
                    $term_id           = $primary_term_id;
                }
                else {
                    if ( ! empty( $terms ) ) {
                        $item->{$tax_slug} = $terms[0]->name;
                        $term_id           = $terms[0]->term_id;
                    }
                }

                if ( $tax_slug === ApplyMethod::SLUG ) {
                    foreach ( $terms as $key => $term ) {
                        $apply_method_color = \get_term_meta( $term->term_id, 'color', true ) ?? '';

                        // Replace specific colors to new ones.
                        if ( $apply_method_color === 'green' ) {
                            $apply_method_color = 'spruce-green';
                        }
                        elseif ( $apply_method_color === 'red' ) {
                            $apply_method_color = 'fuchsia';
                        }
                        elseif ( $apply_method_color === 'blue' ) {
                            $apply_method_color = 'secondary';
                        }

                        $item->fields['apply_method'][ $key ]['apply_method_color']     = $apply_method_color;
                        $item->fields['apply_method'][ $key ]['apply_method_txt_color'] = in_array( $apply_method_color, [ 'fuchsia', 'spruce-green', 'primary' ], true ) ? 'white' : 'black';
                        $item->fields['apply_method'][ $key ]['apply_method_name']      = $term->name;
                    }
                }
            }

            return $item;
        }, $posts );
    }
}
