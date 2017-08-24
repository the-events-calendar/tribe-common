=== Tribe Common ===

== Changelog ==

= [4.5.11] 2017-08-24 =

* Fix - Ensure valid license keys save as expected [84966]
* Tweak - Removing WP Plugin API result adjustments

= [4.5.10.1] 2017-08-16 =

* Fix - Fixed issue with JS/CSS files not loading when WordPress URL is HTTPS but Site URL is not (our thanks to @carcal1 for first reporting this) [85017]

= [4.5.10] 2017-08-09 =

* Fix - Added support to tribe_asset() for non-default plugin directions/usage from within the mu-plugin directory (our thanks to @squirrelandnnuts for reporting this) [82809]
* Fix - Made JSON LD permalinks overridable by all post types, so they can be filtered [76411]
* Tweak - Improve integration with the plugins API/add new plugins screen (our thanks to David Sharpe for highlighting this) [82223]
* Tweak - Improve the Select2 search experience (props to @fabianmarz) [84496]
* Language - 0 new strings added, 312 updated, 1 fuzzied, and 0 obsoleted

= [4.5.9] 2017-07-26 =

* Fix - Avoid accidental overwrite of options when settings are saved in a multisite context [79728]
* Fix - Provide a well sorted list of countries even when this list is translated (our thanks to Johannes in the forums for highlighting this) [69550]
* Tweak - Cleanup logic responsible for handling the default country option and remove confusing translation calls (our thanks to Oliver for flagging this!) [72113]
* Tweak - Added period "." separator to datepicker formats [65282]
* Tweak - Avoid noise relating to PUE checks during WP CLI requests

= [4.5.8] 2017-07-13 =

* Fix - Fixes to the plugin upgrade notice parser including support for environments where the data stream wrapper is unavailable [69486]
* Fix - Ensure the multichoice settings configured to allow no selection work as expected [73183]
* Fix - Enqueue expired notice and CSS on every admin page [81714]
* Tweak - Add helper to retrieve anonymous objects using the class name, hook and callback priority [74938]
* Tweak - Allow dependency.js to handle radio buttons. ensure that they are linked correctly. [82510]
* Fix - Allow passing multiple localize-scripts to tribe-assets. Don't output a localized scrip more than once. [81644]

= [4.5.7] 2017-06-28 =

* Fix - Made the App Shop and help pages work on Windows. [77975]
* Fix - Resolved issue where the Meta Chunker attempted to inappropriately chunk meta for post post_types [80857]
* Fix - Avoid notices during plugin update and installation checks [80492]
* Fix - Ensure an empty dateTimeSeparator option value doesn't break tribe_get_start_date output anymore. [65286]
* Tweak - Improve navigation to elements inside admin pages (don't let the admin toolbar obscure things) [41829]
* Tweak - Textual corrections (with thanks to @garrett-eclipse) [77196]

= [4.5.6] 2017-06-22 =

* Fix - Resolved issue where the Meta Chunker attempted to inappropriately chunk meta for post post_types [80857]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted [tribe-common]
