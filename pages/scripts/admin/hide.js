$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            comment_tag: $("#comment_tag").val(),
            action: $(this).attr('data-action')
        };

        const handler_options = {
            redirect: {
                link: '/admin/',
                delay: 3000
            }
        }

        $.post('/api/admin/hide', user_data, form_default_response(handler_options));
    });
});