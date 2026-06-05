<?php

namespace TMS\Theme\Tredu\Taxonomy;

use \TMS\Theme\Tredu\Interfaces\Taxonomy;
use TMS\Theme\Tredu\PostType\Program;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Tredu\Taxonomy
 */
class ApplyMethod implements Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'apply-method';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => 'Hakutavat',
            'singular_name'         => 'Hakutapa',
            'menu_name'             => 'Hakutavat',
            'all_items'             => 'Kaikki hakutavat',
            'new_item_name'         => 'Lisää uusi hakutapa',
            'add_new_item'          => 'Lisää uusi hakutapa',
            'edit_item'             => 'Muokkaa hakutapaa',
            'update_item'           => 'Päivitä hakutapa',
            'view_item'             => 'Näytä hakutapa',
            'search_items'          => 'Etsi hakutapaa',
            'not_found'             => 'Ei tuloksia',
            'no_terms'              => 'Ei tuloksia',
            'items_list'            => 'Hakutavat',
            'items_list_navigation' => 'Hakutavat',
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
        ];

        register_taxonomy( self::SLUG, [ Program::SLUG ], $args );
    }
}
