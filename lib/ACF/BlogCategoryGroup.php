<?php
namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;

/**
 * Class BlogCategoryGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class BlogCategoryGroup {

    /**
     * BlogCategoryGroup constructor.
     */
    public function __construct() {
        \add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields for blog-category taxonomy.
     */
    protected function register_fields() : void {
        try {
            $field_group = ( new Group( 'Blogikategoria' ) )
                ->set_key( 'fg_blog_category_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'taxonomy', '==', 'blog-category' );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                \apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_image_field( $field_group->get_key() ),
                    ]
                )
            );

            $field_group = \apply_filters(
                'tms/acf/group/' . $field_group->get_key(),
                $field_group
            );

            $field_group->register();
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }
    }

    /**
     * Get image field.
     *
     * @param string $key Field group key.
     *
     * @return Field\Image
     * @throws Exception In case of invalid option.
     */
    protected function get_image_field( string $key ) : Field\Image {
        $strings = [
            'image' => [
                'title'        => \_x( 'Kuva', 'theme ACF', 'tms-theme-tredu' ),
                'instructions' => 'Kuvaa käytetään kategorian listaus-sivulla hero-kuvana',
            ],
        ];

        return ( new Field\Image( $strings['image']['title'] ) )
            ->set_key( "{$key}_image" )
            ->set_name( 'image' )
            ->set_instructions( $strings['image']['instructions'] );
    }
}

( new BlogCategoryGroup() );
