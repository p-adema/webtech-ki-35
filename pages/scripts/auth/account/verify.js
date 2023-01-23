$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {}

        const handler_options = {
            redirect: {
                link: '/auth/login',
                delay: 5000
            }
        }
        $.post("/api/account/verify", user_data, form_default_response(handler_options));
    });
});
