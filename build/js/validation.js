tribe.validation={},dayjs.extend(window.dayjs_plugin_customParseFormat),function(t,e,i){"use strict";var a=e(document);t.selectors={item:".tribe-validation",fields:"input, select, textarea",submit:".tribe-validation-submit",submitButtons:'input[type="submit"], button[type="submit"]',error:".tribe-validation-error",valid:".tribe-validation-valid",notice:".tribe-notice-validation",noticeAfter:".wp-header-end",noticeFallback:".wrap > h1",noticeDismiss:".notice-dismiss"},t.conditions={isRequired:function(t){return""!=t},isGreaterThan:function(e,i,a){var n=t.parseCondition("isGreaterThan",e,i,a);return!1===n||n.constraint<n.value},isGreaterOrEqualTo:function(e,i,a){var n=t.parseCondition("isGreaterOrEqualTo",e,i,a);return!1===n||n.constraint<=n.value},isLessThan:function(e,i,a){var n=t.parseCondition("isLessThan",e,i,a);return!1===n||n.constraint>n.value},isLessOrEqualTo:function(e,i,a){var n=t.parseCondition("isLessOrEqualTo",e,i,a);return!1===n||n.constraint>=n.value},isEqualTo:function(e,i,a){var n=t.parseCondition("isEqualTo",e,i,a);return!1===n||n.constraint==n.value},isNotEqualTo:function(e,i,a){var n=t.parseCondition("isNotEqualTo",e,i,a);return!1===n||n.constraint!=n.value},matchRegExp:function(t,e,i){return null!==new RegExp(e,"g").exec(t)},notMatchRegExp:function(t,e,i){return null===new RegExp(e,"g").exec(t)}},t.parseType={datepicker:function(t,e,a){var n=["yyyy-mm-dd","m/d/yyyy","mm/dd/yyyy","d/m/yyyy","dd/mm/yyyy","m-d-yyyy","mm-dd-yyyy","d-m-yyyy","dd-mm-yyyy","yyyy.mm.dd","mm.dd.yyyy","dd.mm.yyyy"],r=0;e.length&&e.attr("data-datepicker_format")?r=e.attr("data-datepicker_format"):i.isString(n[e])?r=n[e]:e.parents("[data-datepicker_format]").length&&(r=e.parents("[data-datepicker_format]").eq(0).data("datepicker_format")),void 0!==n[r]&&n[r]||(r=0);var s=n[r].toUpperCase();return dayjs(t,s).valueOf()},default:function(t,i,a){return e.isNumeric(t)&&(t=parseFloat(t,10)),t}},t.parseCondition=function(a,n,r,s){var o=s.data("validationType"),l=null,d={value:n,constraint:r};if(o||i.isFunction(t.parseType[o])||(o="default"),!e.isNumeric(r)){if(!(l=e(r)).length)return console.warn("Tribe Validation:","Invalid selector for",s,r),!1;if(!(l=l.not(":disabled")).length)return!1;r=l.val()}return d.constraint=t.parseType[o](r,l,s),d.value=t.parseType[o](n,l,s),d},t.constraints={isRequired:function(t){var e=null;return e=t.data("required")||e,e=t.data("validationRequired")||e,e=t.data("validationIsRequired")||e,e=t.is("[required]")||e,e=t.is("[data-required]")||e,e=t.is("[data-validation-required]")||e,t.is("[data-validation-is-required]")||e},isGreaterThan:function(t){var e=null;return t.is("[data-validation-is-greater-than]")&&(e=t.data("validationIsGreaterThan")),e},isGreaterOrEqualTo:function(t){var e=null;return t.is("[data-validation-is-greater-or-equal-to]")&&(e=t.data("validationIsGreaterOrEqualTo")),e},isLessThan:function(t){var e=null;return t.is("[data-validation-is-less-than]")&&(e=t.data("validationIsLessThan")),e},isLessOrEqualTo:function(t){var e=null;return t.is("[data-validation-is-less-or-equal-to]")&&(e=t.data("validationIsLessOrEqualTo")),e},isEqualTo:function(t){var e=null;return t.is("[data-validation-is-equal-to]")&&(e=t.data("validationIsEqualTo")),e},isNotEqualTo:function(t){var e=null;return t.is("[data-validation-is-not-equal-to]")&&(e=t.data("validationIsNotEqualTo")),e},matchRegExp:function(t){var e=null;return t.is("[data-validation-match-regexp]")&&(e=t.data("validationMatchRegexp")),e},notMatchRegExp:function(t){var e=null;return t.is("[data-validation-not-match-regexp]")&&(e=t.data("validationNotMatchRegexp")),e}},t.fn=function(){return this.each(t.setup)},t.setup=function(i,n){var r=e(n);r.addClass(t.selectors.item.className()),r.find(t.selectors.submitButtons).addClass(t.selectors.submit.className()),r.on("submit.tribe",t.onSubmit),r.on("validation.tribe",t.onValidation),r.on("displayErrors.tribe",t.onDisplayErrors),a.on("click.tribe",t.selectors.submit,t.onClickSubmitButtons),a.on("click.tribe",t.selectors.noticeDismiss,t.onClickDismissNotice)},t.validate=function(i,a){var n=e(a);t.isValid(n)||(n.addClass(t.selectors.error.className()),n.one("change",t.onChangeFieldRemoveError))},t.isValid=function(e){var a=t.getConstraints(e);return i.isObject(a)?i.every(a):a},t.hasErrors=function(e){return 0!==e.find(t.selectors.error).not(":disabled").length},t.getConstraints=function(e){var a=!0;if(e.is(":disabled"))return a;var n=t.getConstraintsValue(e),r=e.val();return i.isEmpty(n)?a:n=i.mapObject(n,(function(i,a){return t.conditions[a](r,i,e)}))},t.getConstraintsValue=function(e){var a={};return e.is(":disabled")?a:(a=t.constraints,a=i.mapObject(a,(function(t){return t(e)})),a=i.pick(a,(function(t){return null!==t})))},t.getConstraintsFields=function(a){var n=t.getConstraintsValue(a);return n=i.mapObject(n,(function(t){var a=null;return i.isNumber(t)||i.isBoolean(t)||(a=e(t)),a})),n=i.pick(n,(function(t){return t instanceof jQuery})),(n=i.values(n)).unshift(a),e(n).map((function(){return this.get()}))},t.onValidation=function(i){var a=e(this),n=a.find(t.selectors.fields);n.removeClass(t.selectors.error.className()),n.each(t.validate),0===a.find(t.selectors.error).not(":disabled").length?a.addClass(t.selectors.valid.className()):a.trigger("displayErrors.tribe")},t.onDisplayErrors=function(n){var r=e(this).find(t.selectors.error).not(":disabled"),s=e("<ul>"),o=e("<span>").addClass(t.selectors.noticeDismiss.className()),l=a.find(t.selectors.notice),d=e("<div>").addClass("notice notice-error is-dismissible tribe-notice").addClass(t.selectors.notice.className()).append(o);if(r.each((function(a,n){var r=e(n),o=r.data("validationError");if(i.isObject(o)){var l={},d=t.getConstraints(r,!1);i.each(o,(function(t,e){l[tribe.utils.camelCase(e)]=t})),i.each(d,(function(e,i){e||t.addErrorLine(l[i],r,s)}))}else t.addErrorLine(o,r,s)})),d.append(s),0===l.length){var u=a.find(t.selectors.noticeAfter);0===u.length&&(u=a.find(t.selectors.noticeFallback)),u.after(d)}else l.replaceWith(d)},t.addErrorLine=function(t,i,a){var n=e("<li>").text(t);n.data("validationField",i),i.data("validationNoticeItem",i),a.append(n)},t.onSubmit=function(i){var a=e(this);if(a.trigger("validation.tribe"),!a.is(t.selectors.valid))return i.preventDefault(),!1},t.onClickSubmitButtons=function(i){var a=e(this).parents(t.selectors.item);if(0!==a.length){a.trigger("validation.tribe");var n=a.find(t.selectors.fields);n.off("invalid.tribe"),n.one("invalid.tribe",t.onInvalidField)}},t.onInvalidField=function(i){var a=e(this),n=a.parents(t.selectors.item);return a.addClass(t.selectors.error.className()),n.trigger("displayErrors.tribe"),a.one("change",t.onChangeFieldRemoveError),i.preventDefault(),!1},t.onChangeFieldRemoveError=function(i){var a=e(this),n=t.getConstraintsFields(a);0!==n.filter(t.selectors.error).length&&n.removeClass(t.selectors.error.className())},t.onClickDismissNotice=function(i){e(this).parents(t.selectors.notice).remove()},t.onReady=function(i){e(t.selectors.item).validation()},e.fn.validation=t.fn,e(t.onReady)}(tribe.validation,jQuery,window.underscore||window._),window.tec=window.tec||{},window.tec.common=window.tec.common||{},window.tec.common.validation={};