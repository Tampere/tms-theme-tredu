<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;

/**
 * Class PageProgramGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class PageProgramGroup {

    /**
     * PageProgramGroup constructor.
     *
     * @return void
     */
    public function __construct() {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields
     *
     * @return void
     */
    protected function register_fields() : void {
        try {
            $field_group = ( new Group( 'Kuvaus' ) )
                ->set_key( 'fg_page_program_fields' );

            $page_program_rule_group = ( new RuleGroup() )
                ->add_rule( 'page_template', '==', \PageProgram::TEMPLATE );

            $field_group
                ->add_rule_group( $page_program_rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields( $this->get_page_program_fields( $field_group->get_key() ) );

            $field_group = apply_filters(
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
     * This returns fields for the group.
     *
     * @param string $key Field key.
     *
     * @return array
     */
    protected function get_page_program_fields( $key ) : array {

        $strings = [
            'page_program_fields' => [
                'label'        => 'Kuvausteksti',
                'instructions' => '',
            ],
        ];

        $page_program_fields = ( new Field\Wysiwyg( $strings['page_program_fields']['label'] ) )
            ->set_key( "{$key}_page_program_description" )
            ->set_name( 'page_program_description' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['page_program_fields']['instructions'] );

        return [
            $page_program_fields,
        ];
    }
}

( new PageProgramGroup() );

