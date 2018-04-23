/**
 * WP Primary Terms
 * http://pareshradadiya.github.io/wp-primary-terms
 *
 * Licensed under the GPLv2+ license.
 */
( function( window, document, $, undefined ) {
	'use strict';

	let setPrimaryButtonTamplate, reSetPrimaryButtonTamplate;

	class WPPrimaryTerms {
		/**
		 * Constructor
		 * @param taxonomy
		 */
		constructor( taxonomy ) {
			this.taxonomy = taxonomy;
		}

		/**
		 * Initializations
		 */
		init() {
			const isTaxonomyMetaBoxExists = this.isTaxonomyMetaBoxExists();
			// Go further only if page has a taxonomy meta-box
			if ( isTaxonomyMetaBoxExists ) {
				this.buildCache();
				this.render();
				this.bindEvents();
			}
		}

		/**
		 *  Build cache
		 */
		buildCache() {
			this.taxonomyMetaBox = document.getElementById( `taxonomy-${ this.taxonomy.name }` );
			if ( this.taxonomyMetaBox ) {
				this.$checkList = $( document.getElementById( `${ this.taxonomy.name }checklist` ) );
				this.termListItems = this.taxonomyMetaBox.querySelectorAll( '.categorychecklist li' );
				this.primaryInputUITemplate = wp.template( `wp-primary-${ this.taxonomy.name }-input` );
				return true;
			}
			return false;
		}

		/**
		 * Do render
		 */
		render() {
			this.taxonomyMetaBox.insertAdjacentHTML( 'beforeend', this.primaryInputUITemplate( this.taxonomy ) );
			this.primaryInput = document.getElementById( `_wp_primary_${ this.taxonomy.name }` );
			this.buildPrimaryTermsUI();
		}

		/**
		 * Event listeners
		 */
		bindEvents() {
			this.clickHandler = this.clickHandler.bind( this );
			this.taxonomyMetaBox.addEventListener( 'click', this.clickHandler );
			this.$checkList.on( 'wpListAddEnd', this.handleNewTermAdded.bind( this ) );
		}

		/**
		 * Add Set/Reset button in all category list items
		 */
		buildPrimaryTermsUI() {
			const primaryTermID = this.getPrimaryTerm();

			for ( const termListItem of this.termListItems ) {
				const catCheckBox = termListItem.querySelector( 'input[type=checkbox]' );

				// If current list item has primary term, add "Rest Primary" button
				if ( catCheckBox.value === primaryTermID ) {
					termListItem.classList.add( 'primary-term' );
					termListItem.firstElementChild.insertAdjacentHTML( 'afterend', reSetPrimaryButtonTamplate );
				} else {
					// Otherwise, add "Set Primary" button
					termListItem.firstElementChild.insertAdjacentHTML( 'afterend', setPrimaryButtonTamplate );
				}
			}
		}

		/**
		 * Click event handler
		 * @param  {Event} e The Click event
		 */
		clickHandler( e ) {
			// Only run if the target is in a taxonomy meta div
			if ( ! e.target ) {
				return;
			}

			if ( e.target.matches( 'input[type=checkbox]' ) ) {
				this.termCheckHandler( e );
			} else if ( e.target.matches( 'a.toggle-primary-term' ) ) {
				e.preventDefault();
				this.togglePrimaryTermHandler( e );
			}
		}

		/**
		 * Insert "Set/Rest Primary" button on lately added items
		 * @param e
		 * @param params
		 */
		handleNewTermAdded( e ) {
			e.target.firstElementChild.firstElementChild.insertAdjacentHTML( 'afterend', setPrimaryButtonTamplate );
		}

		/**
		 * Term checked event handler
		 * @param  {Event} e The Check event
		 */
		termCheckHandler( e ) {
			if ( e.target.parentNode.parentNode.classList.contains( 'primary-term' ) ) {
				this.resetPrimaryTermListItems();
				this.setPrimaryTerm();
			}
		}

		/**
		 * Set/Reset Primary button click handler
		 * @param  {Event} e The Click event
		 */
		togglePrimaryTermHandler( e ) {
			const
				termID = e.target.closest( 'li' ).id.match( /-(\d+)$/ )[ 1 ], // Fetch a term id from the li ID attribute
				listItems = this.taxonomyMetaBox.querySelectorAll( `#popular-${ this.taxonomy.name }-${ termID }, #${ this.taxonomy.name }-${ termID }` );

			// Set primary term
			if ( ! listItems[ 0 ].classList.contains( 'primary-term' ) ) {
				this.setPrimaryTermListItems( listItems );
				// Store term id into hidden input
				this.setPrimaryTerm( termID );
			} else {
				// Reset primary term
				this.resetPrimaryTermListItems();
				this.setPrimaryTerm();
			}
		}

		/**
		 * Set primary term list items
		 * @param termListItems
		 */
		setPrimaryTermListItems( termListItems ) {
			// Reset a previously set primary term, if any.
			this.resetPrimaryTermListItems();

			for ( const termListItem of termListItems ) {
				const primaryButtonWrap = termListItem.querySelector( 'span.primary-term-button' );
				termListItem.removeChild( primaryButtonWrap ); // Remove "Set Primary" button wrap
				// Insert "Reset Primary" button
				termListItem.firstElementChild.insertAdjacentHTML( 'afterend', reSetPrimaryButtonTamplate );
				termListItem.classList.add( 'primary-term' ); // Add 'primary-term' class to list item
			}
		}

		/**
		 * Reset primary term list terms
		 */
		resetPrimaryTermListItems() {
			const primaryTermListItems = this.taxonomyMetaBox.querySelectorAll( 'li.primary-term' );

			for ( const primaryTermListItem of primaryTermListItems ) {
				const primaryButtonWrap = primaryTermListItem.querySelector( 'span.primary-term-button' );

				primaryTermListItem.classList.remove( 'primary-term' ); // Remove primary-term class from LI
				primaryTermListItem.removeChild( primaryButtonWrap ); // Delete "Reset Primary" button wrap
				primaryTermListItem.firstElementChild.insertAdjacentHTML( 'afterend', setPrimaryButtonTamplate );
			}
		}

		/**
		 * Return the primary term id
		 */
		getPrimaryTerm() {
			return this.primaryInput.value;
		}

		/**
		 * Set a primary term id into hidden input
		 * @param termID
		 */
		setPrimaryTerm( termID = 0 ) {
			this.primaryInput.value = termID;
			if ( termID > 0 ) {
				// Check "All Categories" item
				document.getElementById( `in-${ this.taxonomy.name }-${ termID }` ).checked = true;
				// Check Most used item
				const popularTermckBox = document.getElementById( `in-popular-${ this.taxonomy.name }-${ termID }` );
				if ( popularTermckBox ) {
					popularTermckBox.checked = true;
				}
			}
		}

		/**
		 * Determines whether taxonomy meta-box exists or not on a current page
		 * @returns {boolean}
		 */
		isTaxonomyMetaBoxExists() {
			this.taxonomyMetaBox = document.getElementById( `taxonomy-${ this.taxonomy.name }` );
			return !! this.taxonomyMetaBox;
		}
	}

	// Kick it off
	window.onload = function() {
		if ( wpPrimaryTermsVars !== undefined && wpPrimaryTermsVars.length > 0 ) {
			const primaryButtonUITemplate = wp.template( 'wp-primary-term-button' );
			setPrimaryButtonTamplate = primaryButtonUITemplate( { isPrimary: false } );
			reSetPrimaryButtonTamplate = primaryButtonUITemplate( { isPrimary: true } );
			// Loop through each taxonomy and init WPPrimaryTerms class
			wpPrimaryTermsVars.map( taxonomy => new WPPrimaryTerms( taxonomy ).init() );
		}

	};
}( window, document, jQuery ) );
