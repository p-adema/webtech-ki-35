$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();

        const form_data = {
            email: $("#email").val(),
        };
        $.post("/api/forgot_password.php", form_data, function (response_raw) {
            try {
                const response = JSON.parse(response_raw);
                console.log(response);

                if (!response.success) {
                    if (response.errors.email) {
                        $("#email-group").children("span").addClass("has-error").html('<div class="help-block">' + response.errors.email + "</div>");
                    } else {
                        $("#email-group").children("span").addClass("has-error").text("")
                    }
                } else {
                    $("form").html('<div class="alert alert-success">' + response.message + "</div>");
                }

            } catch (e) {
                console.log(response_raw)
            }
        });
    });
});
