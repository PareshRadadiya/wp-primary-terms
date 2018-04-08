/**
 * WP Primary Category
 * http://pareshradadiya.github.io/wp-primary-category
 *
 * Licensed under the GPLv2+ license.
 */

;( function( window, document, $ ) {

	var primaryButtonUITemplate;

	var PrimaryTerms = function( taxonomy ) {
		this.taxonomy = taxonomy;
	};

	PrimaryTerms.prototype = {

		init: function() {
			this.buildCache();
			this.render();
			this.bindEvents();
			return this;
		},

		buildCache: function() {
			this.categoryDiv = document.getElementById( `taxonomy-${this.taxonomy.name}` );
			this.categoryLI  = this.categoryDiv.querySelectorAll( `.${this.taxonomy.name}checklist li` );
			this.primaryInputUITemplate = wp.template( `wpt-primary-${this.taxonomy.name}-input` );
			this.setPrimaryButtonUI = primaryButtonUITemplate({ isPrimary: false });
			this.unSetPrimaryButtonUI = primaryButtonUITemplate({ isPrimary: true });
		},

		render: function() {
			this.categoryDiv.insertAdjacentHTML( 'beforeend', this.primaryInputUITemplate(this.taxonomy) );
			this.primaryInput = document.getElementById( `_wp_primary_${this.taxonomy.name}` );
			this.buildPrimaryTermsUI();
		},

		bindEvents: function() {
			this.clickHandler = this.clickHandler.bind(this);
			this.categoryDiv.addEventListener( 'click', this.clickHandler );
		},

		buildPrimaryTermsUI: function() {
			let primaryTermID = this.getPrimaryTerm();

			for ( let categoryLI of this.categoryLI ) {
				let catCheckBox = categoryLI.querySelector('input[type=checkbox]');

				if ( catCheckBox.value === primaryTermID ) {
					categoryLI.classList.add('primary-term');
					categoryLI.firstElementChild.insertAdjacentHTML( 'afterend', this.unSetPrimaryButtonUI );
				} else {
					categoryLI.firstElementChild.insertAdjacentHTML( 'afterend', this.setPrimaryButtonUI );
				}
			}
		},

		clickHandler: function(e) {

			// Only run if the target is in a category div
			if ( ! e.target ) return;

			if ( e.target.matches('input[type=checkbox]') ) {
				this.termCheckHandler(e);
			} else if ( e.target.matches('a.toggle-primary-term') ) {
				e.preventDefault();
				this.togglePrimaryTermHandler(e)
			}
		},

		termCheckHandler: function(e) {
			if ( e.target.parentNode.parentNode.classList.contains('primary-term') ) {
				this.resetPrimaryTerm();
				this.setPrimaryTerm('');
			}
		},

		togglePrimaryTermHandler: function(e) {

			let
				termID       = e.target.closest('li').id.match(/-(\d+)$/)[1],
				currentLIS   = this.categoryDiv.querySelectorAll(`#popular-${this.taxonomy.name}-${termID}, #${this.taxonomy.name}-${termID}`);


			if ( ! currentLIS[0].classList.contains('primary-term') ) {
				// Reset
				this.resetPrimaryTerm();
				// Delete button

				for ( let currentLI of currentLIS ) {
					let primaryButtonWrap = currentLI.querySelector('span.primary-term-button');
					currentLI.removeChild(primaryButtonWrap);
					currentLI.firstElementChild.insertAdjacentHTML( 'afterend', this.unSetPrimaryButtonUI );
					currentLI.classList.add('primary-term');
				}

				this.setPrimaryTerm( termID );
			} else {
				this.resetPrimaryTerm();
				this.setPrimaryTerm('');
			}
		},

		resetPrimaryTerm: function() {
			let primaryCategoryLIS = this.categoryDiv.querySelectorAll('li.primary-term');

			for ( let primaryCategoryLI of primaryCategoryLIS) {
					let primaryButtonWrap = primaryCategoryLI.querySelector('span.primary-term-button');

					primaryCategoryLI.classList.remove('primary-term');
					primaryCategoryLI.removeChild(primaryButtonWrap);
					primaryCategoryLI.firstElementChild.insertAdjacentHTML( 'afterend', this.setPrimaryButtonUI );
			}
		},

		getPrimaryTerm: function() {
			return this.primaryInput.value;
		},

		setPrimaryTerm: function( termID ) {
			this.primaryInput.value = termID;
			if ( 0 < termID.length ) {
				document.getElementById( `in-${this.taxonomy.name}-${termID}` ).checked = true;
			}
		}
	};

	window.onload = function() {
		primaryButtonUITemplate = wp.template('wpt-primary-term-button');

		var taxonomiesLength = wptPrimaryTaxonomies.length;
		for( var i = 0; i < taxonomiesLength; i++ ) {
			new PrimaryTerms( wptPrimaryTaxonomies[i] ).init();
		}
	};

	// execute init on each taxonomy
}( window, document, jQuery ) );
