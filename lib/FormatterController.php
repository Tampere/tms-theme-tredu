<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu;

use TMS\Theme\Tredu\Interfaces\Formatter;

/**
 * Class FormatterController
 *
 * @package TMS\Theme\Tredu
 */
class FormatterController implements Interfaces\Controller {

    /**
     * The post type class instances
     *
     * @var Formatter[]
     */
    private array $classes = [];

    /**
     * Get a single class instance from Theme Controller
     *
     * @param string|null $class Class name to get.
     *
     * @return Formatter|null
     */
    public function get_class( ?string $class ) : ?Interfaces\Formatter {
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
            \Closure::fromCallable( [ $this, 'register_formatters' ] )
        );
    }

    /**
     * Get namespace for formatter instances
     *
     * @return string
     */
    protected function get_namespace() : string {
        return __NAMESPACE__;
    }

    /**
     * Get formatter files
     *
     * @return array
     */
    protected function get_formatter_files() : array {
        return array_diff( scandir( __DIR__ . '/Formatters' ), [ '.', '..' ] );
    }

    /**
     * This registers all custom post types.
     *
     * @return void
     */
    protected function register_formatters() : void {
        $classes = array_map( function ( $field_class ) {
            $field_class = treduname( $field_class, '.' . pathinfo( $field_class )['extension'] );
            $class_name  = $this->get_namespace() . '\Formatters\\' . $field_class;

            if ( ! \class_exists( $class_name ) ) {
                return null;
            }

            return $class_name;
        }, $this->get_formatter_files() ?? [] );

        $classes = apply_filters( 'tms/theme/tredu/formatters', $classes );

        if ( empty( $classes ) ) {
            return;
        }

        foreach ( $classes as $class ) {
            if ( empty( $class ) ) {
                continue;
            }

            $instance = new $class();

            if ( $instance instanceof Interfaces\Formatter ) {
                $instance->hooks();

                $this->classes[ $instance::NAME ] = $instance;
            }
        }
    }
}
