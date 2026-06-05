/* eslint-disable no-console */
/**
 * Functionality of program search
 */

// Use jQuery as $ within this file scope.
const $ = jQuery; // eslint-disable-line no-unused-vars
import 'jquery-ui/ui/widgets/autocomplete';

export default class ProgramSearch {

    programSearch() {
        const form = document.querySelector( '#program-search' );
        const removeFilterBtns = document.querySelectorAll( '#program-search .remove-filter' );
        removeFilterBtns.forEach( ( button ) => {
            button.addEventListener( 'click', () => {
                button.querySelector( 'input[type="hidden"]' ).setAttribute( 'disabled', 'disabled' );
                form.submit();
            } );

        } );

    }

    autocompleteOnInput() {

        const wordList = JSON.parse( $( '#program-search-words' ).html() );
        const strings = window.s;
        const noResultsStr = strings.home.no_results;
        const resultsStr = strings.program.search.results;
        const resultStr = strings.program.search.result;
        const navWithKeyStr = strings.program.search.key_to_navigate;
        $( 'input[type="search"].program-search' )
            .autocomplete( {
                minLength: 3,
                source: wordList,
                classes: {
                    // eslint-disable-next-line max-len
                    'ui-autocomplete': 'has-background-white has-border-primary has-border-2 is-absolute is-unstyled p-3 has-text-weight-semibold',
                },
                messages: {
                    noResults: noResultsStr,
                    results: ( count ) => {
                        return count + ( count > 1 ? ' ' + resultsStr : ' ' + resultStr ) + ', ' + navWithKeyStr;
                    },
                },
            } );
    }

    docReady() {
        this.programSearch();
        this.autocompleteOnInput();
    }

}
