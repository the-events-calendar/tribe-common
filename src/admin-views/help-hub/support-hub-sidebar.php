<?php
/**
 * The template that displays the support hub sidebar.
 *
 * @var Tribe__Template $this             The template object.
 * @var bool            $is_opted_in      Whether the user has opted in to telemetry.
 * @var bool            $is_license_valid Whether the user has any valid licenses.
 */

if ( $is_license_valid ) {
	if ( $is_opted_in ) {
		$this->template( 'help-hub/support-hub-sidebar-has-license-has-consent' );
	} else {
		$this->template( 'help-hub/shared-sidebar-has-license-no-consent' );
	}
} else {
	$this->template( 'help-hub/support-hub-sidebar-no-license' );
}
