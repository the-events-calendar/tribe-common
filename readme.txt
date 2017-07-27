=== Tribe Common ===

== Changelog ==

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
