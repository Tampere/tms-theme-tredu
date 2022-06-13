<?php
/**
 * Tredu model
 */


/**
 * Class BaseModel
 *
 * This abstract model automatically binds the default header
 * and footer sub models.
 */
abstract class BaseModel extends \DustPress\Model {

    /**
     * Constructor for DustPress BaseModel class.
     *
     * @param array $args   Model arguments.
     * @param mixed $parent Set model parent.
     */
    public function __construct( $args = [], $parent = null ) {
        parent::__construct( $args, $parent );

        if ( method_exists( $this, 'hooks' ) ) {
            $this->hooks();
        }
    }

    /**
     * This method is run automatically on page load.
     * It initializes the model and run all protected methods.
     *
     * @return void
     */
    public function init_model() : void {
        $this->add_classes_to_html();
    }

    /**
     * Bind the submodels
     *
     * @return void
     * @throws Exception If provided submodel name is not a string.
     */
    public function submodels() : void {
        $this->bind_sub( 'Header' );
        $this->bind_sub( 'Footer' );
        $this->bind_sub( 'Strings' );
        $this->bind_sub( 'Readspeaker' );
    }

    /**
     * This method adds the Class name as a CSS class to
     * the HTML tag in the markup, so that the JS code
     * is able to use the class when running page specific code.
     *
     * @return void
     */
    protected function add_classes_to_html() : void {
        $document_class = get_class( $this );

        add_filter( 'dustpress/data/wp', function ( $data ) use ( $document_class ) {
            $data['document_class'] = apply_filters( 'dustpress/document_class', [ $document_class ] );

            return $data;
        } );
    }
}
