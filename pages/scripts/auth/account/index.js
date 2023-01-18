$(document).ready(function () {
    const user_data = JSON.stringify({
        name: $("#name").val(),
        email: $("#email").val(),
        full_name: $("#full_name").val(),
        password: $("#password").val(),
    });

    $("button.form-submit").addClass('disabled').prop('disabled', 'true')
    $("input").keyup(function () {
        let user_data_temporary = JSON.stringify({
            name: $("#name").val(),
            email: $("#email").val(),
            full_name: $("#full_name").val(),
            password: $("#password").val(),
        });
        if (user_data === user_data_temporary) {
            $("button.form-submit").addClass('disabled').prop('disabled', 'true')

        } else {
            $("button.form-submit").removeClass('disabled').removeAttr('disabled', 'true')
            $(window).bind('beforeunload', function(){
               if (!$("button.form-submit").hasClass('disabled')) {
                   return 'Are you sure you want to leave?';
               }

            });
        }

    });


     $("form").submit(function (event) {
         event.preventDefault();
         $('button.form-submit').addClass('pressed').removeClass('error')
         let user_data_temporary = {
             name: $("#name").val(),
             email: $("#email").val(),
             full_name: $("#full_name").val(),
             password: $("#password").val(),
             new_password: $("#new_password").val(),
             repeated_password: $("#repeated_password").val(),
         }


         $.post("/api/account/index.php", user_data_temporary, function (response_raw) {
             try {
                 const response = JSON.parse(response_raw);
                 console.log(response);
                 if (!response.success) {
                     form_handle_errors(response.errors);
                 } else {
                     $("form").html('<span class="form-success">' + response.message + "</span>")
                     setTimeout(function () {
                         // Example redirect, TODO: make auto redirect on already logged in user
                         $(location).attr('href', '/')
                     }, 5000)
                 }
             }catch (e) {
                 console.log(response_raw);
                 console.log(e);
                 $('button.form-submit').addClass('error')
             } finally {
                 $('button.form-submit').removeClass('pressed')
             }
         });
     });
});