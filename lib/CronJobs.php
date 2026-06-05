<?php
/**
 * This class is used to handle WP cron jobs related events, such as
 * adding new jobs and modifying cron schedules.
 */

namespace TMS\Theme\Tredu;

/**
 *
 * Class for cron jobs to run.
 *
 * @package TMS\Theme\Tredu
 */
class CronJobs {

    /**
     * This initializes all tasks related to cron jobs.
     *
     * @return void
     */
    public static function init() : void {

        // Define custom cron interval schedules.
        \add_filter( 'cron_schedules', [ __CLASS__, 'modify_cron_schedules' ] );

        // Call custom cron job.
        static::cron_expirate_posts();
    }

    /**
     * Add a custom time interval to cron schedules.
     *
     * @return array Modified cron schedules.
     */
    public static function modify_cron_schedules() : array {

        $schedules['fifteen_minutes'] = [
            'interval' => 900,
            'display'  => 'Every 15 Minutes',
        ];

        return $schedules;
    }

    /**
     * This calls the expirate method of the Expirator class. It is used
     * to modify posts to draft with an ACF field that defines a scheduled removal.
     *
     * @return void
     */
    public static function cron_expirate_posts() : void {

        if ( \method_exists( '\TMS\Theme\Tredu\Expirator', 'expirate' ) ) {
            \add_action( 'cron_expirator', [ 'TMS\Theme\Tredu\Expirator', 'expirate' ] );
        }

        if ( ! wp_next_scheduled( 'cron_expirator' ) ) {
            $hour_from_now = strtotime( '+1 hour' );
            \wp_schedule_event( $hour_from_now, 'fifteen_minutes', 'cron_expirator' );
        }
    }
}
