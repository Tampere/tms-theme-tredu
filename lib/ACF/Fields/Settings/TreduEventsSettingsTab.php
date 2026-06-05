<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields\Settings;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Tab;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;

/**
 * Class TreduEventsSettingsTab
 *
 * @package TMS\Theme\Tredu\ACF\Tab
 */
class TreduEventsSettingsTab extends Tab {

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
        'tab'               => 'Tredun tapahtumat',
        'tredu_events_page' => [
            'title'        => 'Tredun tapahtumasivu',
            'instructions' => 'Esitetään Tredun tapahtumien murupolussa.',
            // phpcs:ignore
        ],
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
     */
    public function sub_fields() {
        $strings = $this->strings;

        try {
            $program_page = ( new Field\PostObject( $strings['tredu_events_page']['title'] ) )
                ->set_key( 'tredu_events_page' )
                ->set_name( 'tredu_events_page' )
                ->set_post_types( [ PostType\Page::SLUG ] )
                ->set_return_format( 'id' )
                ->set_instructions( $strings['tredu_events_page']['instructions'] );

            $this->add_field( $program_page );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
