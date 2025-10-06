/**
 * PostCSS configuration for tribe-common.
 * This file prevents PostCSS from searching up to parent directories for configuration.
 */

module.exports = {
	plugins: {
		'postcss-import': {},
		'postcss-mixins': {},
		'postcss-nested': {},
		'postcss-custom-media': {},
		'postcss-preset-env': {
			stage: 0,
			features: {
				'custom-properties': false,
			},
		},
	},
};
