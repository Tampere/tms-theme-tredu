<?php

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;
use TMS\Theme\Tredu\Taxonomy\Category;

/**
 * Class ProgramGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class ProgramGroup {

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
            $field_group = ( new Group( 'Koulutuksen tiedot' ) )
                ->set_key( 'fg_program_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\Program::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                \apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_general_tab( $field_group->get_key() ),
                        $this->get_info_tab( $field_group->get_key() ),
                        $this->get_components_tab( $field_group->get_key() ),
                        $this->get_stories_tab( $field_group->get_key() ),
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
     * Get general tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_general_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'                => 'Yleiset tiedot',
            'program_name'       => [
                'title'        => 'Tutkinnon nimi',
                'instructions' => 'esim. Sosiaali- ja terveysalan perustutkinto',
            ],
            'ingress'            => [
                'title'        => 'Ingressi',
                'instructions' => '',
            ],
            'search_keywords'    => [
                'title'        => 'Haun apusanat',
                'instructions' => 'Lisää tähän hakusanoja, joilla tämän koulutuksen halutaan löytyvän haussa',
            ],
            'search_box_title'   => [
                'title'        => 'Hakulaatikon otsikko',
                'instructions' => '',
            ],
            'hide_apply_info'    => [
                'title'        => 'Piilota hakulaatikko',
                'instructions' => '',
            ],
            'search_box_ingress' => [
                'title'        => 'Hakulaatikon lyhyt ote',
                'instructions' => '',
            ],
            'search_box_link'    => [
                'title'        => 'Hakulaatikon nappi',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $program_name_field = ( new Field\Text( $strings['program_name']['title'] ) )
            ->set_key( "{$key}_program_name" )
            ->set_name( 'program_name' )
            ->redipress_include_search()
            ->set_instructions( $strings['program_name']['instructions'] );

        $ingress_field = ( new Field\Textarea( $strings['ingress']['title'] ) )
            ->set_key( "{$key}_ingress" )
            ->set_name( 'ingress' )
            ->redipress_include_search()
            ->set_instructions( $strings['ingress']['instructions'] );

        $search_keywords_field = ( new Field\Textarea( $strings['search_keywords']['title'] ) )
            ->set_key( "{$key}_search_keywords" )
            ->set_name( 'search_keywords' )
            ->redipress_include_search()
            ->set_instructions( $strings['search_keywords']['instructions'] );

        $hide_apply_info_field = ( new Field\TrueFalse( $strings['hide_apply_info']['title'] ) )
            ->set_key( "{$key}_hide_apply_info" )
            ->set_name( 'hide_apply_info' )
            ->use_ui()
            ->set_instructions( $strings['hide_apply_info']['instructions'] );

        $search_box_title = ( new Field\Text( $strings['search_box_title']['title'] ) )
            ->set_key( "{$key}_search_box_title" )
            ->set_name( 'search_box_title' )
            ->set_instructions( $strings['search_box_title']['instructions'] );

        $search_box_ingress = ( new Field\Textarea( $strings['search_box_ingress']['title'] ) )
            ->set_key( "{$key}_search_box_ingress" )
            ->set_name( 'search_box_ingress' )
            ->set_maxlength( 200 )
            ->set_instructions( $strings['search_box_ingress']['instructions'] );

        $search_box_link = ( new Field\Link( $strings['search_box_link']['title'] ) )
            ->set_key( "{$key}_search_box_link" )
            ->set_name( 'search_box_link' )
            ->set_wrapper_width( 40 )
            ->set_instructions( $strings['search_box_link']['instructions'] );

        $tab->add_fields( [
            $program_name_field,
            $ingress_field,
            $search_keywords_field,
            $hide_apply_info_field,
            $search_box_title,
            $search_box_ingress,
            $search_box_link,
        ] );

        return $tab;
    }

    /**
     * Get info tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_info_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'                    => 'Koulutuksen tiedot',
            'apply_start'            => [
                'title'        => 'Haun alkamispvm',
                'instructions' => '',
            ],
            'apply_end'              => [
                'title'        => 'Haun päättymispvm',
                'instructions' => '',
            ],
            'apply_info'             => [
                'title'        => 'Hakuaika',
                'instructions' => 'Korvaa päivämäärien näyttämisen, esim tulossa syksyllä',
            ],
            'audience'               => [
                'title'        => 'Kenelle koulutus on suunnattu',
                'instructions' => '',
            ],
            'show_audience'          => [
                'title'        => 'Kenelle koulutus on suunnattu',
                'off'          => 'Piilota',
                'on'           => 'Näytä',
                'instructions' => 'Näytetäänkö kenelle koulutus on suunnattu koulutuksen sivulla',
            ],
            'start_date'             => [
                'title'        => 'Koulutuksen alkamisajankohta',
                'instructions' => 'Päivämäärä',
            ],
            'start_info'             => [
                'title'        => 'Alkamisajankohdan kuvaus',
                'instructions' => 'Vapaa teksti, joka näytetään päivämäärän sijaan',
            ],
            'price'                  => [
                'title'        => 'Hinta',
                'instructions' => '',
            ],
            'additional_information' => [
                'title'        => 'Lisätiedot koulutuksesta',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $apply_start_field = ( new Field\DatePicker( $strings['apply_start']['title'] ) )
            ->set_key( "{$key}_apply_start" )
            ->set_name( 'apply_start' )
            ->set_return_format( 'Y-m-d' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['apply_start']['instructions'] )
            ->redipress_add_queryable();

        $apply_end_field = ( new Field\DatePicker( $strings['apply_end']['title'] ) )
            ->set_key( "{$key}_apply_end" )
            ->set_name( 'apply_end' )
            ->set_return_format( 'Y-m-d' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['apply_end']['instructions'] )
            ->redipress_add_queryable();

        $apply_info_field = ( new Field\Text( $strings['apply_info']['title'] ) )
            ->set_key( "{$key}_apply_info" )
            ->set_name( 'apply_info' )
            ->set_maxlength( 80 )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['apply_info']['instructions'] );

        $show_audience = ( new Field\TrueFalse( $strings['show_audience']['title'] ) )
            ->set_key( "{$key}_show_audience" )
            ->set_name( 'show_audience' )
            ->set_default_value( true )
            ->set_wrapper_width( 50 )
            ->use_ui()
            ->set_ui_off_text( $strings['show_audience']['off'] )
            ->set_ui_on_text( $strings['show_audience']['on'] )
            ->set_instructions( $strings['show_audience']['instructions'] );

        $start_date_field = ( new Field\DatePicker( $strings['start_date']['title'] ) )
            ->set_key( "{$key}_start_date" )
            ->set_name( 'start_date' )
            ->set_return_format( 'd.m.Y' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['start_date']['instructions'] );

        $start_info_field = ( new Field\Text( $strings['start_info']['title'] ) )
            ->set_key( "{$key}_start_info" )
            ->set_name( 'start_info' )
            ->set_wrapper_width( 50 )
            ->set_maxlength( 80 )
            ->set_instructions( $strings['start_info']['instructions'] );

        $price_field = ( new Field\Text( $strings['price']['title'] ) )
            ->set_key( "{$key}_price" )
            ->set_name( 'price' )
            ->set_wrapper_width( 50 )
            ->set_maxlength( 160 )
            ->set_instructions( $strings['price']['instructions'] );

        $additional_information_field = ( new Field\Text( $strings['additional_information']['title'] ) )
            ->set_key( "{$key}_additional_information" )
            ->set_name( 'additional_information' )
            ->set_wrapper_width( 50 )
            ->set_maxlength( 80 )
            ->set_instructions( $strings['additional_information']['instructions'] );

        $tab->add_fields( [
            $apply_start_field,
            $apply_end_field,
            $apply_info_field,
            $show_audience,
            $start_date_field,
            $start_info_field,
            $price_field,
            $additional_information_field,
        ] );

        return $tab;
    }

    /**
     * Get components tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_components_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'        => 'Komponentit',
            'components' => [
                'title'        => _x( 'Components', 'theme ACF', 'tms-theme-tredu' ),
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $components_field = ( new Field\FlexibleContent( $strings['components']['title'] ) )
            ->set_key( "{$key}_components" )
            ->set_name( 'components' )
            ->set_instructions( $strings['components']['instructions'] );

        $component_layouts = apply_filters(
            'tms/acf/field/' . $components_field->get_key() . '/layouts',
            [
                Layouts\MapLayout::class,
                Layouts\IconLinksLayout::class,
                Layouts\ImageBannerLayout::class,
                Layouts\TextBlockLayout::class,
                Layouts\CallToActionLayout::class,
                Layouts\GravityFormLayout::class,
                Layouts\LogoWallLayout::class,
                Layouts\ImageCarouselLayout::class,
            ],
            $key
        );

        foreach ( $component_layouts as $component_layout ) {
            $components_field->add_layout( new $component_layout( $key ) );
        }

        $tab->add_field( $components_field );

        return $tab;
    }

     /**
      * Get general tab
      *
      * @param string $key Field group key.
      *
      * @return Field\Tab
      * @throws Exception In case of invalid option.
      */
    protected function get_stories_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'            => 'Valmistuneiden tarinat',
            'category'       => [
                'label'        => 'Kategoria',
                'instructions' => 'Voit nostaa valmistuneiden tarinoita valitsemalla artikkeleita joilla on kyseinen kategoria',
            ],
            'stories_amount' => [
                'label'        => 'Lukumäärä',
                'instructions' => '',
            ],
            'link'           => [
                'label'        => 'Lue lisää -linkki',
                'instructions' => '',
            ],

        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $category_field = ( new Field\Taxonomy( $strings['category']['label'] ) )
            ->set_key( "{$key}_category" )
            ->set_name( 'category' )
            ->set_taxonomy( Category::SLUG )
            ->set_return_format( 'id' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['category']['instructions'] );

        $amount_field = ( new Field\Number( $strings['stories_amount']['label'] ) )
            ->set_key( "{$key}_stories_amount" )
            ->set_name( 'stories_amount' )
            ->set_min( 1 )
            ->set_max( 8 )
            ->set_default_value( 4 )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['stories_amount']['instructions'] );

        $link_field = ( new Field\Link( $strings['link']['label'] ) )
            ->set_key( "{$key}_link" )
            ->set_name( 'link' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['link']['instructions'] );

        $tab->add_fields( [
            $category_field,
            $amount_field,
            $link_field,
        ] );

        return $tab;
    }
}

( new ProgramGroup() );
