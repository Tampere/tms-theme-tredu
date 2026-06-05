<?php

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\Taxonomy\ProgramType;

/**
 * Class TaxonomyProgramType
 *
 * @package TMS\Theme\Tredu\ACF
 */
class TaxonomyProgramType {

    /**
     * TaxonomyProgramType constructor.
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
            $field_group = ( new Group( 'Koulutustyypin lisäasetukset' ) )
                ->set_key( 'fg_program_type_fields' );

            $rule_group = ( new RuleGroup() )
            ->add_rule( 'taxonomy', '==', ProgramType::SLUG );

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
            'tab'              => 'Lisäasetukset',
            'hero_image'       => [
                'title'        => 'Hero-kuva',
                'instructions' => '',
            ],
            'long_description' => [
                'title'        => 'Pitkä kuvaus',
                'instructions' => '',
            ],
            'scope'            => [
                'title'        => 'Laajuus',
                'instructions' => '',
            ],
            'who_can_apply'    => [
                'title'        => 'Kuka voi hakea',
                'instructions' => '',
            ],
            'show_cta'         => [
                'title'        => 'Näytä toimintakehote',
                'instructions' => 'Valitse näytetäänkö koulutushaun toimintakehote',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $hero_image_field = ( new Field\Image( $strings['hero_image']['title'] ) )
            ->set_key( "{$key}_hero_image" )
            ->set_name( 'hero_image' )
            ->set_return_format( 'id' )
            ->set_instructions( $strings['hero_image']['instructions'] );

        $long_description_field = ( new Field\Wysiwyg( $strings['long_description']['title'] ) )
            ->set_key( "{$key}_long_description" )
            ->set_name( 'long_description' )
            ->set_tabs( 'visual' )
            ->set_toolbar( [ 'formatselect', 'bold', 'italic', 'link' ] )
            ->disable_media_upload()
            ->set_instructions( $strings['long_description']['instructions'] );

        $scope_field = ( new Field\Text( $strings['scope']['title'] ) )
            ->set_key( "{$key}_scope" )
            ->set_name( 'scope' )
            ->set_instructions( $strings['scope']['instructions'] );

        $who_can_apply_field = ( new Field\Text( $strings['who_can_apply']['title'] ) )
            ->set_key( "{$key}_who_can_apply" )
            ->set_name( 'who_can_apply' )
            ->set_instructions( $strings['who_can_apply']['instructions'] );

        $show_cta_field = ( new Field\TrueFalse( $strings['show_cta']['title'] ) )
            ->set_key( "{$key}_show_cta" )
            ->set_name( 'show_cta' )
            ->use_ui()
            ->set_instructions( $strings['show_cta']['instructions'] );

        $tab->add_fields( [
            $hero_image_field,
            $long_description_field,
            $scope_field,
            $who_can_apply_field,
            $show_cta_field,
        ] );

        return $tab;
    }
}

( new TaxonomyProgramType() );
