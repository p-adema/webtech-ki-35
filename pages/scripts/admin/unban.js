$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            user: $("#user").val(),
            action: 'unban'
        };

        const handler_options = {}

        $.post("/api/admin/ban", user_data, form_default_response(handler_options));
    });
});
