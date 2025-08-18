<?php
/**
 * Definition abstract class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Abstracts;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;
use TEC\Common\REST\TEC\V1\Collections\PropertiesCollection;
use TEC\Common\REST\TEC\V1\Contracts\Parameter_Interface as Parameter;
use RuntimeException;

/**
 * Definition abstract class.
 *
 * @since 6.9.0
 */
abstract class Definition implements Definition_Interface {

	/**
	 * Returns the example of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 *
	 * @throws RuntimeException If the definition is invalid.
	 */
	public function get_example(): array {
		$documentation = $this->get_documentation();

		$docs = empty( $documentation['allOf'] ) ? [ $documentation ] : $documentation['allOf'];

		return $this->get_examples_from_docs( $docs );
	}

	/**
	 * Returns the examples from the docs.
	 *
	 * @since 6.9.0
	 *
	 * @param array $docs The docs.
	 *
	 * @return array
	 *
	 * @throws RuntimeException If the definition is invalid.
	 */
	private function get_examples_from_docs( array $docs ): array {
		$examples = [];

		foreach ( $docs as $doc ) {
			if ( isset( $doc['$ref'] ) ) {
				$class = static::get_instance_from_ref( $doc['$ref'] );

				if ( ! $class ) {
					throw new RuntimeException( 'Definition class not found for ' . $doc['$ref'] );
				}

				$examples = array_merge( $examples, $class->get_example() );
				continue;
			}

			if ( isset( $doc['properties'] ) && $doc['properties'] instanceof PropertiesCollection ) {
				/** @var Parameter $collection */
				foreach ( $doc['properties'] as $param ) {
					$examples[ $param->get_name() ] = $param->get_example();
				}
			}
		}

		return $examples;
	}

	/**
	 * Returns the class from a ref.
	 *
	 * @since 6.9.0
	 *
	 * @param string $ref The ref.
	 *
	 * @return ?Definition_Interface
	 */
	public static function get_instance_from_ref( string $ref ): ?Definition_Interface {
		$ref = str_replace( '#/components/schemas/', '', $ref );

		$possible_classes = [
			"TEC\\Common\\REST\\TEC\\V1\\Documentation\\{$ref}",
			"TEC\\Common\\REST\\TEC\\V1\\Documentation\\{$ref}_Definition",
			"TEC\\Events\\REST\\TEC\\V1\\Documentation\\{$ref}",
			"TEC\\Events\\REST\\TEC\\V1\\Documentation\\{$ref}_Definition",
			"TEC\\Tickets\\REST\\TEC\\V1\\Documentation\\{$ref}",
			"TEC\\Tickets\\REST\\TEC\\V1\\Documentation\\{$ref}_Definition",
			"TEC\\Tickets_Plus\\REST\\TEC\\V1\\Documentation\\{$ref}",
			"TEC\\Tickets_Plus\\REST\\TEC\\V1\\Documentation\\{$ref}_Definition",
			"TEC\\Events_Pro\\REST\\TEC\\V1\\Documentation\\{$ref}",
			"TEC\\Events_Pro\\REST\\TEC\\V1\\Documentation\\{$ref}_Definition",
		];

		foreach ( $possible_classes as $class ) {
			if ( ! class_exists( $class ) ) {
				continue;
			}

			return new $class();
		}

		return null;
	}
}
