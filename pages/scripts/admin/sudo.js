$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            user: $("#user").val(),
        };

        const handler_options = {
            redirect: {
                link: '/admin/',
                delay: 3000
            }
        }

        $.post('/api/admin/sudo', user_data, form_default_response(handler_options));
    });
});
