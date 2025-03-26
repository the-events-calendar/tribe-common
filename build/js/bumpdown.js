!function(t,e){"use strict";t((function(){t(".tribe-bumpdown-trigger").bumpdown()})),t.fn.bumpdown=function(){var n=t(document),r={ID:"tribe-bumpdown-",data_trigger:function(t){return'[data-trigger="'+t+'"]'},bumpdown:".tribe-bumpdown",content:".tribe-bumpdown-content",trigger:".tribe-bumpdown-trigger",hover_trigger:".tribe-bumpdown-trigger:not(.tribe-bumpdown-nohover)",close:".tribe-bumpdown-close",permanent:".tribe-bumpdown-permanent",active:".tribe-bumpdown-active"},o={open:function(e){var n=e.data("bumpdown"),i=n.$trigger.data("width-rule");if(!e.is(":visible")){n.$trigger.addClass(r.active.replace(".",""));var a=e.find(r.content);if("string"==typeof i&&"all-triggers"===i){var d=0;t(r.trigger).each((function(){var e=t(this);if(e.data("width-rule")){var n=e.position();n.left>d&&(d=n.left)}})),d&&(d=d>600?d:600,a.css("max-width",d+"px"))}a.prepend('<a class="tribe-bumpdown-close" title="Close"><i class="dashicons dashicons-no"></i></a>'),a.prepend('<span class="tribe-bumpdown-arrow"></span>'),o.arrow(e),e.data("preventClose",!0),e.slideDown("fast",(function(){e.data("preventClose",!1)}))}},close:function(e){var n=e.data("bumpdown");e.is(":visible")&&!e.data("preventClose")&&(t(this).removeData("is_hoverintent_queued"),e.find(".tribe-bumpdown-close, .tribe-bumpdown-arrow").remove(),e.not(".tribe-bumpdown-trigger").slideUp("fast"),n.$trigger.removeClass(r.active.replace(".","")))},arrow:function(t){var e,n=t.data("bumpdown");e=Math.ceil(n.$trigger.position().left-("block"===n.type?n.$parent.offset().left:0)),n.$bumpdown.find(".tribe-bumpdown-arrow").css("left",e)}};return t(window).on({"resize.bumpdown":function(){n.find(r.active).each((function(){o.arrow(t(this))}))}}),"function"==typeof t.fn.hoverIntent&&n.hoverIntent({over:function(){var e=t(this).data("bumpdown");e.$trigger.data("is_hoverintent_queued",!1),e.$bumpdown.trigger("open.bumpdown")},out:function(){},selector:r.hover_trigger,interval:200}),n.on({mouseenter:function(){void 0===t(this).data("is_hoverintent_queued")&&t(this).data("is_hoverintent_queued",!0)},click:function(e){var n=t(this).data("bumpdown");if(e.preventDefault(),e.stopPropagation(),n.$bumpdown.is(":visible")){if(n.$trigger.data("is_hoverintent_queued"))return n.$trigger.data("is_hoverintent_queued",!1);n.$bumpdown.trigger("close.bumpdown")}else n.$bumpdown.trigger("open.bumpdown")},"open.bumpdown":function(){o.open(t(this))},"close.bumpdown":function(){o.close(t(this))}},r.trigger).on({click:function(e){var n=t(this).parents(r.bumpdown).first().data("bumpdown");e.preventDefault(),e.stopPropagation(),void 0!==n&&void 0!==n.$bumpdown&&n.$bumpdown.trigger("close.bumpdown")}},r.close).on("click",(function(e){var n=t(e.target);n.is(r.bumpdown)||0!==n.parents(r.bumpdown).length||t(r.trigger).not(r.permanent).trigger("close.bumpdown")})).on({"open.bumpdown":function(){o.open(t(this))},"close.bumpdown":function(){o.close(t(this))}},r.bumpdown),this.each((function(){var n={$trigger:t(this),$parent:null,$bumpdown:null,ID:null,html:null,type:"block",is_permanent:!1};if(n.ID=n.$trigger.attr("id"),n.ID||(n.ID=e.uniqueId(r.ID),n.$trigger.attr("id",n.ID)),n.html=n.$trigger.attr("data-bumpdown"),n.html='<div class="tribe-bumpdown-content">'+n.html+"</div>",n.class=n.$trigger.attr("data-bumpdown-class"),n.is_permanent=n.$trigger.is(r.permanent),n.$parent=n.$trigger.parents().filter((function(){return-1<t.inArray(t(this).css("display"),["block","table","table-cell","table-row"])})).first(),n.html)if(n.type=n.$parent.is("td, tr, td, table")?"table":"block","table"===n.type){n.$bumpdown=t("<td>").attr({colspan:2}).addClass("tribe-bumpdown-cell").html(n.html);var i=n.class?"tribe-bumpdown-row "+n.class:"tribe-bumpdown-row",a=t("<tr>").append(n.$bumpdown).addClass(i);n.$parent=n.$trigger.parents("tr").first(),n.$parent.after(a)}else n.$bumpdown=t("<div>").addClass("tribe-bumpdown-block").html(n.html),n.$trigger.after(n.$bumpdown);else n.$bumpdown=t(r.data_trigger(n.ID)),n.type="block";if(n.$trigger.data("bumpdown",n).addClass(r.trigger.replace(".","")),n.$bumpdown.data("bumpdown",n).addClass(r.bumpdown.replace(".","")),n.$trigger.data("depends")){var d=n.$trigger.data("depends");t(document).on("change",d,(function(){o.close(n.$bumpdown)}))}}))}}(jQuery,window.underscore||window._),window.tec=window.tec||{},window.tec.common=window.tec.common||{},window.tec.common.bumpdown={};