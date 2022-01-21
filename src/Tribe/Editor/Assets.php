<?php

/**
 * Events Gutenberg Assets
 *
 * @since 4.8
 */
class Tribe__Editor__Assets {
	/**
	 *
	 * @since 4.8
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'tribe_plugins_loaded', [ $this, 'register' ] );
	}

	/**
	 * Registers and Enqueues the assets
	 *
	 * @since 4.8
	 *
	 * @param string $key Which key we are checking against
	 *
	 * @return boolean
	 */
	public function register() {

		$plugin = Tribe__Main::instance();

		/**
		 * Block editor JS.
		 */
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-vendor',
			'app/vendor.js',
			[ $this, 'filter_event_blocks_editor_deps' ],
			'enqueue_block_editor_assets',
			[
				'in_footer' => false,
				'localize'  => [],
				'priority'  => 10,
			]
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-modules',
			'app/modules.js',
			[ $this, 'filter_event_blocks_editor_deps' ],
			'enqueue_block_editor_assets',
			[
				'in_footer' => false,
				'localize'  => [],
				'priority'  => 11,
			]
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-main',
			'app/main.js',
			[ $this, 'filter_event_blocks_editor_deps' ],
			'enqueue_block_editor_assets',
			[
				'in_footer' => false,
				'localize'  => [
					[
						'name' => 'tribe_editor_config',
						/**
						 * Array used to setup the FE with custom variables from the BE
						 *
						 * @since 4.8
						 *
						 * @param array An array with the variables to be localized
						 */
						'data' => tribe_callback( 'common.editor.configuration', 'localize' ),
					],
				],
				'priority'  => 12,
			]
		);

		/**
		 * Block editor CSS.
		 */
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-vendor-styles',
			'app/vendor.css',
			[],
			'enqueue_block_editor_assets',
			[
				'in_footer' => false,
			]
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-main-styles',
			'app/main.css',
			[],
			'enqueue_block_editor_assets',
			[
				'in_footer' => false,
			]
		);
	}

	/**
	 * Filter the dependencies for event blocks
	 *
	 * @since 4.14.2
	 *
	 * @param array|object|null $assets Array of asset objects, single asset object, or null.
	 *
	 * @return array An array of dependency slugs.
	 */
	public function filter_event_blocks_editor_deps( $asset ) {
		global $pagenow;

		$deps = [
			'react',
			'react-dom',
			'wp-components',
			'wp-api',
			'wp-api-request',
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-editor',
		];

		if ( 'post.php' !== $pagenow && 'post-new.php' !== $pagenow ) {
			if ( ( $key = array_search( 'wp-editor', $deps ) ) !== false ) {
				unset( $deps[ $key ] );
			}
		}

		return $deps;
	}
}
