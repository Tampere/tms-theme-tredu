<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu;

/**
 * Class ThemeController
 *
 * This class sets up the theme functionalities.
 *
 * @package TMS\Theme\Tredu
 */
class ThemeController {

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_classes();
    }

    /**
     * Run setup for theme functionality.
     *
     * @return void
     */
    protected function init_classes() : void {
        $classes = [
            ACFController::class,
            Admin::class,
            Assets::class,
            BlocksController::class,
            Cleanup::class,
            Constants::class,
            Cookiebot::class,
            DustPressController::class,
            Emojis::class,
            FormatterController::class,
            GravityForms::class,
            Images::class,
            LinkedEvents::class,
            Localization::class,
            NavigationController::class,
            PasswordPolicy::class,
            PostTypeController::class,
            Roles::class,
            Security::class,
            SiteController::class,
            TaxonomyController::class,
            ThemeSupports::class,
            Comments::class,
            ProgramSearch::class,
            EmbedController::class,
        ];

        $classes = apply_filters(
            'tms/theme/tredu/functionality/classes',
            $classes
        );

        // Loop through the classes and run hooks methods of all controllers.
        array_walk( $classes, function ( $class ) {
            $instance = new $class();

            if ( $instance instanceof Interfaces\Controller ) {
                $instance->hooks();
            }
        } );

        \Archive::hooks();
        \Home::hooks();
        \Search::hooks();
        \ArchiveBlogArticle::hooks();
        \TMS\Theme\Tredu\CronJobs::init();
    }
}
