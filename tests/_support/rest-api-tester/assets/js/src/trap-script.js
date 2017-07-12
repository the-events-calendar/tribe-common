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
