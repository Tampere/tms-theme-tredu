<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Blocks;

use Geniem\ACF\Block;
use TMS\Theme\Tredu\ACF\Fields\NoticeBannerFields;

/**
 * Class LinkListBlock
 *
 * @package TMS\Theme\Tredu\Blocks
 */
class NoticeBannerBlock extends TreduBlock {

    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'notice-banner';

    /**
     * The block acf-key.
     *
     * @var string
     */
    const KEY = 'notice_banner';

    /**
     * The block icon
     *
     * @var string
     */
    protected $icon = 'info-outline';

    /**
     * Create the block and register it.
     */
    public function __construct() {
        $this->title = 'Huomiobanneri';

        parent::__construct();
    }

    /**
     * Create block fields.
     *
     * @return array
     */
    protected function fields() : array {
        $group = new NoticeBannerFields( $this->title, self::NAME );

        return apply_filters(
            'tms/block/' . self::KEY . '/fields',
            $group->get_fields(),
            self::KEY
        );
    }

    /**
     * This filters the block ACF data.
     *
     * @param array  $data       Block's ACF data.
     * @param Block  $instance   The block instance.
     * @param array  $block      The original ACF block array.
     * @param string $content    The HTML content.
     * @param bool   $is_preview A flag that shows if we're in preview.
     * @param int    $post_id    The parent post's ID.
     *
     * @return array The block data.
     */
    public function filter_data( $data, $instance, $block, $content, $is_preview, $post_id ) : array { // phpcs:ignore
        return apply_filters( 'tms/acf/block/' . self::KEY . '/data', $data );
    }

}
