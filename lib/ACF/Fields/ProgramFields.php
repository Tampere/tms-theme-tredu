<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields;

use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\Taxonomy\ApplyMethod;
use TMS\Theme\Tredu\Taxonomy\EducationalBackground;
use TMS\Theme\Tredu\Taxonomy\Location;
use TMS\Theme\Tredu\Taxonomy\Profession;
use TMS\Theme\Tredu\Taxonomy\ProgramType;

/**
 * Class ProgramFields
 *
 * @package TMS\Theme\Tredu\ACF\Fields
 */
class ProgramFields extends \Geniem\ACF\Field\Group {

    /**
     * UI Strings.
     *
     * @var array
     */
    private array $strings;

    /**
     * The constructor for field.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) {
        parent::__construct( $label, $key, $name );

        $this->strings = [
            'title'                  => [
                'label'        => 'Otsikko',
                'instructions' => '',
            ],
            'description'            => [
                'label'        => 'Kuvaus',
                'instructions' => '',
            ],
            'limit'                  => [
                'label'        => 'Lukumäärä',
                'instructions' => 'Valitse väliltä 4-12',
            ],
            'apply_method'           => [
                'label'        => 'Hakutapa',
                'instructions' => 'Esitä artikkeleja valituista hakutavoista',
            ],
            'program_type'           => [
                'label'        => 'Koulutustyypppi',
                'instructions' => 'Esitä artikkeleja valituista koulutustyypeistä',
            ],
            'profession'             => [
                'label'        => 'Ammattiala',
                'instructions' => 'Esitä artikkeleja valituista ammattialoista',
            ],
            'location'               => [
                'label'        => 'Sijainti',
                'instructions' => 'Esitä artikkeleja valituista sijainneista',
            ],
            'educational_background' => [
                'label'        => 'Koulutustausta',
                'instructions' => 'Esitä artikkeleja valituista koulutustaustoista',
            ],
            'apply_start'            => [
                'label'        => 'Alkamispäivämäärä',
                'instructions' => '',
            ],
            'apply_end_d'            => [
                'label'        => 'Päättymispäivämäärä',
                'instructions' => '',
            ],
            'link'                   => [
                'label'        => 'Linkki',
                'instructions' => '',
            ],
            'load_more_toggle'       => [
                'label'        => 'Näytä "Lataa lisää"-painike',
                'instructions' => '',
            ],
        ];

        try {
            $this->add_fields( $this->sub_fields() );
        }
        catch ( \Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }

    /**
     * This returns all sub fields of the parent groupable.
     *
     * @return array
     * @throws \Geniem\ACF\Exception In case of invalid ACF option.
     */
    protected function sub_fields() : array {
        $key = $this->get_key();

        $title_field = ( new Field\Text( $this->strings['title']['label'] ) )
            ->set_key( "{$key}_title" )
            ->set_name( 'title' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $this->strings['title']['instructions'] );

        $description_field = ( new Field\Textarea( $this->strings['description']['label'] ) )
            ->set_key( "{$key}_description" )
            ->set_name( 'description' )
            ->set_wrapper_width( 50 )
            ->set_rows( 4 )
            ->set_instructions( $this->strings['description']['instructions'] );

        $link_field = ( new Field\Link( $this->strings['link']['label'] ) )
            ->set_key( $this->get_key() . '_link' )
            ->set_name( 'link' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $this->strings['link']['instructions'] );

        $apply_start_field = ( new Field\DatePicker( $this->strings['apply_start']['label'] ) )
            ->set_key( "{$key}_apply_start" )
            ->set_name( 'apply_start' )
            ->set_return_format( 'Y-m-d' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $this->strings['apply_start']['instructions'] );

        $apply_method_field = ( new Field\Taxonomy( $this->strings['apply_method']['label'] ) )
            ->set_key( "{$key}_apply_method" )
            ->set_name( 'apply_method' )
            ->set_taxonomy( ApplyMethod::SLUG )
            ->set_return_format( 'id' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $this->strings['apply_method']['instructions'] );

        $program_type_field = ( new Field\Taxonomy( $this->strings['program_type']['label'] ) )
            ->set_key( "{$key}_program_type" )
            ->set_name( 'program_type' )
            ->set_taxonomy( ProgramType::SLUG )
            ->set_return_format( 'id' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $this->strings['program_type']['instructions'] );

        $profession_field = ( new Field\Taxonomy( $this->strings['profession']['label'] ) )
            ->set_key( "{$key}_profession" )
            ->set_name( 'profession' )
            ->set_taxonomy( Profession::SLUG )
            ->set_return_format( 'id' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $this->strings['profession']['instructions'] );

        $location_field = ( new Field\Taxonomy( $this->strings['location']['label'] ) )
            ->set_key( "{$key}_location" )
            ->set_name( 'location' )
            ->set_taxonomy( Location::SLUG )
            ->set_return_format( 'id' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $this->strings['location']['instructions'] );

        $educational_background_field = ( new Field\Taxonomy( $this->strings['educational_background']['label'] ) )
            ->set_key( "{$key}_educational_background" )
            ->set_name( 'educational_background' )
            ->set_taxonomy( EducationalBackground::SLUG )
            ->set_return_format( 'id' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $this->strings['educational_background']['instructions'] );

        $limit_field = ( new Field\Number( $this->strings['limit']['label'] ) )
            ->set_key( "{$key}_number" )
            ->set_name( 'number' )
            ->set_min( 4 )
            ->set_max( 12 )
            ->set_default_value( 12 )
            ->set_wrapper_width( 50 )
            ->set_instructions( $this->strings['limit']['instructions'] );

        $load_more_toggle = ( new Field\TrueFalse( $this->strings['load_more_toggle']['label'] ) )
            ->set_key( "{$key}_load_more_toggle" )
            ->set_name( 'load_more_toggle' )
            ->use_ui()
            ->set_wrapper_width( 100 )
            ->set_instructions( $this->strings['load_more_toggle']['instructions'] );

        return [
            $title_field,
            $description_field,
            $apply_start_field,
            $apply_method_field,
            $program_type_field,
            $profession_field,
            $location_field,
            $educational_background_field,
            $limit_field,
            $link_field,
            $load_more_toggle,
        ];
    }
}
