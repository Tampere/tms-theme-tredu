<?php

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;

/**
 * Class ProjectGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class ProjectGroup {

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
            $field_group = ( new Group( 'Projektin tiedot' ) )
                ->set_key( 'fg_project_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\Project::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_general_tab( $field_group->get_key() ),
                        $this->get_info_tab( $field_group->get_key() ),
                        $this->get_logo_cloud_tab( $field_group->get_key() ),
                        $this->get_anchor_links_tab( $field_group->get_key() ),
                        $this->get_components_tab( $field_group->get_key() ),
                        $this->get_related_posts_tab( $field_group->get_key() ),
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
            'tab'        => 'Yleiset tiedot',
            'updated_on' => [
                'title'        => 'Päivitetty',
                'instructions' => 'Viimeisimmän päivityksen ajankohta',
            ],
            'ingress'    => [
                'title'        => 'Ingressi',
                'instructions' => '',
            ],
            'is_active'  => [
                'title'        => 'Aktiivinen',
                'instructions' => '',
            ],
            'title_lang' => [
                'title'        => 'Otsikon kieli',
                'instructions' => 'Valitse projektin otsikossa käytetty kieli.',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $ingress_field = ( new Field\Textarea( $strings['ingress']['title'] ) )
            ->set_key( "{$key}_ingress" )
            ->set_name( 'ingress' )
            ->set_instructions( $strings['ingress']['instructions'] );

        $updated_on_field = ( new Field\Text( $strings['updated_on']['title'] ) )
            ->set_key( "{$key}_updated_on" )
            ->set_name( 'updated_on' )
            ->set_instructions( $strings['updated_on']['instructions'] );

        $is_active_field = ( new Field\TrueFalse( $strings['is_active']['title'] ) )
            ->set_key( "{$key}_is_active" )
            ->set_name( 'is_active' )
            ->use_ui()
            ->set_instructions( $strings['is_active']['instructions'] )
            ->redipress_add_queryable( 'is_active' );

        $title_lang_field = ( new Field\Select( $strings['title_lang']['title'] ) )
            ->set_key( "{$key}_title_lang" )
            ->set_name( 'title_lang' )
            ->set_choices( [
                'fi' => 'Suomi',
                'en' => 'Englanti',
                'sv' => 'Ruotsi',
                'de' => 'Saksa',
                'fr' => 'Ranska',
                'es' => 'Espanja',
                'ar' => 'Arabia',
                'fa' => 'Farsi',
                'hi' => 'Hindi',
                'bn' => 'Bengali',
                'uk' => 'Ukraina',
                'ru' => 'Venäjä',
                'zh' => 'Kiina (mandariinikiina)',
                'ku' => 'Kurdi',
                'et' => 'Viro',
                'so' => 'Somali',
            ] )
            ->set_default_value( null )
            ->allow_null()
            ->set_required()
            ->set_instructions( $strings['title_lang']['instructions'] );

        $tab->add_fields( [
            $title_lang_field,
            $ingress_field,
            $updated_on_field,
            $is_active_field,
        ] );

        return $tab;
    }

    /**
     * Get logo cloud tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_logo_cloud_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'         => 'Logopilvi',
            'title'       => [
                'title'        => 'Otsikko',
                'instructions' => '',
            ],
            'description' => [
                'title'        => 'Kuvausteksti',
                'instructions' => '',
            ],
            'logos'       => [
                'title'        => 'Logot',
                'instructions' => '',
            ],
            'logo'        => [
                'title'        => 'Logo',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $title_field = ( new Field\Text( $strings['title']['title'] ) )
            ->set_key( "{$key}_logo_cloud_title" )
            ->set_name( 'logo_cloud_title' )
            ->set_instructions( $strings['title']['instructions'] );

        $description_field = ( new Field\Text( $strings['description']['title'] ) )
            ->set_key( "{$key}_logo_cloud_description" )
            ->set_name( 'logo_cloud_description' )
            ->set_instructions( $strings['description']['instructions'] );

        $logo_repeater_field = ( new Field\Repeater( $strings['logos']['title'] ) )
            ->set_key( "{$key}_logos" )
            ->set_name( 'logos' )
            ->set_instructions( $strings['logos']['instructions'] );

        $logo_field = ( new Field\Image( $strings['logo']['title'] ) )
            ->set_key( "{$key}_logo" )
            ->set_name( 'logo' )
            ->set_instructions( $strings['logo']['instructions'] );

        $logo_repeater_field->add_field( $logo_field );

        $tab->add_fields( [
            $title_field,
            $description_field,
            $logo_repeater_field,
        ] );

        return $tab;
    }

    /**
     * Get logo cloud tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_anchor_links_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'               => 'Ankkurilinkit',
            'anchors_title'     => [
                'title'        => 'Otsikko',
                'instructions' => '',
            ],
            'anchors'           => [
                'title'        => 'Ankkuri',
                'instructions' => 'Ankkurin voit syöttää esimerkiksi Otsikko-lohkoon valitsemalla lohkon aktiiviseksi ja siirtymällä sivupalkista kohtaan Edistyneet asetukset.',
            ],
            'anchor_link_title' => [
                'title'        => 'Otsikko',
                'instructions' => '',
            ],
            'anchor_link'       => [
                'title'        => 'Ankkurilinkki',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $section_title_field = ( new Field\Text( $strings['anchors_title']['title'] ) )
            ->set_key( "{$key}_anchors_title" )
            ->set_name( 'anchors_title' )
            ->set_default_value( 'Klikkaa suoraan sisältöön:' )
            ->set_instructions( $strings['anchors_title']['instructions'] );

        $anchor_repeater_field = ( new Field\Repeater( $strings['anchors']['title'] ) )
            ->set_key( "{$key}_anchors" )
            ->set_name( 'anchors' )
            ->set_instructions( $strings['anchors']['instructions'] );

        $anchor_title_field = ( new Field\Text( $strings['anchor_link_title']['title'] ) )
            ->set_key( "{$key}_anchor_link_title" )
            ->set_name( 'anchor_link_title' )
            ->set_instructions( $strings['anchor_link_title']['instructions'] );

        $anchor_link_field = ( new Field\Text( $strings['anchor_link']['title'] ) )
            ->set_key( "{$key}_anchor_link" )
            ->set_name( 'anchor_link' )
            ->set_instructions( $strings['anchor_link']['instructions'] );

        $anchor_repeater_field->add_fields(
            [
                $anchor_title_field,
                $anchor_link_field,
            ]
        );

        $tab->add_fields( [ $section_title_field, $anchor_repeater_field ] );

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
            'tab'            => 'Projektin tiedot',
            'group_title'    => [
                'title'        => 'Otsikko',
                'instructions' => '',
            ],
            'website'        => [
                'title'        => 'Sivusto',
                'instructions' => '',
            ],
            'website_link'   => [
                'title'        => 'Linkki',
                'instructions' => '',
            ],
            'duration'       => [
                'title'        => 'Kesto',
                'instructions' => '',
            ],
            'portfolio'      => [
                'title'        => 'Salkku',
                'instructions' => '',
            ],
            'portfolio_text' => [
                'title'        => 'Lisätieto',
                'instructions' => '',
            ],
            'contacts'       => [
                'title'        => 'Yhteyshenkilöt',
                'instructions' => '',
            ],
            'contact'        => [
                'title'        => 'Yhteyshenkilö',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $website_group = ( new Field\Group( $strings['website']['title'] ) )
            ->set_name( 'website' );

        $website_title_field = ( new Field\Text( $strings['group_title']['title'] ) )
            ->set_key( "{$key}_website_title" )
            ->set_name( 'website_title' )
            ->set_instructions( $strings['group_title']['instructions'] );

        $website_link_field = ( new Field\Link( $strings['website_link']['title'] ) )
            ->set_key( "{$key}_website_link" )
            ->set_name( 'website_link' )
            ->set_instructions( $strings['website_link']['instructions'] );

        $website_group->add_fields( [
            $website_title_field,
            $website_link_field,
        ] );

        $duration_group = ( new Field\Group( $strings['duration']['title'] ) )
            ->set_name( 'duration' );

        $duration_title_field = ( new Field\Text( $strings['group_title']['title'] ) )
            ->set_key( "{$key}_duration_title" )
            ->set_name( 'duration_title' )
            ->set_instructions( $strings['group_title']['instructions'] );

        $duration_field = ( new Field\Text( $strings['duration']['title'] ) )
            ->set_key( "{$key}_duration" )
            ->set_name( 'duration' )
            ->set_instructions( $strings['duration']['instructions'] );

        $duration_group->add_fields( [
            $duration_title_field,
            $duration_field,
        ] );

        $portfolio_group = ( new Field\Group( $strings['portfolio']['title'] ) )
            ->set_name( 'portfolio' );

        $portfolio_title_field = ( new Field\Text( $strings['group_title']['title'] ) )
            ->set_key( "{$key}_portfolio_title" )
            ->set_name( 'portfolio_title' )
            ->set_instructions( $strings['group_title']['instructions'] );

        $portfolio_text_field = ( new Field\Textarea( $strings['portfolio_text']['title'] ) )
            ->set_key( "{$key}_portfolio_text" )
            ->set_name( 'portfolio_text' )
            ->set_rows( 3 )
            ->set_instructions( $strings['portfolio_text']['instructions'] );

        $portfolio_group->add_fields( [
            $portfolio_title_field,
            $portfolio_text_field,
        ] );

        $contacts_title_field = ( new Field\Text( $strings['group_title']['title'] ) )
            ->set_key( "{$key}_meta_contacts_title" )
            ->set_name( 'meta_contacts_title' )
            ->set_instructions( $strings['group_title']['instructions'] );

        $contact_field = ( new Field\Textarea( $strings['contacts']['title'] ) )
            ->set_key( "{$key}_contacts" )
            ->set_name( 'contacts' )
            ->set_instructions( $strings['contacts']['instructions'] );

        $contacts_group_field = ( new Field\Group( $strings['contacts']['title'] ) )
            ->set_name( 'contacts' );

        $contacts_group_field->add_fields( [
            $contacts_title_field,
            $contact_field,
        ] );

        $tab->add_fields( [
            $website_group,
            $duration_group,
            $portfolio_group,
            $contacts_group_field,
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
                Layouts\EventsLayout::class,
                Layouts\ImageCarouselLayout::class,
                Layouts\CallToActionLayout::class,
                Layouts\LogoWallLayout::class,
                Layouts\IconLinksLayout::class,
                Layouts\SocialMediaLayout::class,
                Layouts\MapLayout::class,
                Layouts\GridLayout::class,
                Layouts\ImageBannerLayout::class,
                Layouts\TextBlockLayout::class,
                Layouts\NoticeBannerLayout::class,
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
     * Get related posts tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_related_posts_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'   => 'Suositellut sisällöt',
            'title' => [
                'title'         => 'Otsikko',
                'instructions'  => 'Suositellut sisällöt noston otsikko',
                'default_value' => __( 'Related posts', 'tms-theme-tredu' ),
            ],
            'link'  => [
                'title'        => 'Arkistolinkki',
                'instructions' => 'Suositellut sisällöt noston linkki',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $title_field = ( new Field\Text( $strings['title']['title'] ) )
            ->set_key( "{$key}_related_title" )
            ->set_name( 'related_title' )
            ->set_default_value( $strings['title']['default_value'] )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['title']['instructions'] );

        $link_field = ( new Field\Link( $strings['link']['title'] ) )
            ->set_key( "{$key}_related_link" )
            ->set_name( 'related_link' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['link']['instructions'] );

        $tab->add_fields( [
            $title_field,
            $link_field,
        ] );

        return $tab;
    }
}

( new ProjectGroup() );
