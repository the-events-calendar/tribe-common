<?php
/**
 * Tests for Promotional_Content_Abstract.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content;
 */

declare( strict_types=1 );

namespace TEC\Common\Tests\Admin\Conditional_Content;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Admin\Conditional_Content\Black_Friday;
use TEC\Common\Admin\Conditional_Content\Stellar_Sale;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__Plugins_API;

/**
 * Class Promotional_Content_Test
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content;
 */
class Promotional_Content_Test extends WPTestCase {

	use With_Uopz;

	/**
	 * @var Black_Friday
	 */
	protected $black_friday;

	/**
	 * @var Stellar_Sale
	 */
	protected $stellar_sale;

	/**
	 * @var array
	 */
	protected array $original_meta;

	/**
	 * Set up test environment.
	 *
	 * @before
	 */
	public function set_up(): void {
		parent::set_up();
		$this->black_friday = tribe( Black_Friday::class );
		$this->stellar_sale = tribe( Stellar_Sale::class );

		// Clear any dismissed notices - delete all instances.
		$user_id = get_current_user_id();
		if ( $user_id > 0 ) {
			$this->original_meta = get_metadata( 'user', $user_id, 'tec-dismissible-content' );
			delete_metadata( 'user', $user_id, 'tec-dismissible-content' );
		}
	}

	/**
	 * Tear down test environment.
	 *
	 * @after
	 */
	public function tear_down(): void {
		// Clean up - delete all instances.
		$user_id = get_current_user_id();
		if ( $user_id > 0 ) {
			delete_metadata( 'user', $user_id, 'tec-dismissible-content' );
		}

		remove_all_filters( 'tec_should_hide_upsell' );
		remove_all_filters( 'tec_admin_conditional_content_black-friday_should_display' );
		remove_all_filters( 'tec_admin_conditional_content_stellar-sale_should_display' );
	}

	/**
	 * Test that should_display returns false when there is no upsell opportunity.
	 *
	 * @test
	 */
	public function should_not_display_when_no_upsell_opportunity() {
		// Mock all paid plugins as installed.
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

		// Force the promo to be in date range.
		add_filter(
			'tec_admin_conditional_content_black-friday_datetime_should_display',
			function ( $should_display ) {
				// We want to isolate testing the upsell opportunity check, so return true for date range.
				return $should_display;
			}
		);

		$reflection = new \ReflectionClass( $this->black_friday );
		$method     = $reflection->getMethod( 'should_display' );
		$method->setAccessible( true );

		$result = $method->invoke( $this->black_friday );

		$this->assertFalse( $result, 'Should not display when there is no upsell opportunity (all paid plugins installed)' );
	}

	/**
	 * Test that should_display returns true when there is an upsell opportunity.
	 *
	 * @test
	 */
	public function should_display_when_upsell_opportunity_exists() {
		// Ensure we have an admin user.
		$user_id = get_current_user_id();
		if ( $user_id === 0 || ! current_user_can( 'manage_options' ) ) {
			$user_id = $this->factory->user->create( [ 'role' => 'administrator' ] );
			wp_set_current_user( $user_id );
		}

		// Mock with some paid plugins not installed.
		$products = [
			'the-events-calendar' => [
				'title'        => 'The Events Calendar',
				'slug'         => 'the-events-calendar',
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
			'tribe-filterbar'     => [
				'title'        => 'Filter Bar',
				'slug'         => 'tribe-filterbar',
				'is_installed' => false,
				'free'         => false,
			],
		];

		$this->set_class_fn_return(
			Tribe__Plugins_API::class,
			'get_products',
			$products
		);

		// Force the promo to be in date range by using the filter.
		add_filter(
			'tec_admin_conditional_content_black-friday_datetime_should_display',
			'__return_true'
		);

		$reflection = new \ReflectionClass( $this->black_friday );
		$method     = $reflection->getMethod( 'should_display' );
		$method->setAccessible( true );

		$result = $method->invoke( $this->black_friday );

		$this->assertTrue( $result, 'Should display when there is an upsell opportunity' );
	}

	/**
	 * Test that tec_should_hide_upsell filter hides the display.
	 *
	 * @test
	 */
	public function should_not_display_when_upsell_hidden_by_filter() {
		// Mock with paid plugins not installed.
		$products = [
			'the-events-calendar' => [
				'title'        => 'The Events Calendar',
				'slug'         => 'the-events-calendar',
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

		// Force the promo to be in date range.
		add_filter(
			'tec_admin_conditional_content_black-friday_datetime_should_display',
			function () {
				return true;
			}
		);

		// Add filter to hide upsell.
		add_filter(
			'tec_should_hide_upsell',
			function ( $hide, $slug ) {
				return $slug === 'black-friday-' . date_i18n( 'Y' );
			},
			10,
			2
		);

		// Check should_display.
		$reflection = new \ReflectionClass( $this->black_friday );
		$method     = $reflection->getMethod( 'should_display' );
		$method->setAccessible( true );

		$result = $method->invoke( $this->black_friday );

		$this->assertFalse( $result, 'Should not display when tec_should_hide_upsell filter returns true' );
	}
}
