<?php
/**
 * TrustedLogin Authorization Template.
 *
 * This template renders the TrustedLogin authorization screen, including
 * the access details, grant/revoke actions, and optional terms of service
 * for the connected vendor.
 *
 * Variables provided:
 * - {{ns}}                  TrustedLogin namespace for scoping CSS/JS.
 * - {{has_access_class}}    CSS class indicating whether access has been granted.
 * - {{intro}}               Introductory message text.
 * - {{auth_header}}         Header section for authorization status.
 * - {{details}}             Role, capabilities, and access expiration details.
 * - {{notices}}             Notices for local environments or warnings.
 * - {{button}}              The Grant/Revoke access button HTML.
 * - {{terms_of_service}}    Optional Terms of Service text/link.
 * - {{secured_by_trustedlogin}} Branding and security badge output.
 * - {{footer}}              Footer section with links and debug info.
 * - {{reference}}           Reference ID for support tracking.
 * - {{admin_debug}}         Debug information if enabled.
 *
 * @since 6.9.5
 *
 * @see https://docs.trustedlogin.com/Client/customization
 *
 * @package TEC\Common\TrustedLogin
 */

?>

<div class="tl-{{ns}}-auth tl-{{ns}}-{{has_access_class}}">
	<section class="tl-{{ns}}-auth__body">
		<h2 class="tl-{{ns}}-auth__intro">{{intro}}</h2>
		<div class="tl-{{ns}}-auth__content">
			<header class="tl-{{ns}}-auth__header">
				{{auth_header}}
			</header>
			<div class="tl-{{ns}}-auth__details">
				{{details}}
			</div>
			<div class="tl-{{ns}}-auth__response" aria-live="assertive"></div>
			{{notices}}
			<div class="tl-{{ns}}-auth__actions">
				{{button}}
			</div>
			{{terms_of_service}}
		</div>
		<div class="tl-{{ns}}-auth__secured_by">{{secured_by_trustedlogin}}</div>
	</section>
	<footer class="tl-{{ns}}-auth__footer">
		{{footer}}
		{{reference}}
	</footer>
	{{admin_debug}}
</div>
