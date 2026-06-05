<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields;

use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;

/**
 * Class HeroFields
 *
 * @package TMS\Theme\Tredu\ACF\Fields
 */
class HeroFields extends \Geniem\ACF\Field\Group {

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
            'image'       => [
                'label'        => 'Kuva',
                'instructions' => '',
            ],
            'title'       => [
                'label'        => 'Otsikko',
                'instructions' => '',
            ],
            'description' => [
                'label'        => 'Kuvaus',
                'instructions' => '',
            ],
            'link'        => [
                'label'        => 'Painike',
                'instructions' => '',
            ],
            'align'       => [
                'label'        => 'Tekstin tasaus',
                'instructions' => '',
            ],
            'use_box'     => [
                'label'        => 'Teksti värilaatikossa',
                'instructions' => '',
            ],
            'links' => [
                'label'        => 'Linkit',
                'instructions' => 'Lisää linkkipainikkeita',
                'add_more_btn' => 'Lisää linkkirivi',
            ],
        ];

        $key = $this->get_key();

        $image_field = ( new Field\Image( $strings['image']['label'] ) )
            ->set_key( "{$key}_image" )
            ->set_name( 'image' )
            ->set_return_format( 'id' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['image']['instructions'] );

        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "{$key}_title" )
            ->set_name( 'title' )
            ->set_maxlength( 90 )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['title']['instructions'] );

        $description_field = ( new Field\Textarea( $strings['description']['label'] ) )
            ->set_key( "{$key}_description" )
            ->set_name( 'description' )
            ->set_maxlength( 200 )
            ->set_rows( 4 )
            ->set_new_lines( 'wpautop' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['description']['instructions'] );

        $link_field = ( new Field\Link( $strings['link']['label'] ) )
            ->set_key( "{$key}_link" )
            ->set_name( 'link' )
            ->set_wrapper_width( 40 )
            ->set_instructions( $strings['link']['instructions'] );

        $rows_field = ( new Field\Repeater( $strings['links']['label'] ) )
            ->set_key( "{$key}_rows" )
            ->set_name( 'rows' )
            ->set_min( 0 )
            ->set_max( 4 )
            ->set_layout( 'block' )
            ->set_button_label( $strings['links']['add_more_btn'] )
            ->set_wrapper_width( 100 )
            ->set_instructions( $strings['links']['instructions'] );

        $rows_field->add_fields( [
            $link_field,
        ] );

        return [
            $image_field,
            $title_field,
            $description_field,
            $rows_field,
        ];
    }
}
