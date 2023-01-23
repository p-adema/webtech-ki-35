$(document).ready(function () {

    const original_data = JSON.stringify({
        name: $("#name").val(),
        email: $("#email").val(),
        full_name: $("#full_name").val(),
        password: $("#password").val(),
        new_password: $("#new_password").val(),
        repeated_password: $("#repeated_password").val(),
    });

    $("button.form-submit").addClass('disabled').prop('disabled', 'true')
    $("input").keyup(function () {
        const user_data = JSON.stringify({
            name: $("#name").val(),
            email: $("#email").val(),
            full_name: $("#full_name").val(),
            password: $("#password").val(),
            new_password: $("#new_password").val(),
            repeated_password: $("#repeated_password").val(),
        });
        if (original_data === user_data) {
            $("button.form-submit").addClass('disabled').prop('disabled', 'true')

        } else {
            $("button.form-submit").removeClass('disabled').removeAttr('disabled', 'true')
            $(window).bind('beforeunload', function () {
                if (!$("button.form-submit").hasClass('disabled')) {
                    return 'Are you sure you want to leave?';
                }

            });
        }

    });


     $("form").submit(function (event) {
         event.preventDefault();
         $('button.form-submit').addClass('pressed').removeClass('error')
         const user_data = {
             name: $("#name").val(),
             email: $("#email").val(),
             full_name: $("#full_name").val(),
             password: $("#password").val(),
             new_password: $("#new_password").val(),
             repeated_password: $("#repeated_password").val(),
         }

         const handler_options = {
             success_handler: form_custom_success
         }

         $.post("/api/account/index", user_data, form_default_response(handler_options));
     });
});

function form_custom_success(_, __) {
    $(window).unbind();
    $(location).attr('href', '/auth/account/index')
}
