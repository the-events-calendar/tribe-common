/**
 * The default configuration coming from the @wordpress/scripts package.
 * Customized following the "Advanced Usage" section of the documentation:
 * See: https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/#advanced-usage
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

const {
	createTECLegacyJs,
	createTECPostCss,
	compileCustomEntryPoints,
	exposeEntry,
	doNotPrefixSVGIdsClasses,
	WindowAssignPropertiesPlugin,
	preprocessPostcssWithPlugins
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
	'/src/resources/js': createTECLegacyJs('tec.common'),

	/**
	 * Compile, recursively, the PostCSS file using PostCSS nesting rules.
	 * By default, the `@wordpress/scripts` configuration would compile files using the CSS
	 * nesting syntax (https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_nesting) where
	 * the `&` symbol indicates the parent element.
	 * The PostCSS syntax followed in TEC files will instead use the `&` symbol to mean "this element".
	 * Handling this correctly requires adding a PostCSS processor specific to the PostCSS files that
	 * will handle the nesting correctly.
	 */
	'/src/resources/postcss': createTECPostCss('tec.common'),
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
 * Build a `modules` bundle, used by the `tribe-common-gutenberg-modules` bundle
 */
customEntryPoints['app/modules'] = exposeEntry('tec.common.app.modules', __dirname + '/src/modules/modules.js');

/**
 * Build the `vendor` bundles: `tribe-common-gutenberg-vendor` and `tribe-common-gutenberg-vendor-styles`.
 * This is built for back-compatibility purposes, and it's little more than a pass-thru of the files originally
 * compiled by the legacy Block Editor system based on WebPack 4.
 */
customEntryPoints['app/vendor'] = exposeEntry('tec.common.app.vendor', __dirname + '/src/modules/vendor/index.js');

/**
 * Transpile files from the `src/resources/vendor` directory into `/build/vendor` directory.
 */
customEntryPoints['vendor/clipboard'] = __dirname + '/src/resources/vendor/clipboard.min.js'
customEntryPoints['vendor/intro'] = __dirname + '/src/resources/vendor/intro/index.js'

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
 * By default, `@wordpress/scripts` would first process PostCSS files using the `autoprefixer` plugin.
 * This will fail if the PostCSS has not been already pre-processed with this two plugins specific to
 * Common:
 * - postcss-nested to resolve nesting the PostCSS way (including media queries).
 * - postcss-custom-media to allow custom media queries to correctly unroll.
 */
preprocessPostcssWithPlugins(
	defaultConfig,
	[
		require('postcss-nested'),
		require('postcss-custom-media')
	]
);

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
