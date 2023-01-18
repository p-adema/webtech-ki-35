$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            item: 'example',
        };

        const handler_options = {
            redirect: {
                link: '/',
                delay: 2000
            }
        }

        $.post("/api/login.php", user_data, form_handle_respone(handler_options));
    });
});
