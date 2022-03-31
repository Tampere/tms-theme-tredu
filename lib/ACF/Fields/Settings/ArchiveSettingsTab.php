<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields\Settings;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Tab;
use TMS\Theme\Tredu\Logger;

/**
 * Class ArchiveSettingsTab
 *
 * @package TMS\Theme\Tredu\ACF\Tab
 */
class ArchiveSettingsTab extends Tab {

    /**
     * Where should the tab switcher be located
     *
     * @var string
     */
    protected $placement = 'left';

    /**
     * Tab strings.
     *
     * @var array
     */
    protected $strings = [
        'tab'                => 'Arkistot',
        'archive_use_images' => [
            'title'        => 'Kuvat käytössä',
            'instructions' => '',
        ],
        'archive_view_type'  => [
            'title'        => 'Listaustyyli',
            'instructions' => '',
        ],
        'program_post_per_page' => [
            'title' => 'Koulutusten määrä per sivu',
            'instructions' => '',
        ]
    ];

    /**
     * The constructor for tab.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) { // phpcs:ignore
        $label = $this->strings['tab'];

        parent::__construct( $label );

        $this->sub_fields( $key );
    }

    /**
     * Register sub fields.
     *
     * @param string $key Field tab key.
     */
    public function sub_fields( $key ) {
        $strings = $this->strings;

        try {
            $use_images_field = ( new Field\TrueFalse( $strings['archive_use_images']['title'] ) )
                ->set_key( "${key}_archive_use_images" )
                ->set_name( 'archive_use_images' )
                ->set_default_value( true )
                ->use_ui()
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['archive_use_images']['instructions'] );

            $view_type_field = ( new Field\Radio( $strings['archive_view_type']['title'] ) )
                ->set_key( "${key}_archive_view_type" )
                ->set_name( 'archive_view_type' )
                ->set_choices( [
                    'grid' => 'Ruudukko',
                    'list' => 'Lista',
                ] )
                ->set_default_value( 'grid' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['archive_view_type']['instructions'] );

            $prorgam_post_per_page_field = ( new Field\Number( $strings['program_post_per_page']['title'] ) )
                ->set_key( "${key}_programs_per_page" )
                ->set_name( 'programs_per_page' )
                ->set_min( 1 )
                ->set_max( 30 )
                ->set_default_value( 20 )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['program_post_per_page']['instructions'] );    

            $this->add_fields( [
                $use_images_field,
                $view_type_field,
                $prorgam_post_per_page_field,
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
