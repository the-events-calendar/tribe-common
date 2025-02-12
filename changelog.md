# Changelog

### [6.5.1.1] 2025-02-12

* Fix - Add a callback to remove the `tribe_pue_key_notices` once on upgrade to version 6.5.1.1 [TEC-5384]
* Fix - Adjustments were made to prevent a fatal error when tec_pue_checker_init was triggered too early, attempting to call tribe_is_truthy() before it was available. The license check and active plugin monitoring now run on admin_init to ensure proper loading. [TEC-5384]
* Fix - Update the license checker to ignore empty licenses. [TEC-5385]

### [6.5.1] 2025-02-10

* Fix - Added more details to `Core_Read_Interface` methods' docblocks to avoid errors in PHPStan. [TCMN-177]
* Fix - Changed the way translations are loaded to work with the latest WordPress language changes. [FBAR-341][CE-252]
* Fix - Providers will fire their registration action only once and only if they are active. [TCMN-178]
* Fix - Tweak load order to prevent Promoter fatal. Ensure PUE gets loaded first.
* Tweak - License validation now runs consistently during plugin activation, ensuring licenses are recognized earlier. [TEC-5351]
* Tweak - Removed actions: `tec_common_ian_loaded`
* Language - 0 new strings added, 32 updated, 1 fuzzied, and 0 obsoleted.

### [6.5.0] 2025-01-30

* Fix - Update asset, dependencies, customizations to align with WordPress 6.7 and React 18. [TEC-5322]
* Language - 0 new strings added, 23 updated, 1 fuzzied, and 0 obsoleted.

### [6.4.2] 2025-01-22

* Tweak - Move Action Scheduler into Common instead of TEC. [TEC-5345]
* Tweak - When installing new plugins `TEC_IS_ANY_LICENSE_VALID_TRANSIENT` will update correctly. [TEC-5332]
* Tweak - Added actions: `tec_pue_checker_init`, `tec_help_hub_iframe_header`
* Fix - Fix fatals due to undefined properties [TCMN-179]
* Fix - Improved data sanitization for tribe_pue_key_notices to prevent memory exhaustion errors caused by corrupted data. [ET-2277]
* Fix - Resolve warning about deprecation of passing null to version_compare function.
* Language - 0 new strings added, 57 updated, 1 fuzzied, and 0 obsoleted.

### [6.4.1] 2024-12-17

* Feature - Add an abstract admin page to start consolidating how we do admin pages. See the "First Time Setup" page (onboarding wizard) for an example. [TEC-5294]
* Tweak - Ensure we are not loading any assets from node_modules. Include anything we need as a 3rd party code in our plugin. [TCMN-175]
* Fix - Cast `$block` argument to string to avoid PHP 8+ deprecation notice when non string (or array) variables are passed as the 3rd argument of `preg_replace`.
* Fix - Correctly identify licenses using uplink, like Event Tickets Plus. [n/a]
* Fix - Ensure that number_format is used with a float value to prevent issues with PHP 8.0+. [ETP-962]
* Fix - Ensure we get an object to test for subnav. Pass the object to class filter for more context. [n/a]
* Fix - Prevent fatal on ET integration page when used with Events Pro but without Event Tickets Plus. [TCMN-174]
* Deprecated - Integrations Tab registration in Event Ticket Settings from common. These will be registered from Event Tickets Plus only instead. [TCMN-174]
* Language - 0 new strings added, 33 updated, 1 fuzzied, and 0 obsoleted.

### [6.4.0] 2024-12-05

* Feature - In-App Notifications system. [TEC-5165]
* Tweak - Added filters: `tec_common_ian_opt_in`, `tec_common_ian_conditional_php`, `tec_common_ian_conditional_wp`, `tec_common_ian_allowed_pages`, `tec_common_ian_show_icon`, `tec_common_ian_setting_optin_tooltip`, `tec_common_ian_api_url`, `tec_common_ian_slugs`, `tec_common_ian_render`
* Tweak - Added actions: `tec_common_ian_loaded`
* Language - 22 new strings added, 15 updated, 1 fuzzied, and 0 obsoleted.

### [6.3.2] 2024-11-19

* Feature - Implemented the core Help Hub logic, providing a flexible framework for managing support integrations, resource templates, and plugin-specific customization.
* Feature - Introduced Asset interface which accounts for symlinks, while still provides a fluent api. [SL-246]
* Feature - Update stellarwp/assets to version 1.4.2. [SL-246]
* Tweak - Added actions: `tec_help_hub_before_render`, `tec_help_hub_after_render`, `tec_help_hub_before_iframe_render`, `tec_help_hub_after_iframe_render`, `tec_help_hub_registered`.
* Tweak - Added filters: `tec_help_hub_resource_sections_{$data_class_name}`, `tec_help_hub_resource_sections`, `tec_help_hub_body_classes`.
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted.

### [6.3.1] 2024-11-04

* Fix - Prevent new Settings pages to over sanitize textarea fields, thus removing HTML from before/after in the Events UI. [TEC-5283]
* Fix - Include backwards compatibility for deprecated proprieties in the Settings class used in The Events Calendar and Event Tickets [TEC-5312]

### [6.3.0] 2024-10-30

* Feature - Added integration with new premium Seating product for Event Tickets.
* Language - 0 new strings added, 23 updated, 1 fuzzied, and 0 obsoleted

### [6.2.0] 2024-10-17

* Feature - New Conditional Content supporting classes with available Traits for User targeted Dismissal.
* Fix - Allow more svg attributes through the fields sanitization. [TEC-5282]
* Tweak - Remove all of the deprecated Marketing related classes and files, as they are no longer used.
* Tweak - Added filters: `tec_settings_sidebar_sections`
* Tweak - Removed filters: `tribe_black_friday_start_time`, `tribe_black_friday_end_time`
* Tweak - Added actions: `tec_conditional_content_black_friday`
* Deprecated - Removed deprecated classes: `Tribe\Admin\Notice\Marketing`, `Tribe\Admin\Notice\Marketing\Black_Friday`, `Tribe\Admin\Notice\Marketing\End_Of_Year_Sale`, `Tribe\Admin\Notice\Marketing\Stellar_Sale`, `Tribe\Admin\Notice\Conditional_Content\`, `Tribe\Admin\Notice\Marketing\Black_Friday`, `Tribe\Admin\Notice\Marketing\End_Of_Year_Sale`, `Tribe\Admin\Notice\Marketing\Stellar_Sale`
* Language - 1 new strings added, 44 updated, 1 fuzzied, and 22 obsoleted

### [6.1.0] 2024-09-26

* Feature - Update core settings functionality and styles to allow for new plugin settings layout. [TEC-5124]
* Feature - Update settings field generation and add functionality to facilitate sidebars on settings pages. [TEC-5137]
* Tweak - Added filters: `tribe_field_output_{$this->type}`, `tribe_field_output_{$this->type}_{$this->id}`, `tec_settings_page_logo_source`, `tribe_settings_wrap_classes`, `tribe_settings_form_class`, `tribe_settings_tab_{$key}`
* Tweak - Removed filters: `tribe_field_output_`, `tribe_settings_form_element_tab_`, `tribe_settings_tab_`
* Tweak - Added actions: `tec_settings_sidebar_start`, `tec_settings_sidebar_header_start`, `tec_settings_sidebar_header_end`, `tec_settings_sidebar_end`, `tec_settings_init`, `tec_settings_render_modal_sidebar`, `tribe_settings_tab_after_link`, `tec_settings_tab_licenses`
* Language - 16 new strings added, 138 updated, 6 fuzzied, and 18 obsoleted

### [6.0.3.1] 2024-09-16

* Security - Improve general escaping for ORM queries to prevent legacy Events methods to be used for SQL injections.

### [6.0.3] 2024-09-09

* Feature - Adding the method `tec_copy_to_clipboard_button` which can be used to print a button which on click would copy a text to the user's clipboard. [ET-2158]
* Fix - Bug when term slugs were numeric.
* Fix - Optimized prime_term_cache to return early when no posts are provided [TEC-5150]

### [6.0.2] 2024-08-20

* Fix - Fixed attendee, updated attendee, and checkin endpoints from having invalid response for workflow operation 'id' to be of type 'Integer' but is of type 'String'. [EVA-160]
* Fix - Stellar Sale's banner links, details, and HTML tags handling. [TEC-5121]
* Tweak - Change setup of queues for Automator integrations to use Event Tickets Plus and Events Calendar Pro hooks instead of the core versions. [EVA-160]
* Tweak - Move Registering of Power Automate and Zapier endpoints to Event Tickets Plus and Events Calendar Pro. [EVA-160]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [6.0.1] 2024-08-06

* Fix - Change hook to detect if TEC is active for Automator Event Endpoints used by Power Automate and Zapier. [TEC-5123]
* Fix - Move action pre-dispatch filters for Zapier to Event Tickets Plus and Events Calendar Pro to fix not authorized errors. [TEC-5123]
* Language - 0 new strings added, 10 updated, 1 fuzzied, and 1 obsoleted

### [6.0.0.2] 2024-07-24

* Fix - Stellar Sale's banner links, details, and HTML tags handling. [TEC-5121]

### [6.0.0.1] 2024-07-23

* Fix - Prevent Fatal on WooCommerce Order transition when Event Tickets plugin is not present. [EVA-166]

### [6.0.0] 2024-07-22

* Feature - Included compatibility with Events Calendar Pro 7.0.0 for integrations with Zapier and Power Automate.
* Feature - Included compatibility with Events Tickets Plus 6.0.0 for integrations with Zapier and Power Automate.
* Fix - Fixed an issue where admin transient notices with the dismiss flag not persisting passed the first page load. [ECP-1808]
* Fix - The Decorated repository was not returning values from `save()` and other methods. Now they return as expected. [BTRIA-2310]
* Fix - Resolved an issue where transient notices would disappear given a certain order of operations. [ECP-1804]
* Tweak - Added a new action hook `tec_event_automator_zapier_provider_registered` to fire after the Zapier service has successfully registered. [EVA-159]
* Tweak - Added filters: `tec_event_automator_integrations_tab_settings`, `tec_tickets_plus_integrations_tab_fields`, `tec_event_automator_{$api_id}_dashboard_fields`, `tec_event_automator_{$api_id}_api_get_user_arguments`, `tec_event_automator_{$api_id}_settings_fields`, `tec_event_automator_{$api_id}_settings_field_placement_key`, `tec_event_automator_rest_swagger_documentation`, `tec_event_automator_{$api_id}_endpoint_details`, `tec_event_automator_{$api_id}_add_to_queue_data`, `tec_event_automator_{$api_id}_add_to_queue_data_{$endpoint_id}`, `tec_event_automator_{$api_id}_is_rest_request`, `tec_event_automator_{$api_id}_enable_add_to_queues`, `tec_event_automator_{$api_id}_add_to_queue`, `tec_event_automator_{$api_id}_max_queue_items`, `tec_event_automator_{$api_id}_max_queue_items_{$queue_name}`, `tec_event_automator_power_automate_admin_ajax_capability`, `tec_event_automator_power_automate_enabled`, `tec_event_automator_power_automate_enable_add_to_queue`, `tec_event_automator_integration_app_name`, `tec_automator_map_attendee_details`, `tec_automator_map_edd_order_details`, `tec_automator_map_tickets_commerce_order_details`, `tec_automator_map_woo_order_details`, `tec_automator_map_event_details`, `tec_automator_map_all_organizers`, `tec_automator_map_organizer_details`, `tec_automator_map_all_venues`, `tec_automator_map_venue_details`, `tec_automator_map_ticket_details`, `tec_event_automator_zapier_app_name`, `tec_event_automator_zapier_admin_ajax_capability`, `tec_event_automator_zapier_enabled`, `tec_event_automator_zapier_enable_add_to_queue`
* Tweak - Added actions: `tec_automator_before_update_{$api_id}_api_keys`, `tec_automator_before_update_zapier_api_keys`, `tec_event_automator_zapier_provider_registered`
* Language - 271 new strings added, 313 updated, 1 fuzzied, and 25 obsoleted

### [5.3.1] 2024-07-18

* Tweak - Support additional select2 attributes in order to improve search performance in select2 fields.
* Language - 0 new strings added, 31 updated, 1 fuzzied, and 0 obsoleted

### [5.3.0.5] 2024-07-11

* Fix - Ensure compatibility with WordPress 6.6 for removed polyfill `regenerator-runtime`. [TEC-5120]

### [5.3.0.4] 2024-06-18

* Fix - In installations where the plugins or wp-content directories were symbolic linked, assets would fail to be located. [TEC-5106]
* Language - 0 new strings added, 0 updated, 0 fuzzied, and 0 obsoleted

### [5.3.0.3] 2024-06-14

* Fix - Issue where scripts would not be enqueued as modules. [ET-2136]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.3.0.2] 2024-06-14

* Fix - Windows Server compatibility issues with updated Assets handling. [TEC-5104]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.3.0.1] 2024-06-13

* Fix - Issue on which some assets (css,js) would not be located in WP installs which could have some WP constant modified (WP_CONTENT_DIR, WP_PLUGIN_DIR)[TEC-5104]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.3.0] 2024-06-11

* Feature - Refactor tribe_asset to use Stellar Assets. [TCMN-172]
* Tweak - Remove ini_check for deprecated safe_mode. [6.2.0]
* Tweak - Added information about upcoming promotion. [ET-2113]
* Tweak - Added filters: `tribe_asset_enqueue_{$asset->get_slug()}`
* Tweak - Removed filters: `tribe_asset_enqueue_{$asset->slug}`, `tribe_asset_pre_register`
* Language - 7 new strings added, 5 updated, 2 fuzzied, and 0 obsoleted

### [5.2.7] 2024-05-14

* Fix - Add dir/filename of `event-automator` in the Plugins_API to fix CTA button text/links in the Help section. [TEC-5071]
* Tweak - Add `aria-hidden="true"` to icons so screen readers ignore it. [TEC-5019]
* Tweak - Updated our `query-string` javascript library to version 6.12. [TEC-5075]
* Tweak - Add Events Schedule Manager cards in the Help and App Shop admin pages to promote. [TEC-5058]
* Tweak - Prevent potential conflict by changing all calls to select2 to our internal select2TEC version. [TCMN-170]
* Tweak - Removed filters: `tec_help_calendar_faqs`, `tec_help_calendar_extensions`, `tec_help_calendar_products`, `tec_help_ticketing_faqs`, `tec_help_ticketing_extensions`, `tec_help_ticketing_products`
* Tweak - Changed views: `v2/components/icons/arrow-right`, `v2/components/icons/caret-down`, `v2/components/icons/caret-left`, `v2/components/icons/caret-right`, `v2/components/icons/search`
* Language - 6 new strings added, 161 updated, 2 fuzzied, and 0 obsoleted

### [5.2.6] 2024-04-18

* Tweak - Added the `position` parameter for submenu pages on the admin pages class. [ET-1707]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.2.5] 2024-04-09

* Tweak - Improve compatibility with some theme styling re: calendar buttons. [TEC-5047]
* Tweak - Rename the `Controller_Test_Case` `setUp` and `tearDown` methods and annotate them with `@before` and `@after` annotations to improve PHPUnit version cross-compat.
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted.

### [5.2.4] 2024-03-20

* Fix - Resolves a PHP 8.2 deprecation error on `Date_Utils` - `PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /.../wp-content/plugins/the-events-calendar/common/src/Tribe/Date_Utils.php on line 256`. [ECP-1620]
* Fix - This fixes an issue where a template with a duplicate name but located in different folders is called it would always reference the first file. Updated the key to be unique by folder as well. [ECP-1627]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.2.3] 2024-02-19

* Tweak - Refactor JS logic to prevent ticketing of recurring events. [ET-1936]
* Fix - Better clean up of global space in Controller test case. [ET-1936]

### [5.2.2] 2024-02-19

* Tweak - Added timezone param to our date utility function `Date_Utils::reformat`. [TEC-5042]
* Language - 1 new strings added, 4 updated, 6 fuzzied, and 0 obsoleted

### [5.2.1] 2024-01-24

* Feature - Add the `get_country_based_on_code` method to the `Tribe__Languages__Locations` class. [EA-469]
* Feature - Enable auto-updates for premium plugins.
* Fix - Correct some signatures in the Tribe__Data class so they conform to the classes it implements, avoiding deprecation notices. [TEC-4992]
* Fix - Fix PHP 8.2 deprecation errors `PHP Deprecated:  html_entity_decode(): Passing null to parameter #1 ($string) of type string is deprecated`. [ECP-1603]
* Tweak - Update the DataTables library used by Event Aggregator. [EA-479]
* Tweak - Improve the notice dismissal logic with more modern JavaScript and PHP.
* Tweak - Added filters: `tec_dialog_id`, `tribe_repository_{$this->filter_name}_before_delete`
* Language - 0 new strings added, 20 updated, 4 fuzzied, and 0 obsoleted

### [5.2.0] 2024-01-22

* Feature - Add the `Tribe__Repository::first_id` method to fetch the first ID of a query. [ET-1490]
* Feature - Add the 'Tribe__Repository__Query_Filters::meta_not' method to work around costly meta queries.
* Feature - Add the 'Tribe__Repository__Query_Filters::meta_not' method to work around costly meta queries.
* Feature - Fire an action on Service Provider registration; register Service Providers on action with `Container::register_on_action`.
* Fix - Ensure we output valid html around <dt> and <dd> elements in an accessible way. [TEC-4812]
* Tweak - Add the `set_request_context( ?string $context)` and `get_request_context(): ?string` methods to the `Tribe__Repository__Interface` and classes. [ET-1813]
* Tweak - Ticketing & RSVP tab selected by default when clicking Help from the Tickets menu. [ET-1837]
* Language - 0 new strings added, 8 updated, 1 fuzzied, and 0 obsoleted

### [5.1.17] 2023-12-14

* Fix - Adding a param safe list to validate input for Select2 usage on AJAX requests. [BTRIA-2148]
* Language - 0 new strings added, 24 updated, 2 fuzzied, and 0 obsoleted

### [5.1.16] 2023-12-13

* Tweak - Include Wallet Plus on Add-Ons Page. [ET-1932]
* Tweak - Include Wallet Plus on Help Page. [ET-1931]
* Language - 7 new strings added, 54 updated, 1 fuzzied, and 0 obsoleted

### [5.1.15.2] 2023-12-04

* Fix - Ensure correct access rights to JSON-LD data depending on the user role. [TEC-4995]
* Language - 0 new strings added, 21 updated, 1 fuzzied, and 0 obsoleted

### [5.1.15.1] 2023-11-20

* Security - Ensure all password protected posts have their settings respected. [TCMN-167]

### [5.1.15] 2023-11-16

* Fix - Ensure the JavaScript module assets are properly getting the `type="module"` added on all scenarios [ET-1921]
* Language - 0 new strings added, 11 updated, 1 fuzzied, and 2 obsoleted

### [5.1.14] 2023-11-13

* Tweak - Added pre-check filter `tribe_repository_{$this->filter_name}_before_delete` to enable overriding the `Repository` delete operation. [TEC-4935]
* Fix - Resolved several `Deprecated: Creation of dynamic property` warnings on: `\Tribe__Field::$allow_clear, $type, $class, $label, $label_attributes, $error, $tooltip, $size, $html, $options, $value, $conditional, $placeholder, $display_callback, $if_empty, $can_be_empty, $clear_after, $tooltip_first` and `\Tribe__Settings_Tab::$priority, public $fields, $show_save, $display_callback, $network_admin` [BTRIA-2088]
* Language - 2 new strings added, 9 updated, 1 fuzzied, and 2 obsoleted.

### [5.1.13.1] 2023-11-10

* Fix - Update Telemetry library to prevent potential fatals. [TEC-4978]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.1.13] 2023-11-08

* Tweak - Ensure stability of opt-in data.

### [5.1.12] 2023-11-01

* Tweak - Ticketing & RSVP tab selected by default when clicking Help from the Tickets menu. [ET-1837]
* Language - 0 new strings added, 124 updated, 1 fuzzied, and 0 obsoleted

### [5.1.11] 2023-10-19

* Tweak - Changed scope of the Tribe__Editor__Blocks__Abstract::$namespace property to protected. [TEC-4792]
* Fix - AM/PM time formats `g:i A` and `g:i a` are now respected for the French locale. [TEC-4807]
* Tweak - Pass the appropriate arguments to telemetry opt-ins.
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.1.10.1] 2023-10-12

* Fix - Correct a problem that can cause a fatal when plugins are deactivated in a certain order. [TEC-4951]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.1.10] 2023-10-11

* Tweak - Add the `tec_cache_listener_save_post_types` filter to allow filtering the post types that should trigger a cache invalidation on post save. [ET-1887]
* Tweak - Updates to the Date_Based banner functionality. [ET-1890]
* Language - 2 new strings added, 2 updated, 1 fuzzied, and 2 obsoleted

### [5.1.9] 2023-10-03

* Tweak - Updated focus state for relevant elements to have default outline ensuring improved accessibility and consistent browser behavior. [TEC-4888]
* Fix - Resolved "Uncaught ReferenceError: lodash is not defined" error by adding `lodash` as a dependency for the Block Editor Assets. [ECP-1575]
* Language - 0 new strings added, 9 updated, 1 fuzzied, and 0 obsoleted

### [5.1.8.1] 2023-09-28

* Fix - Correct issue where Telemetry would register active plugins multiple times. [TEC-4920]
* Fix - Ensure Telemetry's `register_tec_telemetry_plugins()` only runs on the plugins page i.e. on plugin activation. [TEC-4920]

### [5.1.8] 2023-09-13

* Tweak - Compress the size of all images used by the Common module, to reduce the size of the plugin
* Tweak - Set background image to none on the button element to prevent general button styling overrides. [ET-1815]
* Tweak - Add the `set_request_context( ?string $context)` and `get_request_context(): ?string` methods to the `Tribe__Repository__Interface` and classes. [ET-1813]
* Tweak - Ticketing & RSVP tab selected by default when clicking Help from the Tickets menu. [ET-1837]

### [5.1.7] 2023-09-05

* Fix - Broken UI on the WYSIWYG field in the Additional Content section of the admin display settings. [TEC-4861]
* Fix - Resolves a plugin integration bug that happens in certain scenarios with instantiating `Firebase\JWT` library classes. In these scenarios you would see a fatal error similar to `Uncaught TypeError: TEC\Common\Firebase\JWT\JWT::getKey(): Return value must be of type TEC\Common\Firebase\JWT\Key, OpenSSLAsymmetricKey returned..` [TEC-4866]
* Fix - WP Rewrite was being incorrectly initialized in some scenarios due to container DI, and causing some 404s. This was affecting classes that extend the `Tribe__Rewrite`. [TEC-4844]
* Tweak - Add checks to ensure that settings don't pass null to wp_kses() or esc_attr() [6.2.0]
* Language - 0 new strings added, 6 updated, 1 fuzzied, and 0 obsoleted

### [5.1.6] 2023-08-15

* Feature - Add the 'Tribe__Repository__Query_Filters::meta_not' method to work around costly meta queries.

### [5.1.5] 2023-08-15

* Feature - Fire an action on Service Provider registration; register Service Providers on action with `Container::register_on_action`.
* Tweak - Added filters: `tec_block_has_block`, `tec_block_{$block_name}_has_block`, `tec_common_rewrite_dynamic_matchers`, `tec_shortcode_aliased_arguments`, `tec_shortcode_{$registration_slug}_aliased_arguments`
* Language - 0 new strings added, 23 updated, 1 fuzzied, and 0 obsoleted

### [5.1.5] 2023-08-15

* Version - This version was skipped due to a merge and packaging issue.

### [5.1.4] 2023-08-10

* Feature - Fire an action on Service Provider registration; register Service Providers on action with `Container::register_on_action`.
* Fix - Make use of `wp_date` to format dates and avoid translation issues with translating month names in other languages. [ET-1820]
* Fix - Ensure we output valid html around <dt> and <dd> elements in an accessible way. [TEC-4812]
* Tweak - Correct some issues around PHP 8.1 deprecations. [TEC-4871]
* Tweak - Added filters: `tec_integration:should_load`, `tec_integration:{$parent}/should_load`, `tec_integration:{$parent}/{$type}/should_load`, `tec_integration:{$parent}/{$type}/{$slug}/should_load`, `tec_debug_info_sections`, `tec_site_heath_event_stati`, `tec_debug_info_field_get_{$param}`, `tec_debug_info_field_{$field_id}_get_{$param}`, `tec_debug_info_section_get_{$param}`, `tec_debug_info_section_{$section_slug}_get_{$param}`, `tec_common_timed_option_is_active`, `tec_common_timed_option_name`, `tec_common_timed_option_default_value`, `tec_common_timed_option_pre_value`, `tec_common_timed_option_value`, `tec_common_timed_option_pre_exists`, `tec_common_timed_option_exists`, `tec_telemetry_migration_should_load`, `tec_common_telemetry_permissions_url`, `tec_common_telemetry_terms_url`, `tec_common_telemetry_privacy_url`, `tec_common_telemetry_show_optin_modal`, `tec_telemetry_slugs`, `tec_admin_update_page_bypass`, `tec_disable_logging`, `tec_common_parent_plugin_file`, `tec_model_{$this->get_cache_slug()}_read_cache_properties`, `tec_model_{$this->get_cache_slug()}_put_cache_properties`, `tec_pue_invalid_key_notice_plugins`, `tec_pue_expired_key_notice_plugins`, `tec_pue_upgrade_key_notice_plugins`, `tec_common_rewrite_localize_matcher`
* Tweak - Removed filters: `tribe_google_data_markup_json`, `tribe_general_settings_tab_fields`
* Tweak - Added actions: `tec_container_registered_provider`, `tec_container_registered_provider_`, `tribe_log`, `tec_telemetry_auto_opt_in`, `tec_common_telemetry_preload`, `tec_common_telemetry_loaded`, `stellarwp/telemetry/optin`, `tec_locale_translations_load_before`, `tec_locale_translations_load_after`, `tec_locale_translations_restore_before`, `tec_locale_translations_restore_after`
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.1.3] 2023-07-13

* Tweak - Prevents Telemetry servers from being hit when migrating from Freemius to Telemetry more than once.
* Tweak - Various improvements to event creation to improve sanitization.
* Tweak - Update Stellar Sale banner. [TEC-4841]
* Fix - Properly handle plugin paths on Windows during telemetry booting. [TEC-4842]
* Language - 16 new strings added, 24 updated, 1 fuzzied, and 1 obsoleted.

### [5.1.2.2] 2023-06-23

* Fix - Ensure there is backwards compatibility with Extensions and Pods.

### [5.1.2.1] 2023-06-22

* Fix - Prevent Telemetry from being initialized and triggering a Fatal when the correct conditionals are not met.

### [5.1.2] 2023-06-22

* Fix - Lock our container usage(s) to the new Service_Provider contract in tribe-common. This prevents conflicts and potential fatals with other plugins that use a di52 container.

### [5.1.1.2] 2023-06-21

* Fix - Adjusted our PHP Exception usage to protect against third-party code causing fatals when attempting to access objects that have not been initialized.

### [5.1.1.1] 2023-06-20

* Fix - Adding Configuration feature, to enable simple feature flag and other checks, with less boilerplate. See [readme](https://github.com/the-events-calendar/tribe-common/pull/1923/files#diff-cf03646ad083f81f8ec80bbdd775d8ac45c75c7bc1bf302f6fb06dfa34a1dc64) for more details. [ECP-1505]
* Fix - In some scenarios the garbage collection of our query filters would slow page speeds. Removed garbage collection for the filters. [ECP-1505]
* Fix - Increase the reliability of Telemetry initialization for Event Tickets loading [TEC-4836]

### [5.1.1] 2023-06-15

* Feature - Include a Integrations framework that was ported from The Events Calendar.
* Enhancement - Made settings field widths more uniform and mobile-friendly. [ET-1734]
* Fix - Change image field styling for a better look and user experience.

### [5.1.0] 2023-06-14

* Feature - Replace Freemius with Telemetry - an in-house info system. [TEC-4700]
* Feature - Add architecture for adding our plugins to the Site Health admin page. [TEC-4701]
* Fix - Elementor and other themes would inadvertently override styles on the tickets button, when the global styles were set. This hardens the common button (rsv/ticket button) styles a bit more. [TEC-4794]
* Tweak - Update our container architecture.
* Tweak - Added filters: `tec_common_rewrite_localize_matcher`
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.0.17] 2023-05-08

* Feature - Add the `TEC\Provider\Controller` abstract class to kick-start Controllers and the `TEC\Common\Tests\Provider\Controller_Test_Case` class to test them.
* Fix - Fix for the fatal `PHP Fatal error: Uncaught ArgumentCountError: Too few arguments to function Firebase\JWT\JWT::encode(), 2 passed` from other plugins using a different version of the `Firebase\JWT` library. Setup a Strauss namespaced version for this library. [TEC-4635]
* Fix - Fixes a cache bug that showed up in ECP-1475. The underlying issue was cache would carry stale data and not clear with the `save_post` trigger being hit repeatedly.
* Fix - Minor button style hardening to prevent some common theme global style bleed, namely from Elementor global styles. [TEC-4677]
* Tweak - Added filters: `tec_common_rewrite_localize_matcher`
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.0.15] 2023-04-10

* Fix - Update the Google Maps API setting url on the Troubleshooting page. [TEC-4728]
* Fix - Updates the Monolog repository to use TEC namespacing via Strauss, to provide more compatibility with other plugins. [TEC-4730]
* Tweak - Replace the use of `FILTER_SANITIZE_STRING` in favour of `tec_sanitize_string` to improve PHP 8.1 compatibility. [TEC-4666]
* Tweak - More flexible filtering of localized and dynamic matchers in the Rewrite component to allow easier rewrite rules translation. [TEC-4689]
* Tweak - Added filters: `tec_common_rewrite_localize_matcher`
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [5.0.14] 2023-04-03

* Fix - Fixed issue with "Upload Theme" button not working properly when a notification was displayed on the Theme page. [CT-77]
* Enhancement - Added an `email_list` validation check for validating a delimited string of valid email addresses. [ET-1621]
* Tweak - Fix styles for checkboxes and toggle, to have the description in the same line. [ET-1692]
* Language - 1 new strings added, 6 updated, 1 fuzzied, and 0 obsoleted

### [5.0.13] 2023-03-20

* Feature - Add the `is_editing_posts_list` method to the `Tribe__Context` class. [APM-5]
* Feature - Add the `Tribe__Context::is_inline_editing_post` method.
* Fix - Fix a false positive on checking if a cache value is set after cache expiration passed.
* Tweak - Extract `TEC\Common\Context\Post_Request_Type` class from `Tribe__Context` class; proxy post request type methods to it.
* Tweak - Removed actions: `tribe_log`
* Tweak - Changed views: `single-event`, `v2/day/event/featured-image`, `v2/latest-past/event/featured-image`, `v2/list/event/featured-image`, `v2/month/calendar-body/day/calendar-events/calendar-event/featured-image`, `v2/month/calendar-body/day/calendar-events/calendar-event/tooltip/featured-image`, `v2/month/mobile-events/mobile-day/mobile-event/featured-image`, `v2/widgets/widget-events-list/event/date-tag`

### [5.0.12] 2023-03-08

* Enhancement - Added a way to customize the WYSIWYG editor field by passing in a `settings` parameter. [ET-1565]
* Feature - Added new toggle field for settings in the admin area. [ET-1564]

### [5.0.11] 2023-02-22

* Tweak - PHP version compatibility bumped to PHP 7.4
* Tweak - Version Composer updated to 2
* Tweak - Version Node updated to 18.13.0
* Tweak - Version NPM update to 8.19.3
* Tweak - Reduce JavaScript bundle sizes for Blocks editor

### [5.0.10] 2023-02-09

* Feature - Add new `get_contrast_color` and `get_contrast_ratio` methods to the color utility for determining contrasting colors. [ET-1551]
* Feature - Add the stellarwp/db library and configure it.
* Feature - Add the stellarwp/installer library and bootstrap it.
* Fix - Set max width to image in image setting field. [ET-1597]
* Fix - Added safeguard against the `rewrite_rules_array` filter being passed non-array values, avoids fatal. [TEC-4679]
* Tweak - Added filters: `tec_disable_logging`
* Language - 0 new strings added, 21 updated, 1 fuzzied, and 0 obsoleted

### [5.0.9] 2023-01-26

* Feature - Add Event Automator to Add-ons and Help page. [TEC-4660]
* Language - 7 new strings added, 140 updated, 1 fuzzied, and 2 obsoleted.

### [5.0.8] 2023-01-19

* Fix - Correct handling of translated slugs in rewrite context. [TEC-3733]
* Fix - Handle the case where rewrite rules map to arrays avoiding fatal errors. [TEC-4567]
* Tweak - Allow disabling the Logger by setting the `TEC_DISABLE_LOGGING` constant or environment variable to truthy value or by means of the `tec_disable_logging` filter. [n/a]

### [5.0.7] 2023-01-16

* Tweak - Added a dashboard notice for sites running PHP versions lower than 7.4 to alert them that the minimum version of PHP is changing to 7.4 in February 2023.
* Language - 1 new strings added, 0 updated, 1 fuzzied, and 2 obsoleted

### [5.0.6] 2022-12-14

* Feature - Include `Timed_Options` as a storage for simple replacement for Flags, avoiding Transients for these cases to improve performance and reliability. [TEC-4413]
* Fix - Prevent calls to `supports_async_process` that were slowing down servers due to not stopping reliably once a decision was made [TEC-4413]
* Fix - Ensure the `clear country` icon resets the value as expect in the create/edit venue page. [TEC-4393]
* Tweak - Added filters: `tec_common_timed_option_is_active`, `tec_common_timed_option_name`, `tec_common_timed_option_default_value`, `tec_common_timed_option_pre_value`, `tec_common_timed_option_value`, `tec_common_timed_option_pre_exists`, `tec_common_timed_option_exists`
* Language - 0 new strings added, 21 updated, 1 fuzzied, and 0 obsoleted

### [5.0.5] 2022-12-08

* Tweak - Sync `tribe-common-styles` to its latest, in order to fix styling issues. [ETP-828]

### [5.0.4] 2022-11-29

* Fix - Fixed a bug where the `Tribe\Utils\Taxonomy::prime_term_cache()` method would throw on invalid term results (thanks @shawfactor). [TCMN-160]
* Tweak - Add some styling for the ECP View teasers. [TCMN-149]
* Tweak - Move the General and Display settings tab content to TEC. [TCMN-149]
* Tweak - Removed filters: `tribe_general_settings_tab_fields`.
* Language - 6 new strings added, 17 updated, 3 fuzzied, and 26 obsoleted.

### [5.0.3] 2022-11-15

* Fix - Prevent `Lazy_String` from ever returning anything that is not a string, avoiding PHP 8.1 warnings. Props @amiut
* Fix - Ensure the TEC timezone settings are applied correctly when using a combination of the WP Engine System MU plugin and Divi or Avada Themes. [TEC-4387]
* Fix - Ensure that when filtering script tags we return the expected string no matter what we're given. [TEC-4556]
* Language - 0 new strings added, 1 updated, 1 fuzzied, and 0 obsoleted.

### [5.0.2.1] 2022-11-03

* Fix - Refactor the Post model code to avoid serialization/unserialization issues in object caching context. [TEC-4379]

### [5.0.2] 2022-10-20

* Feature - Adds a new `by_not_related_to` repository method for retrieving posts not related to other posts via a meta_value [ET-1567]
* Fix - Update version of Firebase/JWT from 5.x to 6.3.0
* Fix - Prevents fatal around term cache primer with empty object ID or term name.
* Fix - Prevent Warnings from Lazy_String on PHP 8.1 [5.0.6]
* Tweak - Support replacement license keys in premium products and services.
* Tweak - Deprecated the `Tribe__Settings_Manager::add_help_admin_menu_item()` method in favour of `Settings::add_admin_pages()`. [TEC-4443]
* Tweak - Add a function to Tribe__Date_Utils to determine if "now" is between two dates. [TEC-4454]
* Language - 0 new strings added, 14 updated, 1 fuzzied, and 0 obsoleted.

### [5.0.1] 2022-09-22

* Fix - Avoid invoking unwanted callables with ORM post creation/updates. [ET-1560]
* Tweak - patch some PHP8 compatibility and ensure we don't try to test globals that might not be set. (props to @theskinnyghost for the implode fix!)  [TEC-4453]
* Language - 0 new strings added, 1 updated, 1 fuzzied, and 0 obsoleted

### [5.0.0.1] 2022-09-07

* Fix - Prevent `E_ERROR` from showing up when calling `tribe_context()->is( 'is_main_query' )` too early in execution. [TEC-4464]

### [5.0.0] 2022-09-06

* Feature - Set the Logger logging threshold do DEBUG when WP_DEBUG is defined.
* Fix - Avoid fatal errors when transient notices are registered from inactive plugins.
* Tweak - Allow suppression of admin notices for specific plugins via the filters `tec_pue_expired_key_notice_plugins`, `tec_pue_invalid_key_notice_plugins`, and `tec_pue_upgrade_key_notice_plugins`.
* Language - 2 new strings added, 185 updated, 1 fuzzied, and 1 obsoleted

### [4.15.5] 2022-08-15

* Feature - Added image field for settings in the admin area. [ET-1541]
* Feature - Added color field for settings in the admin area. [ET-1540]
* Tweak - Prevent a possible infinite hook loop. [ECP-1203]
* Language - 4 new strings added, 104 updated, 3 fuzzied, and 2 obsoleted.

### [4.15.4.1] 2022-07-21

* Fix - Update Freemius to avoid PHP 8 fatals. [TEC-4330]

### [4.15.4] 2022-07-20

* Tweak - Implement 2022 Stellar Sale banner. [TEC-4433]
* Tweak - Added filters: `tribe_{$this->slug}_notice_extension_date`
* Tweak - Changed views: `v2/components/icons/stellar-icon`
* Language - 2 new strings added, 4 updated, 1 fuzzied, and 0 obsoleted

### [4.15.3] 2022-07-06

* Fix - Correct some hardcoded admin URLs. [ECP-1175]
* Tweak - Add a target ID for the EA Troubleshooting page link. [TEC-4403]

### [4.15.2] 2022-06-08

* Fix - Only show Event Aggregator status on the troubleshooting page if Event Aggregator is accessible. [ET-1517]

### [4.15.1] 2022-05-31

* Feature - Add Calendar Export icon as a template. [TEC-4176]
* Tweak - Add Stellar Discounts tab in Event Add-Ons
* Tweak - Element Classes now will support callbacks inside of arrays as well as non boolean values that are validated by `tribe_is_truthy`
* Tweak - Add Stellar Discounts tab in Event Add-Ons. [TEC-4302]
* Fix - On the import preview screen when ctrl/shift click to multi-select rows make sure all the in between rows are counted as selected. [EA-123]
* Language - 21 new strings added, 46 updated, 1 fuzzied, and 0 obsoleted

### [4.15.0.1] 2022-05-23

* Fix - Check if function exists for `get_current_screen` to avoid a fatal if not.

### [4.15.0] 2022-05-19

* Feature - Introducing new admin pages structure and updating the settings framework to have Settings on multiple pages. [ET-1335]
* Tweak - Add Stellar Discounts tab in Event Add-Ons
* Language - 0 new strings added, 150 updated, 0 fuzzied, and 43 obsoleted

### [4.14.20.1] 2022-05-12

* Tweak - Modify PUE Checker class to support faster and more reliable license checking [ET-1513]

### [4.14.20] 2022-05-11

* Fix - Fixed missing target and rel attribute for admin view links. [ETP-792]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.14.19] 2022-04-27

* Tweak - Add long-term license validation storage using options in addition to transients. [ET-1498]
* Language - 0 new strings added, 26 updated, 1 fuzzied, and 1 obsoleted.

### [4.14.18.1] 2022-04-28

* Fix - Undo reversion.

### [4.14.18] 2022-04-28

* Feature - First iteration of changes for Full Site Editor compatibility. [TEC-4262]
* Tweak - Added EA status row showing if it is enabled or disabled in the Event Aggregator system status [TCMN-134]
* Tweak - Added actions: `tec_start_widget_`, `tec_end_widget_`.
* Fix - Ensure the Classic Editor "forget" parameter overrides all else when loading the editor w/Classic Editor active. [TEC-4287]
* Fix - Do not autoload options used to save batched data. [EA-427]
* Fix - Update bootstrap logic to make sure Common will correctly load completely in the context of plugin activations requests. [TEC-4338]
* Language - 1 new strings added, 29 updated, 1 fuzzied, and 2 obsoleted.

### [4.14.17] 2022-04-05

* Feature - New customizable upsell element to offer upgrades, additions and services that are available. [ET-1351]
* Fix - Updated Dropdown functionality to work with PHP8, thanks @huubl. [CE-141]
* Tweak - Changed the wording to include upgrading required plugins to reduce confusion. [TCMN-132]
* Language - 2 new strings added, 1 updated, 1 fuzzied, and 1 obsoleted

### [4.14.16] 2022-03-15

* Fix - Modify logic of `filter_modify_to_module` so that we can safely set as module those assets that are loaded in themes without support for `html5`, `scripts`. [ET-1447]
* Fix - Ensure our full common variables file requires the skeleton variables. [TEC-4308]
* Fix - Correct Troubleshooting Menu Item label in Admin Bar. [TEC-4310]
* Language - 0 new strings added, 24 updated, 1 fuzzied, and 0 obsoleted

### [4.14.15] 2022-03-01

* Tweak - Update version of Freemius to 2.4.3.

### [4.14.14] 2022-02-24

* Feature - The PUE Checker now stores a transient with the status of the last license key check.
* Language - 0 new strings added, 49 updated, 1 fuzzied, and 0 obsoleted

### [4.14.13] 2022-02-15

* Tweak - Prevent scripts from loading on all Admin pages, only load on pages needed.
* Tweak - Performance improvements around Block Asset loading and redundancy.
* Tweak - Internal caching of values to reduce `get_option()` call count.
* Tweak - Switch from `sanitize_title_with_dashes` to `sanitize_key` in a couple instances for performance gains.
* Tweak - Prevent asset loading from repeating calls to plugin URL and path, resulting in some minor performance gains.
* Fix - Update the way we handle Classic Editor compatibility. Specifically around user choice. [TEC-4016]
* Fix - Remove incorrect reference for moment.min.js.map [TEC-4148]
* Fix - Fixed troubleshooting page styles for standalone Event Tickets setup [ET-1382]
* Fix - Remove singleton created from a deprecated class.
* Language - 0 new strings added, 38 updated, 1 fuzzied, and 0 obsoleted

### [4.14.12] 2022-01-17

* Fix - Prevent Onboarding assets from loading on the admin when not needed.
* Tweak - Included new filter `tec_system_information` allowing better control over the Troubleshooting Help page.

### [4.14.11] 2022-01-10

* Fix - Alter logic to not test regex with missing delimiters fail them as invalid immediately. [TEC-4180]
* Language - 0 new strings added, 4 updated, 1 fuzzied, and 0 obsoleted

### [4.14.10] 2021-12-20

* Fix - Initial steps to make The Events Calendar compatible with PHP 8.1

### [4.14.9] 2021-12-14

* Feature - Add loader template for the admin views. [VE-435]
* Feature - Included Price, Currency and Value classes to improve monetary handling from Common [ET-1331]
* Tweak - Included End of Year Sale promotion to the General Settings panel and banner. [TCMN-129]
* Fix - Prevent PHP 8 warnings when using extensions. (props to @huubl for this fix!) [TEC-4165]
* Fix - Modify the encoding for Help Page data to enable a better experience when sharing with support.
* Language - 5 new strings added, 4 updated, 1 fuzzied, and 0 obsoleted

### [4.14.8] 2021-11-17

* Feature - Add link to TEC customizer section in admin menu and on Event->Settings->Display page [TEC-4126]
* Feature - Adding Onboarding functionality, featuring `Tours` and `Hints`.
* Tweak - Added the `tribe_repository_{$filter_name}_pre_first_post`, `tribe_repository_{$filter_name}_pre_last_post`, and `tribe_repository_{$filter_name}_pre_get_ids_for_posts` actions. (Props to @sc0ttkclark)
* Language - 10 new strings added, 3 updated, 1 fuzzied, and 0 obsoleted

### [4.16.7] 2021-11-04

* Feature - Added Black Friday promo to the General Settings panel. [TCMN-127]
* Tweak - Update Black Friday banner. [TCMN-126]

### [4.14.6] 2021-10-12

* Fix - Ensure all SVG elements have unique IDs to improve accessibility. [TEC-4064]
* Fix - Ensure the proper domain name is sent to PUE when validating licenses. [TCMN-122]
* Fix - Correct block use checks around the Classic Editor plugin. [TEC-4099]

### [4.14.5] 2021-09-14

* Fix - Ensure all the content within the recent template changes section in the troubleshooting page is visible. [TEC-4062]
* Fix - Updated dropdowns controlled via ajax to return unescaped html entities instead of the escaped version. [CE-97]
* Fix - Ensure Troubleshooting page has the required DOM pieces and the call to TEC.com works as expected. [TEC-4052]w
* Fix - Updated dropdowns controlled via ajax to return unescaped html entities instead of the escaped version. [CE-97]
* Language - 6 new strings added, 88 updated, 1 fuzzied, and 2 obsoleted

### [4.14.4] 2021-08-31

* Tweak - Separation of the CSS variables and the Media Queries which are still compiled into the build Assets.
* Language - 0 new strings added, 22 updated, 1 fuzzied, and 0 obsoleted

### [4.14.3] 2021-08-24

* Feature - Added a new Warning dialog for the Dialog API. [ECP-901]
* Feature - Alter common postcss to leverage exposed namespaced custom properties from common-styles. [TCMN-104]
* Feature - Add new custom Customizer controls - Number, Range Slider, Toggle. [TEC-3897]
* Tweak - Added a `tribe_post_id` filter to `post_id_helper` in the Main class.
* Tweak - Alter Customizer and Section objects to be more versatile. [TCMN-104]
* Tweak - Split pcss variable imports so we only import hte necessary variables for skeleton, and don't import more than once. [TCMN-104]
* Tweak - added new `get_hex_with_hash` function to Tribe/Utils/Color.php to reduce need for manual string concatenation. [TCMN-104]
* Language - 0 new strings added, 50 updated, 1 fuzzied, and 0 obsoleted

### [4.14.2] 2021-08-17

* Feature - Redesign In-App help and troubleshooting pages. [TEC-3741]
* Fix - Fix issue of time selector for recurring rules not working for the block editor. [ECP-918]
* Fix - Ensure that $wp_query->is_search is false for calendar views that have no search term. [TEC-4012]
* Fix - Fix issue of month names not being translatable. This was caused by a missing moment js localization dependency. [ECP-739]
* Fix - Ensure that block editor scripts don't enqueue wp-editor on non-post block editor pages (widgets) [TEC-4028]
* Tweak - Alter Assets->register and tribe_asset() to accept a callable for assets. [TEC-4028]
* Tweak - Change label of API Settings tab to "Integrations". [TEC_4015]
* Language - 169 new strings added, 121 updated, 2 fuzzied, and 0 obsoleted

### [4.14.1] 2021-07-21

* Feature - Add new notice for Stellar Sale. [TCMN-111]
* Feature - Create a Notice Service Provider and some initial tests. Move the BF sale notice to the new provider, as well as several of the others.  [TCMN-111]
* Language - 0 new strings added, 24 updated, 1 fuzzied, and 0 obsoleted

### [4.14.0] 2021-07-01

* Feature - Add new custom Customizer controls.
* Tweak - Add central compatibility functionality. A step in the move from using body classes to container classes.
* Language - 0 new strings added, 22 updated, 1 fuzzied, and 0 obsoleted

### [4.13.5] 2021-06-23

* Feature - Add checkbox switch template and css [VE-353]
* Fix - Fix call to call_user_func_array( 'array_merge'... ) to make PHP8 compatible
* Tweak - Set up recurring, featured, and virtual icons to not rely on aria-labeled. [TEC-3396]
* Language - 3 new strings added, 1 updated, 2 fuzzied, and 0 obsoleted

### [4.13.4] 2021-06-09

* Tweak - When using The Events Calendar and Event Tickets split the admin footer rating link 50/50. [ET-1120]
* Language - 1 new strings added, 2 updated, 1 fuzzied, and 1 obsoleted

### [4.13.3] 2021-05-27

* Feature - Create new functionality in Tribe__Customizer__Section to allow for simpler creation of controls and sections. [TEC-3836]
* Feature - Added the `set_chunkable_transient` and `get_chunkable_transient` functions to the Cache class, see doc-blocks. [TEC-3627]
* Fix - Compatibility with Avada themes and third party plugins or themes loading `selectWoo` at the same time. [ECP-737]
* Tweak - Adjust the actions used to register and load the styles for the tooltip component [TEC-3796]
* Tweak - Update lodash to 4.17.21. [TEC-3885]
* Language - 0 new strings added, 2 updated, 1 fuzzied, and 0 obsoleted

### [4.13.2] 2021-04-29

* Fix - Modify Select2 to clone the `jQuery.fn.select2` into `jQuery.fn.select2TEC` to avoid conflicting with third-party usage that didn't include the full version of Select2 [TEC-3748]
* Fix - Add filtering hooks to Cache Listener to allow modifications of which options trigger an occurrence. [ECP-826] [ECP-824]
* Language - 0 new strings added, 1 updated, 1 fuzzied, and 0 obsoleted

### [4.13.1] 2021-04-22

* Feature - Add the hybrid icon as a template. [VE-303]
* Fix - Add compatibility for the new default theme, TwentyTwentyOne. [ET-1047]
* Language - 0 new strings added, 2 updated, 1 fuzzied, and 0 obsoleted

### [4.13.0.1] 2021-04-05

* Fix - Reduce overhead of widget setup on every page load by setting up the widgets only as needed. [TEC-3833]

### [4.13.0] 2021-03-29

* Feature - JavaScript and Styles can be set to be printed as soon as enqueued, allowing usages like shortcodes to not have jumpy styles.
* Feature - Include code around administration notices to support recurring notices. [TEC-3809]
* Fix - Makes sure Javascript extra data is loaded following WordPress architecture, respecting it's dependencies.
* Fix - Decode country picker names [TEC-3360]
* Tweak - Include a way for the context locations to be regenerated, with plenty of warnings about the risk [FBAR-36]
* Tweak - Remove deprecated filter `tribe_events_{$asset->type}_version`
* Tweak - Include Utils for dealing with Taxonomies with two methods, one for translating terms query into a repository arguments and another for translating shortcode arguments to term IDs. [ECP-728]
* Language - 3 new strings added, 304 updated, 8 fuzzied, and 2 obsoleted

### [4.12.19] 2021-03-02

* Fix - Prevent problems when using longer array keys in `Tribe__Cache` so the correct non-persistent groups are referenced. [ET-1023]
* Language - 0 new strings added, 1 updated, 1 fuzzied, and 0 obsolete

### [4.12.18] 2021-02-24

* Feature - JavaScript Assets can now be marked for async or defer, giving the asset manager more flexibility.
* Tweak - Modify all of the jQuery to be compatible with 3.5.X in preparation for WordPress 5.7 [TCMN-99]
* Fix - Ensure we don't enqueue widget customizer styles before the widget stylesheets. [ECP-574]
* Tweak - Created templates for admin Widgets form `admin-views/widgets/components/fields.php`, `admin-views/widgets/components/form.php`, `admin-views/widgets/components/fields/fieldset.php`, `admin-views/widgets/components/fields/section.php` ,`admin-views/widgets/components/fields/text.php`, `admin-views/widgets/components/fields/radio.php`, `admin-views/widgets/components/fields/checkbox.php`, `admin-views/widgets/components/fields/dropdown.php` [ECP-486]
* Language - 0 new strings added, 4 updated, 1 fuzzied, and 0 obsoleted

### [4.12.17] 2021-02-16

* Tweak - Allow usage of HTML within the Tribe Dialog button. [ETP-523]
* Language - 0 new strings added, 1 updated, 1 fuzzied, and 1 obsoleted

### [4.12.16] 2021-01-28

* Fix - Increase the minimum width of the datetime dropdown when editing an event with the block editor. [TEC-3126]
* Fix - Ordering with an Array when using `Tribe__Repository` now properly ignores the global order passed as the default. [ECP-598]
* Fix - Resolve PHP 8.0 incompatibility with `__wakeup` and `__clone` visibility on Extension class.
* Fix - Prevent `tribe_sort_by_priority` from throwing warnings on `uasort` usage for PHP 8+ compatibility.
* Fix - Update Di52 to include PHP 8+ compatibility.
* Fix - Modify Freemius `class-fs-logger.php` file to prevent PHP 8+ warnings.
* Fix - Correctly handle *nix and Windows server paths that contain falsy values (e.g. `0` or spaces) when building template paths. [TEC-3712]
* Language - 3 new strings added, 3 updated, 2 fuzzied, and 1 obsoleted

### [4.12.15.1] 2020-12-29

* Tweak - Point PUE URLs to the correct servers to avoid redirects.

### [4.12.15] 2020-12-15

* Tweak - Add the `tribe_customizer_print_styles_action` to allow filtering the action the Customizer will use to print inline styles. [TEC-3686]
* Tweak - Allow disabling and enabling logging functionality by calling hte `tribe( 'log' )->disable()` and `tribe( 'log' )->enable()` methods on the Log service provider.
* Tweak - Update di52 containers to latest version for compatibility with WPStaging Pro. [TCMN-136]
* Language - 0 new strings added, 9 updated, 1 fuzzied, and 0 obsoleted

### [4.12.14] 2020-12-02

* Fix - Correctly handle multiple calls to the Repository `by` or `where` method that would cause issues in some Views [ECP-357]
* Fix - Do not try to store overly large values in transients when not using external object cache. [TEC-3615]
* Fix - Improve the Rewrite component to correctly parse and handle URLs containing accented chars. [TEC-3608]
* Tweak - Add the `Tribe__Utils__Array::merge_recursive_query_vars` method to correctly recursively merge nested arrays in the format used by `WP_Query` [ECP-357]
* Language - 0 new strings added, 109 updated, 1 fuzzied, and 0 obsoleted

### [4.12.13.1] 2020-11-20

* Fix - Prevent `tribe_get_first_ever_installed_version()` from having to spawn an instance of the Main class for version history.

### [4.12.13] 2020-11-19

* Tweak - Allow deletion of non persistent keys from Tribe__Cache handling. [ET-917]
* Fix - Prevent items without children to be marked as groups in SelectWoo UI. [CE-106]
* Fix - Update the MomentJS version to 2.19.3 for the `tribe-moment` asset. [TEC-3676]
* Language - 0 new strings added, 3 updated, 1 fuzzied, and 0 obsoleted

### [4.12.12.1] 2020-11-19

* Tweak - Update version of Freemius to the latest version 2.4.1 [TEC-3668]
* Tweak - Include a new Notice style for Banners [TCMN-90]

### [4.12.12] 2020-10-22

* Tweak - Add the `tribe_suspending_filter` function to run a callback detaching and reattaching a filter. [TEC-3587]
* Fix - Correctly register and handle Block Editor translations. [ECP-458]
* Fix - Update our use of Monolog logger to avoid issues when the plugins are used together with the WooCommerce Bookings plugin. [TEC-3638]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.12.11] 2020-10-19

* Fix - Dropdown AJAX search for taxonomy terms properly using SelectWoo search formatting, used in Community Events tags and Event categories. [CE-96]
* Language - 0 new strings added, 7 updated, 1 fuzzied, and 0 obsoleted

### [4.12.10] 2020-09-28

* Tweak - Adjust SelectWoo dropdown container attachment to include search and minimum results for search. [FBAR-139]
* Tweak - Move border style button styles to border-small and add various border button styles that match the solid button style. [FBAR-143]
* Tweak - Add the common views folder to the `Tribe__Template` lookup folders, the folder will be searched for matching template files only if no plugin-provided template was found. [FBAR-148]
* Tweak - Add the `tribe_template_common_path` filter to allow controlling the path of the template file provided by common. [FBAR-148]
* Tweak - Add the `tribe_without_filters` function to run a callback or closure suspending a set of filters and actions. [TEC-3579]
* Tweak - Added hover and focus colors, update default colors to make them accessible. [FBAR-165]
* Fix - Prevent `register_rest_route` from throwing notices related to `permission_callback` (props @hanswitteprins)
* Language - 0 new strings added, 2 updated, 1 fuzzied, and 0 obsoleted

### [4.12.9] 2020-09-21

* Tweak - Added Support for overriding individual arguments while registering group assets using `tribe_assets`. [TCMN-88]
* Tweak - Introduce the `tribe_doing_shortcode()` template tag to check if one of our shortcodes is being done. [ET-904]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.12.8] 2020-08-26

* Fix - Added IE11 compatibility for the toggles styles using `tribe-common-form-control-toggle` CSS class. [ET-865]
* Tweak - Improve regular expressions used to parse UTC timezones by removing non-required grouping and characters. [TCMN-68]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.12.7] 2020-08-24

* Tweak - Allow SelectWoo dropdown to be attached to the container via the `data-attach-container` attribute. [FBAR-129]
* Tweak - Adjust the border radius of the form checkbox styles. [FBAR-126]
* Tweak - Adjust the layout styles for tribe common checkboxes and radios. [FBAR-126] [FBAR-127]
* Fix - Correctly handle array format query arguments while generating clean, or canonical, URLs; this solves some issues with Filter Bar and Views v2 where filters would be dropped when changing Views, paginating or using the datepicker. [FBAR-74, FBAR-85, FBAR-86]
* Language - 3 new strings added, 30 updated, 3 fuzzied, and 1 obsoleted

### [4.12.6.1] 2020-08-17

* Fix - Pass extra props down to Modal component to allow addition of extra properties. [GTRIA-275]

### [4.12.6] 2020-07-27

* Feature - Added the `tribe_normalize_orderby` function to parse and build WP_Query `orderby` in a normalized format. [TEC-3548]
* Feature - Added the `pluck`, `pluck_field`, `pluck_taxonomy` and `pluck_combine` methods to the `Tribe__Utils__Post_Collection` class to allow  more flexible result handling when dealing with ORM result sets. [TEC-3548]
* Tweak - Adjust verbosity level to report connection issues with Promoter [PRMTR-404]
* Tweak - Modify default parameters on `tribe_register_rest_route` for `permission_callback` to prevent notices on WordPress 5.5.
* Tweak - Add the `tribe_asset_print_group` function to allow printing scripts or styles managed by the `tribe_assets` function in the page HTML. [ECP-374, ECP-376]
* Tweak - Add the `Tribe__Customizer::get_styles_scripts` method to allow getting the Theme Customizer scripts or styles managed managed by the plugins. [ECP-374, ECP-376]
* Tweak - Adjust verbosity level to report connection issues with Promoter. [PRMTR-404]
* Tweak - Include Virtual Events on Help Page sidebar widget [TEC-3547]
* Tweak - Update process to generate Promoter keys. [TCMN-85]
* Tweak - Register Promoter key as part of the WP Settings API. [TCMN-85]
* Tweak - Adjust level of access (protected to public) in 'Tribe__Promoter__Connector' class for external use of connector calls. [TCMN-82]
* Fix - Correct issue with Body_Classes removing classes added by other plugins. [TEC-3537]
* Fix - Set proper timezone on block editor when creating a new event. [TEC-3543]
* Fix - Properly enqueue the customizer styles to allow overriding of theme styles. [TEC-3531]
* Fix - Allow customizer styles to be applied on shortcode events views via the use of the filter `tribe_customizer_shortcode_should_print`. [ECP-450]
* Language - 1 new strings added, 22 updated, 1 fuzzied, and 0 obsoleted

### [4.12.5] 2020-06-24

* Feature - Added the `Tribe\Traits\With_Db_Lock` trait to provide methods useful to acquire and release database locks.
* Feature - Added the `tribe_db_lock_use_msyql_functions` filter to control whether Database locks should be managed using MySQL functions (default, compatible with MySQL 5.6+) or SQL queries.
* Tweak - Added case for manual control of field in dependency JS.
* Tweak - Add filter `tribe_promoter_max_retries_on_failure` to set the maximum number of attempts to notify promoter of a change on the WordPress installation, default to 3.
* Tweak - Register logs when notifications to Promoter failed and retry to notify until the limit of `tribe_promoter_max_retries_on_failure` is reached per notification.
* Fix - Backwards compatibility for `tribe_upload_image` allow to use the function on version of WordPress before `5.2.x`
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.12.4] 2020-06-22

* Feature - Added the `Tribe\Traits\With_Meta_Updates_Handling` trait to provide methods useful in handling with meta.
* Fix - Prevent `$legacy_hook_name` and `$hook_name` template Actions and Filters to be fired if they are the same, preventing duplicated hook calls.
* Language - 10 new strings added, 27 updated, 1 fuzzied, and 2 obsoleted

### [4.12.3.1] 2020-06-09

* Security - Remove deprecated usage of escapeMarkup in Select2 (props to miha.jirov for reporting this).

### [4.12.3] 2020-05-27

* Fix - When using Block Editor we ensure that `apply_filters` for `the_content` on `tribe_get_the_content`, the lack of that filter prevented blocks from rendering. [TEC-3456]
* Tweak - Added the `bulk_edit` and `inline_save` locations to the Context. [VE-8]
* Language - 99 new strings added, 14 updated, 1 fuzzied, and 17 obsoleted

### [4.12.2] 2020-05-20

* Feature - Added array utility methods: `parse_associative_array_alias` to build an array with canonical keys while taking alias keys into account and `filter_to_flat_scalar_associative_array` to help do so. Useful for aliasing shortcode arguments, for example.
* Feature - Added `tribe_extension_is_disallowed` filter for The Events Calendar's core plugins to deactivate an extension whose functionality has become duplicative or conflicting.
* Language - 1 new strings added, 1 updated, 1 fuzzied, and 0 obsoleted

### [4.12.1] 2020-05-11

* Feature - Added a helper method `Tribe__Plugins::is_active( 'slug' )` to check if a given plugin is active.
* Feature - Add entry points through filters to be able to add content after the opening html tag or before the closing html tag. [TCMN-65]
* Tweak - Extended support for namespaced classes in the Autoloader.
* Tweak - Make Customizer stylesheet enqueue filterable via `tribe_customizer_inline_stylesheets`. [TEC-3401]
* Tweak - Normalize namespaced prefixes with trailing backslash when registering them in the Autoloader. [VE-14]
* Language - 1 new strings added, 15 updated, 1 fuzzied, and 0 obsoleted

### [4.12.0] 2020-04-23

* Feature - Management of Shortcodes now are fully controlled by Common Manager classes [TCMN-56]
* Fix - Prevent Blocks editor from throwing browser alert when leaving the page without any changes applied to the edited post.
* Fix - Clear the views HTML cache on language settings changes to ensure we don't mix up translated strings. [TEC-3326]
* Fix - Blocks editor CSS compatibility with WordPress 5.4 with new module classes: `.block-editor-inner-blocks`
* Fix - Add style override for <ul> in Divi due to theme use of IDs. [TEC-3235]
* Fix - Change text domain loading to occur on 'init' hook instead of 'plugins_loaded'. Added new `tribe_load_text_domains` action hook for our other plugins to use for their own text domain loading on 'init' as well. [TCMN-58]
* Fix - Change curly quotes to straight quotes in some HTML markup when doing 'tribe_required_label' for Modal dialogs.
* Tweak - Added a method that returns whether the events are being served through Blocks or the Classical Editor. [ETP-234]
* Tweak - Added homepage settings to system information.
* Tweak - Add the `tribe_template_done` filter to be able to disable a template before rendering. [TEC-3385]
* Tweak - Improved on meta data handling of for Blocks editor.
* Tweak - Deprecate Select2 3.5.4 in favor of SelectWoo
* Language - 0 new strings added, 38 updated, 2 fuzzied, and 1 obsoleted

### [4.11.5.1] 2020-03-23

* Fix - Assets class modification to prevent JavaScript and CSS failing to load when `SCRIPT_DEBUG=true` [TCMN-52]

### [4.11.5] 2020-03-23

* Tweak - Added context to the country and the state of Georgia to allow separate translation [TCMN-137]
* Tweak - Allow uploads of images with a large size and images with no extension provided from the URL, as the extension from the URL was used to define the type of the file to be uploaded and when the extension was not present on the URL the file was considered invalid. [TCMN-46]
* Tweak - Expired transient garbage collector will only run once per request and when needed [TCMN-38]
* Language - 2 new strings added, 0 updated, 1 fuzzied, and 1 obsoleted

### [4.11.4] 2020-03-18

* Fix - Increase range of actions that trigger changes on Promoter with a `WP_Post` instance or using an ID. [TCMN-47]

### [4.11.3] 2020-02-26

* Fix - JavaScript error in tribe dialog when there are no dialogs. Change fallback from object to array. [TCMN-34]
* Fix - Fix display of Dialogs in Safari 12 mobile. [ETP-155]
* Fix - Bring back the dialog icons. [ETP-155]
* Tweak - Add theme compatibility for the tribe dialog [ETP-156]
* Tweak - Add check if in `the_content` filter to prevent it from being called again. [ECP-345]

### [4.11.2.1] 2020-02-25

* Fix - Plugin dependency registration with `Plugin_Register` will not prevent loading of all plugins in list if the last loaded fails. [TCMN-41]

### [4.11.2] 2020-02-19

* Tweak - Add the `tribe_cache` function as proxy to `tribe( 'cache' )` [TEC-3241]
* Tweak - Add the a JSON-LD data dedicated Debug Bar panel [TEC-3241]
* Tweak - Add the `post_tag` location to the context [TEC-3241]
* Tweak - Add some visibility-related methods to the `Tribe__Admin__Notices` class [TEC-2994]
* Tweak - Include `Rewrites::is_plain_permalink()` with proper caching [TEC-3120]
* Tweak - Included two new locations for `tribe_context()`: `plain_permalink` and `permalink_structure` [TEC-3120]
* Tweak - Update version of Freemius internally to 2.3.2 [TEC-3171]
* Fix - Prevent warning on when saving empty slug for Tribe Setting Fields.
* Fix - Set a default value for the datepicker format option to avoid issues in some settings combinations, thanks @helgatheviking. [TEC-3229]
* Language - 1 new strings added, 35 updated, 1 fuzzied, and 0 obsoleted

### [4.11.1] 2020-02-12

* Fix - Fix style overrides for new view shortcodes for Genesis theme. [ECP-316]
* Fix - Fix style overrides for new view shortcodes for Enfold theme. [ECP-315]
* Tweak - Update `adjustStart()` function in moment utils to allow start and end time to be the same. [TEC-3009]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.11.0.1] 2020-02-05

* Tweak - Add filtered method to Date Utils for fetching the datepickerFormat. [TEC-3229]
* Fix - Fatal in Context when global query object is not set. [TEC-3228]

### [4.11.0] 2020-01-27

* Feature - Inclusion of `Date_I18n_Immutable` and `Date_I18n` as WP friendly options to `DateTimeImmutable` and `DateTime` respectively.
* Tweak - Caching of Tribe Options in memory to improve performance.
* Tweak - Set the default datepicker (compact) format to MM/D/YYYY [136789]
* Tweak - Add the `Tribe\Traits\Cache_User::reset_caches` method to clear cache entries [138357]
* Fix - Template class now will properly create file name for the hooks when in a different namespace.
* Fix - Template class now will properly determine the Theme folder when dealing with a different namespace.
* Language - 0 new strings added, 8 updated, 1 fuzzied, and 0 obsoleted

### [4.10.3] 2019-12-19

* Feature - Add Repository filter `where_meta_related_by_meta` for getting a post by the meta value an associated post. [133333]
* Fix - Correct missing block when switching from blocks to classic editor. [131493]

### [4.10.2] 2019-12-10

* Tweak - Add the `Tribe__Cache::warmup_post_caches` method to warmup the post caches for a set of posts [136624]
* Tweak - Add the `tribe_cache_warmup_post_cache_limit` filter to allow filtering the LIMIT of those warmup fetches [136624]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.10.1] 2019-12-10

* Fix - Updated the .pot file as it was outdated when shipping Tribe Common 4.10
* Language - 8 new strings added, 0 updated, 0 fuzzied, and 0 obsoleted

### [4.10] 2019-11-20

* Feature - Add new tribe-dialog object. Implements mt-a11y-dialog as `tribe-dialog` (or `tribe('dialog.view')`) as an extension of `Tribe_Template`. [129434]
* Feature - New dialogs can be created with a simple call to `tribe( 'dialog.view' )->render_dialog( $args )` in php. [129434]
* Feature - The tribe-dialog object sets up all necessary javascript and HTML via passed parameters. [129434]
* Feature - Add a basic dialog, modal, confirmation dialog, and alert as templates. [129434]
* Feature - Add methods `render_modal()`, `render_confirm()` and `render_alert()` to streamline common dialog types in Dialog View class. [129434]
* Feature - Add `tribe_installed_before`, `tribe_installed_after` and `tribe_installed_on` to test the install version against a passed version. Requires the plugin have the `VERSION` constant and `$version_history_slug` property set. `$version_history_slug` is a new property being added specifically for these functions. [133048]
* Tweak - Added filters: `tribe_dialog_args`, `tribe_dialog_template`, `tribe_dialog_html`, `tribe_dialog_script_args`, `tribe_dialog_script_html`
* Tweak - Added actions: `tribe_dialog_additional_scripts`, `tribe_dialog_additional_scripts_`, `tribe_dialog_additional_scripts_`, `tribe_dialog_register`, `tribe_dialog_hooks`, `tribe_dialog_assets_registered`
* Tweak - Changed views: `dialog/alert`, `dialog/button`, `dialog/confirm`, `dialog/dialog`, `dialog/modal`, `tooltip/tooltip`

### [4.9.23] 2019-11-20

* Tweak - Add the `tribe_get_query_var` function [137262]
* Tweak - Add `tribe_get_the_content()` and `tribe_the_content()` for PHP 7.2 compatibility with WordPress 5.2
* Language - 0 new strings added, 21 updated, 1 fuzzied, and 0 obsoleted

### [4.9.22.1] 2019-11-18

* Fix - Pass the event to the onRequestClose handlers for the admin modal. [137394]

### [4.9.22] 2019-11-13

* Fix - Add some sanity checks to `is_editing_post` to ensure we don't show PHP error notices in some edge cases [122334]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.9.21] 2019-11-12

* Tweak - Added additional datepicker formats for simpler selection [116086, 126472, 117909]
* Tweak - Updated the Repository implementation to handle more complex `orderby` constructs [133303]
* Tweak - Added the `Tribe__Date_Utils::get_week_start_end` method [133303]

### [4.9.20] 2019-10-16

* Tweak - added the `tribe_sanitize_deep` function to sanitize and validate input values [134427]
* Tweak - use the `tribe_sanitize_deep` function to sanitize the values returned by the `tribe_get_request_var` function [134427]
* Tweak - Rename "Datepicker Date Format" to "Compact Date Format" [134526]
* Tweak - Adjust Promoter loading order to increase compatibility with plugins that use authentication early in the process [134862]
* Tweak - Add support for Authentication using a Header when using Promoter [133922]
* Language - 2 new strings added, 21 updated, 1 fuzzied, and 2 obsoleted

### [4.9.19] 2019-10-14

* Feature - Added new `tribe_strpos()` function that helps prevent fatal errors when hosting environments do not have support for multibyte functionality [135202]
* Language - 1 new strings added, 32 updated, 1 fuzzied, and 1 obsoleted

### [4.9.18] 2019-09-25

* Tweak - Added a missing space to the plugin list in the system information [134364]
* Fix - Use the correct name for North Macedonia
* Language - 1 new strings added, 32 updated, 1 fuzzied, and 1 obsoleted

### [4.9.17] 2019-09-16

* Tweak - Changed the 'url' validation error text to just say it needs to be valid, not that it has to be a valid *absolute* URL [72214]
* Tweak - Smarter plugin dependency checking with more accurate admin notices if not all requirements are satisfied [131080]
* Tweak - `tribe_get_request_var()` now includes explicit check against $_REQUEST [132248]
* Fix - Enqueue Thickbox script on all admin pages when needed [131080]
* Language - 2 new strings added, 48 updated, 1 fuzzied, and 2 obsoleted

### [4.9.16] 2019-09-04

* Tweak - Added the Monolog logging library as alternative logging backend [120785]
* Tweak - Hook Monolog logger on `tribe_log` action [120785]
* Tweak - Add redirection of `tribe( 'logger' )->log()` calls to the Monolog logger using the `tribe_log_use_action_logger` filter [120785]
* Fix - Handling of featured image setting [127132]
* Language - 1 new strings added, 5 updated, 1 fuzzied, and 0 obsoleted

### [4.9.15.1] 2019-08-27

* Fix - Resolve JS console warnings from tooltip.js by adding missing `tribe` var when the var is not setup on the current page already [133207]

### [4.9.15] 2019-08-22

* Tweak - Add IDs to radio fields so we can target them with tribe-dependency [131428]
* Fix - Fixed alignment of description text for checkbox and radio fields in admin settings screens [131353]
* Language - 0 new strings added, 73 updated, 1 fuzzied, and 0 obsoleted

### [4.9.14] 2019-08-19

* Tweak - Update Lodash version on Block editor to prevent any possibility of a security issue with the package. From v4.17.11 to v4.17.15 [131421]
* Fix - Prevent mascot image to get blown up out of proportions to a larger size on buggy CSS loading. [131910]
* Language - 0 new strings added, 66 updated, 1 fuzzied, and 4 obsoleted

### [4.9.13] 2019-07-25

* Tweak - Update Freemius library to `2.3.0` [130281]
* Fix - Location filtering for Context class moved out of construct, resolving lots of navigation problems across The Events Calendar [130754]
* Language - 0 new strings added, 21 updated, 1 fuzzied, and 0 obsoleted

### [4.9.12] 2019-07-03

* Feature - Include `tribe_classes()` and `tribe_get_classes()` for HTML class attribute handling in a similar way as the JS `classNames()`
* Tweak - Include proper documentation of why the plugin has been deactivated and a knowledgebase article about how to downgrade [129726]
* Tweak - When trying to update The Events Calendar with an incompatible version of an Addon that is expired, it will stop the upgrade [129727]
* Tweak - Add filter `tribe_is_classic_editor_plugin_active` to change the output if the classic editor is active or not [121267]
* Tweak - Create a new key if `AUTH_KEY` is not defined or is empty and add a new filter `tribe_promoter_secret_key` to filter the result [127183]
* Tweak - Divide the `tribe-common.js` file to prevent that file from being bloated with external dependencies. [129526]
* Tweak - Make sure `UTC-0` is converted back to `UTC` instead of `UTC-01` [129240]
* Tweak - Add new function `tribe_register_rest_route` Wrapper around `register_rest_route` to filter the arguments when a new REST endpoint is created [129517]
* Tweak - Add new method `Tribe__Cost_Utils::parse_separators` to infer decimal and thousands separators from a value that might have been formatted in a local different from the current one [98061]
* Fix - Prevent Clipboard Javascript from loading all over the place on `/wp-admin/` [129526]
* Fix - PHP 5.6 compatibility for `trait Cache_User` by using WP action `shutdown` instead of `__destruct` on our `WP_Rewrite` [129860]
* Language - 4 new strings added, 66 updated, 1 fuzzied, and 0 obsoleted

### [4.9.11.2] 2019-06-20

* Fix - Add Promoter PCSS file so that the proper CSS will be generated on package build [129584]

### [4.9.11.1] 2019-06-13

* Fix - Resolve fatal errors with references directly to The Events Calendar class constants [129107]

### [4.9.11] 2019-06-05

* Tweak - Add ability to prevent duplicate JOINs by allowing an optionally supplied ID per join [128202]
* Tweak - Added the `Tribe__Template::get_local_values` and `Tribe__Template::get_global_values` methods.
* Tweak - Added the `Tribe__Rewrite::get_canonical_url` and `Tribe__Rewrite::parse_request` methods.
* Language - 0 new strings added, 24 updated, 1 fuzzied, and 0 obsoleted

### [4.9.10] 2019-05-23

* Tweak - Add ability to prevent duplicate JOINs by allowing an optionally supplied ID per join [128202]
* Tweak - Add ability to turn on/off no_found_rows logic for queries [128202]
* Fix - Resolve issues with pagination in REST API by making the query cache more comprehensive [127710]

### [4.9.9] 2019-05-16

* Tweak - Reduced file size by removing .po files and directing anyone creating or editing local translations to translations.theeventscalendar.com
* Tweak - Optimize the autoloader function to eliminate duplicate path checks.
* Fix - Fixed incorrect position of arg in filter_var function of email validation in Validate.php (props @dharmin) [125915]

### [4.9.8] 2019-05-14

* Feature - Add new `tooltip.view` PHP class to render new tooltips that utilize the existing `tribe-tooltip` CSS class for universal utility [120856]
* Tweak - Added filters: `tribe_context_locations`, `tribe_tooltip_template`, `tribe_tooltip_html`
* Tweak - Changed views: `tooltip/tooltip`

### [4.9.7] 2019-05-02

* Fix - Fixed cron to handle EA featured image processing while importing [124019]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.9.6.2] 2019-04-26

* Fix - Prevent Composer autoloader from throwing Fatal due to nonexistent `setClassMapAuthoritative()` method [126590]

### [4.9.6.1] 2019-04-25

* Fix - Switch from using `any` to `[ 'publish', 'private' ]` for `post_status` on any Object Relational Mapping queries [126377]
* Fix - Resolve ORM Decorator issues that could cause fatal errors when calling methods not defined in the extending class

### [4.9.6] 2019-04-23

* Tweak - Ability to use ->where_multi() in Tribe_Repository objects to search for text matches on multiple fields (supports post fields, terms, and meta values) [125878]
* Tweak - Allow for external modal control for modal button component [123818]
* Tweak - Keep track of whether the current request was authorized by the Promoter connector [117668]
* Tweak - Added filters: `tribe_common_log_to_wpcli`, `tribe_promoter_authorized_redirect_url`
* Tweak - Changed views: `promoter/auth`
* Language - 0 new strings added, 17 updated, 1 fuzzied, and 1 obsoleted

### [4.9.5] 2019-04-17

* Feature - Include Freemius integration on our Common Libraries to enable information collection opt-in for some new users
* Tweak - Improve Object Relation Mapping base repository and filter classes to support usage of events
* Tweak - Modify `Date_Utils.php` and include another way of building DateTime object with Timezone `build_date_object`
* Tweak - Include The Events Calendar Context panel in the Debug Bar plugin
* Tweak - Include the `tribe_image_uploader_local_urls` filter in Image Uploader class
* Tweak - Include `tribe_process_allow_nopriv_handling` for non-logged users to improve control when async requests fire
* Tweak - Fork `WP_Background_Process` to `Tribe__Process__Handler` to allow for better internal maintenance by our team
* Tweak - Include more Array handling methods: `recursive_ksort`, `add_prefixed_keys_to`, `flatten`, `filter_prefixed`, `add_unprefixed_keys_to`
* Fix - Adjust `Tribe__Admin__Helpers::is_screen()` to avoid false positives and flag the events menu Tags page as a Tribe screen [107413]
* Fix - Improve the handling asynchronous requests for our Process Handler
* Fix - Correct problems with image asynchronous processing of thumbnail images
* Fix - Confirm that multisite background processing saves options and progress to the correct table in the database
* Language - 8 new strings added, 25 updated, 1 fuzzied, and 0 obsoleted

### [4.9.4] 2019-04-01

* Tweak - Keep track of whether the current request was authorized by the Promoter connector [117668]
* Tweak - Adjust `determine_current_user` priority used to identify Promoter user on calls to the REST API [124302]

### [4.9.3.2] 2019-03-14

* Fix - Resolve issues where some CSS files were not properly packaged with previous release

### [4.9.3.1] 2019-03-06

* Feature - Attach the post ID to Promoter calls and remove hook from all post saves [123732]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.9.3] 2019-03-04

* Fix - Make sure we pass and get the parameter when using cron jobs to import images on Event Aggregator [119269]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.9.2] 2019-02-26

* Feature - Add Promoter access from the WP Admin Bar
* Fix - Update the order of loading of providers to ensure correct execution for Promoter
* Tweak - Added Promoter to the App Shop [122550]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.9.1] 2019-02-14

* Feature - date and timezone building and validation methods to the `Tribe__Date_Utils` and `Tribe__Timezones` classes [116356, 115579]
* Feature - the `tribe_is_regex` and `tribe_unfenced_regex` functions [115582]
* Feature - Add new action `tribe_editor_register_blocks` used to register Event blocks via `common`
* Fix - Make sure assets are injected before is too late
* Fix - Fix an issue where feature detection of async-process support would fire too many requests [118876]
* Fix - Interface and Abstracts for REST base structures are now PHP 5.2 compatible
* Fix - Prevent to trigger error when using `array_combine` with empty arrays
* Fix - Improve conditionals on `Tribe__Timezones::generate_timezone_string_from_utc_offset` to return only string timezones [120647]
* Language - 0 new strings added, 13 updated, 1 fuzzied, and 0 obsoleted

### [4.9.0.1] 2019-02-07

* Fix - Modify extension dependency checking with new system to determine if it can load [122368]

### [4.9] 2019-02-05

* Feature - Add system to check plugin versions to inform you to update and prevent site breaking errors [116841]
* Tweak - Added support for Promoter licenses [120320]
* Tweak - Added filters: `tribe_register_{$main_class}_plugin_version`, `tribe_register_{$main_class}_plugin_dependencies`
* Tweak - Added actions: `tribe_plugins_loaded `
* Tweak - Changed views: `promoter/auth`
* Language - 3 new strings added, 10 updated, 1 fuzzied, and 1 obsoleted

### [4.8.5] 2019-01-21

* Fix - Updated translation strings from the Gutenberg extension merge [118656]
* Add - Added `strip_dynamic_blocks` method in `Tribe__Editor__Utils` [118679]
* Add - Added `exclude_tribe_blocks` method in `Tribe__Editor__Utils` [118679]
* Tweak - Allow better control of when we are in Classic editor with a new filter `tribe_editor_classic_is_active` [120137]
* Tweak - Adjusted content in the admin welcome page that users are brought to upon newly activating Event Tickets or The Events Calendar [117795]
* Language - 0 new strings added, 9 updated, 1 fuzzied, and 1 obsoleted

### [4.8.4] 2019-01-15

* Add - Added new filter `tribe_asset_data_add_object_{$object_name}` to allow integrations to customize the object data and add additional properties [119760]

### [4.8.3] 2018-12-19

* Tweak - Refreshing the Welcome page for The Events Calendar and Event Tickets [117795]
* Fix - Prevent admin tooltips to that full page width on Blocks Editor [118883]
* Fix - Datepicker code will now use the correct datetime format [117428]

### [4.8.2] 2018-12-13

* Feature - Add new action `tribe_editor_register_blocks` used to register Event blocks via `common`
* Fix - Make sure assets are injected before is too late
* Fix - Fix an issue where feature detection of async-process support would fire too many requests [118876]
* Fix - Interface and Abstracts for REST base structures are now PHP 5.2 compatible
* Fix - Ensure admin CSS is enqueued any time a notice is displayed atop an admin page [119452]
* Fix - Prevent to trigger error when using `array_combine` with empty arrays
* Fix - Compatibility with classic editor plugin [119426]
* Tweak - Add functions to remove inner blocks [119426]

### [4.8.1] 2018-12-05

* Fix - speed up and improve robustness of the asynchronous process feature detection code [118934]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.8.0.1] 2018-11-30

* Fix - Added safety measure to reduce risk of a fatal error when examining list of network-activated plugins [115826]
* Fix - Corrected a usage of array syntax within the PUE client, in order to ensure compatibility with PHP 5.2.4 (our thanks to @megabit81 for promptly flagging this issue!) [119073]
* Language - 0 new strings added, 3 updated, 1 fuzzied, and 0 obsoleted

### [4.8] 2018-11-29

* Add - Added `tribe_cache_expiration` filter that allows plugins to use persistent caching based on cache key [117158]
* Fix - The invalid license key notice won't be displayed for Products with empty license keys [115562]
* Language - 9 new strings added, 7 updated, 1 fuzzied, and 0 obsoleted

### [4.7.23.1] 2018-11-21

* Fixed - Use of the `wp_doing_cron` function that would break compatibility with sites not on WordPress version 4.8 or later [118627]

### [4.7.23] 2018-11-13

* Add - Added `Tribe__Admin__Notice__Marketing` class for bespoke marketing admin notices [114903]
* Add - Added `TRIBE_HIDE_MARKETING_NOTICES` constant that, if defined to `true` in your site's `wp-config.php` file, will hide all marketing admin notices [114903]
* Fix - Fixed the setting-up of strings in the Tribe Bar datepicker to ensure they're translatable into languages other than English [115286]
* Language - 1 new strings added, 22 updated, 1 fuzzied, and 0 obsoleted

### [4.7.22] 2018-10-22

* Fix - Update `Tribe__Admin__Help_Page::is_current_page()` to return true when viewing the help page from the network settings [109563]
* Language - 3 new strings added, 35 updated, 3 fuzzied, and 1 obsoleted

### [4.7.21] 2018-10-03

* Fix - Only load Customizer CSS when loading main stylesheets or widget stylesheets of PRO [112127]
* Fix - Restore functionality of admin notices that display when a license key is invalid (thanks to @tyrann0us on GitHub for submitting the fix!) [113660]
* Fix - Update our mascot terminology to the preferred verbiage [114426]
* Fix - Handle the upload of images with more complex URLs [114201]
* Tweak - Added the `tribe_global_id_valid_types` action to allow new EA origins [114652]
* Tweak - Added the `tribe_global_id_type_origins` action to allow new EA origins [114652]

### [4.7.20] 2018-09-12

* Add - Added is_string_or_empty, is_image_or_empty, is_url_or_empty variations for REST API validation of values that are allowed to be set as empty [108834]
* Add - Introduce folder lookup for `Tribe__Template` to allow usage on Themes [112478]
* Fix - When option to avoid creating duplicate Organizers/Venues is enabled, we now exclude trash and autodraft posts when looking up potential duplicates [113882]
* Fix - Allow settings to restrict to only one country [106974]
* Tweak - Removed filters: `tribe_template_base_path`
* Tweak - Added new filters: `tribe_template_before_include`, `tribe_template_after_include`, `tribe_template_html`, `tribe_template_path_list`, `tribe_template_public_path`, `tribe_template_public_namespace`, `tribe_template_plugin_path`

### [4.7.19] 2018-08-22

* Fix - Add the following datepicker formats to the validation script: YYYY.MM.DD, MM.DD.YYYY, DD.MM.YYYY [102815]
* Add - Added the `Tribe__Process__Queue::delete_all_queues` method [111856]
* Tweak - updated some foundation code for the Tickets REST API [108021]
* Tweak - Event Aggregator Add-On text due to the removal of Facebook Imports [111729]

### [4.7.18] 2018-08-01

* Fix - Add `target="_blank"` to repository links in the Help Page [107974]
* Fix - Change 3rd parameter to be relative path to plugin language files instead of the mofile for load_plugin_textdomain(), thanks to jmortell [63144]
* Tweak - Deprecate the usage of old asset loading methods [40267]

### [4.7.17] 2018-07-10

* Add - Method to sanitize a multidimensional array [106000]
* Add - New is_not_null and is_null methods for Tribe__Validator__Base [109482]
* Tweak - Added new filter `tribe_plugins_get_list` to give an opportunity to modify the list of tribe plugins [69581]

### [4.7.16] 2018-06-20

* Fix - Fixed a PHP warning related to the RSS feed in the Help page [108398]
* Tweak - Add notices related to PHP minimum versions [107852]

### [4.7.15] 2018-06-04

* Add - Method to parse the Global ID string [104379]
* Add - Load tribe-common script to prevent undefined function errors with tribe-dropdowns [107610]

### [4.7.14] 2018-05-29

* Fix - Adjust the `Tribe__PUE__Checker` $stats creation regarding WordPress multisite installs [84231]
* Fix - Hide any errors generated by servers that don't support `set_time_limit()` [64183]

### [4.7.13] 2018-05-16

* Fix - Prevent PHP 5.2 error on new Queuing Process `T_PAAMAYIM_NEKUDOTAYIM` [106696]
* Fix - Modify some language and typos

### [4.7.12] 2018-05-09

* Fix - Updated datatables.js to its most recent version to prevent conflicts [102465]
* Tweak - Added the `Tribe__Process__Queue` class to handle background processing operations
* Tweak - Changed 'forums' for 'help desk' in the Help content [104561]
* Tweak - Updated datatables.js to most recent version, to prevent conflicts [102465]
* Tweak - Add `tribe_set_time_limit()` wrapper function to prevent errors from `set_time_limit()` [64183]
* Tweak - Changed 'forums' to 'help desk' throughout the content in the "Help" tab [104561]
* Language - 3 new strings added, 84 updated, 3 fuzzied, and 3 obsoleted

### [4.7.11] 2018-04-18

* Fix - Restore "type" attribute to some inline `<script>` tags to ensure proper character encoding in Customizer-generated CSS [103167]
* Tweak - Allow to register the same ID of a post if has multiple types for JSON-LD `<script>` tag [94989]
* Tweak - Added the `a5hleyrich/wp-background-processing` package and the asynchronous process handling base [102323]
* Tweak - Added the `Tribe__Process__Post_Thumbnail_Setter` class to handle post thumbnail download and creation in an asynchronous manner [102323]
* Tweak - Deprecated the `Tribe__Main::doing_ajax()` method and moved it to the `Tribe__Context::doing_ajax()` method [102323]
* Tweak - Modified the `select2` implementation to work with the `maximumSelectionSize` argument via data attribute. [103577]
* Tweak - Add new filters: `tribe_countries` and `tribe_us_states` to allow easier extensibility on the names used for each country [79880]
* Fix - Updated Timezones::abbr() with additional support for timezone strings not covered by PHP date format "T" [102705]

### [4.7.10] 2018-03-28

* Tweak - Adjusted app shop text in relation to The Events Calendar's ticketing solutions [101655]
* Tweak - Added wrapper function around use of `tribe_events_get_the_excerpt` for safety [95034]

### [4.7.9] 2018-03-12

* Tweak - Added the a `tribe_currency_cost` filtering for Currency control for Prices and Costs

### [4.7.8] 2018-03-06

* Feature - Added new `tribe_get_global_query_object()` template tag for accessing the $wp_query global without triggering errors if other software has directly manipulated the global [100199]
* Fix - Remove unnecessary timezone-abbreviation caching approach to improve accuracy of timezone abbreviations and better reflect DST changes [97344]
* Fix - Make sure JSON strings are always a single line of text [99089]

### [4.7.7.1] 2018-02-16

* Fix - Rollback changes introduced in version 4.7.7 to allow month view to render correctly.

### [4.7.7] 2018-02-14

* Fix - Fixed the behavior of the `tribe_format_currency` function not to overwrite explicit parameters [96777]
* Fix - Modified timezone handling in relation to events, in order to avoid DST changes upon conversion to UTC [69784]
* Tweak - Improved the performance of dropdown and recurrent events by using caching on objects (our thanks to Gilles in the forums for flagging this problem) [81993]
* Tweak - Reduced the risk of conflicts when lodash and underscore are used on the same site [92205]
* Tweak - Added the `tribe_transient_notice` and `tribe_transient_notice_remove` functions to easily create and remove fire-and-forget admin notices
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.7.6] 2018-01-23

* Fix - Make sure to apply `$settings` to each section with the initial values in the customizer [96821]
* Tweak - Include permalink structure into the report for support [68687]
* Tweak - Added `not_empty()` validation method to the `Tribe__Validate` class for more options while validating date formats [94725]
* Tweak - Update label on report for support to avoid confusions [68687]
* Tweak - Deprecated the unused $timezone parameter in the `tribe_get_start_date()` and `tribe_get_end_date()` template tags [73400]

### [4.7.5] 2018-01-10

* Fix - Added safety check to avoid errors surrounding the use of count() (our thanks to daftdog for highlighting this issue) [95527]
* Fix - Improved file logger to gracefully handle further file system restrictions (our thanks to Richard Palmer for highlighting further issues here) [96747]

### [4.7.4] 2017-12-18

* Fix - Fixed Event Cost field causing an error if it did not contain any numeric characters [95400]
* Fix - Fixed the color of the license key validation messages [91890]
* Fix - Added a safety check to avoid errors in the theme customizer when the search parameter is empty (props @afragen)
* Language - 1 new strings added, 5 updated, 1 fuzzied, and 0 obsoleted

### [4.7.3] 2017-12-07

* Tweak - Tweaked Tribe Datepicker to prevent conflicts with third-party styles [94161]

### [4.7.2] 2017-11-21

* Feature - Added Template class which adds a few layers of filtering to any template file included
* Tweak - Included `tribe_callback_return` for static returns for Hooks
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

### [4.7.1] 2017-11-16

* Fix - Added support for translatable placeholder text when dropdown selectors are waiting on results being returned via ajax [84926]
* Fix - Implemented an additional file permissions check within default error logger (our thanks to Oscar for highlighting this) [73551]
* Tweak - Added new `tribe_is_site_using_24_hour_time()` function to easily check if the site is using a 24-hour time format [78621]
* Tweak - Ensure the "Debug Mode" helper text in the Events Settings screen displays all of the time (it previously would vanish with certain permalinks settings) [92315]
* Tweak - Allow for non-Latin characters to be used as the Events URL slug and the Single Event URL slug (thanks @daviddweb for originally reporting this) [61880]
* Tweak - Removed restrictions imposed on taxonomy queries by Tribe__Ajax__Dropdown (our thanks to Ian in the forums for flagging this issue) [91762]
* Tweak - Fixed the definition of Tribe__Rewrite::get_bases() to address some PHP strict notices its previous definition triggered [91828]
* Language - 0 new strings added, 16 updated, 1 fuzzied, and 0 obsoleted

### [4.7] 2017-11-09

* Feature - Included a new Validation.js for Forms and Fields
* Feature - Included a Camel case Utils for JavaScript
* Tweak - Added Groups functionality for Tribe Assets class
* Tweak - Improve Dependency.js with better Documentation
* Tweak - Timepicker.js is now part of Common instead of The Events Calendar
* Language - 0 new strings added, 23 updated, 1 fuzzied, and 0 obsoleted

### [4.6.3] 2017-11-02

* Fix - Added some more specification to our jquery-ui-datepicker CSS to limit conflicts with other plugins and themes [90577]
* Fix - Fixed compatibility issue with Internet Explorer 10 & 11 when selecting a venue from the dropdown (thanks (@acumenconsulting for reporting this) [72924]
* Fix - Improved process for sharing JSON data in the admin environment so that it also works within the theme customizer screen [72127]
* Tweak - Obfuscated the API key for the google_maps_js_api_key field in the "System Information" screen [89795]
* Tweak - Updated the list of countries used in the country dropdown [75769]
* Tweak - Added additional timezone handling facilities [78233]
* Language - 7 new strings added, 292 updated, 18 fuzzied, and 3 obsoleted

### [4.6.2] 2017-10-18

* Fix - Restored functionality to the "currency position" options in Events Settings, and in the per-event cost settings (props @schola and many others!) [89918]
* Fix - Added safety checks to reduce the potential for errors stemming from our logging facilities (shout out to Brandon Stiner and Russell Todd for highlighting some remaining issues here) [90436, 90544]
* Fix - Added checks to avoid the generation of warnings when rendering the customizer CSS template (props: @aristath) [91070]
* Fix - Added safety checks to the Tribe__Post_Transient class to avoid errors when an array is expected but not available [91258]
* Tweak - Improved strategy for filtering of JSON LD data (our thanks to Mathew in the forums for flagging this issue) [89801]
* Tweak - Added new tribe_is_wpml_active() function for unified method of checking (as its name implies) if WPML is active [82286]
* Tweak - Removed call to deprecated screen_icon() function [90985]

### [4.6.1] 2017-10-04

* Fix - Fixed issues with the jQuery Timepicker vendor script conflicting with other plugins' similar scripts (props: @hcny et al.) [74644]
* Fix - Added support within Tribe__Assets for when someone filters plugins_url() (Thank you @boonebgorges for the pull request!) [89228]
* Fix - Improved performance of retrieving the country and US States lists [68472]
* Tweak - Limited the loading of several Tribe Common scripts and stylesheets to only load where needed within the wp-admin (props: @traildamage ) [75031]
* Tweak - Removed explicit width styles from app shop "buy now" buttons to better accommodate longer language strings (thanks @abrain on GitHub for submitting this fix!) [88868]
* Tweak - Implemented a re-initializing of Select2 inputs on use of a browser's "Back" button to prevent some UI bugs, e.g. with such inputs' placeholder attributes not being populated (props @uwefunk!) [74553]
* Language - Improvement to composition of various strings, to aid translatability (props: @ramiy) [88982]
* Language - 3 new strings added, 331 updated, 1 fuzzied, and 2 obsoleted

### [4.6] 2017-09-25

* Feature - Add support for create, update, and delete REST endpoints
* Language - 1 new strings added, 24 updated, 1 fuzzied, and 0 obsoleted

### [4.5.13] 2017-09-20

* Feature - Remove 'France, Metropolitan' option from country list to prevent issues with Google Maps API (thanks @varesanodotfr for pointing this out) [78023]
* Fix - Prevents breakages resulting from deprecated filter hooks
* Tweak - Added an id attribute to dropdowns generated by the Fields API [spotfix]
* Fix - Prevents resetting selected Datatables rows when changing pages (thanks @templesinai for reporting) [88437]

### [4.5.12] 2017-09-06

* Fix - Added check to see if log directory is readable before listing logs within it (thank you @rodrigochallengeday-org and @richmondmom for reporting this) [86091]
* Tweak - Datatables Head and Foot checkboxes will not select all items, only the current page [77395]
* Tweak - Added method into Date Utils class to allow us to easily convert all datepicker formats into the default one [77819]
* Tweak - Added a filter to customize the list of states in the USA that are available to drop-downs when creating or editing venues.
* Language - 3 new strings added, 46 updated, 1 fuzzied, and 4 obsoleted

### [4.5.11] 2017-08-24

* Fix - Ensure valid license keys save as expected [84966]
* Tweak - Removing WP Plugin API result adjustments

### [4.5.10.1] 2017-08-16

* Fix - Fixed issue with JS/CSS files not loading when WordPress URL is HTTPS but Site URL is not (our thanks to @carcal1 for first reporting this) [85017]

### [4.5.10] 2017-08-09

* Fix - Added support to tribe_asset() for non-default plugin directions/usage from within the mu-plugin directory (our thanks to @squirrelandnnuts for reporting this) [82809]
* Fix - Made JSON LD permalinks overridable by all post types, so they can be filtered [76411]
* Tweak - Improve integration with the plugins API/add new plugins screen (our thanks to David Sharpe for highlighting this) [82223]
* Tweak - Improve the Select2 search experience (props to @fabianmarz) [84496]
* Language - 0 new strings added, 312 updated, 1 fuzzied, and 0 obsoleted

### [4.5.9] 2017-07-26

* Fix - Avoid accidental overwrite of options when settings are saved in a multisite context [79728]
* Fix - Provide a well sorted list of countries even when this list is translated (our thanks to Johannes in the forums for highlighting this) [69550]
* Tweak - Cleanup logic responsible for handling the default country option and remove confusing translation calls (our thanks to Oliver for flagging this!) [72113]
* Tweak - Added period "." separator to datepicker formats [65282]
* Tweak - Avoid noise relating to PUE checks during WP CLI requests

### [4.5.8] 2017-07-13

* Fix - Fixes to the plugin upgrade notice parser including support for environments where the data stream wrapper is unavailable [69486]
* Fix - Ensure the multichoice settings configured to allow no selection work as expected [73183]
* Fix - Enqueue expired notice and CSS on every admin page [81714]
* Tweak - Add helper to retrieve anonymous objects using the class name, hook and callback priority [74938]
* Tweak - Allow dependency.js to handle radio buttons. ensure that they are linked correctly. [82510]
* Fix - Allow passing multiple localize-scripts to tribe-assets. Don't output a localized scrip more than once. [81644]

### [4.5.7] 2017-06-28

* Fix - Made the App Shop and help pages work on Windows. [77975]
* Fix - Resolved issue where the Meta Chunker attempted to inappropriately chunk meta for post post_types [80857]
* Fix - Avoid notices during plugin update and installation checks [80492]
* Fix - Ensure an empty dateTimeSeparator option value doesn't break tribe_get_start_date output anymore. [65286]
* Tweak - Improve navigation to elements inside admin pages (don't let the admin toolbar obscure things) [41829]
* Tweak - Textual corrections (with thanks to @garrett-eclipse) [77196]

### [4.5.6] 2017-06-22

* Fix - Resolved issue where the Meta Chunker attempted to inappropriately chunk meta for post post_types [80857]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted [tribe-common]
