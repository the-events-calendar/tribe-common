<?php
/**
 * IP parameter type.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use Closure;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;

/**
 * IP parameter type.
 *
 * @since 6.9.0
 */
class IP extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'ip';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		return $this->validator ?? function ( $value ): bool {
			if ( ! filter_var( $value, FILTER_VALIDATE_IP ) ) {
				// translators: 1) is the name of the parameter.
				$exception = new InvalidRestArgumentException( sprintf( __( 'Parameter `{%1$s}` must be a valid IP address.', 'tribe-common' ), $this->get_name() ) );
				$exception->set_argument( $this->get_name() );
				$exception->set_internal_error_code( 'tec_rest_invalid_ip_parameter' );

				// translators: 1) is the name of the parameter.
				$exception->set_details( sprintf( __( 'The parameter `{%1$s}` is not a valid IP address.', 'tribe-common' ), $this->get_name() ) );
				throw $exception;
			}

			return true;
		};
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'ip',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return $this->example ?? '127.0.0.1';
	}
}
