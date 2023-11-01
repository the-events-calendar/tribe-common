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
    public function __construct( string $id, string $label, $value, int $priority );
}