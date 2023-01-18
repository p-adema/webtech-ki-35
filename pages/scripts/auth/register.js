$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            name: $("#name").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            re_pwd: $("#re_pwd").val(),
            full_name: $("#full_name").val(),
        };

        const handler_options = {
            redirect: {
                link: '/',
                delay: 5000
            }
        }

        $.post("/api/register.php", user_data, form_handle_respone(handler_options));
    });
});
