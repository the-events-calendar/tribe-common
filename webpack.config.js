/**
 * The default configuration coming from the @wordpress/scripts package.
 * Customized following the "Advanced Usage" section of the documentation:
 * See: https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/#advanced-usage
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

const {
	TECLegacyJs,
	TECPostCss,
	compileCustomEntryPoints,
	exposeEntry,
	doNotPrefixSVGIdsClasses,
	WindowAssignPropertiesPlugin
} = require('@stellarwp/tyson');

/**
 * Compile a list of entry points to be compiled to the format used by WebPack to define multiple entry points.
 * This is akin to the compilation system used for multi-page applications.
 * See: https://webpack.js.org/concepts/entry-points/#multi-page-application
 */
const customEntryPoints = compileCustomEntryPoints({
	/**
	 * All existing Javascript files will be compiled to ES6, most will not be changed at all,
	 * minified and cleaned up.
	 * This is mostly a pass-thru with the additional benefit that the compiled packages will be
	 * exposed on the `window.tec.common` object.
	 * E.g. the `src/resources/js/admin-ignored-events.js` file will be compiled to
	 * `/build/js/admin-ignored-events.js` and exposed on `window.tec.common.adminIgnoredEvents`.
	 */
	'/src/resources/js': TECLegacyJs,

	/**
	 * Compile, recursively, the PostCSS file using PostCSS nesting rules.
	 * By default, the `@wordpress/scripts` configuration would compile files using the CSS
	 * nesting syntax (https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_nesting) where
	 * the `&` symbol indicates the parent element.
	 * The PostCSS syntax followed in TEC files will instead use the `&` symbol to mean "this element".
	 * Handling this correctly requires adding a PostCSS processor specific to the PostCSS files that
	 * will handle the nesting correctly.
	 */
	'/src/resources/postcss': TECPostCss,
}, defaultConfig);

/**
 * Following are static entry points, to be included in the build non-recursively.
 * These are built following a modern module approach where the root `index.js` file
 * will include the whole module.
 */

/**
 * Blocks from `/src/modules/index.js` are built to `/build/app/main.js`.
 * The existing Block Editor code is not follow the `block.json` based convention expected by
 * `@wordpress/scripts` so here we explicitly point out the root index.
 */
customEntryPoints['app/main'] = exposeEntry('tec.common.app.main', __dirname + '/src/modules/index.js');

/**
 * Prepends a loader for SVG files that will be applied after the default one. Loaders are applied
 * in a LIFO queue in WebPack.
 * By default `@wordpress/scripts` uses `@svgr/webpack` to handle SVG files and, together with it,
 * the default SVGO (package `svgo/svgo-loader`) configuration that includes the `prefixIds` plugin.
 * To avoid `id` and `class` attribute conflicts, the `prefixIds` plugin would prefix all `id` and
 * `class` attributes in SVG tags with a generated prefix. This would break TEC classes (already
 * namespaced) so here we prepend a rule to handle SVG files in the `src/modules` directory by
 * disabling the `prefixIds` plugin.
 */
doNotPrefixSVGIdsClasses(defaultConfig);

/**
 * Prepend a loader for the PostCSS files that processes them using the postcss-custom-media plugins.
 */
defaultConfig.module.rules = [
	...defaultConfig.module.rules,
	{
		test: /\.pcss$/,
		use: [
			{
				loader: 'postcss-loader',
				options: {
					postcssOptions: {
						plugins: [
							[
								require('postcss-custom-media'),
								{
									preserve: false,
								},
							],
						],
					},
				},
			},
		],
	},
];

/**
 * Finally the customizations are merged with the default WebPack configuration.
 */
module.exports = {
	...defaultConfig,
	...{
		entry: (buildType) => {
			const defaultEntryPoints = defaultConfig.entry(buildType);
			return {
				...defaultEntryPoints, ...customEntryPoints,
			};
		},
		output: {
			...defaultConfig.output,
			...{
				enabledLibraryTypes: ['window'],
			},
		},
		plugins: [
			...defaultConfig.plugins,
			new WindowAssignPropertiesPlugin(),
		],
	},
};
