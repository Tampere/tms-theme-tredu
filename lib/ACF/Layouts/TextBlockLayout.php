<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Layouts;

use Geniem\ACF\Exception;
use TMS\Theme\Tredu\ACF\Fields\TextBlockFields;
use TMS\Theme\Tredu\Logger;

/**
 * Class TextBlockLayout
 *
 * @package TMS\Theme\Tredu\ACF\Layouts
 */
class TextBlockLayout extends TreduLayout {

    /**
     * Layout key
     */
    const KEY = '_textblock';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( string $key ) {
        parent::__construct(
            'Tekstikappale',
            $key . self::KEY,
            'textblock'
        );

        $this->add_layout_fields();
    }

    /**
     * Add layout fields
     *
     * @return void
     */
    private function add_layout_fields() : void {
        $fields = new TextBlockFields(
            $this->get_label(),
            $this->get_key(),
            $this->get_name()
        );

        try {
            $this->add_fields(
                $this->filter_layout_fields( $fields->get_fields(), $this->get_key(), self::KEY )
            );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
