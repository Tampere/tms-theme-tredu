<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF;

use \Geniem\ACF\Exception;
use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use TMS\Theme\Tredu\ACF\Fields\Settings\ArchiveSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\HeaderSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\BlogArticleSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\ContactsSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\Error404SettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\EventsSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\ExceptionNoticeSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\FooterSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\MapSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\PageSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\SocialMediaSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\ThemeColorTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\SitemapSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\ProgramSettingsTab;
use TMS\Theme\Tredu\ACF\Fields\Settings\TreduEventsSettingsTab;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;


/**
 * Class SettingsGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class SettingsGroup {

    /**
     * SettingsGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $group_title = _x( 'Site settings', 'theme ACF', 'tms-theme-tredu' );

            $field_group = ( new Group( $group_title ) )
                ->set_key( 'fg_site_settings' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\Settings::SLUG );

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
                        new HeaderSettingsTab( '', $field_group->get_key() ),
                        new FooterSettingsTab( '', $field_group->get_key() ),
                        new ThemeColorTab( '', $field_group->get_key() ),
                        new MapSettingsTab( '', $field_group->get_key() ),
                        new SocialMediaSettingsTab( '', $field_group->get_key() ),
                        new Error404SettingsTab( '', $field_group->get_key() ),
                        new ArchiveSettingsTab( '', $field_group->get_key() ),
                        new EventsSettingsTab( '', $field_group->get_key() ),
                        new TreduEventsSettingsTab( '', $field_group->get_key() ),
                        new PageSettingsTab( '', $field_group->get_key() ),
                        new ExceptionNoticeSettingsTab( '', $field_group->get_key() ),
                        new BlogArticleSettingsTab( '', $field_group->get_key() ),
                        new ContactsSettingsTab( '', $field_group->get_key() ),
                        new SitemapSettingsTab( '', $field_group->get_key() ),
                        new ProgramSettingsTab( '', $field_group->get_key() ),
                    ],
                    $field_group->get_key()
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
     * Get exception notice fields
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_exception_notice_fields( string $key ) : Field\Tab {
        $strings = [
            'tab'  => 'Poikkeusilmoitus',
            'text' => [
                'title'        => 'Teksti',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $exception_text_field = ( new Field\Textarea( $strings['text']['title'] ) )
            ->set_key( "${key}_exception_text" )
            ->set_name( 'exception_text' )
            ->set_rows( 2 )
            ->set_wrapper_width( 50 )
            ->set_maxlength( 200 )
            ->set_instructions( $strings['text']['instructions'] );

        $tab->add_fields( [
            $exception_text_field,
        ] );

        return $tab;
    }
}

( new SettingsGroup() );
