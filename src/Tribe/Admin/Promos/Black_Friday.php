<?php
namespace Tribe\Admin\Promos;

/**
 * Set up for Black Friday promo.
 *
 * @since TBD
 */
class Black_Friday extends Promo {
	/**
	 * Promo slug.
	 */
	protected $slug = 'black_friday';

	/**
	 * Start date.
	 */
	protected $start_date = 'November 22nd';

	/**
	 * End Date.
	 */
	protected $end_date = 'November 30th';

	/**
	 * Register actions and filters.
	 *
	 * @since TBD
	 * @return void
	 */
	public function hook() {
		add_action( 'tribe_general_settings_tab_fields', [ $this, 'add_promo_markup' ] );
	}

	/**
	 * Replace the opening markup for the general settings info box.
	 *
	 * @since TBD
	 * @return void
	 */
	public function add_promo_markup( $fields ) {
		// Check if the promo should currently be displayed.
		if( ! $this->should_display() ) {
			return $fields;
		}

		// Get our promo template and store in variable.
		ob_start();
		include \Tribe__Main::instance()->plugin_path . 'src/admin-views/promos/black-friday.php';
		$promo = ob_get_clean();

		// Replace starting info box markup.
		$fields['info-start']['html'] = '<div id="modern-tribe-info">' . $promo;

		return $fields;
	}
}
