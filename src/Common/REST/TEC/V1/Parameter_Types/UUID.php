<?php
/**
 * UUID parameter type.
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
 * UUID parameter type.
 *
 * @since 6.9.0
 */
class UUID extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'uuid';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		return $this->validator ?? function ( $value ): bool {
			if ( ! is_string( $value ) || ! preg_match( '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', (string) $value ) ) {
				// translators: 1) is the name of the parameter.
				$exception = new InvalidRestArgumentException( sprintf( __( 'Parameter `{%1$s}` must be a valid UUID.', 'tribe-common' ), $this->get_name() ) );
				$exception->set_argument( $this->get_name() );
				$exception->set_internal_error_code( 'tec_rest_invalid_uuid_parameter' );

				// translators: 1) is the name of the parameter.
				$exception->set_details( sprintf( __( 'The parameter `{%1$s}` is not a valid UUID. It should be a string like `123e4567-e89b-12d3-a456-426614174000`.', 'tribe-common' ), $this->get_name() ) );
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
			'format' => 'uuid',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return $this->example ?? '123e4567-e89b-12d3-a456-426614174000';
	}
}
