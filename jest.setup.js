/**
 * External dependencies
 */
import moment from 'moment-timezone';
import React from 'react';
import $ from 'jquery';
import renderer from 'react-test-renderer';

global.jQuery = $;
global.$ = $;
global.wp = {
	element: React,
	api: {},
	apiRequest: {},
	components: {},
	data: {},
	blockEditor: {},
	editor: {},
	hooks: {},
	i18n: {
		_x: (input) => input,
	},
};

global.renderer = renderer;

/**
 * Mock DateFormatter — a WordPress global provided by PHP scripts.
 */
global.DateFormatter = class DateFormatter {
	parseDate( value, format ) {
		if ( ! value ) {
			return undefined;
		}
		const d = new Date( value );
		return isNaN( d.getTime() ) ? undefined : d;
	}

	formatDate( date, format ) {
		if ( ! date ) {
			return '';
		}
		const options = { year: 'numeric', month: 'long', day: 'numeric' };
		return date.toLocaleDateString( 'en-US', options );
	}
};

moment.tz.setDefault( 'UTC' );
