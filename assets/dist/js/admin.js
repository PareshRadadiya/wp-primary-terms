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

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * WP Primary Category
 * http://pareshradadiya.github.io/wp-primary-category
 *
 * Licensed under the GPLv2+ license.
 */

;(function (window, document, $, undefined) {
	'use strict';

	var primaryButtonUITemplate = void 0;

	var WPPrimaryTerms = function () {

		/**
   * Constructor
   * @param taxonomy
   */
		function WPPrimaryTerms(taxonomy) {
			_classCallCheck(this, WPPrimaryTerms);

			this.taxonomy = taxonomy;
		}

		/**
   * Initializations
   */


		_createClass(WPPrimaryTerms, [{
			key: 'init',
			value: function init() {
				this.buildCache();
				this.render();
				this.bindEvents();
			}

			/**
    *  Build cache
    */

		}, {
			key: 'buildCache',
			value: function buildCache() {
				this.categoryDiv = document.getElementById('taxonomy-' + this.taxonomy.name);
				this.$checkList = $(document.getElementById(this.taxonomy.name + 'checklist'));
				this.termListItems = this.categoryDiv.querySelectorAll('.' + this.taxonomy.name + 'checklist li');
				this.primaryInputUITemplate = wp.template('wpt-primary-' + this.taxonomy.name + '-input');
				this.setPrimaryButtonUI = primaryButtonUITemplate({ isPrimary: false });
				this.unSetPrimaryButtonUI = primaryButtonUITemplate({ isPrimary: true });
			}

			/**
    * Do render
    */

		}, {
			key: 'render',
			value: function render() {
				this.categoryDiv.insertAdjacentHTML('beforeend', this.primaryInputUITemplate(this.taxonomy));
				this.primaryInput = document.getElementById('_wp_primary_' + this.taxonomy.name);
				this.buildPrimaryTermsUI();
			}

			/**
    * Event listeners
    */

		}, {
			key: 'bindEvents',
			value: function bindEvents() {
				this.clickHandler = this.clickHandler.bind(this);
				this.categoryDiv.addEventListener('click', this.clickHandler);
				this.$checkList.on('wpListAddEnd', this.handleNewTermAdded.bind(this));
			}

			/**
    * Add Set/Reset button in all category list items
    */

		}, {
			key: 'buildPrimaryTermsUI',
			value: function buildPrimaryTermsUI() {
				var primaryTermID = this.getPrimaryTerm();

				var _iteratorNormalCompletion = true;
				var _didIteratorError = false;
				var _iteratorError = undefined;

				try {
					for (var _iterator = this.termListItems[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
						var termListItem = _step.value;

						var catCheckBox = termListItem.querySelector('input[type=checkbox]');

						// If current list item has primary term, add "Rest Primary" button
						if (catCheckBox.value === primaryTermID) {
							termListItem.classList.add('primary-term');
							termListItem.firstElementChild.insertAdjacentHTML('afterend', this.unSetPrimaryButtonUI);
						} else {
							// Otherwise, add "Set Primary" button
							termListItem.firstElementChild.insertAdjacentHTML('afterend', this.setPrimaryButtonUI);
						}
					}
				} catch (err) {
					_didIteratorError = true;
					_iteratorError = err;
				} finally {
					try {
						if (!_iteratorNormalCompletion && _iterator.return) {
							_iterator.return();
						}
					} finally {
						if (_didIteratorError) {
							throw _iteratorError;
						}
					}
				}
			}

			/**
    * Click event handler
    * @param  {Event} e The Click event
    */

		}, {
			key: 'clickHandler',
			value: function clickHandler(e) {

				// Only run if the target is in a category div
				if (!e.target) return;

				if (e.target.matches('input[type=checkbox]')) {
					this.termCheckHandler(e);
				} else if (e.target.matches('a.toggle-primary-term')) {
					e.preventDefault();
					this.togglePrimaryTermHandler(e);
				}
			}

			/**
    * Insert "Set/Rest Primary" button on lately added items
    * @param e
    * @param params
    */

		}, {
			key: 'handleNewTermAdded',
			value: function handleNewTermAdded(e, params) {
				e.target.firstElementChild.firstElementChild.insertAdjacentHTML('afterend', this.setPrimaryButtonUI);
			}

			/**
    * Term checked event handler
    * @param  {Event} e The Check event
    */

		}, {
			key: 'termCheckHandler',
			value: function termCheckHandler(e) {
				if (e.target.parentNode.parentNode.classList.contains('primary-term')) {
					this.resetPrimaryTermListItems();
					this.setPrimaryTerm();
				}
			}

			/**
    * Set/Reset Primary button click handler
    * @param  {Event} e The Click event
    */

		}, {
			key: 'togglePrimaryTermHandler',
			value: function togglePrimaryTermHandler(e) {

				var termID = e.target.closest('li').id.match(/-(\d+)$/)[1],
				    // Fetch a term id from the li ID attribute
				listItems = this.categoryDiv.querySelectorAll('#popular-' + this.taxonomy.name + '-' + termID + ', #' + this.taxonomy.name + '-' + termID);

				// Set primary term
				if (!listItems[0].classList.contains('primary-term')) {
					this.setPrimaryTermListItems(listItems);
					// Store term id into hidden input
					this.setPrimaryTerm(termID);
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

		}, {
			key: 'setPrimaryTermListItems',
			value: function setPrimaryTermListItems(termListItems) {
				// Reset a previously set primary term, if any.
				this.resetPrimaryTermListItems();

				var _iteratorNormalCompletion2 = true;
				var _didIteratorError2 = false;
				var _iteratorError2 = undefined;

				try {
					for (var _iterator2 = termListItems[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
						var termListItem = _step2.value;

						var primaryButtonWrap = termListItem.querySelector('span.primary-term-button');
						termListItem.removeChild(primaryButtonWrap); // Remove "Set Primary" button wrap
						// Insert "Reset Primary" button
						termListItem.firstElementChild.insertAdjacentHTML('afterend', this.unSetPrimaryButtonUI);
						termListItem.classList.add('primary-term'); // Add 'primary-term' class to list item
					}
				} catch (err) {
					_didIteratorError2 = true;
					_iteratorError2 = err;
				} finally {
					try {
						if (!_iteratorNormalCompletion2 && _iterator2.return) {
							_iterator2.return();
						}
					} finally {
						if (_didIteratorError2) {
							throw _iteratorError2;
						}
					}
				}
			}

			/**
    * Reset primary term list terms
    */

		}, {
			key: 'resetPrimaryTermListItems',
			value: function resetPrimaryTermListItems() {
				var primaryTermListItems = this.categoryDiv.querySelectorAll('li.primary-term');

				var _iteratorNormalCompletion3 = true;
				var _didIteratorError3 = false;
				var _iteratorError3 = undefined;

				try {
					for (var _iterator3 = primaryTermListItems[Symbol.iterator](), _step3; !(_iteratorNormalCompletion3 = (_step3 = _iterator3.next()).done); _iteratorNormalCompletion3 = true) {
						var primaryTermListItem = _step3.value;

						var primaryButtonWrap = primaryTermListItem.querySelector('span.primary-term-button');

						primaryTermListItem.classList.remove('primary-term'); // Remove primary-term class from LI
						primaryTermListItem.removeChild(primaryButtonWrap); // Delete "Reset Primary" button wrap
						primaryTermListItem.firstElementChild.insertAdjacentHTML('afterend', this.setPrimaryButtonUI);
					}
				} catch (err) {
					_didIteratorError3 = true;
					_iteratorError3 = err;
				} finally {
					try {
						if (!_iteratorNormalCompletion3 && _iterator3.return) {
							_iterator3.return();
						}
					} finally {
						if (_didIteratorError3) {
							throw _iteratorError3;
						}
					}
				}
			}

			/**
    * Return the primary term id
    */

		}, {
			key: 'getPrimaryTerm',
			value: function getPrimaryTerm() {
				return this.primaryInput.value;
			}

			/**
    * Set a primary term id into hidden input
    * @param termID
    */

		}, {
			key: 'setPrimaryTerm',
			value: function setPrimaryTerm() {
				var termID = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;

				this.primaryInput.value = termID;
				if (0 < termID) {
					document.getElementById('in-' + this.taxonomy.name + '-' + termID).checked = true;
				}
			}
		}]);

		return WPPrimaryTerms;
	}();

	window.onload = function () {
		primaryButtonUITemplate = wp.template('wpt-primary-term-button');
		// Loop through each taxonomy and init WPPrimaryTerms class
		wptPrimaryTaxonomies.map(function (taxonomy) {
			return new WPPrimaryTerms(taxonomy).init();
		});
	};
})(window, document, jQuery);

/***/ }),
/* 2 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
//# sourceMappingURL=admin.js.map