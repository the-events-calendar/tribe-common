/**
 * Babel configuration for tribe-common.
 * This file prevents Babel from searching up to parent directories for configuration.
 */

module.exports = function( api ) {
	api.cache( true );

	return {
		presets: [ '@wordpress/babel-preset-default' ],
		plugins: [ '@babel/plugin-transform-runtime' ],
	};
};
