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
		externals: [],
		entry: {
			modules: resolve( __dirname, './src/modules/modules.js' ),
		},
		output: {
			path: __dirname,
			library: [ 'tribe', 'modules' ],
		},
		optimization: {},
	},
);

const calendashConfig = merge.strategy( {
    externals: 'replace',
    optimization: 'replace',
})(
    common,
    {
        externals: [],
        entry: {
            'calendash': resolve( __dirname, './src/modules/calendash.js' ),
        },
        output: {
            path: resolve( __dirname, './src/resources/js/app/' ),
            filename: '[name].js',
            library: [ 'tribe', 'calendash' ],
        },
        optimization: {},
        module: {
            rules: [
                {
                    test: /\.(js|jsx)$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: [ '@babel/preset-env', '@babel/preset-react' ],
                        },
                    },
                },
            ],
        },
    },
);

module.exports = [
	config,
	modulesConfig,
	calendashConfig,
];
