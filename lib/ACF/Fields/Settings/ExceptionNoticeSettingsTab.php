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
 * Class ExceptionNoticeSettingsTab
 *
 * @package TMS\Theme\Tredu\ACF\Tab
 */
class ExceptionNoticeSettingsTab extends Tab {

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
        'tab'  => 'Poikkeusilmoitus',
        'text' => [
            'title'        => 'Teksti',
            'instructions' => '',
        ],
        'cta_link' => [
            'title'        => 'Painikkeen url',
            'instructions' => '',
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
            $exception_text_field = ( new Field\Textarea( $strings['text']['title'] ) )
                ->set_key( "${key}_exception_text" )
                ->set_name( 'exception_text' )
                ->set_rows( 2 )
                ->set_wrapper_width( 50 )
                ->set_maxlength( 200 )
                ->set_instructions( $strings['text']['instructions'] );

            $exception_cta_link_field = ( new Field\URL( $strings['cta_link']['title'] ) )
                ->set_key( "${key}_exception_cta_link" )
                ->set_name( 'exception_cta_link' )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['cta_link']['instructions'] );

            $this->add_fields( [
                $exception_text_field,
                $exception_cta_link_field,
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
