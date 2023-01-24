$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            password: $("#password").val(),
            password_repeated: $("#password_repeated").val(),
        };

        const handler_options = {
            redirect: {
                link: '/',
                delay: 5000
            }
        }

        $.post("/api/reset_password", user_data, form_default_response(handler_options));
    });
});
