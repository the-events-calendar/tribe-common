<?php
/**
 * Definition abstract class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Abstracts;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;
use RuntimeException;

/**
 * Definition abstract class.
 *
 * @since TBD
 */
abstract class Definition implements Definition_Interface {

	/**
	 * Returns the example of the definition.
	 *
	 * @since TBD
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
	 * @since TBD
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
				$class = $this->get_class_from_ref( $doc['$ref'] );

				if ( ! $class ) {
					throw new RuntimeException( 'Definition class not found for ' . $doc['$ref'] );
				}

				$examples = array_merge( $examples, $class->get_example() );
				continue;
			}

			if ( isset( $doc['properties'] ) ) {
				foreach ( $doc['properties'] as $property => $data ) {
					if ( ! isset( $data['example'] ) ) {
						continue;
					}

					$examples[ $property ] = $data['example'];
				}

				continue;
			}

			throw new RuntimeException( 'Invalid definition.' );
		}

		return $examples;
	}

	/**
	 * Returns the class from a ref.
	 *
	 * @since TBD
	 *
	 * @param string $ref The ref.
	 *
	 * @return ?Definition_Interface
	 */
	private function get_class_from_ref( string $ref ): ?Definition_Interface {
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
			if ( class_exists( $class ) ) {
				return new $class();
			}
		}

		return null;
	}
}
