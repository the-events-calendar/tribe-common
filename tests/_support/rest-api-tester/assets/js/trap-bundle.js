(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
(function ($, undefined) {
    var renderjson = require('renderjson');
    var setRequestResponse = function (data, status, response) {
        var json = data.responseJSON || data;
        var status = data.status || response.status || 200;
        var color = 'green';
        if (status >= 300 && status < 400) {
            // redirection
            color = 'orange';
        }
        else if (status >= 400 && status < 500) {
            // bad request
            color = 'red';
        }
        else if (status >= 500) {
            // internal error
            color = 'white';
        }
        var $responseTab = $('#trap-response');
        $responseTab.empty();
        $responseTab.prepend('<div class="response-header ' + color + '">' + status + '</div>');
        $('#trap-json').text(JSON.stringify(json));
        formatResponseJson();
    };
    var startLoadingResponse = function () {
        var $button = $('#trap-request');
        $button.prop('disabled', true);
        $button.text(Trap.button_loading_response_text);
    };
    var formatResponseJson = function () {
        renderjson.set_show_to_level(3);
        var json = $('#trap-json').text().trim();
        var $response = $('#trap-response');
        if (!json) {
            $response.empty();
            return;
        }
        document.getElementById('trap-response').appendChild(renderjson(JSON.parse(json)));
    };
    var formatDocumentationJson = function () {
        renderjson.set_show_to_level(5);
        var json = $('#trap-documentation-json').text().trim();
        var $doc = $('#trap-documentation');
        if (!json) {
            $doc.empty();
            return;
        }
        document.getElementById('trap-documentation').appendChild(renderjson(JSON.parse(json)));
    };
    var showMethodParameters = function (evt) {
        var method = '';
        if (evt instanceof Event) {
            method = $(evt.target).val();
        }
        else {
            method = $('#trap-request-method').val();
        }
        $('.method-parameters').hide();
        $('#' + method + '-method-parameters').show();
    };
    var startLoading = function () {
        var $button = $('#trap-request');
        $button.prop('disabled', true);
        $button.text(Trap.button_loading_text);
    };
    var stopLoading = function () {
        var $button = $('#trap-request');
        $button.prop('disabled', false);
        $button.text(Trap.button_text);
    };
    var nonceError = function () {
        alert('Could not get nonce for selected user... weird.');
    };
    var setNonce = function (data) {
        $('#trap-nonce').val(data);
    };
    var getNonceForUser = function (userId) {
        startLoading();
        $.ajax({
            url: Trap.nonce_url + userId,
            dataType: 'json',
        }).done(setNonce).fail(nonceError).always(stopLoading);
    };
    var generateUserNonce = function (evt) {
        var userId = 0;
        if (evt instanceof Event) {
            userId = $(evt.target).val();
        }
        else {
            userId = $('#trap-user-id').val();
        }
        var $nonceField = $('#trap-nonce');
        if (0 == userId) {
            $nonceField.val('');
            return;
        }
        $nonceField.val(getNonceForUser(userId));
    };
    var submitRequest = function () {
        var url = $('#trap-url').val();
        var method = $('#trap-request-method').val();
        var nonce = $('#trap-nonce').val();
        var user = $('#trap-user-id').val();
        var inputs = $('#trap-wrap #' + method + '-method-parameters .method-parameter input');
        var queryArgs = {
            user: user,
        };
        inputs.each(function (index, element) {
            var $element = $(element);
            var value = $element.val().trim();
            if ('' === value) {
                return;
            }
            var name = $element.data('name');
            var position = $element.data('in');
            if ('path' === position) {
                url = url.replace(new RegExp('\{' + name + '\}', 'g'), value);
                return;
            }
            queryArgs[name] = value;
        });
        // remove any remaining placeholders
        url = url.replace(new RegExp('\{.*\}', 'g'), '');
        var args = {
            url: url,
            method: method,
            beforeSend: null,
            data: queryArgs,
        };
        if (nonce) {
            args.beforeSend = function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', nonce);
            };
        }
        startLoadingResponse();
        $.ajax(args).always(stopLoading).done(setRequestResponse).fail(setRequestResponse);
    };
    $(document).ready(function () {
        formatResponseJson();
        formatDocumentationJson();
        showMethodParameters();
        $('#trap-request-method').on('change', showMethodParameters);
        generateUserNonce();
        $('#trap-user-id').on('change', generateUserNonce);
        $('#trap-request').on('click', submitRequest);
    });
})(jQuery);

},{"renderjson":2}],2:[function(require,module,exports){
// Copyright © 2013-2017 David Caldwell <david@porkrind.org>
//
// Permission to use, copy, modify, and/or distribute this software for any
// purpose with or without fee is hereby granted, provided that the above
// copyright notice and this permission notice appear in all copies.
//
// THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
// WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
// MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY
// SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
// WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION
// OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN
// CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.

// Usage
// -----
// The module exports one entry point, the `renderjson()` function. It takes in
// the JSON you want to render as a single argument and returns an HTML
// element.
//
// Options
// -------
// renderjson.set_icons("+", "-")
//   This Allows you to override the disclosure icons.
//
// renderjson.set_show_to_level(level)
//   Pass the number of levels to expand when rendering. The default is 0, which
//   starts with everything collapsed. As a special case, if level is the string
//   "all" then it will start with everything expanded.
//
// renderjson.set_max_string_length(length)
//   Strings will be truncated and made expandable if they are longer than
//   `length`. As a special case, if `length` is the string "none" then
//   there will be no truncation. The default is "none".
//
// renderjson.set_sort_objects(sort_bool)
//   Sort objects by key (default: false)
//
// renderjson.set_replacer(replacer_function)
//   Equivalent of JSON.stringify() `replacer` argument when it's a function
//
// renderjson.set_property_list(property_list)
//   Equivalent of JSON.stringify() `replacer` argument when it's an array
//
// Theming
// -------
// The HTML output uses a number of classes so that you can theme it the way
// you'd like:
//     .disclosure    ("⊕", "⊖")
//     .syntax        (",", ":", "{", "}", "[", "]")
//     .string        (includes quotes)
//     .number
//     .boolean
//     .key           (object key)
//     .keyword       ("null", "undefined")
//     .object.syntax ("{", "}")
//     .array.syntax  ("[", "]")

var module, window, define, renderjson=(function() {
    var themetext = function(/* [class, text]+ */) {
        var spans = [];
        while (arguments.length)
            spans.push(append(span(Array.prototype.shift.call(arguments)),
                              text(Array.prototype.shift.call(arguments))));
        return spans;
    };
    var append = function(/* el, ... */) {
        var el = Array.prototype.shift.call(arguments);
        for (var a=0; a<arguments.length; a++)
            if (arguments[a].constructor == Array)
                append.apply(this, [el].concat(arguments[a]));
            else
                el.appendChild(arguments[a]);
        return el;
    };
    var prepend = function(el, child) {
        el.insertBefore(child, el.firstChild);
        return el;
    }
    var isempty = function(obj, pl) { var keys = pl || Object.keys(obj);
                                      for (var i in keys) if (Object.hasOwnProperty.call(obj, keys[i])) return false;
                                      return true; }
    var text = function(txt) { return document.createTextNode(txt) };
    var div = function() { return document.createElement("div") };
    var span = function(classname) { var s = document.createElement("span");
                                     if (classname) s.className = classname;
                                     return s; };
    var A = function A(txt, classname, callback) { var a = document.createElement("a");
                                                   if (classname) a.className = classname;
                                                   a.appendChild(text(txt));
                                                   a.href = '#';
                                                   a.onclick = function(e) { callback(); if (e) e.stopPropagation(); return false; };
                                                   return a; };

    function _renderjson(json, indent, dont_indent, show_level, options) {
        var my_indent = dont_indent ? "" : indent;

        var disclosure = function(open, placeholder, close, type, builder) {
            var content;
            var empty = span(type);
            var show = function() { if (!content) append(empty.parentNode,
                                                         content = prepend(builder(),
                                                                           A(options.hide, "disclosure",
                                                                             function() { content.style.display="none";
                                                                                          empty.style.display="inline"; } )));
                                    content.style.display="inline";
                                    empty.style.display="none"; };
            append(empty,
                   A(options.show, "disclosure", show),
                   themetext(type+ " syntax", open),
                   A(placeholder, null, show),
                   themetext(type+ " syntax", close));

            var el = append(span(), text(my_indent.slice(0,-1)), empty);
            if (show_level > 0)
                show();
            return el;
        };

        if (json === null) return themetext(null, my_indent, "keyword", "null");
        if (json === void 0) return themetext(null, my_indent, "keyword", "undefined");

        if (typeof(json) == "string" && json.length > options.max_string_length)
            return disclosure('"', json.substr(0,options.max_string_length)+" ...", '"', "string", function () {
                return append(span("string"), themetext(null, my_indent, "string", JSON.stringify(json)));
            });

        if (typeof(json) != "object" || [Number, String, Boolean, Date].indexOf(json.constructor) >= 0) // Strings, numbers and bools
            return themetext(null, my_indent, typeof(json), JSON.stringify(json));

        if (json.constructor == Array) {
            if (json.length == 0) return themetext(null, my_indent, "array syntax", "[]");

            return disclosure("[", " ... ", "]", "array", function () {
                var as = append(span("array"), themetext("array syntax", "[", null, "\n"));
                for (var i=0; i<json.length; i++)
                    append(as,
                           _renderjson(options.replacer.call(json, i, json[i]), indent+"    ", false, show_level-1, options),
                           i != json.length-1 ? themetext("syntax", ",") : [],
                           text("\n"));
                append(as, themetext(null, indent, "array syntax", "]"));
                return as;
            });
        }

        // object
        if (isempty(json, options.property_list))
            return themetext(null, my_indent, "object syntax", "{}");

        return disclosure("{", "...", "}", "object", function () {
            var os = append(span("object"), themetext("object syntax", "{", null, "\n"));
            for (var k in json) var last = k;
            var keys = options.property_list || Object.keys(json);
            if (options.sort_objects)
                keys = keys.sort();
            for (var i in keys) {
                var k = keys[i];
                if (!(k in json)) continue;
                append(os, themetext(null, indent+"    ", "key", '"'+k+'"', "object syntax", ': '),
                       _renderjson(options.replacer.call(json, k, json[k]), indent+"    ", true, show_level-1, options),
                       k != last ? themetext("syntax", ",") : [],
                       text("\n"));
            }
            append(os, themetext(null, indent, "object syntax", "}"));
            return os;
        });
    }

    var renderjson = function renderjson(json)
    {
        var options = Object.assign({}, renderjson.options);
        options.replacer = typeof(options.replacer) == "function" ? options.replacer : function(k,v) { return v; };
        var pre = append(document.createElement("pre"), _renderjson(json, "", false, options.show_to_level, options));
        pre.className = "renderjson";
        return pre;
    }
    renderjson.set_icons = function(show, hide) { renderjson.options.show = show;
                                                  renderjson.options.hide = hide;
                                                  return renderjson; };
    renderjson.set_show_to_level = function(level) { renderjson.options.show_to_level = typeof level == "string" &&
                                                                                        level.toLowerCase() === "all" ? Number.MAX_VALUE
                                                                                                                      : level;
                                                     return renderjson; };
    renderjson.set_max_string_length = function(length) { renderjson.options.max_string_length = typeof length == "string" &&
                                                                                                 length.toLowerCase() === "none" ? Number.MAX_VALUE
                                                                                                                                 : length;
                                                          return renderjson; };
    renderjson.set_sort_objects = function(sort_bool) { renderjson.options.sort_objects = sort_bool;
                                                        return renderjson; };
    renderjson.set_replacer = function(replacer) { renderjson.options.replacer = replacer;
                                                   return renderjson; };
    renderjson.set_property_list = function(prop_list) { renderjson.options.property_list = prop_list;
                                                         return renderjson; };
    // Backwards compatiblity. Use set_show_to_level() for new code.
    renderjson.set_show_by_default = function(show) { renderjson.options.show_to_level = show ? Number.MAX_VALUE : 0;
                                                      return renderjson; };
    renderjson.options = {};
    renderjson.set_icons('⊕', '⊖');
    renderjson.set_show_by_default(false);
    renderjson.set_sort_objects(false);
    renderjson.set_max_string_length("none");
    renderjson.set_replacer(void 0);
    renderjson.set_property_list(void 0);
    return renderjson;
})();

if (define) define({renderjson:renderjson})
else (module||{}).exports = (window||{}).renderjson = renderjson;

},{}]},{},[1]);
