<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Blocks;

use Geniem\ACF\Block;
use TMS\Theme\Tredu\Settings;

/**
 * Class ProgramCallToActionBlock
 *
 * @package TMS\Theme\Tredu\Blocks
 */
class ProgramCallToAction extends BaseBlock {

    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'program-call-to-action';

    /**
     * The block acf-key.
     *
     * @var string
     */
    const KEY = 'program_call_to_action';

    /**
     * The block icon
     *
     * @var string
     */
    protected $icon = 'format-status';

    /**
     * The block title.
     *
     * @var string
     */
    protected string $title;

    /**
     * Create the block and register it.
     */
    public function __construct() {
        $this->title = 'Koulutusten toimintakehote';

        parent::__construct();
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
        $data = self::add_filter_attributes( $data, $instance, $block, $content, $is_preview, $post_id );

        $data['title']       = Settings::get_setting( 'program_call_to_action_cta_title' ) ?? '';
        $data['description'] = Settings::get_setting( 'program_call_to_action_cta_description' ) ?? '';
        $data['link']        = Settings::get_setting( 'program_call_to_action_cta_link' ) ?? '';

        return apply_filters( 'tms/acf/block/' . self::KEY . '/data', $data );
    }

}
