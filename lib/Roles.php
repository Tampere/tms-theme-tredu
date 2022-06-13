<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu;

use TMS\Theme\Tredu\Interfaces\Controller;

/**
 * Class Roles
 *
 * @package TMS\Theme\Tredu
 */
class Roles implements Controller {

    /**
     * Remove these capabilities from all Roles.
     *
     * @var array
     */
    private $remove_from_all = [
        'edit_themes',
        'edit_files',
        'update_plugins',
        'install_plugins',
        'update_themes',
        'install_themes',
        'delete_themes',
        'update_core',
        'customize',
    ];

    /**
     * Default Post type 'Post' capabilities.
     *
     * @var string[]
     */
    private $posts_all_capabilities = [
        'edit_post',
        'read_post',
        'delete_post',
        'edit_posts',
    ];

    /**
     * Pages / page (default page type)
     *
     * @var string[]
     */
    private $pages_all_capabilities = [
        'edit_page',
        'read_page',
        'delete_page',
        'edit_pages',
        'edit_others_pages',
        'delete_pages',
        'publish_pages',
        'read_private_pages',
        'read',
        'delete_private_pages',
        'delete_published_pages',
        'delete_others_pages',
        'edit_private_pages',
        'edit_published_pages',
        'edit_pages',
    ];

    /**
     * Materials plugin / material-cpt.
     *
     * @var string[]
     */
    private $materials_all_capabilities = [
        'delete_material',
        'delete_materials',
        'delete_others_materials',
        'delete_private_materials',
        'delete_published_materials',
        'edit_material',
        'edit_materials',
        'edit_others_materials',
        'edit_private_materials',
        'edit_published_materials',
        'publish_materials',
        'read',
        'read_material',
        'read_private_materials',
    ];

    /**
     * Site settings / settings-cpt.
     *
     * @var array
     */
    private $site_settings_all_capabilities = [
        'edit_site_setting',
        'read_site_setting',
        'delete_site_setting',
        'edit_others_site_settings',
        'delete_site_settings',
        'publish_site_settings',
        'read_private_site_settings',
        'delete_private_site_settings',
        'delete_published_site_settings',
        'delete_others_site_settings',
        'edit_private_site_settings',
        'edit_published_site_settings',
        'edit_site_settings',
    ];

    /**
     * Contact / contact-cpt.
     *
     * @var array
     */
    private $contact_all_capabilities = [
        'edit_contact',
        'read_contact',
        'delete_contact',
        'edit_others_contacts',
        'delete_contacts',
        'publish_contacts',
        'read_private_contacts',
        'delete_private_contacts',
        'delete_published_contacts',
        'delete_others_contacts',
        'edit_private_contacts',
        'edit_published_contacts',
        'edit_contacts',
    ];

    /**
     * Tredu Event / tredu-event-cpt.
     *
     * @var array
     */
    private $tredu_event_all_capabilities = [
        'edit_tredu_event',
        'read_tredu_event',
        'delete_tredu_event',
        'edit_others_tredu_events',
        'delete_tredu_events',
        'publish_tredu_events',
        'read_private_tredu_events',
        'delete_private_tredu_events',
        'delete_published_tredu_events',
        'delete_others_tredu_events',
        'edit_private_tredu_events',
        'edit_published_tredu_events',
        'edit_tredu_events',
    ];

    /**
     * Program / program-cpt
     *
     * @var array
     */
    private $program_all_capabilities = [
        'edit_program',
        'read_program',
        'delete_program',
        'edit_others_programs',
        'delete_programs',
        'publish_programs',
        'read_private_programs',
        'delete_private_programs',
        'delete_published_programs',
        'delete_others_programs',
        'edit_private_programs',
        'edit_published_programs',
        'edit_programs',
    ];

    /**
     * Project / project-cpt
     *
     * @var array
     */
    private $project_all_capabilities = [
        'edit_project',
        'read_project',
        'delete_project',
        'edit_others_projects',
        'delete_projects',
        'publish_projects',
        'read_private_projects',
        'delete_private_projects',
        'delete_published_projects',
        'delete_others_projects',
        'edit_private_projects',
        'edit_published_projects',
        'edit_projects',
    ];

    /**
     * Base taxonomy capabilities, only for admins.
     *
     * @var string[]
     */
    private $taxonomy_category_all_capabilities = [
        'manage_categories',
        'edit_categories',
        'delete_categories',
        'assign_categories',
    ];

    /**
     * Base post tag capabilities, only for admins.
     *
     * @var string[]
     */
    private $taxonomy_post_tag_all_capabilities = [
        'manage_post_tags',
        'edit_post_tags',
        'delete_post_tags',
        'assign_post_tags',
    ];

    /**
     * Material Type taxonomy
     *
     * @var string[]
     */
    private $taxonomy_material_type_all_capabilities = [
        'manage_material_types',
        'edit_material_types',
        'delete_material_types',
        'assign_material_types',
    ];

    /**
     * Delivery Method taxonomy
     *
     * @var string[]
     */
    private $taxonomy_delivery_method_all_capabilities = [
        'manage_delivery_methods',
        'edit_delivery_methods',
        'delete_delivery_methods',
        'assign_delivery_methods',
    ];

    /**
     * Apply Method taxonomy
     *
     * @var string[]
     */
    private $taxonomy_apply_method_all_capabilities = [
        'manage_apply_methods',
        'edit_apply_methods',
        'delete_apply_methods',
        'assign_apply_methods',
    ];

    /**
     * Educational Background taxonomy
     *
     * @var string[]
     */
    private $taxonomy_educational_background_all_capabilities = [
        'manage_educational_backgrounds',
        'edit_educational_backgrounds',
        'delete_educational_backgrounds',
        'assign_educational_backgrounds',
    ];

    /**
     * Location taxonomy
     *
     * @var string[]
     */
    private $taxonomy_location_all_capabilities = [
        'manage_locations',
        'edit_locations',
        'delete_locations',
        'assign_locations',
    ];

    /**
     * Portfolio taxonomy
     *
     * @var string[]
     */
    private $taxonomy_portfolio_all_capabilities = [
        'manage_portfolios',
        'edit_portfolios',
        'delete_portfolios',
        'assign_portfolios',
    ];

    /**
     * Profession taxonomy
     *
     * @var string[]
     */
    private $taxonomy_profession_all_capabilities = [
        'manage_professions',
        'edit_professions',
        'delete_professions',
        'assign_professions',
    ];

    /**
     * ProgramType taxonomy
     *
     * @var string[]
     */
    private $taxonomy_program_type_all_capabilities = [
        'manage_program_types',
        'edit_program_types',
        'delete_program_types',
        'assign_program_types',
    ];

    /**
     * Tablepress capabilities
     *
     * @var string[]
     */
    private $tablepress_all_capabilities = [
        'tablepress_edit_tables',
        'tablepress_delete_tables',
        'tablepress_list_tables',
        'tablepress_add_tables',
        'tablepress_copy_tables',
        'tablepress_import_tables',
        'tablepress_export_tables',
        'tablepress_access_options_screen',
        'tablepress_access_about_screen',
    ];

    /**
     * Gravity Forms suppressed capabilities
     *
     * @var array|string[]
     */
    private array $gravity_forms_suppressed_capabilities = [
        'gravityforms_create_form',
        'gravityforms_delete_forms',
        'gravityforms_edit_forms',
        'gravityforms_preview_forms',
        'gravityforms_view_entries',
        'gravityforms_edit_entries',
        'gravityforms_delete_entries',
        'gravityforms_view_entry_notes',
        'gravityforms_edit_entry_notes',
        'gravityforms_export_entries',
    ];

    /**
     * Hooks
     */
    public function hooks() : void {
        $this->modify_administrator_caps();

        add_filter(
            'map_meta_cap',
            \Closure::fromCallable( [ $this, 'add_unfiltered_html_capability' ] ),
            1,
            3
        );

        // If wp-geniem-roles is active.
        if ( class_exists( '\Geniem\Roles' ) ) {
            // Run Geniem roles functions here.
            $this->modify_admin_caps(); // These modifications are automatically added to superadmin.
            $this->modify_superadmin_caps();
            $this->modify_editor_caps();
            $this->modify_author_caps();
            $this->modify_contributor_caps();
            $this->modify_subscriber_caps();
        }

        add_filter( 'editable_roles', function ( $all_roles ) {
            // If you are not a super_administrator, you can't promote people to that level.
            if (
                array_key_exists( 'super_administrator', $all_roles ) &&
                ! user_can( get_current_user_id(), 'super_administrator' )
            ) {
                unset( $all_roles['super_administrator'] );
            }

            return $all_roles;
        }, 10, 1 );
    }

    /**
     * Modify 'administrator' capabilities
     */
    public function modify_administrator_caps() : void {
        $admin_rights = [
            // Site settings
            'edit_site_setting'              => true,
            'read_site_setting'              => true,
            'delete_site_setting'            => true,
            'edit_others_site_settings'      => true,
            'delete_site_settings'           => true,
            'publish_site_settings'          => true,
            'read_private_site_settings'     => true,
            'delete_private_site_settings'   => true,
            'delete_published_site_settings' => true,
            'delete_others_site_settings'    => true,
            'edit_private_site_settings'     => true,
            'edit_published_site_settings'   => true,
            'edit_site_settings'             => true,

            // Materials
            'edit_material'                  => true,
            'read_material'                  => true,
            'delete_material'                => true,
            'edit_others_materials'          => true,
            'delete_materials'               => true,
            'publish_materials'              => true,
            'read_private_materials'         => true,
            'delete_private_materials'       => true,
            'delete_published_materials'     => true,
            'delete_others_materials'        => true,
            'edit_private_materials'         => true,
            'edit_published_materials'       => true,
            'edit_materials'                 => true,

            // Material Type taxonomy
            'manage_material_types'          => true,
            'edit_material_types'            => true,
            'delete_material_types'          => true,
            'assign_material_types'          => true,

            // Common
            'unfiltered_html'                => true,
            'edit_theme_options'             => true, // Navigation changes
        ];

        $admin = get_role( 'administrator' );

        if ( empty( $admin ) ) {
            return;
        }

        foreach ( $admin_rights as $cap => $grant ) {
            $admin->add_cap( $cap, $grant );
        }

        unset( $admin );
    }

    /**
     * Create and Modify Super Admin Name and Capabilities
     * Also known as: Verkon Pääkäyttäjä.
     */
    private function modify_superadmin_caps() : void {
        /**
         * Administrator role.
         *
         * @var \Geniem\Role|null $admin
         */
        $admin = \Geniem\Roles::get( 'administrator' );

        if ( ! ( $admin instanceof \Geniem\Role ) ) {
            return;
        }

        // Create or get if already created.
        $role = \Geniem\Roles::create(
            'super_administrator',
            _x( 'Super Administrator', 'wp-geniem-roles', 'tms-theme-tredu' ),
            $admin->capabilities ?? []
        );

        $role->add_caps( [
            'add_users',
            'edit_user',
            'promote_user',
            'create_sites',
            'create_users',
            'modify_users',
            'delete_sites',
            'edit_network_users',
            'manage_network',
            'manage_network_options',
            'manage_network_plugins',
            'manage_network_themes',
            'manage_network_users',
            'manage_sites',
            'edit_theme_options', // Navigation changes
            'view_stream', // Plugin: Stream
        ] );

        // Post types
        $role->add_caps( $this->posts_all_capabilities );
        $role->add_caps( $this->pages_all_capabilities );
        $role->add_caps( $this->site_settings_all_capabilities );
        $role->add_caps( $this->materials_all_capabilities );
        $role->add_caps( $this->contact_all_capabilities );
        $role->add_caps( $this->program_all_capabilities );
        $role->add_caps( $this->project_all_capabilities );
        $role->add_caps( $this->tredu_event_all_capabilities );

        // Taxonomies
        $role->add_caps( $this->taxonomy_category_all_capabilities );
        $role->add_caps( $this->taxonomy_post_tag_all_capabilities );
        $role->add_caps( $this->taxonomy_material_type_all_capabilities );
        $role->add_caps( $this->taxonomy_delivery_method_all_capabilities );
        $role->add_caps( $this->taxonomy_apply_method_all_capabilities );
        $role->add_caps( $this->taxonomy_educational_background_all_capabilities );
        $role->add_caps( $this->taxonomy_location_all_capabilities );
        $role->add_caps( $this->taxonomy_portfolio_all_capabilities );
        $role->add_caps( $this->taxonomy_profession_all_capabilities );
        $role->add_caps( $this->taxonomy_program_type_all_capabilities );

        $role->remove_caps( $this->remove_from_all );

        apply_filters( 'tms/roles/super_administrator', $role );
    }

    /**
     * Modify Admin Name and Capabilities
     * Also known as: Pääkäyttäjä.
     */
    private function modify_admin_caps() : void {
        /**
         * Administrator role.
         *
         * @var \Geniem\Role|null $role
         */
        $role = \Geniem\Roles::get( 'administrator' );

        if ( ! ( $role instanceof \Geniem\Role ) ) {
            return;
        }

        // Post types
        $role->add_caps( $this->posts_all_capabilities );
        $role->add_caps( $this->pages_all_capabilities );
        $role->add_caps( $this->site_settings_all_capabilities );
        $role->add_caps( $this->contact_all_capabilities );
        $role->add_caps( $this->program_all_capabilities );
        $role->add_caps( $this->tredu_event_all_capabilities );
        $role->add_caps( $this->project_all_capabilities );

        // Taxonomies
        $role->add_caps( $this->taxonomy_category_all_capabilities );
        $role->add_caps( $this->taxonomy_post_tag_all_capabilities );
        $role->add_caps( $this->taxonomy_material_type_all_capabilities );
        $role->add_caps( $this->taxonomy_delivery_method_all_capabilities );
        $role->add_caps( $this->taxonomy_apply_method_all_capabilities );
        $role->add_caps( $this->taxonomy_educational_background_all_capabilities );
        $role->add_caps( $this->taxonomy_location_all_capabilities );
        $role->add_caps( $this->taxonomy_portfolio_all_capabilities );
        $role->add_caps( $this->taxonomy_profession_all_capabilities );
        $role->add_caps( $this->taxonomy_program_type_all_capabilities );

        // Other
        $role->add_caps( [
            'add_users',
            'edit_user',
            'list_users',
            'edit_users',
            'modify_users',
            'manage_network_users',
            'promote_user',
        ] );

        $role->add_caps( $this->tablepress_all_capabilities );

        $role->remove_caps( $this->remove_from_all );

        // Remove administration pages
        $role->remove_menu_pages( [
            'customize.php',
            'themes.php' => [
                'themes.php',
                'customize.php',
            ],
        ] );

        $role = apply_filters( 'tms/roles/admin', $role );
        $role->rename( _x( 'Administrator', 'wp-geniem-roles', 'tms-theme-tredu' ) );
    }

    /**
     * Modify Editor Name and Capabilities.
     * Also known as: Site Manager / Sivustovastaava.
     */
    private function modify_editor_caps() : void {
        /**
         * Editor role.
         *
         * @var \Geniem\Role|null $role
         */
        $role = \Geniem\Roles::get( 'editor' );

        if ( ! ( $role instanceof \Geniem\Role ) ) {
            return;
        }

        // Post types
        $role->add_caps( $this->posts_all_capabilities );
        $role->add_caps( $this->pages_all_capabilities );
        $role->add_caps( $this->materials_all_capabilities );
        $role->add_caps( $this->site_settings_all_capabilities );
        $role->add_caps( $this->contact_all_capabilities );
        $role->add_caps( $this->program_all_capabilities );
        $role->add_caps( $this->tredu_event_all_capabilities );
        $role->add_caps( $this->project_all_capabilities );

        // Taxonomies
        $role->add_caps( $this->taxonomy_category_all_capabilities );
        $role->add_caps( $this->taxonomy_post_tag_all_capabilities );
        $role->add_caps( $this->taxonomy_material_type_all_capabilities );
        $role->add_caps( $this->taxonomy_delivery_method_all_capabilities );
        $role->add_caps( $this->taxonomy_apply_method_all_capabilities );
        $role->add_caps( $this->taxonomy_educational_background_all_capabilities );
        $role->add_caps( $this->taxonomy_location_all_capabilities );
        $role->add_caps( $this->taxonomy_portfolio_all_capabilities );
        $role->add_caps( $this->taxonomy_profession_all_capabilities );
        $role->add_caps( $this->taxonomy_program_type_all_capabilities );

        // Other
        $role->add_caps( [
            'edit_theme_options', // Navigation changes
            'copy_posts',
        ] );

        $role->add_caps( $this->gravity_forms_suppressed_capabilities );

        $role->remove_caps( $this->remove_from_all );

        // Remove administration pages
        $role->remove_menu_pages( [
            'customize.php',
            'themes.php' => [
                'themes.php',
                'customize.php',
            ],
        ] );

        $role->add_caps( $this->tablepress_all_capabilities );

        $role = apply_filters( 'tms/roles/editor', $role );
        $role->rename( _x( 'Site Manager', 'wp-geniem-roles', 'tms-theme-tredu' ) );
    }

    /**
     * Modify Author Name and Capabilities.
     * Also known as: Toimittaja.
     */
    private function modify_author_caps() : void {
        /**
         * Author role.
         *
         * @var \Geniem\Role|null $role
         */
        $role = \Geniem\Roles::get( 'author' );

        if ( ! ( $role instanceof \Geniem\Role ) ) {
            return;
        }

        // Post types
        $role->add_caps( [ 'edit_others_posts', 'publish_posts', 'read_private_posts' ] );
        $role->add_caps( $this->pages_all_capabilities );
        $role->add_caps( $this->materials_all_capabilities );
        $role->add_caps( $this->contact_all_capabilities );
        $role->add_caps( $this->program_all_capabilities );
        $role->add_caps( $this->tredu_event_all_capabilities );
        $role->add_caps( $this->project_all_capabilities );

        // Taxonomies
        $role->add_caps( [
            'assign_categories',
            'assign_post_tags',
            'assign_material_types',
            'assign_delivery_methods',
            'assign_apply_methods',
            'assign_educational_backgrounds',
            'assign_locations',
            'assign_portfolios',
            'assign_professions',
            'assign_program_types',
        ] );

        // Other
        $role->add_caps( [ 'edit_theme_options', 'unfiltered_html', 'copy_posts' ] );

        // Remove administration pages
        $role->remove_menu_pages( [
            'customize.php',
            'themes.php' => [
                'themes.php',
                'customize.php',
            ],
        ] );

        $role->remove_caps( $this->remove_from_all );

        $role->add_caps( $this->tablepress_all_capabilities );

        $role = apply_filters( 'tms/roles/author', $role );
        $role->rename( _x( 'Author', 'wp-geniem-roles', 'tms-theme-tredu' ) );
    }

    /**
     * Modify Contributor Name and Capabilities.
     * Also known as: Avustaja.
     */
    private function modify_contributor_caps() : void {
        /**
         * Contributor role.
         *
         * @var \Geniem\Role|null $role
         */
        $role = \Geniem\Roles::get( 'contributor' );

        if ( ! ( $role instanceof \Geniem\Role ) ) {
            return;
        }

        // Materials plugin / material-cpt
        $role->add_caps( [
            'delete_material',
            'delete_materials',
            'edit_material',
            'edit_materials',
            'read',
            'read_material',
            'read_private_materials',
        ] );

        $role->add_caps( [
            'edit_page',
            'read_page',
            'delete_page',
            'edit_pages',
            'edit_others_pages',
            'read_private_pages',
            'read',
            'delete_private_pages',
            'delete_published_pages',
            'delete_others_pages',
            'edit_private_pages',
            'edit_published_pages',
            'edit_pages',
        ] );

        $role->add_caps( [
            'edit_contact',
            'read_contact',
            'delete_contact',
            'delete_contacts',
            'read_private_contacts',
            'delete_private_contacts',
            'delete_published_contacts',
            'edit_private_contacts',
            'edit_published_contacts',
            'edit_contacts',
        ] );

        $role->add_caps( [
            'edit_project',
            'read_project',
            'delete_project',
            'delete_projects',
            'read_private_projects',
            'delete_private_projects',
            'delete_published_projects',
            'edit_private_projects',
            'edit_published_projects',
            'edit_projects',
        ] );

        $role->add_caps( [
            'edit_tredu_event',
            'read_tredu_event',
            'delete_tredu_event',
            'delete_tredu_events',
            'read_private_tredu_events',
            'delete_private_tredu_events',
            'delete_published_tredu_events',
            'edit_private_tredu_events',
            'edit_published_tredu_events',
            'edit_tredu_events',
        ] );

        $role->add_caps( [
            'edit_program',
            'read_program',
            'delete_program',
            'delete_programs',
            'read_private_programs',
            'delete_private_programs',
            'delete_published_programs',
            'edit_private_programs',
            'edit_published_programs',
            'edit_programs',
        ] );

        // Taxonomies
        $role->add_caps( [
            'assign_categories',
            'assign_post_tags',
            'assign_material_types',
            'assign_delivery_methods',
            'assign_apply_methods',
            'assign_educational_backgrounds',
            'assign_locations',
            'assign_portfolios',
            'assign_professions',
            'assign_program_types',
        ] );

        // Other
        $role->add_caps( [ 'unfiltered_html' ] );

        $role->remove_caps( $this->remove_from_all );

        $role = apply_filters( 'tms/roles/contributor', $role );
        $role->rename( _x( 'Contributor', 'wp-geniem-roles', 'tms-theme-tredu' ) );
    }

    /**
     * Modify Subscriber Name and Capabilities.
     * Also known as: Tilaaja.
     */
    private function modify_subscriber_caps() : void {
        /**
         * Subscriber role.
         *
         * @var \Geniem\Role|null $role
         */
        $role = \Geniem\Roles::get( 'subscriber' );

        if ( ! ( $role instanceof \Geniem\Role ) ) {
            return;
        }

        $role = apply_filters( 'tms/roles/subscriber', $role );
        $role->rename( _x( 'Subscriber', 'wp-geniem-roles', 'tms-theme-tredu' ) );
    }

    /**
     * Enable unfiltered_html capability for Editors and Administrators.
     *
     * @param array  $caps    The user's capabilities.
     * @param string $cap     Capability name.
     * @param int    $user_id The user ID.
     *
     * @return array  $caps    The user's capabilities, with 'unfiltered_html' potentially added.
     */
    protected function add_unfiltered_html_capability( $caps, $cap, $user_id ) : array {
        if (
            $cap === 'unfiltered_html' &&
            ( user_can( $user_id, 'administrator' ) ||
              user_can( $user_id, 'editor' ) ||
              user_can( $user_id, 'author' ) ||
              user_can( $user_id, 'contributor' ) )
        ) {
            $caps = [ 'unfiltered_html' ];
        }

        return $caps;
    }
}
