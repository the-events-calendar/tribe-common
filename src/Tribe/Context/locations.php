<?php
/**
 * Defines the locations the `Tribe__Context` class should look up.
 *
 * The location definitions are moved here to avoid burdening the `Tribe__Context` class with a long array definition
 * that would be loaded upfront every time the `Tribe__Context` class file is loaded. Since locations will be required
 * only when the Context is built moving them here is a small optimization.
 * This file is meant to be included by the `Tribe__Context::populate_locations` method.
 *
 * @since 4.9.11
 */

return [
	'posts_per_page'              => [
		'read'  => [
			Tribe__Context::REQUEST_VAR  => 'posts_per_page',
			Tribe__Context::TRIBE_OPTION => [ 'posts_per_page', 'postsPerPage' ],
			Tribe__Context::OPTION       => 'posts_per_page',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'posts_per_page',
		],
	],
	'event_display'               => [
		'read'  => [
			Tribe__Context::WP_MATCHED_QUERY => [ 'eventDisplay' ],
			Tribe__Context::WP_PARSED        => [ 'eventDisplay' ],
			Tribe__Context::REQUEST_VAR      => [ 'view', 'tribe_view', 'tribe_event_display', 'eventDisplay' ],
			Tribe__Context::QUERY_VAR        => 'eventDisplay',
			Tribe__Context::TRIBE_OPTION     => 'viewOption',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => [ 'view', 'tribe_view', 'tribe_event_display', 'eventDisplay' ],
			Tribe__Context::QUERY_VAR   => 'eventDisplay',
		],
	],
	'view'                        => [
		'read'  => [
			Tribe__Context::WP_MATCHED_QUERY => [ 'eventDisplay' ],
			Tribe__Context::WP_PARSED        => [ 'eventDisplay' ],
			Tribe__Context::REQUEST_VAR      => [ 'view', 'tribe_view', 'tribe_event_display', 'eventDisplay' ],
			Tribe__Context::QUERY_VAR        => [ 'tribe_view', 'eventDisplay' ],
			Tribe__Context::TRIBE_OPTION     => 'viewOption',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => [ 'view', 'tribe_view', 'tribe_event_display', 'eventDisplay' ],
			Tribe__Context::QUERY_VAR   => [ 'tribe_view', 'eventDisplay' ],
		],
	],
	'view_data'                   => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'tribe_view_data',
			Tribe__Context::QUERY_VAR   => 'tribe_view_data',
			Tribe__Context::FILTER      => 'tribe_view_data',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'tribe_view_data',
			Tribe__Context::QUERY_VAR   => 'tribe_view_data',
		],
	],
	'event_date'                  => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'eventDate',
			Tribe__Context::QUERY_VAR   => 'eventDate',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'eventDate',
			Tribe__Context::QUERY_VAR   => 'eventDate',
		],
	],
	'event_sequence'              => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'eventSequence',
			Tribe__Context::QUERY_VAR   => 'eventSequence',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'eventSequence',
			Tribe__Context::QUERY_VAR   => 'eventSequence',
		],
	],
	'ical'                        => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'ical',
			Tribe__Context::QUERY_VAR   => 'ical',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'ical',
			Tribe__Context::QUERY_VAR   => 'ical',
		],
	],
	'start_date'                  => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'start_date',
			Tribe__Context::QUERY_VAR   => 'start_date',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'start_date',
			Tribe__Context::QUERY_VAR   => 'start_date',
		],
	],
	'end_date'                    => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'end_date',
			Tribe__Context::QUERY_VAR   => 'end_date',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'end_date',
			Tribe__Context::QUERY_VAR   => 'end_date',
		],
	],
	'featured'                    => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'featured',
			Tribe__Context::QUERY_VAR   => 'featured',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'featured',
			Tribe__Context::QUERY_VAR   => 'featured',
		],
	],
	'tribe_events_cat' => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'tribe_events_cat',
			Tribe__Context::QUERY_VAR   => 'tribe_events_cat',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'tribe_events_cat',
			Tribe__Context::QUERY_VAR   => 'tribe_events_cat',
		],
	],
	'remove_date_filters'         => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'tribe_remove_date_filters',
			Tribe__Context::QUERY_VAR   => 'tribe_remove_date_filters',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'tribe_remove_date_filters',
			Tribe__Context::QUERY_VAR   => 'tribe_remove_date_filters',
		],
	],
	'is_main_query'               => [
		'read'  => [
			Tribe__Context::FUNC => static function () {
				global $wp_query;

				return $wp_query->is_main_query();
			},
		],
		'write' => [
			Tribe__Context::FUNC => static function () {
				global $wp_query, $wp_the_query;
				$wp_the_query = $wp_query;
			},
		],
	],
	'paged' => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => 'paged',
			Tribe__Context::QUERY_VAR   => 'paged',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'paged',
			Tribe__Context::QUERY_VAR   => 'paged',
		],
	],
	'event_display_mode' => [
		/**
		 * We use the `eventDisplay` query var with duplicity: when parsed from the path it represents the View, when
		 * appended as a query var it represents the "view mode". Here we invert the order to read the appended query
		 * var first and get, from its position, a clean variable we can consume in Views.
		 */
		'read' => [
			Tribe__Context::REQUEST_VAR => [ 'view', 'tribe_view', 'tribe_event_display', 'eventDisplay' ],
			Tribe__Context::WP_PARSED   => [ 'eventDisplay' ],
			Tribe__Context::QUERY_VAR   => 'eventDisplay',
		],
	],
];
