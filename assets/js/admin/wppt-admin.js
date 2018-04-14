/**
 * WP Primary Category
 * http://pareshradadiya.github.io/wp-primary-category
 *
 * Licensed under the GPLv2+ license.
 */

;( function( window, document, $, undefined ) {
	'use strict';

	let primaryButtonUITemplate;

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
			this.buildCache();
			this.render();
			this.bindEvents();
		}

		/**
		 *  Build cache
		 */
		buildCache() {
			this.categoryDiv = document.getElementById( `taxonomy-${this.taxonomy.name}` );
			this.$checkList = $( document.getElementById(`${this.taxonomy.name}checklist`) );
			this.termListItems  = this.categoryDiv.querySelectorAll( `.${this.taxonomy.name}checklist li` );
			this.primaryInputUITemplate = wp.template( `wpt-primary-${this.taxonomy.name}-input` );
			this.setPrimaryButtonUI = primaryButtonUITemplate({ isPrimary: false });
			this.unSetPrimaryButtonUI = primaryButtonUITemplate({ isPrimary: true });
		}

		/**
		 * Do render
		 */
		render() {
			this.categoryDiv.insertAdjacentHTML( 'beforeend', this.primaryInputUITemplate(this.taxonomy) );
			this.primaryInput = document.getElementById( `_wp_primary_${this.taxonomy.name}` );
			this.buildPrimaryTermsUI();
		}

		/**
		 * Event listeners
		 */
		bindEvents() {
			this.clickHandler = this.clickHandler.bind(this);
			this.categoryDiv.addEventListener( 'click', this.clickHandler );
			this.$checkList.on( 'wpListAddEnd', this.handleNewTermAdded.bind(this) );
		}

		/**
		 * Add Set/Reset button in all category list items
		 */
		buildPrimaryTermsUI() {
			let primaryTermID = this.getPrimaryTerm();

			for ( let termListItem of this.termListItems ) {
				let catCheckBox = termListItem.querySelector('input[type=checkbox]');

				// If current list item has primary term, add "Rest Primary" button
				if ( catCheckBox.value === primaryTermID ) {
					termListItem.classList.add('primary-term');
					termListItem.firstElementChild.insertAdjacentHTML( 'afterend', this.unSetPrimaryButtonUI );
				} else {
					// Otherwise, add "Set Primary" button
					termListItem.firstElementChild.insertAdjacentHTML( 'afterend', this.setPrimaryButtonUI );
				}
			}
		}

		/**
		 * Click event handler
		 * @param  {Event} e The Click event
		 */
		clickHandler(e) {

			// Only run if the target is in a category div
			if ( ! e.target ) return;

			if ( e.target.matches('input[type=checkbox]') ) {
				this.termCheckHandler(e);
			} else if ( e.target.matches('a.toggle-primary-term') ) {
				e.preventDefault();
				this.togglePrimaryTermHandler(e)
			}
		}

		/**
		 * Insert "Set/Rest Primary" button on lately added items
		 * @param e
		 * @param params
		 */
		handleNewTermAdded( e, params ) {
			e.target.firstElementChild.firstElementChild.insertAdjacentHTML( 'afterend', this.setPrimaryButtonUI );
		}

		/**
		 * Term checked event handler
		 * @param  {Event} e The Check event
		 */
		termCheckHandler(e) {
			if ( e.target.parentNode.parentNode.classList.contains('primary-term') ) {
				this.resetPrimaryTermListItems();
				this.setPrimaryTerm();
			}
		}

		/**
		 * Set/Reset Primary button click handler
		 * @param  {Event} e The Click event
		 */
		togglePrimaryTermHandler(e) {

			let
				termID       = e.target.closest('li').id.match(/-(\d+)$/)[1], // Fetch a term id from the li ID attribute
				listItems   = this.categoryDiv.querySelectorAll(`#popular-${this.taxonomy.name}-${termID}, #${this.taxonomy.name}-${termID}`);

			// Set primary term
			if ( ! listItems[0].classList.contains('primary-term') ) {
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

			for ( let termListItem of termListItems ) {
				let primaryButtonWrap = termListItem.querySelector('span.primary-term-button');
				termListItem.removeChild(primaryButtonWrap); // Remove "Set Primary" button wrap
				// Insert "Reset Primary" button
				termListItem.firstElementChild.insertAdjacentHTML( 'afterend', this.unSetPrimaryButtonUI );
				termListItem.classList.add('primary-term'); // Add 'primary-term' class to list item
			}

		}

		/**
		 * Reset primary term list terms
		 */
		resetPrimaryTermListItems() {
			let primaryTermListItems = this.categoryDiv.querySelectorAll('li.primary-term');

			for ( let primaryTermListItem of primaryTermListItems) {
				let primaryButtonWrap = primaryTermListItem.querySelector('span.primary-term-button');

				primaryTermListItem.classList.remove('primary-term'); // Remove primary-term class from LI
				primaryTermListItem.removeChild(primaryButtonWrap); // Delete "Reset Primary" button wrap
				primaryTermListItem.firstElementChild.insertAdjacentHTML( 'afterend', this.setPrimaryButtonUI );
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
			if ( 0 < termID ) {
				document.getElementById( `in-${this.taxonomy.name}-${termID}` ).checked = true;
			}
		}
	}

	window.onload = function() {
		primaryButtonUITemplate = wp.template('wpt-primary-term-button');
		// Loop through each taxonomy and init WPPrimaryTerms class
		wptPrimaryTaxonomies.map( taxonomy => new WPPrimaryTerms( taxonomy ).init() );
	};

}( window, document, jQuery ) );
