<?php

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;

/**
 * Class PageDialTreduGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class PageDialTreduGroup {

    /**
     * PageGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $field_group = ( new Group( 'Sisältökentät' ) )
                ->set_key( 'fg_page_dial_tredu_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'page_template', '==', \PageDialTredu::TEMPLATE );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_general_tab( $field_group->get_key() ),
                    ]
                )
            );

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
     * Get general tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_general_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'     => 'Yleiset tiedot',
            'ingress' => [
                'title'        => 'Ingressi',
                'instructions' => 'Maks. 600 merkkiä',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $ingress_field = ( new Field\Textarea( $strings['ingress']['title'] ) )
            ->set_key( "{$key}_ingress" )
            ->set_name( 'ingress' )
            ->set_maxlength( 600 )
            ->set_instructions( $strings['ingress']['instructions'] );

        $tab->add_fields( [
            $ingress_field,
        ] );

        return $tab;
    }
}

( new PageDialTreduGroup() );
