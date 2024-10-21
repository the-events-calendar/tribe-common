<?php
/**
 * The template that displays the support hub sidebar.
 *
 * @var Tribe__Template $this    The template object.
 * @var bool $is_opted_in        Whether the user has opted in to telemetry.
 * @var bool $is_license_valid   Whether the user has any valid licenses.
 * @var string $zendesk_chat_key The zendesk chat ID.
 */

?>
<div id="docsbot-widget-embed" style="height: 600px; max-height:100vh;"></div>
<!-- Start of Zendesk Widget script -->
<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=<?php echo urlencode( $zendesk_chat_key ); ?>"></script>
<!-- End of Zendesk Widget script -->
<script type="text/javascript">window.DocsBotAI=window.DocsBotAI||{},DocsBotAI.init=function(c){return new Promise(function(e,o){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://widget.docsbot.ai/chat.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n),t.addEventListener("load",function(){window.DocsBotAI.mount({id:c.id,supportCallback:c.supportCallback,identify:c.identify,options:c.options,signature:c.signature});var t;t=function(n){return new Promise(function(e){if(document.querySelector(n))return e(document.querySelector(n));var o=new MutationObserver(function(t){document.querySelector(n)&&(e(document.querySelector(n)),o.disconnect())});o.observe(document.body,{childList:!0,subtree:!0})})},t&&t("#docsbotai-root").then(e).catch(o)}),t.addEventListener("error",function(t){o(t.message)})})};</script>
