<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\ACF\Layouts;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;

/**
 * Class TreduEventGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class TreduEventGroup {

    /**
     * PageGroup constructor.
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
            $group_title = _x( 'Tiedot', 'theme ACF', 'tms-theme-tredu' );

            $field_group = ( new Group( $group_title ) )
                ->set_key( 'fg_tredu_event_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\TreduEvent::SLUG );

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
                        $this->get_event_tab( $field_group->get_key() ),
                        $this->get_components_tab( $field_group->get_key() ),
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
     * Get event tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_event_tab( string $key ) : ?Field\Tab {
        $strings = [
            'tab'         => 'Tapahtuma',
            'excerpt'     => [
                'title'        => 'Ingressi',
                'instructions' => '',
            ],
            'start_date'  => [
                'title'        => 'Alkupäivämäärä',
                'instructions' => '',
            ],
            'end_date'    => [
                'title'        => 'Loppupäivämäärä',
                'instructions' => '',
            ],
            'time'        => [
                'title'        => 'Kellonaika',
                'instructions' => '',
            ],
            'location'    => [
                'title'        => 'Sijainti',
                'instructions' => '',
            ],
            'enroll_link' => [
                'title'        => 'Ilmoittautumislinkki',
                'instructions' => '',
            ],
            'contacts'    => [
                'title'        => 'Yhteystiedot',
                'instructions' => '',
                'subfields'    =>
                    [
                        'name'  => [
                            'title'        => 'Nimi',
                            'instructions' => '',
                        ],
                        'title' => [
                            'title'        => 'Titteli',
                            'instructions' => '',
                        ],
                        'email' => [
                            'title'        => 'Sähköposti',
                            'instructions' => '',
                        ],
                        'phone' => [
                            'title'        => 'Puhelinnumero',
                            'instructions' => '',
                        ],
                        'info'  => [
                            'title'        => 'Lisätiedot',
                            'instructions' => '',
                        ],
                    ],
            ],
        ];

        try {
            $tab = ( new Field\Tab( $strings['tab'] ) )
                ->set_placement( 'left' );

            $display_format = 'j.n.Y';
            $return_format  = $display_format;

            $excerpt_field = ( new Field\Textarea( $strings['excerpt']['title'] ) )
                ->set_key( "{$key}_excerpt" )
                ->set_name( 'excerpt' )
                ->set_rows( 4 )
                ->set_instructions( $strings['excerpt']['instructions'] );

            $start_date_field = ( new Field\DatePicker( $strings['start_date']['title'] ) )
                ->set_key( "{$key}_start_date" )
                ->set_name( 'start_date' )
                ->set_wrapper_width( 33 )
                ->set_display_format( $display_format )
                ->set_return_format( $return_format )
                ->set_required()
                ->set_instructions( $strings['start_date']['instructions'] );

            $end_date_field = ( new Field\DatePicker( $strings['end_date']['title'] ) )
                ->set_key( "{$key}_end_date" )
                ->set_name( 'end_date' )
                ->set_wrapper_width( 33 )
                ->set_display_format( $display_format )
                ->set_return_format( $return_format )
                ->set_instructions( $strings['end_date']['instructions'] );

            $time_field = ( new Field\Text( $strings['time']['title'] ) )
                ->set_key( "{$key}_time" )
                ->set_name( 'time' )
                ->set_maxlength( 20 )
                ->set_wrapper_width( 33 )
                ->set_instructions( $strings['time']['instructions'] );

            $location_field = ( new Field\Text( $strings['location']['title'] ) )
                ->set_key( "{$key}_location" )
                ->set_name( 'location' )
                ->set_wrapper_width( 33 )
                ->set_instructions( $strings['location']['instructions'] );

            $enroll_link = ( new Field\Link( $strings['enroll_link']['title'] ) )
                ->set_key( "{$key}_enroll_link" )
                ->set_name( 'enroll_link' )
                ->set_wrapper_width( 33 )
                ->set_instructions( $strings['enroll_link']['instructions'] );

            $contacts_repeater = ( new Field\Repeater( $strings['contacts']['title'] ) )
                ->set_key( "{$key}_contacts" )
                ->set_name( 'contacts' )
                ->set_layout( 'block' )
                ->set_instructions( $strings['contacts']['instructions'] );

            $name_field = ( new Field\Text( $strings['contacts']['subfields']['name']['title'] ) )
                ->set_key( "{$key}_name" )
                ->set_name( 'name' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['contacts']['subfields']['name']['instructions'] );

            $title_field = ( new Field\Text( $strings['contacts']['subfields']['title']['title'] ) )
                ->set_key( "{$key}_title" )
                ->set_name( 'title' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['contacts']['subfields']['title']['instructions'] );

            $email_field = ( new Field\Text( $strings['contacts']['subfields']['email']['title'] ) )
                ->set_key( "{$key}_email" )
                ->set_name( 'email' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['contacts']['subfields']['email']['instructions'] );

            $phone_field = ( new Field\Text( $strings['contacts']['subfields']['phone']['title'] ) )
                ->set_key( "{$key}_phone" )
                ->set_name( 'phone' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['contacts']['subfields']['phone']['instructions'] );

            $info_field = ( new Field\Textarea( $strings['contacts']['subfields']['info']['title'] ) )
                ->set_key( "{$key}_info" )
                ->set_name( 'info' )
                ->set_rows( 4 )
                ->set_instructions( $strings['contacts']['subfields']['info']['instructions'] );

            $contacts_repeater->add_fields(
                [
                    $name_field,
                    $title_field,
                    $email_field,
                    $phone_field,
                    $info_field,
                ]
            );

            $tab->add_fields( [
                $excerpt_field,
                $start_date_field,
                $end_date_field,
                $time_field,
                $location_field,
                $enroll_link,
                $contacts_repeater,
            ] );

            return $tab;
        }
        catch ( \Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return null;
    }

    /**
     * Get components tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_components_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'        => 'Komponentit',
            'components' => [
                'title'        => 'Komponentit',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $components_field = ( new Field\FlexibleContent( $strings['components']['title'] ) )
            ->set_key( "{$key}_components" )
            ->set_name( 'components' )
            ->set_instructions( $strings['components']['instructions'] );

        $component_layouts = apply_filters(
            'tms/acf/field/' . $components_field->get_key() . '/layouts',
            [
                Layouts\EventsLayout::class,
                Layouts\IconLinksLayout::class,
                Layouts\ShareLinksLayout::class,
                Layouts\ContactsLayout::class,
                Layouts\GravityFormLayout::class,
                Layouts\ImageBannerLayout::class,
                Layouts\TextBlockLayout::class,
                Layouts\NoticeBannerLayout::class,
                Layouts\AccessibilityIconLinksLayout::class,
            ],
            $key
        );

        foreach ( $component_layouts as $component_layout ) {
            $components_field->add_layout( new $component_layout( $key ) );
        }

        $tab->add_field( $components_field );

        return $tab;
    }
}

( new TreduEventGroup() );
