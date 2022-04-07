<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;

/**
 * Class QuoteFields
 *
 * @package TMS\Theme\Tredu\ACF\Fields
 */
class QuoteFields extends Field\Group {

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
     * @throws Exception In case of invalid ACF option.
     */
    protected function sub_fields() : array {
        $strings = [
            'quote'            => [
                'label'        => 'Lainaus',
                'instructions' => '',
            ],
            'author'           => [
                'label'        => 'Sitaatin antaja',
                'instructions' => '',
            ],
            'is_wide'          => [
                'label'        => 'Näytä leveänä',
                'instructions' => '',
            ],
            'background_color' => [
                'label'        => 'Taustaväri',
                'instructions' => '',
            ],
        ];

        $key = $this->get_key();

        $quote_field = ( new Field\Textarea( $strings['quote']['label'] ) )
            ->set_key( "${key}_quote" )
            ->set_name( 'quote' )
            ->set_required()
            ->set_new_lines( 'wpautop' )
            ->set_instructions( $strings['quote']['instructions'] );

        $author_field = ( new Field\Text( $strings['author']['label'] ) )
            ->set_key( "${key}_author" )
            ->set_name( 'author' )
            ->set_instructions( $strings['author']['instructions'] );

        $is_wide_field = ( new Field\TrueFalse( $strings['is_wide']['label'] ) )
            ->set_key( "${key}_is_wide" )
            ->set_name( 'is_wide' )
            ->use_ui()
            ->set_default_value( false )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['is_wide']['instructions'] );

        $background_color_field = ( new Field\Select( $strings['background_color']['label'] ) )
            ->set_key( "${key}_background_color" )
            ->set_name( 'background_color' )
            ->set_choices( [
                'white'   => 'Valkoinen',
                'primary' => 'Tumma',
            ] )
            ->set_default_value( 'white' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['background_color']['instructions'] );

        return [
            $quote_field,
            $author_field,
            $is_wide_field,
            $background_color_field,
        ];
    }
}
