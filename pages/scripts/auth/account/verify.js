$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {}

        const handler_options = {
            redirect: {
                link: '/',
                delay: 5000
            }
        }
        $.post("/api/account/verify.php", user_data, form_handle_respone(handler_options));
    });
});