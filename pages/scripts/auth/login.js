$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            name: $("#name").val(),
            password: $("#password").val(),
        };

        const handler_options = {
            redirect: {
                link: $(this).attr('tag'),
                delay: 3000
            }
        }

        $.post("/api/login", user_data, form_default_response(handler_options));
    });
});
