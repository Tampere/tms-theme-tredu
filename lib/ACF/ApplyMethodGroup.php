<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;
use TMS\Theme\Tredu\Taxonomy\ApplyMethod;

/**
 * Class CommentGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class ApplyMethodGroup {

    /**
     * CommentGroup constructor.
     */
    public function __construct() {
        \add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields.
     */
    protected function register_fields() : void {
        try {
            $field_group = ( new Group( 'Hakutapa' ) )
                ->set_key( 'fg_apply-method_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'taxonomy', '==', ApplyMethod::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                \apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_color_field( $field_group->get_key() ),
                    ]
                )
            );

            $field_group = \apply_filters(
                'tms/acf/group/' . $field_group->get_key(),
                $field_group
            );

            $field_group->register();
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }
    }

    /**
     * Get color field.
     *
     * @param string $key Field group key.
     *
     * @return Field\Select
     * @throws Exception In case of invalid option.
     */
    protected function get_color_field( string $key ) : Field\Select {

        $strings = [
            'color' => [
                'label'        => 'Värivalinta',
                'instructions' => '',
            ],
        ];

        $color_field = ( new Field\Select( $strings['color']['label'] ) )
            ->set_key( "{$key}_color" )
            ->set_name( 'color' )
            ->set_choices( [
                'primary' => 'Tumman sininen',
                'blue'    => 'Sininen',
                'green'   => 'Havunvihreä',
                'red'     => 'Fuksia',
            ] )
            ->set_default_value( 'primary' )
            ->set_wrapper_width( 30 )
            ->set_instructions( $strings['color']['instructions'] );

        return $color_field;
    }
}

( new ApplyMethodGroup() );
