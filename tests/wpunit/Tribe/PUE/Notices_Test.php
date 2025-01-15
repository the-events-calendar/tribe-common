<?php
namespace Tribe\PUE;

use lucatume\WPBrowser\TestCase\WPTestCase;
use Tribe__PUE__Notices as Notices;

class Notices_Test extends WPTestCase
{
	public function test_render_invalid_key_with_empty_notices(): void {
		// Empty the notices.
		update_option( Notices::STORE_KEY, [] );
		// Add one empty pue install key to trigger the rendering.
		update_option( 'pue_install_key_for_something', '' );

		$notices = new Notices();
		ob_start();
		$notices->render_invalid_key();
		$output = ob_get_clean();

		$this->assertEmpty( $output );
	}

	public function test_render_invalid_key_with_null_invalid_key_entry_in_notices():void{
		// Empty the `invalid_key` entry in the notices.
		update_option( Notices::STORE_KEY, [ 'invalid_key' => null ] );
		// Add one empty pue install key to trigger the rendering.
		update_option( 'pue_install_key_for_something', '' );

		$notices = new Notices();
		ob_start();
		$notices->render_invalid_key();
		$output = ob_get_clean();

		$this->assertEmpty( $output );
	}
}
