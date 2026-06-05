<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields\Settings;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Tab;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType\Page;

/**
 * Class SearchSettingsTab
 *
 * @package TMS\Theme\Tredu\ACF\Tab
 */
class SearchSettingsTab extends Tab {

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
        'tab'                 => 'Haku',
        'events_search_title' => [
            'title'        => 'Tapahtumahakulaatikon otsikko',
            'instructions' => '',
        ],
        'events_search_text'  => [
            'title'        => 'Tapahtumahakulaatikon teksti',
            'instructions' => '',
        ],
        'events_search_page'  => [
            'title'        => 'Tapahtumahaku-sivu',
            'instructions' => 'Sivu, jolle on valittu Tapahtumahaku-sivupohja.',
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
     *
     * @param string $key Field tab key.
     */
    public function sub_fields( $key ) {
        $strings = $this->strings;

        try {
            $event_search_title = ( new Field\Text( $strings['events_search_title']['title'] ) )
                ->set_key( "{$key}_events_search_title" )
                ->set_name( 'events_search_title' )
                ->set_instructions( $strings['events_search_title']['instructions'] );

            $event_search_text = ( new Field\Text( $strings['events_search_text']['title'] ) )
                ->set_key( "{$key}_events_search_text" )
                ->set_name( 'events_search_text' )
                ->set_instructions( $strings['events_search_text']['instructions'] );

            $events_search_page_field = ( new Field\PostObject( $strings['events_search_page']['title'] ) )
                ->set_key( "{$key}_events_search_page" )
                ->set_name( 'events_search_page' )
                ->set_post_types( [ 'page' ] )
                ->set_return_format( 'id' )
                ->set_instructions( $strings['events_search_page']['instructions'] );

            $this->add_fields( [
                $event_search_title,
                $event_search_text,
                $events_search_page_field,
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
