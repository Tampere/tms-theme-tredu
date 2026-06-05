<?php
namespace TMS\Theme\Tredu\ACF\Layouts;

use Geniem\ACF\Exception;
use TMS\Theme\Tredu\ACF\Fields\SomeLinkListFields;
use TMS\Theme\Tredu\Logger;

/**
 * Class SomeLinkListLayout
 *
 * @package TMS\Theme\Tredu\ACF\Layouts
 */
class SomeLinkListLayout extends BaseLayout {

    /**
     * Layout key
     */
    const KEY = '_some_link_list';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( string $key ) {
        parent::__construct(
            'Some-linkkilista',
            $key . self::KEY,
            'some_link_list'
        );

        $this->add_layout_fields();
    }

    /**
     * Add layout fields
     *
     * @return void
     */
    private function add_layout_fields() : void {
        $fields = new SomeLinkListFields(
            $this->get_label(),
            $this->get_key(),
            $this->get_name()
        );

        try {
            $this->add_fields(
                $this->filter_layout_fields( $fields->get_fields(), $this->get_key(), self::KEY )
            );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
