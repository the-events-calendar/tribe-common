<?php
/**
 * Tests for Has_Generic_Upsell_Opportunity trait.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */

declare( strict_types=1 );

namespace TEC\Common\Tests\Admin\Conditional_Content\Traits;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Admin\Conditional_Content\Traits\Has_Generic_Upsell_Opportunity;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__Plugins_API;

/**
 * Class Has_Generic_Upsell_Opportunity_Test
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */
class Has_Generic_Upsell_Opportunity_Test extends WPTestCase {

	use With_Uopz;

	/**
	 * Test implementation class.
	 */
	protected $test_class;

	/**
	 * Set up test environment.
	 *
	 * @before
	 */
	public function set_up(): void {
		// Create a test class that uses the trait.
		$this->test_class = new class() {
			use Has_Generic_Upsell_Opportunity;

			protected string $slug = 'test-content';

			// Expose protected method for testing.
			public function public_has_upsell_opportunity(): bool {
				return $this->has_upsell_opportunity();
			}
		};
	}

	/**
	 * Tear down test environment.
	 *
	 * @after
	 */
	public function tear_down(): void {
		// No specific teardown needed.
	}

	/**
	 * Test that has_upsell_opportunity returns true when paid plugins are not installed.
	 *
	 * @test
	 */
	public function should_have_upsell_opportunity_when_paid_plugins_not_installed() {
		// Mock the Plugins API to return products with no paid plugins installed.
		$products = [
			'the-events-calendar' => [
				'title'        => 'The Events Calendar',
				'slug'         => 'the-events-calendar',
				'is_installed' => true,
				'free'         => true,
			],
			'event-tickets'       => [
				'title'        => 'Event Tickets',
				'slug'         => 'event-tickets',
				'is_installed' => true,
				'free'         => true,
			],
			'events-calendar-pro' => [
				'title'        => 'Events Calendar Pro',
				'slug'         => 'events-calendar-pro',
				'is_installed' => false,
				'free'         => false,
			],
			'event-tickets-plus'  => [
				'title'        => 'Event Tickets Plus',
				'slug'         => 'event-tickets-plus',
				'is_installed' => false,
				'free'         => false,
			],
		];

		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			$products
		);

		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertTrue( $result, 'Should have upsell opportunity when paid plugins are not installed' );
	}

	/**
	 * Test that has_upsell_opportunity returns false when all paid plugins are installed.
	 *
	 * @test
	 */
	public function should_not_have_upsell_opportunity_when_all_paid_plugins_installed() {
		// Mock the Plugins API to return all paid plugins as installed.
		$products = [
			'the-events-calendar'    => [
				'title'        => 'The Events Calendar',
				'slug'         => 'the-events-calendar',
				'is_installed' => true,
				'free'         => true,
			],
			'event-tickets'          => [
				'title'        => 'Event Tickets',
				'slug'         => 'event-tickets',
				'is_installed' => true,
				'free'         => true,
			],
			'events-calendar-pro'    => [
				'title'        => 'Events Calendar Pro',
				'slug'         => 'events-calendar-pro',
				'is_installed' => true,
				'free'         => false,
			],
			'event-tickets-plus'     => [
				'title'        => 'Event Tickets Plus',
				'slug'         => 'event-tickets-plus',
				'is_installed' => true,
				'free'         => false,
			],
			'tribe-filterbar'        => [
				'title'        => 'Filter Bar',
				'slug'         => 'tribe-filterbar',
				'is_installed' => true,
				'free'         => false,
			],
			'events-community'       => [
				'title'        => 'Community Events',
				'slug'         => 'events-community',
				'is_installed' => true,
				'free'         => false,
			],
			'tribe-eventbrite'       => [
				'title'        => 'Eventbrite Tickets',
				'slug'         => 'tribe-eventbrite',
				'is_installed' => true,
				'free'         => false,
			],
			'promoter'               => [
				'title'        => 'Promoter',
				'slug'         => 'promoter',
				'is_installed' => true,
				'free'         => false,
			],
			'event-aggregator'       => [
				'title'        => 'Event Aggregator',
				'slug'         => 'event-aggregator',
				'is_installed' => true,
				'free'         => false,
			],
			'image-widget-plus'      => [
				'title'        => 'Image Widget Plus',
				'slug'         => 'image-widget-plus',
				'is_installed' => true,
				'free'         => false,
			],
			'event-schedule-manager' => [
				'title'        => 'Event Schedule Manager',
				'slug'         => 'event-schedule-manager',
				'is_installed' => true,
				'free'         => false,
			],
		];

		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			$products
		);

		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertFalse( $result, 'Should not have upsell opportunity when all paid plugins are installed' );
	}

	/**
	 * Test that has_upsell_opportunity returns true when only one paid plugin is missing.
	 *
	 * @test
	 */
	public function should_have_upsell_opportunity_when_one_paid_plugin_missing() {
		// Mock the Plugins API with all but one paid plugin installed.
		$products = [
			'the-events-calendar'    => [
				'title'        => 'The Events Calendar',
				'slug'         => 'the-events-calendar',
				'is_installed' => true,
				'free'         => true,
			],
			'events-calendar-pro'    => [
				'title'        => 'Events Calendar Pro',
				'slug'         => 'events-calendar-pro',
				'is_installed' => true,
				'free'         => false,
			],
			'event-tickets-plus'     => [
				'title'        => 'Event Tickets Plus',
				'slug'         => 'event-tickets-plus',
				'is_installed' => true,
				'free'         => false,
			],
			'tribe-filterbar'        => [
				'title'        => 'Filter Bar',
				'slug'         => 'tribe-filterbar',
				'is_installed' => false, // This one is not installed.
				'free'         => false,
			],
			'events-community'       => [
				'title'        => 'Community Events',
				'slug'         => 'events-community',
				'is_installed' => true,
				'free'         => false,
			],
			'tribe-eventbrite'       => [
				'title'        => 'Eventbrite Tickets',
				'slug'         => 'tribe-eventbrite',
				'is_installed' => true,
				'free'         => false,
			],
			'promoter'               => [
				'title'        => 'Promoter',
				'slug'         => 'promoter',
				'is_installed' => true,
				'free'         => false,
			],
			'event-aggregator'       => [
				'title'        => 'Event Aggregator',
				'slug'         => 'event-aggregator',
				'is_installed' => true,
				'free'         => false,
			],
			'image-widget-plus'      => [
				'title'        => 'Image Widget Plus',
				'slug'         => 'image-widget-plus',
				'is_installed' => true,
				'free'         => false,
			],
			'event-schedule-manager' => [
				'title'        => 'Event Schedule Manager',
				'slug'         => 'event-schedule-manager',
				'is_installed' => true,
				'free'         => false,
			],
		];

		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			$products
		);

		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertTrue( $result, 'Should have upsell opportunity when even one paid plugin is not installed' );
	}

	/**
	 * Test that only free plugins being installed counts as upsell opportunity.
	 *
	 * @test
	 */
	public function should_ignore_free_plugins_when_checking_upsell_opportunity() {
		// Mock with only free plugins installed.
		$products = [
			'the-events-calendar' => [
				'title'        => 'The Events Calendar',
				'slug'         => 'the-events-calendar',
				'is_installed' => true,
				'free'         => true,
			],
			'event-tickets'       => [
				'title'        => 'Event Tickets',
				'slug'         => 'event-tickets',
				'is_installed' => true,
				'free'         => true,
			],
			'events-calendar-pro' => [
				'title'        => 'Events Calendar Pro',
				'slug'         => 'events-calendar-pro',
				'is_installed' => false,
				'free'         => false,
			],
		];

		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			$products
		);

		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertTrue( $result, 'Should have upsell opportunity even when free plugins are installed (only checking paid plugins)' );
	}

	/**
	 * Test that has_upsell_opportunity returns false when no products exist.
	 *
	 * @test
	 */
	public function should_return_false_when_no_products_exist() {
		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			[]
		);

		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertFalse( $result, 'Should return false when no products exist' );
	}

	/**
	 * Test that has_upsell_opportunity returns true when only paid plugins exist and none are installed.
	 *
	 * @test
	 */
	public function should_return_true_when_only_paid_plugins_exist_and_none_installed() {
		$products = [
			'events-calendar-pro' => [
				'title'        => 'Events Calendar Pro',
				'slug'         => 'events-calendar-pro',
				'is_installed' => false,
				'free'         => false,
			],
			'event-tickets-plus'  => [
				'title'        => 'Event Tickets Plus',
				'slug'         => 'event-tickets-plus',
				'is_installed' => false,
				'free'         => false,
			],
		];

		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			$products
		);

		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertTrue( $result, 'Should return true when paid plugins exist and none are installed' );
	}

	/**
	 * Test that has_upsell_opportunity returns false when only free plugins exist.
	 *
	 * @test
	 */
	public function should_return_false_when_only_free_plugins_exist() {
		$products = [
			'the-events-calendar' => [
				'title'        => 'The Events Calendar',
				'slug'         => 'the-events-calendar',
				'is_installed' => true,
				'free'         => true,
			],
			'event-tickets'       => [
				'title'        => 'Event Tickets',
				'slug'         => 'event-tickets',
				'is_installed' => true,
				'free'         => true,
			],
		];

		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			$products
		);

		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertFalse( $result, 'Should return false when only free plugins exist' );
	}

	/**
	 * Test that has_upsell_opportunity returns true when ignoring plugin checks.
	 *
	 * @test
	 */
	public function should_always_return_true_when_ignoring_plugin_checks() {
		// Create a test class that overrides should_ignore_plugin_checks to return true.
		$ignore_checks_class = new class() {
			use Has_Generic_Upsell_Opportunity;

			protected string $slug = 'test-content';

			// Override to ignore plugin checks.
			protected function should_ignore_plugin_checks(): bool {
				return true;
			}

			// Expose protected method for testing.
			public function public_has_upsell_opportunity(): bool {
				return $this->has_upsell_opportunity();
			}
		};

		// Mock all paid plugins as installed (normally would return false).
		$products = [
			'events-calendar-pro' => [
				'title'        => 'Events Calendar Pro',
				'slug'         => 'events-calendar-pro',
				'is_installed' => true,
				'free'         => false,
			],
			'event-tickets-plus'  => [
				'title'        => 'Event Tickets Plus',
				'slug'         => 'event-tickets-plus',
				'is_installed' => true,
				'free'         => false,
			],
		];

		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			$products
		);

		$result = $ignore_checks_class->public_has_upsell_opportunity();

		$this->assertTrue( $result, 'Should always return true when ignoring plugin checks' );
	}

	/**
	 * Test that ignoring plugin checks works even with no products.
	 *
	 * @test
	 */
	public function should_return_true_when_ignoring_checks_and_no_products() {
		// Create a test class that overrides should_ignore_plugin_checks to return true.
		$ignore_checks_class = new class() {
			use Has_Generic_Upsell_Opportunity;

			protected string $slug = 'test-content';

			// Override to ignore plugin checks.
			protected function should_ignore_plugin_checks(): bool {
				return true;
			}

			// Expose protected method for testing.
			public function public_has_upsell_opportunity(): bool {
				return $this->has_upsell_opportunity();
			}
		};

		// Mock no products.
		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			[]
		);

		$result = $ignore_checks_class->public_has_upsell_opportunity();

		$this->assertTrue( $result, 'Should return true when ignoring plugin checks even with no products' );
	}

	/**
	 * Test that should_ignore_plugin_checks defaults to false.
	 *
	 * @test
	 */
	public function should_default_to_checking_plugins() {
		// Mock all paid plugins as installed.
		$products = [
			'events-calendar-pro' => [
				'title'        => 'Events Calendar Pro',
				'slug'         => 'events-calendar-pro',
				'is_installed' => true,
				'free'         => false,
			],
		];

		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			$products
		);

		// Default test class should check plugins (return false when all installed).
		$result = $this->test_class->public_has_upsell_opportunity();

		$this->assertFalse( $result, 'Should check plugins by default when should_ignore_plugin_checks returns false' );
	}
}
