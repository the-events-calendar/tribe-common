<?php
/**
 * Events Gutenberg Assets
 *
 * @since TBD
 */
class Tribe__Editor__Assets {
	/**
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'tribe_plugins_loaded', array( $this, 'register' ) );
	}

	/**
	 * Registers and Enqueues the assets
	 *
	 * @since TBD
	 *
	 * @param string $key Which key we are checking against
	 *
	 * @return boolean
	 */
	public function register() {

		$plugin = tribe( 'common.main' );

		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-data',
			'app/data.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 11,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-utils',
			'app/utils.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 12,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-store',
			'app/store.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 13,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-icons',
			'app/icons.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 14,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-hoc',
			'app/hoc.js',
			/**
			 * @todo revise this dependencies
			 */
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 15,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-components',
			'app/components.js',
			/**
			 * @todo revise this dependencies
			 */
			array( 'react', 'react-dom', 'wp-components', 'wp-api', 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 16,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-elements',
			'app/elements.js',
			/**
			 * @todo revise this dependencies
			 */
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 17,
			)
		);
		/**
		 * @todo: figure out why element styles are loading for tickets but not events.
		 */
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-components',
			'app/components.js',
			/**
			 * @todo revise this dependencies
			 */
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 17,
			)
		);
		tribe_asset(
			$plugin,
			'tribe-common-gutenberg-elements-styles',
			'app/elements.css',
			array(),
			'enqueue_block_editor_assets',
			array(
				'in_footer'    => false,
			)
		);
	}
}
