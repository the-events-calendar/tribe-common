<?php
/**
 * The Harbor integration controller.
 *
 * @since TBD
 *
 * @package TEC\Common\Integrations\Harbor
 */
namespace TEC\Common\Integrations\Harbor;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Libraries\Harbor;
use TEC\Common\Contracts\Container;

/**
 * The Harbor integration controller.
 *
 * @since TBD
 *
 * @package TEC\Common\Integrations\Harbor
 */
abstract class Integration_Controller extends Controller_Contract {
	/**
	 * The Harbor instance.
	 *
	 * @since TBD
	 *
	 * @var Harbor
	 */
	protected Harbor $harbor;

	/**
	 * The constructor.
	 *
	 * @since TBD
	 *
	 * @param Container $container The container.
	 * @param Harbor   $harbor     The Harbor instance.
	 */
	public function __construct( Container $container, Harbor $harbor ) {
		parent::__construct( $container );
		$this->harbor = $harbor;
	}
}
