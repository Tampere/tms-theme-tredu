<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;

/**
 * Class GravityFormFields
 *
 * @package TMS\Theme\Tredu\ACF\Fields
 */
class GravityFormFields extends Field\Group {

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
            'text' => [
                'label'        => 'Lomake',
                'instructions' => '',
            ],
        ];

        $key = $this->get_key();

        $field = ( new Field\GravityForms( $strings['text']['label'] ) )
            ->set_key( "${key}_form" )
            ->set_name( 'form' )
            ->set_required()
            ->set_instructions( $strings['text']['instructions'] );

        return [ $field ];
    }
}
