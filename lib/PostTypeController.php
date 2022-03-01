<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu;

use TMS\Theme\Tredu\Interfaces\PostType;

/**
 * Class PostTypeController
 *
 * @package TMS\Theme\Tredu
 */
class PostTypeController implements Interfaces\Controller {

    /**
     * The post type class instances
     *
     * @var PostType[]
     */
    protected array $classes = [];

    /**
     * Get a single class instance from Theme Controller
     *
     * @param string|null $class Class name to get.
     *
     * @return PostType|null
     */
    public function get_class( ?string $class ) : ?Interfaces\PostType {
        return $this->classes[ $class ] ?? null;
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_cpts' ] )
        );

        add_filter(
            'wp_insert_post_data',
            \Closure::fromCallable( [ $this, 'set_menu_order_default' ] )
        );
    }

    /**
     * This registers all custom post types.
     *
     * @return void
     */
    protected function register_cpts() : void {
        $instances = $this->get_post_type_instances();

        if ( empty( $instances ) ) {
            return;
        }

        foreach ( $instances as $instance ) {
            if ( $instance instanceof Interfaces\PostType ) {
                $instance->hooks();

                $this->classes[ $instance::SLUG ] = $instance;
            }
        }
    }

    /**
     * Get namespace for CPT instances
     *
     * @return string
     */
    protected function get_namespace() : string {
        return __NAMESPACE__;
    }

    /**
     * Get custom post type files
     *
     * @return array
     */
    protected function get_post_type_files() : array {
        return array_diff( scandir( __DIR__ . '/PostType' ), [ '.', '..' ] );
    }

    /**
     * Get custom post type instances
     *
     * @return array
     */
    protected function get_post_type_instances() : array {
        return array_map( function ( $field_class ) {
            $field_class = treduname( $field_class, '.' . pathinfo( $field_class )['extension'] );
            $class_name  = $this->get_namespace() . '\PostType\\' . $field_class;

            if ( ! \class_exists( $class_name ) ) {
                return null;
            }

            return new $class_name();
        }, $this->get_post_type_files() );
    }

    /**
     * Set default value for menu_order
     *
     * @param array $data An array of slashed, sanitized, and processed post data.
     *
     * @return array
     */
    protected function set_menu_order_default( $data ) {
        if ( 0 === intval( $data['menu_order'] ) ) {
            $data['menu_order'] = apply_filters( 'tms/theme/default_menu_order', 100 );
        }

        return $data;
    }
}
