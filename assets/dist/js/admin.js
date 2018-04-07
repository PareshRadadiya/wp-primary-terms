/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(2);


/***/ }),
/* 1 */
/***/ (function(module, exports) {

/**
 * WP Primary Category
 * http://pareshradadiya.github.io/wp-primary-category
 *
 * Licensed under the GPLv2+ license.
 */

;(function (window, document, $) {

	var primaryButtonUITemplate, primaryInputUITemplate;

	var PrimaryTerms = function (taxonomy) {
		this.taxonomy = taxonomy;
	};

	PrimaryTerms.prototype = {

		init: function () {
			this.buildCache();
			this.render();
			this.bindEvents();
			return this;
		},

		buildCache: function () {
			this.categoryDiv = document.getElementById('taxonomy-' + this.taxonomy.name);
			this.categoryLI = this.categoryDiv.querySelectorAll('.' + this.taxonomy.name + 'checklist li');
			this.setPrimaryButtonUI = primaryButtonUITemplate({ isPrimary: false });
			this.unSetPrimaryButtonUI = primaryButtonUITemplate({ isPrimary: true });
		},

		render: function () {
			this.categoryDiv.insertAdjacentHTML('beforeend', primaryInputUITemplate(this.taxonomy));
			this.primaryInput = document.getElementById('_wp_primary_' + this.taxonomy.name);
			this.buildPrimaryTermsUI();
		},

		bindEvents: function () {
			this.clickHandler = this.clickHandler.bind(this);
			this.categoryDiv.addEventListener('click', this.clickHandler);
		},

		buildPrimaryTermsUI: function () {
			var catLILength = this.categoryLI.length,
			    primaryTermID = this.getPrimaryTerm();

			for (var i = 0; i < catLILength; i++) {

				var catCheckBox = this.categoryLI[i].querySelector('input[type=checkbox]');

				if (catCheckBox.value === primaryTermID) {
					this.categoryLI[i].classList.add('primary-term');
					this.categoryLI[i].insertAdjacentHTML('beforeend', this.unSetPrimaryButtonUI);
				} else {
					this.categoryLI[i].insertAdjacentHTML('beforeend', this.setPrimaryButtonUI);
				}
			}
		},

		clickHandler: function (e) {

			// Only run if the target is in a category div
			if (!e.target) return;

			if (e.target.matches('input[type=checkbox]')) {
				this.termCheckHandler(e);
			} else if (e.target.matches('a.toggle-primary-term')) {
				e.preventDefault();
				this.togglePrimaryTermHandler(e);
			}
		},

		termCheckHandler: function (e) {
			if (e.target.parentNode.parentNode.classList.contains('primary-term')) {
				this.resetPrimaryTerm();
				this.setPrimaryTerm('');
			}
		},

		togglePrimaryTermHandler: function (e) {

			var toggleButton = e.target,
			    buttonWrap = toggleButton.parentNode,
			    currentLI = buttonWrap.parentNode,
			    termCheckBox = currentLI.firstChild.firstChild,
			    termID = termCheckBox.value;

			if (!currentLI.classList.contains('primary-term')) {
				// Reset
				this.resetPrimaryTerm();
				// Delete button
				currentLI.removeChild(buttonWrap);
				currentLI.insertAdjacentHTML('beforeend', this.unSetPrimaryButtonUI);
				currentLI.classList.add('primary-term');
				this.setPrimaryTerm(termID);
			} else {
				this.resetPrimaryTerm();
				this.setPrimaryTerm('');
			}
		},

		resetPrimaryTerm: function () {
			var primaryCategoryLI = this.categoryDiv.querySelector('li.primary-term');

			if (primaryCategoryLI) {
				var primaryButtonWrap = this.categoryDiv.querySelector('li.primary-term > span.primary-term-button');

				primaryCategoryLI.classList.remove('primary-term');
				primaryCategoryLI.removeChild(primaryButtonWrap);
				primaryCategoryLI.insertAdjacentHTML('beforeend', this.setPrimaryButtonUI);
			}
		},

		getPrimaryTerm: function () {
			return this.primaryInput.value;
		},

		setPrimaryTerm: function (termID) {
			this.primaryInput.value = termID;
			if (0 < termID.length) {
				document.getElementById('in-' + this.taxonomy.name + '-' + termID).checked = true;
			}
		}
	};

	window.onload = function () {
		primaryButtonUITemplate = wp.template('wpt-primary-term-button');
		primaryInputUITemplate = wp.template('wpt-primary-term-input');

		var taxonomiesLength = wptPrimaryTaxonomies.length;
		for (var i = 0; i < taxonomiesLength; i++) {
			new PrimaryTerms(wptPrimaryTaxonomies[i]).init();
		}
	};

	// execute init on each taxonomy
})(window, document, jQuery);

/***/ }),
/* 2 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
//# sourceMappingURL=admin.js.map