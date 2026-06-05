<?php

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use PageDialTredu;
use PageProgram;
use PageProject;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;

/**
 * Class PageHeroGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class PageHeroGroup {

    /**
     * PageHeroGroup constructor.
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
     * Register fields.
     *
     * @return void
     */
    protected function register_fields() : void {
        try {
            $field_group = ( new Group( 'Herokuvan asetukset' ) )
                ->set_key( 'fg_page_hero_fields' );

            $page_hero_rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\Page::SLUG )
                ->add_rule( 'page_template', '!=', \PageFrontPage::TEMPLATE )
                ->add_rule( 'page_template', '!=', PageProgram::TEMPLATE )
                ->add_rule( 'page_template', '!=', PageProject::TEMPLATE )
                ->add_rule( 'page_template', '!=', PageDialTredu::TEMPLATE );

            $field_group
                ->add_rule_group( $page_hero_rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields( $this->get_page_hero_fields( $field_group->get_key() ) );

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
     * @throws \Geniem\ACF\Exception In case of invalid ACF option.
     */
    protected function get_page_hero_fields( $key ) : array {

        $strings = [
            'page_hero_overlay' => [
                'label'        => 'Heron tummennus',
                'instructions' => 'Jos kuvalla on tummennus, sivun otsikko on heron päällä. Muutoin otsikko näytetään heron alla.
                ',
                'off'          => 'Pois',
                'on'           => 'Päällä',
            ],
        ];

        $page_hero_overlay_field = ( new Field\TrueFalse( $strings['page_hero_overlay']['label'] ) )
            ->set_key( "{$key}_page_hero_overlay" )
            ->set_name( 'page_hero_overlay' )
            ->set_default_value( true )
            ->set_wrapper_width( 50 )
            ->use_ui()
            ->set_ui_off_text( $strings['page_hero_overlay']['off'] )
            ->set_ui_on_text( $strings['page_hero_overlay']['on'] )
            ->set_instructions( $strings['page_hero_overlay']['instructions'] );

        return [
            $page_hero_overlay_field,
        ];
    }
}

( new PageHeroGroup() );

