<?php
/**
 * A dummy implementation of the process handler.
 *
 * @package Tribe\Common\Tests
 */

namespace Tribe\Common\Tests;

/**
 * Class Dummy_Process_Handler
 *
 * @package Tribe\Common\Tests
 */
class Dummy_Process_Handler extends \Tribe__Process__Handler {

	/**
	 * An injectable handle callback that will be fired on calls to the `handle` method
	 * on the object.
	 *
	 * @var callable
	 */
	public $handle_callback;

	/**
	 * {@inheritdoc}
	 */
	public static function action() {
		return 'dummy_handler';
	}

	/**
	 * Handles the process immediately, not in an async manner.
	 *
	 * @since 4.7.12
	 *
	 * @param array|null $data_source If not provided the method will read the handler data from the
	 *                                request array.
	 *
	 * @return mixed
	 */
	public function sync_handle( array $data_source = null ) {
	}

	/**
	 * Sets the feature detection abstraction object for the class.
	 *
	 * @param \Tribe__Feature_Detection $feature_detection The feature detection abstraction
	 *                                                     object.
	 */
	public function set_feature_detection( \Tribe__Feature_Detection $feature_detection ) {
		$this->feature_detection = $feature_detection;
	}

	/**
	 * Handle
	 *
	 * Override this method to perform any actions required
	 * during the async request.
	 *
	 * @param array|null $data_source Unused.
	 */
	protected function handle( array $data_source = null ) {
		\call_user_func($this->handle_callback);
	}
}
