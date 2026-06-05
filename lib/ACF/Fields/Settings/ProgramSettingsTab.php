<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields\Settings;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Tab;
use Geniem\ACF\Group;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;
use TMS\Theme\Tredu\Taxonomy\ApplyMethod;

/**
 * Class ProgramSettingsTab
 *
 * @package TMS\Theme\Tredu\ACF\Tab
 */
class ProgramSettingsTab extends Tab {

    /**
     * Where should the tab switcher be located
     *
     * @var string
     */
    protected $placement = 'left';

    /**
     * Tab strings.
     *
     * @var array
     */
    protected $strings = [
        'tab'                   => 'Koulutusten asetukset',
        'program_search_group'  => [
            'title' => 'Koulutushaun asetukset',
        ],
        'program_page'          => [
            'title'        => 'Koulutushaun sivu',
            'instructions' => 'Sivu, jolle on valittu Koulutushaku-sivupohja. Käytetään pikahaussa hakulomakkeen lähettämisessä koulutusten hakusivulle', // phpcs:ignore
        ],
        'program_post_per_page' => [
            'title'        => 'Koulutusten määrä per sivu',
            'instructions' => '',
        ],
        'program_cta_group'     => [
            'title' => 'Koulutushaun asetukset',
        ],
        'cta_title_field'       => [
            'title'        => 'Toimintakehotteen otsikko',
            'instructions' => 'Enimmäismerkkimäärä 90',
        ],
        'cta_description_field' => [
            'title'        => 'Toimintakehotteen kuvausteksti',
            'instructions' => 'Enimmäismerkkimäärä 200',
        ],
        'cta_link'              => [
            'title'        => 'Linkki',
            'instructions' => '',
        ],
        'apply_method_radios'   => [
            'title'        => 'Hakutavat valintapainikkeisiin',
            'instructions' => 'Valitse tästä halutut hakutavat koulutushaun radio-painike suodattimeen.',
        ],
    ];

    /**
     * The constructor for tab.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) { // phpcs:ignore
        $label = $this->strings['tab'];

        parent::__construct( $label );

        $this->sub_fields( $key );
    }

    /**
     * Register sub fields.
     *
     * @param string $key Field tab key.
     */
    public function sub_fields( $key ) {
        $strings = $this->strings;

        try {
            $program_page = ( new Field\PostObject( $strings['program_page']['title'] ) )
                ->set_key( 'program_page' )
                ->set_name( 'program_page' )
                ->set_post_types( [ PostType\Page::SLUG ] )
                ->set_return_format( 'id' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['program_page']['instructions'] );

            $prorgam_post_per_page_field = ( new Field\Number( $strings['program_post_per_page']['title'] ) )
                ->set_key( 'programs_per_page' )
                ->set_name( 'programs_per_page' )
                ->set_min( 1 )
                ->set_max( 30 )
                ->set_default_value( 20 )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['program_post_per_page']['instructions'] );

            $cta_title_field = ( new Field\Text( $this->strings['cta_title_field']['title'] ) )
                ->set_key( 'cta_title' )
                ->set_name( 'cta_title' )
                ->set_required()
                ->set_maxlength( 90 )
                ->set_instructions( $this->strings['cta_title_field']['instructions'] )
                ->set_default_value( '' );

            $cta_description_field = ( new Field\Textarea( $this->strings['cta_description_field']['title'] ) )
                ->set_key( 'cta_desc' )
                ->set_name( 'cta_description' )
                ->set_maxlength( 200 )
                ->set_instructions( $this->strings['cta_description_field']['instructions'] );

            $link_field = ( new Field\Link( $strings['cta_link']['title'] ) )
                ->set_key( 'cta_link' )
                ->set_name( 'cta_link' )
                ->set_required()
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['cta_link']['instructions'] );

            $program_cta_group = ( new Field\Group( $strings['program_cta_group']['title'] ) )
                ->set_key( "{$key}_program_call_to_action" )
                ->set_name( 'program_call_to_action' );

            $program_cta_group->add_fields( [
                $cta_title_field,
                $cta_description_field,
                $link_field,
            ] );

            $program_search_group = ( new Field\Group( $strings['program_search_group']['title'] ) )
                ->set_key( "{$key}_program_search" )
                ->set_name( 'program_search' );

            $apply_method_radios = ( new Field\Taxonomy( $strings['apply_method_radios']['title'] ) )
                ->set_key( "{$key}_program_apply_methods" )
                ->set_name( 'program_apply_methods' )
                ->set_taxonomy( ApplyMethod::SLUG )
                ->allow_null( true )
                ->set_instructions( $strings['apply_method_radios']['instructions'] );

            $program_search_group->add_fields( [
                $program_page,
                $prorgam_post_per_page_field,
            ] );

            $this->add_fields( [
                $program_search_group,
                $program_cta_group,
                $apply_method_radios,
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
