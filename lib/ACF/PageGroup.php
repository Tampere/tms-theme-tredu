<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use PageDialTredu;
use PageProject;
use TMS\Theme\Tredu\ACF\Layouts;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;

/**
 * Class PageGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class PageGroup {

    /**
     * PageGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] ),
            100
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $group_title = _x( 'Page Components', 'theme ACF', 'tms-theme-tredu' );
            $key         = 'fg_page_components';

            $field_group = ( new Group( $group_title ) )
                ->set_key( $key );

            $rules = apply_filters(
                'tms/acf/group/' . $key . '/rules',
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => PostType\Page::SLUG,
                    ],
                    [
                        'param'    => 'page_template',
                        'operator' => '!=',
                        'value'    => \PageFrontPage::TEMPLATE,
                    ],
                    [
                        'param'    => 'page_template',
                        'operator' => '!=',
                        'value'    => \PageEventsCalendar::TEMPLATE,
                    ],
                    [
                        'param'    => 'page_template',
                        'operator' => '!=',
                        'value'    => \PageOnepager::TEMPLATE,
                    ],
                    [
                        'param'    => 'page_template',
                        'operator' => '!=',
                        'value'    => \PageEventsCalendar::TEMPLATE,
                    ],
                    [
                        'param'    => 'page_template',
                        'operator' => '!=',
                        'value'    => \PageEventsSearch::TEMPLATE,
                    ],
                    [
                        'param'    => 'page_template',
                        'operator' => '!=',
                        'value'    => \PageProgram::TEMPLATE,
                    ],
                    [
                        'param'    => 'page_template',
                        'operator' => '!=',
                        'value'    => PageProject::TEMPLATE,
                    ],
                    [
                        'param'    => 'page_template',
                        'operator' => '!=',
                        'value'    => PageDialTredu::TEMPLATE,
                    ],
                    [
                        'param'    => 'page_type',
                        'operator' => '!=',
                        'value'    => 'posts_page',
                    ],
                ]
            );

            $rule_group = new RuleGroup();

            foreach ( $rules as $rule ) {
                $rule_group->add_rule(
                    $rule['param'],
                    $rule['operator'],
                    $rule['value'],
                );
            }

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' )
                ->set_hidden_elements(
                    [
                        'discussion',
                        'comments',
                        'format',
                        'send-trackbacks',
                    ]
                );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_components_field( $field_group->get_key() ),
                    ]
                )
            );

            $field_group = apply_filters(
                'tms/acf/group/' . $field_group->get_key(),
                $field_group
            );

            $field_group->register();
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }
    }

    /**
     * Get components fields
     *
     * @param string $key Field group key.
     *
     * @return Field\FlexibleContent
     * @throws Exception In case of invalid option.
     */
    protected function get_components_field( string $key ) : Field\FlexibleContent {
        $strings = [
            'components' => [
                'title'        => _x( 'Components', 'theme ACF', 'tms-theme-tredu' ),
                'instructions' => '',
            ],
        ];

        $components_field = ( new Field\FlexibleContent( $strings['components']['title'] ) )
            ->set_key( "{$key}_components" )
            ->set_name( 'components' )
            ->set_instructions( $strings['components']['instructions'] );

        $component_layouts = apply_filters(
            'tms/acf/field/' . $components_field->get_key() . '/layouts',
            [
                Layouts\CallToActionLayout::class,
                Layouts\ImageBannerLayout::class,
                Layouts\GridLayout::class,
                // Layouts\ContentColumnsLayout::class,
                Layouts\LogoWallLayout::class,
                Layouts\IconLinksLayout::class,
                Layouts\MapLayout::class,
                Layouts\SocialMediaLayout::class,
                Layouts\ImageCarouselLayout::class,
                Layouts\SubpageLayout::class,
                Layouts\TextBlockLayout::class,
                Layouts\EventsLayout::class,
                Layouts\ArticlesLayout::class,
                // Layouts\BlogArticlesLayout::class,
                Layouts\SitemapLayout::class,
                Layouts\NoticeBannerLayout::class,
                Layouts\GravityFormLayout::class,
                Layouts\ContactsLayout::class,
                Layouts\AccessibilityIconLinksLayout::class,
                Layouts\CountdownLayout::class,
                Layouts\ShareLinksLayout::class,
                Layouts\ProgramLayout::class,
                Layouts\TreduEventsLayout::class,
                Layouts\VideoLayout::class,
                Layouts\SomeLinkListLayout::class,
            ],
            $key
        );

        foreach ( $component_layouts as $component_layout ) {
            $components_field->add_layout( new $component_layout( $key ) );
        }

        return $components_field;
    }
}

( new PageGroup() );
