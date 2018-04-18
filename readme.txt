=== Tribe Common ===

== Changelog ==

= [4.7.11] 2018-04-18 =

* Fix - Restore "type" attribute to some inline `<script>` tags to ensure proper character encoding in Customizer-generated CSS [103167]
* Tweak - Allow to register the same ID of a post if has multiple types for JSON-LD `<script>` tag [94989]
* Tweak - Added the `a5hleyrich/wp-background-processing` package and the asynchronous process handling base [102323]
* Tweak - Added the `Tribe__Process__Post_Thumbnail_Setter` class to handle post thumbnail download and creation in an asynchronous manner [102323]
* Tweak - Deprecated the `Tribe__Main::doing_ajax()` method and moved it to the `Tribe__Context::doing_ajax()` method [102323]
* Tweak - Modified the `select2` implementation to work with the `maximumSelectionSize` argument via data attribute. [103577]
* Tweak - Add new filters: `tribe_countries` and `tribe_us_states` to allow easier extensibility on the names used for each country [79880]
* Fix - Updated Timezones::abbr() with additional support for timezone strings not covered by PHP date format "T" [102705]

= [4.7.10] 2018-03-28 =

* Tweak - Adjusted app shop text in relation to Modern Tribe's ticketing solutions [101655]
* Tweak - Added wrapper function around use of `tribe_events_get_the_excerpt` for safety [95034]

= [4.7.9] 2018-03-12 =

* Tweak - Added the a `tribe_currency_cost` filtering for Currency control for Prices and Costs

= [4.7.8] 2018-03-06 =

* Feature - Added new `tribe_get_global_query_object()` template tag for accessing the $wp_query global without triggering errors if other software has directly manipulated the global [100199]
* Fix - Remove unnecessary timezone-abbreviation caching approach to improve accuracy of timezone abbreviations and better reflect DST changes [97344]
* Fix - Make sure JSON strings are always a single line of text [99089]

= [4.7.7.1] 2018-02-16 =

* Fix - Rollback changes introduced in version 4.7.7 to allow month view to render correctly.

= [4.7.7] 2018-02-14 =

* Fix - Fixed the behavior of the `tribe_format_currency` function not to overwrite explicit parameters [96777]
* Fix - Modified timezone handling in relation to events, in order to avoid DST changes upon conversion to UTC [69784]
* Tweak - Improved the performance of dropdown and recurrent events by using caching on objects (our thanks to Gilles in the forums for flagging this problem) [81993]
* Tweak - Reduced the risk of conflicts when lodash and underscore are used on the same site [92205]
* Tweak - Added the `tribe_transient_notice` and `tribe_transient_notice_remove` functions to easily create and remove fire-and-forget admin notices
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.7.6] 2018-01-23 =

* Fix - Make sure to apply `$settings` to each section with the initial values in the customizer [96821]
* Tweak - Include permalink structure into the report for support [68687]
* Tweak - Added `not_empty()` validation method to the `Tribe__Validate` class for more options while validating date formats [94725]
* Tweak - Update label on report for support to avoid confusions [68687]
* Tweak - Deprecated the unused $timezone parameter in the `tribe_get_start_date()` and `tribe_get_end_date()` template tags [73400]

= [4.7.5] 2018-01-10 =

* Fix - Added safety check to avoid errors surrounding the use of count() (our thanks to daftdog for highlighting this issue) [95527]
* Fix - Improved file logger to gracefully handle further file system restrictions (our thanks to Richard Palmer for highlighting further issues here) [96747]

= [4.7.4] 2017-12-18 =

* Fix - Fixed Event Cost field causing an error if it did not contain any numeric characters [95400]
* Fix - Fixed the color of the license key validation messages [91890]
* Fix - Added a safety check to avoid errors in the theme customizer when the search parameter is empty (props @afragen)
* Language - 1 new strings added, 5 updated, 1 fuzzied, and 0 obsoleted

= [4.7.3] 2017-12-07 =

* Tweak - Tweaked Tribe Datepicker to prevent conflicts with third-party styles [94161]

= [4.7.2] 2017-11-21 =

* Feature - Added Template class which adds a few layers of filtering to any template file included
* Tweak - Included `tribe_callback_return` for static returns for Hooks
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.7.1] 2017-11-16 =

* Fix - Added support for translatable placeholder text when dropdown selectors are waiting on results being returned via ajax [84926]
* Fix - Implemented an additional file permissions check within default error logger (our thanks to Oscar for highlighting this) [73551]
* Tweak - Added new `tribe_is_site_using_24_hour_time()` function to easily check if the site is using a 24-hour time format [78621]
* Tweak - Ensure the "Debug Mode" helper text in the Events Settings screen displays all of the time (it previously would vanish with certain permalinks settings) [92315]
* Tweak - Allow for non-Latin characters to be used as the Events URL slug and the Single Event URL slug (thanks @daviddweb for originally reporting this) [61880]
* Tweak - Removed restrictions imposed on taxonomy queries by Tribe__Ajax__Dropdown (our thanks to Ian in the forums for flagging this issue) [91762]
* Tweak - Fixed the definition of Tribe__Rewrite::get_bases() to address some PHP strict notices its previous definition triggered [91828]
* Language - 0 new strings added, 16 updated, 1 fuzzied, and 0 obsoleted

= [4.7] 2017-11-09 =

* Feature - Included a new Validation.js for Forms and Fields
* Feature - Included a Camelcase Utils for JavaScript
* Tweak - Added Groups functionality for Tribe Assets class
* Tweak - Improve Dependency.js with better Documentation
* Tweak - Timepicker.js is now part of Common instead of The Events Calendar
* Language - 0 new strings added, 23 updated, 1 fuzzied, and 0 obsoleted

= [4.6.3] 2017-11-02 =

* Fix - Added some more specification to our jquery-ui-datepicker CSS to limit conflicts with other plugins and themes [90577]
* Fix - Fixed compatibility issue with Internet Explorer 10 & 11 when selecting a venue from the dropdown (thanks (@acumenconsulting for reporting this) [72924]
* Fix - Improved process for sharing JSON data in the admin environment so that it also works within the theme customizer screen [72127]
* Tweak - Obfuscated the API key for the google_maps_js_api_key field in the "System Information" screen [89795]
* Tweak - Updated the list of countries used in the country dropdown [75769]
* Tweak - Added additional timezone handling facilities [78233]
* Language - 7 new strings added, 292 updated, 18 fuzzied, and 3 obsoleted

= [4.6.2] 2017-10-18 =

* Fix - Restored functionality to the "currency position" options in Events Settings, and in the per-event cost settings (props @schola and many others!) [89918]
* Fix - Added safety checks to reduce the potential for errors stemming from our logging facilities (shout out to Brandon Stiner and Russell Todd for highlighting some remaining issues here) [90436, 90544]
* Fix - Added checks to avoid the generation of warnings when rendering the customizer CSS template (props: @aristath) [91070]
* Fix - Added safety checks to the Tribe__Post_Transient class to avoid errors when an array is expected but not available [91258]
* Tweak - Improved strategy for filtering of JSON LD data (our thanks to Mathew in the forums for flagging this issue) [89801]
* Tweak - Added new tribe_is_wpml_active() function for unified method of checking (as its name implies) if WPML is active [82286]
* Tweak - Removed call to deprecated screen_icon() function [90985]

= [4.6.1] 2017-10-04 =

* Fix - Fixed issues with the jQuery Timepicker vendor script conflicting with other plugins' similar scripts (props: @hcny et al.) [74644]
* Fix - Added support within Tribe__Assets for when someone filters plugins_url() (Thank you @boonebgorges for the pull request!) [89228]
* Fix - Improved performance of retrieving the country and US States lists [68472]
* Tweak - Limited the loading of several Tribe Common scripts and stylesheets to only load where needed within the wp-admin (props: @traildamage ) [75031]
* Tweak - Removed explicit width styles from app shop "buy now" buttons to better accommodate longer language strings (thanks @abrain on GitHub for submitting this fix!) [88868]
* Tweak - Implemented a re-initializing of Select2 inputs on use of a browser's "Back" button to prevent some UI bugs, e.g. with such inputs' placeholder attributes not being populated (props @uwefunk!) [74553]
* Language - Improvement to composition of various strings, to aid translatability (props: @ramiy) [88982]
* Language - 3 new strings added, 331 updated, 1 fuzzied, and 2 obsoleted

= [4.6] 2017-09-25 =

* Feature - Add support for create, update, and delete REST endpoints
* Language - 1 new strings added, 24 updated, 1 fuzzied, and 0 obsoleted

= [4.5.13] 2017-09-20 =

* Feature - Remove 'France, Metropolitan' option from country list to prevent issues with Google Maps API (thanks @varesanodotfr for pointing this out) [78023]
* Fix - Prevents breakages resulting from deprecated filter hooks
* Tweak - Added an id attribute to dropdowns generated by the Fields API [spotfix]
* Fix - Prevents resetting selected Datatables rows when changing pages (thanks @templesinai for reporting) [88437]

= [4.5.12] 2017-09-06 =

* Fix - Added check to see if log directory is readable before listing logs within it (thank you @rodrigochallengeday-org and @richmondmom for reporting this) [86091]
* Tweak - Datatables Head and Foot checkboxes will not select all items, only the current page [77395]
* Tweak - Added method into Date Utils class to allow us to easily convert all datepicker formats into the default one [77819]
* Tweak - Added a filter to customize the list of states in the USA that are available to drop-downs when creating or editing venues.
* Language - 3 new strings added, 46 updated, 1 fuzzied, and 4 obsoleted

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

