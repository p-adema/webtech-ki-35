$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed')

        const user_data = {
            name: $("#name").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            full_name: $("#full_name").val(),
        };

        $.post("/api/register.php", user_data, function (response_raw) {
            const response = JSON.parse(response_raw);
            console.log(response);

            if (!response.success) {
                for (let form_elem in response.errors) {
                    if (response.errors[form_elem].length !== 0) {
                        $(`div#${form_elem}-group`).addClass("has-error")
                        $(`span#${form_elem}-error`).css('visibility', 'visible').html(
                            response.errors[form_elem].join('<br/>')
                        );
                    } else {
                        $(`div#${form_elem}-group`).removeClass("has-error")
                        $(`span#${form_elem}-error`).css('visibility', 'hidden').html('No error');
                    }
                }
            } else {
                $("form").html(
                    '<div class="form-success"><span>' + response.message + "</span></div>"
                )
                setTimeout(function () {
                    // Example redirect, TODO: make auto redirect on already logged in user (to home)
                    $(location).attr('href', '/')
                }, 1500)
            }
            $('button.form-submit').removeClass('pressed')
        });
    });
});
