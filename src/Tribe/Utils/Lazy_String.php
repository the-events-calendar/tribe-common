<?php
/**
 * A string lazily built, suited to any string value that might be costly to be built.
 *
 * @since 4.9.16
 *
 * @package Tribe\Utils
 */


namespace Tribe\Utils;

/**
 * Class Lazy_String.
 *
 * @since 4.9.16
 *
 * @package Tribe\Utils
 */
class Lazy_String implements \JsonSerializable {
	use Lazy_Events;

	/**
	 * The string value produced by the callback, cached.
	 *
	 * @since 4.9.16
	 *
	 * @var string
	 */
	protected $string;

	/**
	 * The callback that will be used to set the string value when called the first time.
	 *
	 * @since 4.9.16
	 *
	 * @var callable
	 */
	protected $value_callback;

	/**
	 * The callback that will be used to escape the string in the `escaped()` method..
	 *
	 * @since 4.9.16
	 *
	 * @var callable
	 */
	protected $escape_callback;

	/**
	 * The escaped string value.
	 *
	 * @since 4.9.16
	 *
	 * @var string
	 */
	protected $escaped;

	/**
	 * Lazy_String constructor.
	 *
	 * @param callable     $callback        The callback that will be used to populate the string on the first fetch.
	 * @param string|false $escape_callback The callback that will be used to escape the string in the `escaped`
	 *                                      method.
	 */
	public function __construct( callable $callback, $escape_callback = 'esc_html' ) {
		$this->value_callback  = $callback;
		$this->escape_callback = $escape_callback;
	}

	/**
	 * Inits, and returns, the string value of the string.
	 *
	 * @since 4.9.16
	 *
	 * @return string The unescaped string value.
	 */
	public function __toString() {
		if ( null === $this->string ) {
			$value =  call_user_func( $this->value_callback );
			if ( ! $value instanceof \Generator ) {
				$value = (string) $value;
			}

			$this->string = $value;
			$this->resolved();
		}

		return $this->string;
	}

	/**
	 * Returns the HTML ready, escaped version of the string.
	 *
	 * @since 4.9.16
	 *
	 * @return string The escaped version of the string.
	 */
	public function escaped() {
		if ( null !== $this->escaped ) {
			return $this->escaped;
		}

		$this->escaped = empty( $this->escape_callback )
			? $this->__toString()
			: call_user_func( $this->escape_callback, $this->__toString() );

		return $this->escaped;
	}

	/**
	 * Returns the string value, just a proxy of the `__toString` method.
	 *
	 * @since 4.9.16
	 *
	 * @return string The string value.
	 */
	public function value() {
		return $this->__toString();
	}

	/**
	 * Legacy serialization method kept for backward compatibility.
	 *
	 * @since 4.9.16
	 * @deprecated 6.12.0 Use __serialize() instead.
	 *
	 * @return string The serialized representation of the object.
	 */
	public function serialize() {
		_deprecated_function( __METHOD__, '6.12.0', '__serialize()' );

		return maybe_serialize( $this->__serialize() );
	}

	/**
	 * Legacy unserialization method kept for backward compatibility.
	 *
	 * @since 4.9.16
	 * @deprecated 6.12.0 Use __unserialize() instead.
	 *
	 * @param string $serialized The serialized data.
	 *
	 * @return void
	 */
	public function unserialize( $serialized ) {
		_deprecated_function( __METHOD__, '6.12.0', '__unserialize()' );

		$data = maybe_unserialize( $serialized );
		$this->__unserialize( $data );
	}

	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->value();
	}

	/**
	 * PHP 8.0+ compatible implementation of the serialization logic.
	 *
	 * @since 5.0.6
	 *
	 * @return array The data to serialize.
	 */
	public function __serialize(): array {
		return [
			'string'  => $this->__toString(),
			'escaped' => $this->escaped(),
		];
	}

	/**
	 * PHP 8.0+ compatible implementation of the unserialization logic.
	 *
	 * @since 5.0.6
	 *
	 * @param array $data The data to unserialize.
	 */
	public function __unserialize( array $data ): void {
		$this->string  = $data['string'] ?? null;
		$this->escaped = $data['escaped'] ?? null;
	}
}
