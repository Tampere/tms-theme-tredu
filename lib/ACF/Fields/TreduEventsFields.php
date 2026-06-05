<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields;

use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\Taxonomy\DeliveryMethod;
use TMS\Theme\Tredu\Taxonomy\Location;
use TMS\Theme\Tredu\Taxonomy\Profession;
use TMS\Theme\Tredu\Taxonomy\ProgramType;

/**
 * Class TreduEventsFields
 *
 * @package TMS\Theme\Tredu\ACF\Fields
 */
class TreduEventsFields extends \Geniem\ACF\Field\Group {

    /**
     * The constructor for field.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) {
        parent::__construct( $label, $key, $name );

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
        $strings = [
            'title'           => [
                'label'        => 'Otsikko',
                'instructions' => '',
            ],
            'program_type'    => [
                'title'        => 'Tyyppi',
                'instructions' => '',
            ],
            'profession'      => [
                'title'        => 'Ammattiala',
                'instructions' => '',
            ],
            'location'        => [
                'title'        => 'Sijainti',
                'instructions' => '',
            ],
            'delivery_method' => [
                'title'        => 'Toteutustapa',
                'instructions' => '',
            ],
            'start_date'      => [
                'title'        => 'Alkupäivämäärä',
                'instructions' => '',
            ],
            'end_date'        => [
                'title'        => 'Loppupäivämäärä',
                'instructions' => '',
            ],
            'page_size'       => [
                'label'        => 'Näytettävien tapahtumien määrä',
                'instructions' => '',
            ],
            'show_images'     => [
                'label'        => 'Näytä kuvat',
                'instructions' => '',
            ],
            'all_events_link' => [
                'label'        => '"Katso kaikki tapahtumat" -linkki',
                'instructions' => '',
            ],
        ];

        $key = $this->get_key();

        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "{$key}_title" )
            ->set_name( 'title' )
            ->set_instructions( $strings['title']['instructions'] );

        $program_type_field = ( new Field\Taxonomy( $strings['program_type']['title'] ) )
            ->set_key( "{$key}_program_type" )
            ->set_name( 'program_type' )
            ->set_taxonomy( ProgramType::SLUG )
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['program_type']['instructions'] );

        $profession_type_field = ( new Field\Taxonomy( $strings['profession']['title'] ) )
            ->set_key( "{$key}_profession" )
            ->set_name( 'profession' )
            ->set_taxonomy( Profession::SLUG )
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['profession']['instructions'] );

        $location_type_field = ( new Field\Taxonomy( $strings['location']['title'] ) )
            ->set_key( "{$key}_location" )
            ->set_name( 'location' )
            ->set_taxonomy( Location::SLUG )
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['location']['instructions'] );

        $delivery_method_type_field = ( new Field\Taxonomy( $strings['delivery_method']['title'] ) )
            ->set_key( "{$key}_delivery_method" )
            ->set_name( 'delivery_method' )
            ->set_taxonomy( DeliveryMethod::SLUG )
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['delivery_method']['instructions'] );

        $display_format = 'j.n.Y';
        $return_format  = 'Y-m-d';

        $start_date_field = ( new Field\DatePicker( $strings['start_date']['title'] ) )
            ->set_key( "{$key}_start_date" )
            ->set_name( 'start_date' )
            ->set_wrapper_width( 33 )
            ->set_display_format( $display_format )
            ->set_return_format( $return_format )
            ->set_instructions( $strings['start_date']['instructions'] );

        $end_date_field = ( new Field\DatePicker( $strings['end_date']['title'] ) )
            ->set_key( "{$key}_end_date" )
            ->set_name( 'end_date' )
            ->set_wrapper_width( 33 )
            ->set_display_format( $display_format )
            ->set_return_format( $return_format )
            ->set_instructions( $strings['end_date']['instructions'] );

        $page_size_field = ( new Field\Number( $strings['page_size']['label'] ) )
            ->set_key( "{$key}_page_size" )
            ->set_name( 'page_size' )
            ->set_min( 3 )
            ->set_max( 12 )
            ->set_default_value( 4 )
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['page_size']['instructions'] );

        $show_images_field = ( new Field\TrueFalse( $strings['show_images']['label'] ) )
            ->set_key( "{$key}_show_images" )
            ->set_name( 'show_images' )
            ->use_ui()
            ->set_default_value( true )
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['show_images']['instructions'] );

        $all_events_link_field = ( new Field\Link( $strings['all_events_link']['label'] ) )
            ->set_key( "{$key}_all_events_link" )
            ->set_name( 'all_events_link' )
            ->set_wrapper_width( 33 )
            ->set_instructions( $strings['all_events_link']['instructions'] );

        return [
            $title_field,
            $program_type_field,
            $profession_type_field,
            $location_type_field,
            $delivery_method_type_field,
            $start_date_field,
            $end_date_field,
            $page_size_field,
            $show_images_field,
            $all_events_link_field,
        ];
    }
}
