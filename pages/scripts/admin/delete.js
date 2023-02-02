$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            item_tag: $("#item_tag").val(),
        };

        const handler_options = {
            redirect: {
                link: '/admin/',
                delay: 3000
            }
        }

        $.post('/api/admin/delete', user_data, form_default_response(handler_options));
    });
});
