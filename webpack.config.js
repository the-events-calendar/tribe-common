/**
 * External dependencies
 */
const { resolve } = require( 'path' );
const merge = require( 'webpack-merge' );
const common = require( '@the-events-calendar/product-taskmaster/webpack/common/webpack.config' );
const wpExternals = require( '@the-events-calendar/product-taskmaster/webpack/externals/wp.js' );
const vendor = require( '@the-events-calendar/product-taskmaster/webpack/externals/vendor.js' );
const { getDirectoryNames } = require( '@the-events-calendar/product-taskmaster/webpack/utils/directories' );
const { generateEntries } = require( '@the-events-calendar/product-taskmaster/webpack/entry/tribe' );

const directoryNames = getDirectoryNames( resolve( __dirname, './src/modules' ) );
const PLUGIN_SCOPE = 'common';

const config = merge.strategy( {
	externals: 'replace',
} )(
	common,
	{
		externals: { ...wpExternals, ...vendor }, // Only use WP externals
		entry: generateEntries( __dirname, directoryNames ),
		output: {
			path: __dirname,
			library: [ 'tribe', PLUGIN_SCOPE, '[name]' ],
		},
	}
);

module.exports = config;
