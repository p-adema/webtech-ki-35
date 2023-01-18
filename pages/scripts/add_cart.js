$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            type: 'add',
            item: 'example'
        };

        const handler_options = {
            redirect: {
                link: '/',
                delay: 2000
            }
        }

        $.post("/api/cart/modify.php", user_data, form_handle_respone(handler_options));
    });
});
