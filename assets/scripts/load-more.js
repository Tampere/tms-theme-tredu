/* eslint-disable no-console */
/**
 * Load more functionality for koulutusnosto-component.
 */

// Use jQuery as $ within this file scope.
const $ = jQuery;

/**
 * Class LoadMore
 */
export default class LoadMore {

    cache() {

        this.loadMoreText = $( '#js-load-more-text' );
        this.loadMoreSpinner = $( '#js-load-more-spinner' );
        this.postsWrapper = $( '#js-article-list' );
        this.postsUl = $( '#archive-articles' );
        this.loadMoreButton = $( '#js-load-more' );
        this.loadMoreLoaded = window.s.load_more.load_more_loaded;
        this.foundPosts = this.postsWrapper.attr( 'data-found-posts' );
        this.postsPerPage = this.postsWrapper.attr( 'data-per-page' );

        // Component data variables for query args
        this.educationalBackground = this.postsWrapper.attr( 'data-educational-background' );
        this.location = this.postsWrapper.attr( 'data-location' );
        this.profession = this.postsWrapper.attr( 'data-profession' );
        this.programType = this.postsWrapper.attr( 'data-program-type' );
        this.applyMethod = this.postsWrapper.attr( 'data-apply-method' );
        this.applyStart = this.postsWrapper.attr( 'data-apply-start' );
    }

    events() {
        this.loadMoreButton.on( 'click', ( e ) => this.loadMore( e ) );
    }

    loadMore( e ) {
        e.preventDefault();

        // Show spinner and hide load more text.
        this.loadMoreText.addClass( 'is-hidden' );
        this.loadMoreSpinner.removeClass( 'is-hidden' );

        const currentSet = this.postsWrapper.attr( 'data-current-set' );
        const added = +currentSet + 1;
        const totalNumOfPosts = ( +added * +this.postsPerPage );

        // Load more with DustPress.js
        dp( 'Page/Programslist', {
            partial: 'load-more-programs',
            tidy: true,
            data: true,
            args: {
                ajax_params: {
                    currentSet: added,
                    postsPerPage: this.postsPerPage,
                    educational_background: this.educationalBackground,
                    location: this.location,
                    profession: this.profession,
                    programType: this.programType,
                    applyMethod: this.applyMethod,
                    applyStart: this.applyStart,

                },
            },
        } ).then( ( response ) => {

            // Hide spinner and show load more text.
            this.loadMoreText.removeClass( 'is-hidden' );
            this.loadMoreSpinner.addClass( 'is-hidden' );

            // Inform screen readers that there's more articles loaded.
            wp.a11y.speak( this.loadMoreLoaded, 'polite' );

            // Append posts to the wrapping element.
            this.postsUl.append( response.success );

            // Focus on the first new element
            this.postsUl.find( '.program-list__item' )
                .eq( ( totalNumOfPosts - this.postsPerPage ) )
                .find( 'a' ).focus();

            // Set new value to data-attribute data-current.
            this.postsWrapper.attr( 'data-current-set', added );

            // If found posts is less than total number of posts hide the load more button.
            if ( this.foundPosts <= totalNumOfPosts ) {
                this.loadMoreButton.hide();
            }
        } ).catch( ( error ) => {
            // eslint-disable-next-line no-console
            console.log( error );
        } );
    }

    /**
     * Run when the document is ready.
     */
    docReady() {
        this.cache();
        this.events();
    }
}
