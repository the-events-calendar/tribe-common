<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

abstract class Tribe__Admin__Notice__Abstract {

	/**
	 * Holds which slugs are registred
	 *
	 * @var array
	 */
	public static $slugs = array();

	/**
	 * User Meta Key that stores which notices have been dimissed
	 *
	 * @var string
	 */
	public static $meta_key = 'tribe-dismiss-notice';

	/**
	 * Holder of the Instances
	 *
	 * @var array
	 */
	private static $instances = array();

	/**
	 * The class singleton constructor.
	 *
	 * @return self
	 */
	public static function instances( $name = null ) {
		if ( empty( self::$instances[ $name ] ) ) {
			self::$instances[ $name ] = new $name;
		}

		return self::$instances[ $name ];
	}

	/**
	 * Constructor of the Class:
	 *
	 * Adds the Slug of the current child to the abstract static variable
	 * Hooks the required methods to the correct actions
	 *
	 * @return void
	 */
	public function __construct() {
		// Add the current Slug to the Possible Ones
		self::$slugs[] = $this->get_slug();

		// Not in the admin we don't even care
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'wp_ajax_tribe_notice_dismiss', array( $this, 'maybe_dismiss' ) );

		// Doing AJAX? bail.
		if ( Tribe__Main::instance()->doing_ajax() ) {
			return;
		}

		// Hooks the Dismissal of the Notice
		add_action( 'admin_init', array( $this, 'maybe_dismiss' ) );
		add_action( 'admin_notices', array( $this, 'maybe_notice' ) );
	}

	/**
	 * This needs to be hooked to `admin_init`
	 *
	 * @return void
	 */
	public function maybe_dismiss() {
		if ( empty( $_GET[ self::$meta_key ] ) ) {
			return;
		}

		$notice_slug = esc_attr( $_GET[ self::$meta_key ] );

		// We don't care if the slug is not the one we configured
		if ( $this->get_slug() !== $notice_slug ) {
			return;
		}

		// We also don't care about it when it's not registred
		if ( ! in_array( $notice_slug, self::$slugs ) ) {
			return;
		}

		$status = $this->dismiss();

		// If it's AJAX return 1 or 0
		if ( Tribe__Main::instance()->doing_ajax() ) {
			echo json_encode( (bool) $status );
			exit;
		}

		if ( $status ) {
			// After adding the meta we remove notice action
			remove_action( 'admin_notices', array( $this, 'notice' ) );
		}
	}

	/**
	 * A Method to actually add the Meta value telling that this notice has been dismissed
	 *
	 * @return boolean
	 */
	public function dismiss( $user_id = null ) {
		if ( is_null( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		// If this user has dimissed we don't care either
		if ( $this->has_user_dimissed( $user_id ) ) {
			return true;
		}

		return add_user_meta( $user_id, self::$meta_key, $this->get_slug(), false );
	}

	/**
	 * Checks if a given user has dimissed this notice.
	 *
	 * @param  int|null  $user_id The user ID
	 * @return boolean
	 */
	public function has_user_dimissed( $user_id = null ) {
		if ( is_null( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$dismissed_notices = get_user_meta( $user_id, self::$meta_key );

		if ( ! is_array( $dismissed_notices ) ) {
			return false;
		}

		if ( ! in_array( $this->get_slug(), $dismissed_notices ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Method hooked to `admin_notices` to display notices depending on the is_visible() conditional
	 *
	 * @return void
	 */
	public function maybe_notice() {
		// Check if the User has Dimissed this Notice and if the visible conditional is true
		if ( ! $this->is_visible() ){
			return;
		}

		$this->notice();
	}

	/**
	 * Method returning a boolean to determine if the notice is visible
	 *
	 * @return boolean
	 */
	public function is_visible() {
		if ( $this->has_user_dimissed() ) {
			return false;
		}

		return true;
	}

	/**
	 * On PHP 5.2 the child class doesn't get spawned on the Parent one, so we don't have
	 * access to that information on the other side unless we pass it around as a param
	 * so we throw __CLASS__ to the parent::instance() method to be able to spawn new instance
	 * of this class and save on the parent::$instances variable.
	 *
	 * @return self::_instance( __CLASS__ )
	 */
	abstract public static function instance();

	/**
	 * Abstract method to get the Slug of this Notice
	 *
	 * @return string
	 */
	abstract public function get_slug();

	/**
	 * Abstract method to display the Notice
	 *
	 * @return void
	 */
	abstract public function notice();
}
