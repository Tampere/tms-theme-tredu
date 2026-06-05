<?php
/**
 * Define the single dial tredu.
 */

use TMS\Theme\Tredu\Traits\Components;
use TMS\Theme\Tredu\Settings;

/**
 * The SingleDialTredu class.
 */
class SingleDialTredu extends BaseModel {

    use Components;

    /**
     * Hero image
     *
     * @return array
     */
    public function content() : array {
        return [
            'title'   => get_the_title(),
            'ingress' => get_field( 'ingress' ),
            'icon'    => get_field( 'icon' ),
        ];
    }

    /**
     * Show a "go back to main page" link if it's set in theme settings
     *
     * @return array
     */
    public function go_back_link() : array {
        $dial_tredu_page = Settings::get_setting( 'dial_tredu_page' );

        $dial_tredu_link = [];

        if ( ! empty( $dial_tredu_page ) ) {
            $dial_tredu_link[] = [
                'permalink' => get_the_permalink( $dial_tredu_page ),
                'text'      => __( 'Go back', 'tms-theme-tredu' ),
                'icon'      => 'chevron-left',
            ];
        }

        return $dial_tredu_link;
    }

}
