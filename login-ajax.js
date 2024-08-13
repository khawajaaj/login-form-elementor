jQuery(document).ready(function ($) {
    $('.wptech-login-form').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var message = $('#wptech-login-message');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: wptech_login_ajax.ajax_url,
            data: {
                'action': 'wptech_ajax_login',
                'username': form.find('input[name="log"]').val(),
                'password': form.find('input[name="pwd"]').val(),
                'remember': form.find('input[name="rememberme"]').is(':checked'),
                'security': wptech_login_ajax.nonce
            },
            success: function (response) {
                if (response.loggedin == true) {
                    message.html('<div class="wptech-login-success">' + response.message + '</div>');
                    window.location.href = wptech_login_ajax.redirect_url;
                } else {
                    message.html('<div class="wptech-login-error">' + response.message + '</div>');
                }
            }
        });
    });
});