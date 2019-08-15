/* global NodeList, Element, define */

(function (global) {
	'use strict';

	var FOCUSABLE_ELEMENTS = ['a[href]', 'area[href]', 'input:not([disabled])', 'select:not([disabled])', 'textarea:not([disabled])', 'button:not([disabled])', 'iframe', 'object', 'embed', '[contenteditable]', '[tabindex]:not([tabindex^="-"])'];
	var TAB_KEY = 9;
	var ESCAPE_KEY = 27;
	var focusedBeforeDialog;
	var browser = browserTests();
	var scroll = 0;
	var scroller = browser.ie || browser.firefox || (browser.chrome && !browser.edge) ? document.documentElement : document.body;

	/**
	 * Define the constructor to instantiate a dialog
	 *
	 * @constructor
	 * @param {Object} options
	 */
	function A11yDialog(options) {
		this.options = extend({
			appendTarget: '',
			bodyLock: true,
			closeButtonAriaLabel: 'Close this dialog window',
			closeButtonClasses: 'a11y-dialog__close-button',
			contentClasses: 'a11y-dialog__content',
			effect: 'none',
			effectSpeed: 300,
			effectEasing: 'ease-in-out',
			overlayClasses: 'a11y-dialog__overlay',
			overlayClickCloses: true,
			trigger: null,
			wrapperClasses: 'a11y-dialog',
		}, options);
		// Prebind the functions that will be bound in addEventListener and
		// removeEventListener to avoid losing references
		this._rendered = false;
		this._show = this.show.bind(this);
		this._hide = this.hide.bind(this);
		this._maintainFocus = this._maintainFocus.bind(this);
		this._bindKeypress = this._bindKeypress.bind(this);

		this.trigger = isString(this.options.trigger) ? getNodes(this.options.trigger, false, document, true)[0] : this.options.trigger;
		this.node = null;

		if (!this.trigger) {
			console.warn('Lookup for a11y target node failed.');
			return;
		}

		// Keep an object of listener types mapped to callback functions
		this._listeners = {};

		// Initialise everything needed for the dialog to work properly
		this.create();
	}

	/**
	 * Set up everything necessary for the dialog to be functioning
	 *
	 * @return {this}
	 */
	A11yDialog.prototype.create = function () {
		this.shown = false;
		this.trigger.addEventListener('click', this._show);

		// Execute all callbacks registered for the `create` event
		this._fire('create');

		return this;
	};

	/**
	 * Render the dialog
	 *
	 * @return {this}
	 */
	A11yDialog.prototype.render = function (event) {
		var contentNode = getNodes(this.trigger.dataset.content)[0];
		if (!contentNode) {
			return this;
		}
		var node = document.createElement('div');
		node.setAttribute('aria-hidden', 'true');
		node.classList.add(this.options.wrapperClasses);
		node.innerHTML = '<div data-js="a11y-overlay" tabindex="-1" class="' + this.options.overlayClasses + '"></div>\n' +
			'  <div class="' + this.options.contentClasses + '" role="dialog">\n' +
			'    <div role="document">\n' +
			'      <button ' +
			'           data-js="a11y-close-button"' +
			'           class="' + this.options.closeButtonClasses + '" ' +
			'           type="button" ' +
			'           aria-label="' + this.options.closeButtonAriaLabel + '"' +
			'       ></button>\n' +
			'      ' + contentNode.innerHTML +
			'    </div>\n' +
			'  </div>';

		var appendTarget = this.trigger;
		if (this.options.appendTarget.length) {
			appendTarget = document.querySelectorAll(this.options.appendTarget)[0] || this.trigger;
		}
		insertAfter(node, appendTarget);
		this.node = node;
		this.overlay = getNodes('a11y-overlay', false, this.node)[0];
		this.closeButton = getNodes('a11y-close-button', false, this.node)[0];
		if (this.options.overlayClickCloses) {
			this.overlay.addEventListener('click', this._hide);
		}
		this.closeButton.addEventListener('click', this._hide);
		this._rendered = true;
		this._fire('render', event);
		return this;
	};

	/**
	 * Show the dialog element, disable all the targets (siblings), trap the
	 * current focus within it, listen for some specific key presses and fire all
	 * registered callbacks for `show` event
	 *
	 * @param {Event} event
	 * @return {this}
	 */
	A11yDialog.prototype.show = function (event) {
		// If the dialog is already open, abort
		if (this.shown) {
			return this;
		}

		if (!this._rendered) {
			this.render(event);
		}

		if (!this._rendered) {
			return this;
		}

		this.shown = true;
		this._applyOpenEffect();
		this.node.setAttribute('aria-hidden', 'false');
		if (this.options.bodyLock) {
			lock();
		}

		// Keep a reference to the currently focused element to be able to restore
		// it later, then set the focus to the first focusable child of the dialog
		// element
		focusedBeforeDialog = document.activeElement;
		setFocusToFirstItem(this.node);

		// Bind a focus event listener to the body element to make sure the focus
		// stays trapped inside the dialog while open, and start listening for some
		// specific key presses (TAB and ESC)
		document.body.addEventListener('focus', this._maintainFocus, true);
		document.addEventListener('keydown', this._bindKeypress);

		// Execute all callbacks registered for the `show` event
		this._fire('show', event);

		return this;
	};

	/**
	 * Hide the dialog element, enable all the targets (siblings), restore the
	 * focus to the previously active element, stop listening for some specific
	 * key presses and fire all registered callbacks for `hide` event
	 *
	 * @param {Event} event
	 * @return {this}
	 */
	A11yDialog.prototype.hide = function (event) {
		// If the dialog is already closed, abort
		if (!this.shown) {
			return this;
		}

		this.shown = false;
		this.node.setAttribute('aria-hidden', 'true');
		this._applyCloseEffect();

		if (this.options.bodyLock) {
			unlock();
		}

		// If their was a focused element before the dialog was opened, restore the
		// focus back to it
		if (focusedBeforeDialog) {
			focusedBeforeDialog.focus();
		}

		// Remove the focus event listener to the body element and stop listening
		// for specific key presses
		document.body.removeEventListener('focus', this._maintainFocus, true);
		document.removeEventListener('keydown', this._bindKeypress);

		// Execute all callbacks registered for the `hide` event
		this._fire('hide', event);

		return this;
	};

	/**
	 * Destroy the current instance (after making sure the dialog has been hidden)
	 * and remove all associated listeners from dialog openers and closers
	 *
	 * @return {this}
	 */
	A11yDialog.prototype.destroy = function () {
		// Hide the dialog to avoid destroying an open instance
		this.hide();

		this.trigger.removeEventListener('click', this._show);
		if (this.options.overlayClickCloses) {
			this.overlay.removeEventListener('click', this._hide);
		}
		this.closeButton.removeEventListener('click', this._hide);

		// Execute all callbacks registered for the `destroy` event
		this._fire('destroy');

		// Keep an object of listener types mapped to callback functions
		this._listeners = {};

		return this;
	};

	/**
	 * Register a new callback for the given event type
	 *
	 * @param {string} type
	 * @param {Function} handler
	 */
	A11yDialog.prototype.on = function (type, handler) {
		if (typeof this._listeners[type] === 'undefined') {
			this._listeners[type] = [];
		}

		this._listeners[type].push(handler);

		return this;
	};

	/**
	 * Unregister an existing callback for the given event type
	 *
	 * @param {string} type
	 * @param {Function} handler
	 */
	A11yDialog.prototype.off = function (type, handler) {
		var index = this._listeners[type].indexOf(handler);

		if (index > -1) {
			this._listeners[type].splice(index, 1);
		}

		return this;
	};

	/**
	 * Iterate over all registered handlers for given type and call them all with
	 * the dialog element as first argument, event as second argument (if any).
	 *
	 * @access private
	 * @param {string} type
	 * @param {Event} event
	 */
	A11yDialog.prototype._fire = function (type, event) {
		var listeners = this._listeners[type] || [];

		listeners.forEach(function (listener) {
			listener(this.node, event);
		}.bind(this));
	};

	/**
	 * Private event handler used when listening to some specific key presses
	 * (namely ESCAPE and TAB)
	 *
	 * @access private
	 * @param {Event} event
	 */
	A11yDialog.prototype._bindKeypress = function (event) {
		// If the dialog is shown and the ESCAPE key is being pressed, prevent any
		// further effects from the ESCAPE key and hide the dialog
		if (this.shown && event.which === ESCAPE_KEY) {
			event.preventDefault();
			this.hide();
		}

		// If the dialog is shown and the TAB key is being pressed, make sure the
		// focus stays trapped within the dialog element
		if (this.shown && event.which === TAB_KEY) {
			trapTabKey(this.node, event);
		}
	};

	/**
	 * Private event handler used when making sure the focus stays within the
	 * currently open dialog
	 *
	 * @access private
	 * @param {Event} event
	 */
	A11yDialog.prototype._maintainFocus = function (event) {
		// If the dialog is shown and the focus is not within the dialog element,
		// move it back to its first focusable child
		if (this.shown && !this.node.contains(event.target)) {
			setFocusToFirstItem(this.node);
		}
	};

	/**
	 * Applies effects to the opening of the dialog.
	 *
	 * @access private
	 */

	A11yDialog.prototype._applyOpenEffect = function () {
		var _this = this;
		if (this.options.effect === 'fade') {
			this.node.style.opacity = '0';
			this.node.style.transition = 'opacity ' + this.options.effectSpeed + 'ms ' + this.options.effectEasing;
			setTimeout(function(){
				_this.node.style.opacity = '1';
			}, 50);
		}
	};

	/**
	 * Applies effects to the closing of the dialog.
	 *
	 * @access private
	 */

	A11yDialog.prototype._applyCloseEffect = function () {
		var _this = this;
		if (this.options.effect === 'fade') {
			this.node.setAttribute('aria-hidden', 'false');
			this.node.style.opacity = '0';
			setTimeout(function(){
				_this.node.style.transition = '';
				_this.node.setAttribute('aria-hidden', 'true');
			}, this.options.effectSpeed);
		}
	};

	/**
	 * Highly efficient function to convert a nodelist into a standard array. Allows you to run Array.forEach
	 *
	 * @param {Element|NodeList} elements to convert
	 * @returns {Array} Of converted elements
	 */

	function convertElements(elements) {
		var converted = [];
		var i = elements.length;
		for (i; i--; converted.unshift(elements[i])); // eslint-disable-line

		return converted;
	}

	/**
	 * Should be used at all times for getting nodes throughout our app. Please use the data-js attribute whenever possible
	 *
	 * @param selector The selector string to search for. If arg 4 is false (default) then we search for [data-js="selector"]
	 * @param convert Convert the NodeList to an array? Then we can Array.forEach directly. Uses convertElements from above
	 * @param node Parent node to search from. Defaults to document
	 * @param custom Is this a custom selector where we don't want to use the data-js attribute?
	 * @returns {NodeList}
	 */

	function getNodes(selector, convert, node, custom) {
		if (!node) {
			node = document;
		}
		var selectorString = custom ? selector : '[data-js="' + selector + '"]';
		var nodes = node.querySelectorAll(selectorString);
		if (convert) {
			nodes = convertElements(nodes);
		}
		return nodes;
	}

	/**
	 * Query the DOM for nodes matching the given selector, scoped to context (or
	 * the whole document)
	 *
	 * @param {String} selector
	 * @param {Element} [context = document]
	 * @return {Array<Element>}
	 */
	function $$(selector, context) {
		return convertElements((context || document).querySelectorAll(selector));
	}

	/**
	 * Set the focus to the first focusable child of the given element
	 *
	 * @param {Element} node
	 */
	function setFocusToFirstItem(node) {
		var focusableChildren = getFocusableChildren(node);

		if (focusableChildren.length) {
			focusableChildren[0].focus();
		}
	}

	/**
	 * Insert a node after another node
	 *
	 * @param newNode {Element|NodeList}
	 * @param referenceNode {Element|NodeList}
	 */
	function insertAfter(newNode, referenceNode) {
		referenceNode.parentNode.insertBefore(newNode, referenceNode.nextElementSibling);
	}

	/**
	 * Get the focusable children of the given element
	 *
	 * @param {Element} node
	 * @return {Array<Element>}
	 */
	function getFocusableChildren(node) {
		return $$(FOCUSABLE_ELEMENTS.join(','), node).filter(function (child) {
			return !!(child.offsetWidth || child.offsetHeight || child.getClientRects().length);
		});
	}

	function isString(x) {
		return Object.prototype.toString.call(x) === "[object String]"
	}

	function extend(obj, src) {
		Object.keys(src).forEach(function(key) { obj[key] = src[key]; });
		return obj;
	}

	/**
	 * Trap the focus inside the given element
	 *
	 * @param {Element} node
	 * @param {Event} event
	 */
	function trapTabKey(node, event) {
		var focusableChildren = getFocusableChildren(node);
		var focusedItemIndex = focusableChildren.indexOf(document.activeElement);

		// If the SHIFT key is being pressed while tabbing (moving backwards) and
		// the currently focused item is the first one, move the focus to the last
		// focusable item from the dialog element
		if (event.shiftKey && focusedItemIndex === 0) {
			focusableChildren[focusableChildren.length - 1].focus();
			event.preventDefault();
			// If the SHIFT key is not being pressed (moving forwards) and the currently
			// focused item is the last one, move the focus to the first focusable item
			// from the dialog element
		} else if (!event.shiftKey && focusedItemIndex === focusableChildren.length - 1) {
			focusableChildren[0].focus();
			event.preventDefault();
		}
	}

	/**
	 * @function lock
	 * @description Lock the body at a particular position and prevent scroll,
	 * use margin to simulate original scroll position.
	 */

	function lock() {
		scroll = scroller.scrollTop;
		document.body.classList.add('a11y-dialog__body-locked');
		document.body.style.position = 'fixed';
		document.body.style.width = '100%';
		document.body.style.marginTop = '-' + scroll + 'px';
	}

	/**
	 * @function unlock
	 * @description Unlock the body and return it to its actual scroll position.
	 */

	function unlock() {
		document.body.style.marginTop = '';
		document.body.style.position = '';
		document.body.style.width = '';
		scroller.scrollTop = scroll;
		document.body.classList.remove('a11y-dialog__body-locked');
	}

	function browserTests() {
		var android = /(android)/i.test(navigator.userAgent);
		var chrome = !!window.chrome;
		var firefox = typeof InstallTrigger !== 'undefined';
		var ie = document.documentMode;
		var edge = !ie && !!window.StyleMedia;
		var ios = !!navigator.userAgent.match(/(iPod|iPhone|iPad)/i);
		var iosMobile = !!navigator.userAgent.match(/(iPod|iPhone)/i);
		var opera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
		var safari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 || !chrome && !opera && window.webkitAudioContext !== 'undefined'; // eslint-disable-line
		var os = navigator.platform;

		return {
			android: android,
			chrome: chrome,
			edge: edge,
			firefox: firefox,
			ie: ie,
			ios: ios,
			iosMobile: iosMobile,
			opera: opera,
			safari: safari,
			os: os,
		}
	}

	if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
		module.exports = A11yDialog;
	} else if (typeof define === 'function' && define.amd) {
		define('A11yDialog', [], function () {
			return A11yDialog;
		});
	} else if (typeof global === 'object') {
		global.A11yDialog = A11yDialog;
	}
}(typeof global !== 'undefined' ? global : window));
