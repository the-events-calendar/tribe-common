<?php

namespace Tribe\Editor;

use Tribe\Editor\Compatibility\Classic_Editor;

/**
 * Editor Compatibility with other plugins and themes.
 *
 * @since TBD
 */
class Compatibility extends \tad_DI52_ServiceProvider {
	public function register() {
		$this->container->singleton( self::class, $this );
		$this->container->singleton( 'editor.compatibility', $this );

		// Conditionally load compatibility for the Classic Editor plugin
		if ( Classic_Editor::is_classic_plugin_active() ) {
			$this->container->singleton( 'editor.compatibility.classic-editor', Classic_Editor::class );
			tribe( 'editor.compatibility.classic-editor' )->init();
		}

		// @todo: conditionally load compatibility for Divi
		// @see Divi/includes/builder/feature/ClassicEditor.php
	}
}
