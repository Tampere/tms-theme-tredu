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
 * Class PageSettingsTab
 *
 * @package TMS\Theme\Tredu\ACF\Tab
 */
class PageSettingsTab extends Tab {

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
        'tab'                        => 'Sisältösivut',
        'enable_sibling_navigation'  => [
            'title'        => 'Rinnakkaissivujen navigointi',
            'instructions' => 'Esitetään sivujen alasivuilla ennen alatunnistetta.',
        ],
        'sibling_navigation_heading' => [
            'title'        => 'Rinnakkaissivujen navigoinnin otsikko',
            'instructions' => 'Jos kenttä jätetään tyhjäksi, näytetään oletusteksti',
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
            $display_siblings = ( new Field\TrueFalse( $strings['enable_sibling_navigation']['title'] ) )
                ->set_key( "{$key}_enable_sibling_navigation" )
                ->set_name( 'enable_sibling_navigation' )
                ->set_default_value( true )
                ->use_ui()
                ->set_wrapper_width( 100 )
                ->set_instructions( $strings['enable_sibling_navigation']['instructions'] );

            $siblings_heading = ( new Field\Text( $strings['sibling_navigation_heading']['title'] ) )
                ->set_key( "{$key}_sibling_navigation_heading" )
                ->set_name( 'sibling_navigation_heading' )
                ->set_wrapper_width( 50 )
                ->set_default_value( '' )
                ->set_instructions( $strings['sibling_navigation_heading']['instructions'] );

            $this->add_fields( [
                $display_siblings,
                $siblings_heading,
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
