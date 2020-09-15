<?php
/**
 * The base test case that snapshot test cases should extend for a minimum amount of required setup.
 *
 * @package Tribe\Tests\Snapshots
 */

namespace Tribe\Tests\Snapshots;

use Tribe__Template as Template;

/**
 * Class Snapshot_Test_Case
 */
class Snapshot_Test_Case extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var string The path, either relative to the `/src` directory or absolute, to the template to test.
	 */
	protected $template_path;

	/**
	 * @var Template The template instance, set up on the template path.
	 */
	protected $template;

	/**
	 * Sets up the template that will be used in the tests.
	 */
	public function setUp() {
		parent::setUp();

		if ( empty( $this->template_path ) ) {
			throw new \RuntimeException( 'Extending test cases should define the "template_path" property.' );
		}

		$template_file = is_dir( $this->template_path )
			? $this->template_path
			: dirname( __DIR__ ) . '/../src/' . ltrim( $this->template_path, '\\/' );

		if ( ! is_file( $template_file ) ) {
			throw new \RuntimeException( "The {$template_file} file does not exist." );
		}

		$template_file = realpath( $template_file );
		if ( false === $template_file ) {
			throw new \RuntimeException( "Could not resolve template file {$template_file} to real file." );
		}

		// To streamline the set up create the template as instance of an anonymous class set up for success.
		$this->template = new class( $template_file ) extends Template {
			protected $template_context_extract = true;

			public function __construct( string $template_file ) {
				$this->template_file = $template_file;
			}

			public function get_template_file( $name ) {
				return $this->template_file;
			}
		};
	}

	/**
	 * Renders the template with the provided context array and returns the output it produced.
	 *
	 * @param array<string,mixed> $context The template render context, a map of values.
	 *
	 * @return string|false Either the final content HTML or `false` if no template could be found.
	 */
	protected function render( array $context = [] ) {
		return $this->template->template( $this->template_path, $context, false );
	}
}
