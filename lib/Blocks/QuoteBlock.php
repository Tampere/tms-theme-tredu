<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Blocks;

use Geniem\ACF\Block;
use TMS\Theme\Tredu\ACF\Fields\QuoteFields;
use TMS\Theme\Tredu\PostType\BlogArticle;
use TMS\Theme\Tredu\PostType\Page;
use TMS\Theme\Tredu\PostType\Post;

/**
 * Class QuoteBlock
 *
 * @package TMS\Theme\Tredu\Blocks
 */
class QuoteBlock extends BaseBlock {

    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'quote';

    /**
     * The block acf-key.
     *
     * @var string
     */
    const KEY = 'quote';

    /**
     * The block icon
     *
     * @var string
     */
    protected $icon = 'format-quote';

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
        $this->title = 'Sitaatti';

        parent::__construct();
    }

    /**
     * Create block fields.
     *
     * @return array
     */
    protected function fields() : array {
        $group = new QuoteFields( $this->title, self::NAME );

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
    public function filter_data( $data, $instance, $block, $content, $is_preview, $post_id ) : array {
        $background_color = $data['background_color'] ?? 'white';

        $classes = [
            'container' => [
                "has-background-$background_color",
                'mt-9',
                'mb-9',
            ],
            'author'    => [
                'has-text-weight-semibold',
                'is-family-secondary ',
            ],
        ];

        if ( ! empty( $data['is_wide'] ) ) {
            $classes['container'][] = 'is-align-wide';
        }

        $data['classes'] = $classes;

        $data = apply_filters( 'tms/acf/block/' . self::KEY . '/data', $data );

        return $data;
    }

}
