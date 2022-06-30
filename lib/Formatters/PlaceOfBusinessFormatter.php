<?php

namespace TMS\Theme\Tredu\Formatters;

use TMS\Plugin\ContactImporter\PlaceOfBusinessApiController;
use TMS\Plugin\ContactImporter\PlaceOfBusinessFacade;

/**
 * Class PlaceOfBusinessFormatter
 *
 * @package TMS\Theme\Base\Formatters
 */
class PlaceOfBusinessFormatter implements \TMS\Theme\Tredu\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'PlaceOfBusiness';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/block/place-of-business/data',
            [ $this, 'format' ]
        );

        add_filter(
            'tms/acf/layout/place-of-business/data',
            [ $this, 'format' ]
        );
    }

    /**
     * Format view data
     *
     * @param array $data ACF data.
     *
     * @return array
     */
    public function format( array $data ) {
        if ( empty( $data['place_of_business'] ) ) {
            return $data;
        }

        $data['items'] = $this->map_api_results(
            $data['place_of_business'],
        );

        $data['column_class'] = 'is-12-mobile is-6-tablet';

        return $data;
    }

    /**
     * Map api place of businesses to post like arrays
     *
     * @param array $ids Array of API ID's.
     *
     * @return array|array[]
     */
    public function map_api_results( array $ids = [] ) : array {
        if ( empty( $ids ) ) {
            return [];
        }

        $results = ( new PlaceOfBusinessApiController() )->get_results();

        if ( empty( $results ) ) {
            return [];
        }

        $results = array_map(
            fn( $result ) => ( new PlaceOfBusinessFacade( $result ) )->to_array(),
            $results
        );

        return (array) array_filter( $results, function ( $result ) use ( $ids ) {
            return in_array( $result['id'], $ids, true );
        } );
    }
}
