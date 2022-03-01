<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

/**
 * Class AccessibilityIconLinksFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class AccessibilityIconLinksFormatter implements \TMS\Theme\Tredu\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'AccessibilityIconLinks';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/acc_icon_links/data',
            [ $this, 'format' ]
        );

        add_filter(
            'tms/acf/block/acc_icon_links/data',
            [ $this, 'format' ]
        );
    }

    /**
     * Format layout data
     *
     * @param array $layout ACF Layout data.
     *
     * @return array
     */
    public function format( array $layout ) : array {
        if ( empty( $layout['rows'] ) ) {
            return $layout;
        }

        foreach ( $layout['rows'] as $key => $row ) {
            if ( empty( $layout['rows'][ $key ]['link'] ) ) {
                continue;
            }

            $layout['rows'][ $key ]['link']['icon'] = 'chevron-right';
        }

        return $layout;
    }
}
