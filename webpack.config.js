/**
 * External dependencies
 */
const { resolve } = require( 'path' );
const merge = require( 'webpack-merge' );
const common = require( '@the-events-calendar/product-taskmaster/webpack/common/webpack.config' );
const wpExternals = require( '@the-events-calendar/product-taskmaster/webpack/externals/wp.js' );
const vendor = require( '@the-events-calendar/product-taskmaster/webpack/externals/vendor.js' );
const lodash = require( '@the-events-calendar/product-taskmaster/webpack/externals/lodash.js' );
const modules = require( '@the-events-calendar/product-taskmaster/webpack/externals/modules.js' );

const PLUGIN_SCOPE = 'common';

const config = merge.strategy( {
	externals: 'replace',
} )(
	common,
	{
		externals: [
			wpExternals,
			vendor,
			lodash,
			modules,
		],
		entry: {
			main: resolve( __dirname, './src/modules/index.js' ),
		},
		output: {
			path: __dirname,
			library: [ 'tribe', PLUGIN_SCOPE ],
		},
	}
);

const modulesConfig = merge.strategy( {
	externals: 'replace',
	optimization: 'replace',
} )(
	common,
	{
		externals: [
			wpExternals,
			vendor,
			lodash
		],
		entry: {
			modules: resolve( __dirname, './src/modules/modules.js' ),
		},
		resolve: {
			...common.resolve,
			alias: {
				...common.resolve?.alias,
				'react-day-picker/moment': resolve(
					__dirname,
					'node_modules/moment'
				),
			},
		},
		output: {
			path: __dirname,
			library: [ 'tribe', 'modules' ],
		},
		optimization: {},
	},
);

module.exports = [
	config,
	modulesConfig,
];
