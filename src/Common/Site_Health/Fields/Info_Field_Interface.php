<?php

namespace TEC\Common\Site_Health\Fields;

/**
 * Interface for Site Health Info Fields.
 *
 * @since TBD
 *
 * @package TEC\Common\Site_Health\Fields
 */
interface Info_Field_Interface {

	/**
	 * Configure all the params for a generic field.
	 *
	 * @param string                           $id
	 * @param string                           $label
	 * @param array<string,string>|string|null $value
	 * @param int                              $priority
	 */
    public function __construct( string $id, string $label, $value = null, int $priority = 50 );
}