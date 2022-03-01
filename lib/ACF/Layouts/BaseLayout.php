<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Layouts;

/**
 * BaseLayout
 */
class BaseLayout extends \Geniem\ACF\Field\Flexible\Layout {
    /**
     * Run default filters to our fields.
     *
     * @param array  $fields   ACF Fields.
     * @param string $key      ACF Group Key.
     * @param string $base_key Layout self::KEY.
     *
     * @return array
     */
    public function filter_layout_fields( $fields, $key, $base_key = '' ) : array {
        if ( $base_key !== $key && ! empty( $base_key ) ) {
            $fields = apply_filters( 'tms/acf/layout/' . $base_key . '/fields', $fields, $key );
        }

        return apply_filters( 'tms/acf/layout/' . $key . '/fields', $fields, $key );
    }
}
