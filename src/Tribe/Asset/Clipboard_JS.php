<?php


	class Tribe__Events__Asset__Clipboard_JS extends Tribe__Events__Asset__Abstract_Asset {

		public function handle() {
			$path = Tribe__Events__Template_Factory::getMinFile( $this->vendor_url . 'clipboard-js/dist/clipboard.js', true );
			$deps = array_merge( $this->deps, array( 'jquery' ) );

			$handle = $this->prefix . '-clipboard-js';
			wp_enqueue_script( $handle, $path, $deps, '1.5.12', false );
			Tribe__Events__Template_Factory::add_vendor_script( $handle );
		}
	}