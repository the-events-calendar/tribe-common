<?php
/**
 * The template that displays the resources sidebar.
 *
 * @var Tribe__Template $this  The template object.
 * @var bool $is_opted_in      Whether the user has opted in to telemetry.
 * @var bool $is_license_valid Whether the user has any valid licenses.
 */

if ( ! $is_license_valid ) {
	$this->template( 'help-hub/resources-sidebar-no-license' );
} elseif ( $is_opted_in ) {
	$this->template( 'help-hub/resources-sidebar-has-license-has-consent' );
} else {
	$this->template( 'help-hub/resources-sidebar-has-license-no-consent' );
}
