/* eslint-disable no-console */
/**
 * Favorites-functionality for programs.
 */

// Use jQuery as $ within this file scope.
const $ = jQuery;

/**
 * Class FavoritePrograms
 */
export default class FavoritePrograms {
    /**
     * Execute on page load
     */
    docReady() {
        // Bail if browser does not support localStorage.
        if ( typeof Storage === 'undefined' ) {
            return;
        }

        this.cache();
        this.favorites = this.parseFavorites();

        if ( ! this.hasFavorites() ) {
            this.showNoResults();
        }

        // Init functionalities common to all templates.
        this.initCommon();

        // Init functionalities for favorite-listing.
        this.initFavorites();

        // Favorite popup functionalities
        this.favoritePopup();
    }

    /**
     * Cache selectors
     */
    cache() {
        this.classes = {
            hasFavorites: 'has-favorites',
            isFavorite: 'is-favorite',
            hide: 'is-hidden',
        };

        this.selectors = {
            button: '.js-toggle-favorite',
        };
        this.storageKey = 'tredu-program-favorites';
        this.closeFavorites = $( '#js-favorites-close' );
        this.openFavorites = $( '#js-favorites-open' );
        this.favoritesContainer = $( '#js-favorites-container' );
        this.favoritePrograms = $( '#js-favorite-programs' );
        this.favoritesContent = $( '#js-favorites-content' );
        this.favoritesNoResults = $( '#js-favorites-no-results' );
    }

    /**
     * Initialize functionalities common for all templates.
     */
    initCommon() {
        this.favoriteButtons = $( this.selectors.button );

        if ( this.favoriteButtons.length > 0 ) {
            this.populateFavorites();
        }
    }

    /**
     * Initialize functionalities for favorites listing.
     */
    initFavorites() {
        // Fetch favorites according to localStorage.
        if ( this.favorites.length !== 0 ) {
            dp( 'ProgramFavorites/FavoritePrograms', {
                partial: 'header-favorites-item',
                tidy: true,
                data: true,
                args: {
                    ids: this.favorites,
                },
            } ).then( ( response ) => {
                if ( ! response.success ) {
                    this.showNoResults();
                    return;
                }

                // Set favorites content.
                this.favoritePrograms.html( response.success );

                // Show the container.
                this.favoritePrograms.removeClass( this.classes.hide );
            } ).catch( ( error ) => {
                console.log( error );
                this.showNoResults();
            } );
        }

        // Update favorites when favorite-button clicked
        if ( this.favoritesContainer.length > 0 ) {
            this.favoriteButtons.on( 'click', ( e ) => {
                e.stopPropagation();
                this.toggleFavorite( e.currentTarget );
                this.loadFavorites();
            } );

            $( document ).on( 'click', '.js-remove-favorite', ( e ) => {
                e.stopPropagation();
                this.removeFavorite( e.currentTarget );
                this.loadFavorites();
            } );
        }
    }

    /**
     * Update favorite popup button attributes.
     */
    favoritePopup() {
        this.closeFavorites.on( 'click', ( e ) => {
            e.stopPropagation();
            this.openFavorites.attr( 'aria-expanded', 'false' );
            this.openFavorites.removeClass( 'is-active' );
        } );
    }

    /**
     * Parse favorite programs from localStorage.
     *
     * @return {Array} The localStorage values or an empty array.
     */
    parseFavorites() {
        const favorites = localStorage.getItem( this.storageKey );

        if ( ! favorites ) {
            return [];
        }

        return JSON.parse( favorites );
    }

    /**
     * Save passed favorites to localStorage.
     *
     * @param {Array} favorites The favorites as array
     */
    saveFavorites( favorites = [] ) {
        localStorage.setItem( this.storageKey, JSON.stringify( favorites ) );
    }

    /**
     * Populate favorites from localStorage.
     *
     * @return {void}
     */
    populateFavorites() {
        // Loop over the favorite buttons and update their favorite-status.
        for ( const item of this.favoriteButtons ) {
            const programId = $( item ).data( 'program-id' );

            if ( this.favorites.includes( programId ) ) {
                $( item ).addClass( this.classes.isFavorite );
                $( item ).attr( 'aria-pressed', 'true' );
                $( item ).find( '.text-favorite-active' ).removeClass( this.classes.hide );
                $( item ).find( '.text-favorite-inactive' ).addClass( this.classes.hide );
            }
        }
    }

    /**
     * Check if there are favorites in the localStorage.
     *
     * @return {boolean} True if favorites exist.
     */
    hasFavorites() {
        return this.favorites.length > 0;
    }

    /**
     * Changes favorite-status of the selected favorite.
     *
     * @param {Element} selectedItem The button element to be toggled.
     * @return {void}
     */
    toggleFavorite( selectedItem ) {
        const programId = $( selectedItem ).data( 'program-id' );

        // Add or remove program ID from localStorage.
        if ( this.favorites.includes( programId ) ) {
            this.favorites = this.favorites.filter( ( fav ) => fav !== programId );
            $( selectedItem ).removeClass( this.classes.isFavorite );
            $( selectedItem ).find( '.text-favorite-active' ).addClass( this.classes.hide );
            $( selectedItem ).find( '.text-favorite-inactive' ).removeClass( this.classes.hide );
            $( selectedItem ).attr( 'aria-pressed', 'false' );
        }
        else {
            this.favorites.push( programId );
            $( selectedItem ).addClass( this.classes.isFavorite );
            $( selectedItem ).find( '.text-favorite-active' ).removeClass( this.classes.hide );
            $( selectedItem ).find( '.text-favorite-inactive' ).addClass( this.classes.hide );
            $( selectedItem ).attr( 'aria-pressed', 'true' );
        }

        // Save to localStorage.
        this.saveFavorites( this.favorites );
    }

    /**
     * Remove favorite.
     *
     * @param {Element} selectedItem The button element to be toggled.
     * @return {void}
     */
    removeFavorite( selectedItem ) {
        const programId = $( selectedItem ).data( 'program-id' );

        // Remove program ID from localStorage.
        if ( this.favorites.includes( programId ) ) {
            this.favorites = this.favorites.filter( ( fav ) => fav !== programId );
            this.toggleFavoriteButton = $( '.js-toggle-favorite[data-program-id="' + programId + '"]' );

            // Remove toggle-buttons attributes and texts
            if ( this.toggleFavoriteButton.length > 0 ) {
                this.toggleFavoriteButton.removeClass( this.classes.isFavorite );
                this.toggleFavoriteButton.attr( 'aria-pressed', 'false' );
                this.toggleFavoriteButton.find( '.text-favorite-active' ).addClass( this.classes.hide );
                this.toggleFavoriteButton.find( '.text-favorite-inactive' ).removeClass( this.classes.hide );
            }
        }

        // Save to localStorage.
        this.saveFavorites( this.favorites );
    }

    /**
     * Show "no results" content.
     */
    showNoResults() {
        this.favoritesContent.addClass( this.classes.hide );
        this.favoritesNoResults.removeClass( this.classes.hide );
    }

    /**
     * Hide "no results" content.
     */
    hideNoResults() {
        this.favoritesContent.removeClass( this.classes.hide );
        this.favoritesNoResults.addClass( this.classes.hide );
    }

    /**
     * Load and show the favorite programs.
     *
     * @return {void}
     */
    loadFavorites() {
        // Fetch favorites according to localStorage.
        dp( 'ProgramFavorites/FavoritePrograms', {
            partial: 'header-favorites-item',
            tidy: true,
            data: true,
            args: {
                ids: this.favorites,
            },
        } ).then( ( response ) => {
            if ( ! response.success ) {
                this.showNoResults();
            }
            else {
                this.hideNoResults();
            }

            // Set favorites content.
            this.favoritePrograms.html( response.success );

            // Refresh favorite buttons and add event listeners.
            this.initCommon();

            // Show the container.
            this.favoritePrograms.removeClass( this.classes.hide );
        } ).catch( () => {
            this.showNoResults();
        } );
    }

}
