<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Layouts;

use Geniem\ACF\Exception;
use TMS\Theme\Tredu\ACF\Fields\AccessibilityIconLinksFields;
use TMS\Theme\Tredu\Logger;

/**
 * Class AccessibilityIconLinksLayout
 *
 * @package TMS\Theme\Tredu\ACF\Layouts
 */
class AccessibilityIconLinksLayout extends BaseLayout {

    /**
     * Layout key
     */
    const KEY = '_acc_icon_links';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( string $key ) {
        parent::__construct(
            'Saavutettavuus- ja palveluikonit',
            $key . self::KEY,
            'acc_icon_links'
        );

        $this->add_layout_fields();
    }

    /**
     * Add layout fields
     *
     * @return void
     */
    private function add_layout_fields() : void {
        $fields = new AccessibilityIconLinksFields(
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