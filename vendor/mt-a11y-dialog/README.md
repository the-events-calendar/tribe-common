# [MT A11y Dialog](https://github.com/faction23/a11y-dialog)

[mt-a11y-dialog](https://github.com/faction23/a11y-dialog) is a lightweight yet flexible script to create accessible dialog windows. Forked from [edenspiekermann](http://edenspiekermann.github.io/a11y-dialog/)

✔︎ No dependencies  
✔︎ Closing dialog on overlay click and <kbd>ESC</kbd>  
✔︎ Toggling `aria-*` attributes  
✔︎ Trapping and restoring focus    
✔︎ Firing events  
✔︎ DOM and JS APIs  
✔︎ Fast and tiny  

## Installation

```
npm install mt-a11y-dialog --save
```

Or you could also copy/paste the script in your project directly, but you will be disconnected from this repository, making it hard for your to get updates.

## Usage

You will find a concrete demo in the [example](https://github.com/faction23/a11y-dialog/tree/master/example) folder of this repository, but basically here is the gist:

### Required Markup

All you require is a trigger, most likely a button since we want to be accessible, and then a script of type "text/template" which contains the contents of the dialog. Your trigger will need a "data-content" attribute which contains the string that the script tag has as its "data-js" attribute.

```html
<button data-js="trigger-newsletter-signup" data-content="newsletter-signup-content">Open the dialog window</button>
<script data-js="newsletter-signup-content" type="text/template">
	HTML CONTENT
</script>
```

### Styling layer

You will have to implement some styles for the dialog to “work” (visually speaking). The script itself does not take care of any styling whatsoever, not even the `display` property. It basically mostly toggles the `aria-hidden` attribute on the dialog itself and its counterpart containers. You can use this to show and hide the dialog:

```css
.dialog[aria-hidden='true'] {
  display: none;
}
```

The example directory here has a styled modal you can use in your code for inspiration. You also have control over all the css classes used in the dialog as is explained further on.

### JavaScript instantiation

```javascript
// Instantiate a new A11yDialog module
const dialog = new A11yDialog({ trigger: '.some-selector or an element node' });
```
Here are all the options you can pass to the dialog on init, and the defaults that the system uses:

```javascript
appendTarget: '', // the dialog will be inserted after the button, you could supply a selector string here to override
bodyLock: true, // lock the body while dialog open?
closeButtonAriaLabel: 'Close this dialog window', // aria label for close button
closeButtonClasses: 'a11y-dialog__close-button', // classes for close button
contentClasses: 'a11y-dialog__content', // dialog content classes
effect: 'none', // none or fade (for now)
effectSpeed: 300, // effect speed in milliseconds
effectEasing: 'ease-in-out', // a css easing string
overlayClasses: 'a11y-dialog__overlay', // overlay classes
overlayClickCloses: true, // clicking overlay closes dialog
trigger: null, // the trigger for the dialog, can be selector string or element node
wrapperClasses: 'a11y-dialog', // the wrapper class for the dialog
```

## JS API

Regarding the JS API, it simply consists of `show()` and `hide()` methods on the dialog instance.

```javascript
// Show the dialog
dialog.show();

// Hide the dialog
dialog.hide();
```

For advanced usages, there are `create()` and `destroy()` methods. These are responsible for attaching click event listeners to dialog openers and closers. Note that the `create()` method is **automatically called on instantiation** so there is no need to call it again directly.

```javascript
// Unbind click listeners from dialog openers and closers and remove all bound
// custom event listeners registered with `.on()`
dialog.destroy();

// Bind click listeners to dialog openers and closers
dialog.create();
```

## Events

When shown, hidden and destroyed, the instance will emit certain events. It is possible to subscribe to these with the `on()` method which will receive the dialog DOM element and the [event object](https://developer.mozilla.org/en-US/docs/Web/API/Event) (if any).

The event object can be used to know which trigger (opener / closer) has been used in case of a `show` or `hide` event.

```javascript
dialog.on('render', function (dialogEl, event) {
  // The dialog is not rendered until the first time its called for by the trigger
  // Instantiate any js that needs to run on the dialog content here, like a slider init
});

dialog.on('show', function (dialogEl, event) {
  // Do something when dialog gets shown
  // Note: opener is `event.currentTarget`
});

dialog.on('hide', function (dialogEl, event) {
  // Do something when dialog gets hidden
  // Note: closer is `event.currentTarget`
});

dialog.on('destroy', function (dialogEl) {
  // Do something when dialog gets destroyed
});

dialog.on('create', function (dialogEl) {
  // Do something when dialog gets created
  // Note: because the initial `create()` call is made from the constructor, it
  // is not possible to react to this particular one (as registering will be
  // done after instantiation)
});
```

You can unregister these handlers with the `off()` method.

```javascript
dialog.on('show', doSomething);
// …
dialog.off('show', doSomething);
```

## Disclaimer & credits

This repository is a fork from [edenspiekermann](http://edenspiekermann.github.io/a11y-dialog/)
