<?php
/**
 * Interface Custom_Table_Repository_Interface
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\Contracts;

/**
 * Interface Custom_Table_Repository_Interface
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts
 */
interface Custom_Table_Repository_Interface extends Repository_Interface {
	/**
	 * Gets the model class.
	 *
	 * @since TBD
	 *
	 * @return class-string<Model> The model class.
	 */
	public function get_model_class(): string;

	/**
	 * Gets the schema.
	 *
	 * @since TBD
	 *
	 * @return array The schema.
	 */
	public function get_schema(): array;

	/**
	 * Sets whether to use the default arguments.
	 *
	 * @since TBD
	 *
	 * @param bool $use_default_args Whether to use the default arguments.
	 *
	 * @return self The repository instance.
	 */
	public function set_use_default_args( bool $use_default_args ): self;
}
