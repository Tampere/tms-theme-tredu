<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

use TMS\Theme\Tredu\Interfaces\Formatter;

/**
 * Class NoticeBannerFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class NoticeBannerFormatter implements Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'NoticeBanner';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/notice_banner/data',
            [ $this, 'format' ]
        );

        add_filter(
            'tms/acf/block/notice_banner/data',
            [ $this, 'format' ]
        );
    }

    /**
     * Format layout or block data
     *
     * @param array $data ACF data.
     *
     * @return array
     */
    public function format( array $data ) : array {
        $data['background_color'] = $data['background_color'] ?? 'primary';
        $data['text_color']       = $data['background_color'] === 'primary'
            ? 'has-text-primary-invert'
            : 'has-text-black';

        $data['container_classes'] = 'has-background-' . $data['background_color'];
        $data['icon_classes']      = $data['background_color'] === 'primary'
            ? 'is-primary-invert'
            : 'is-primary-light';

        return $data;
    }
}
