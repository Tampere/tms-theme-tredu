<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF;

use Closure;
use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use PageProject;
use TMS\Theme\Tredu\ACF\Layouts;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\Taxonomy\Portoflio;

/**
 * Class PageProjectGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class PageProjectGroup {

    /**
     * PageGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $group_title = 'Sivun sisältökentät';

            $field_group = ( new Group( $group_title ) )
                ->set_key( 'fg_page_project_settings' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'page_template', '==', PageProject::TEMPLATE );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $key = $field_group->get_key();

            $strings = [
                'ingress'    => [
                    'title'        => 'Ingressi',
                    'instructions' => '',
                ],
                'components' => [
                    'title'        => _x( 'Components', 'theme ACF', 'tms-theme-tredu' ),
                    'instructions' => '',
                ],
            ];

            $ingress_field = ( new Field\Textarea( $strings['ingress']['title'] ) )
                ->set_key( "{$key}_ingress" )
                ->set_name( 'ingress' )
                ->set_instructions( $strings['ingress']['instructions'] );

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
                ],
                $key
            );

            foreach ( $component_layouts as $component_layout ) {
                $components_field->add_layout( new $component_layout( $key ) );
            }

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $ingress_field,
                        $components_field,
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
}

( new PageProjectGroup() );


