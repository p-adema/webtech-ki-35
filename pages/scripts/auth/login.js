$(document).ready(function () {
    $("form").submit(function (event) {
        let userData = {
            name : $("#name").val(),
            password : $("#password").val(),
        };

        $.post("/api/login_form.php", userData, function(data) {
            const server_data =  JSON.parse(data);
            console.log(server_data);

            if (!server_data.success) {
                if (server_data.errors.name) {
                    $("#name-group").children("span").addClass("has-error").html(
                        '<div class="help-block">' + server_data.errors.name + "</div>"
                    );
                }
                else {
                    $("#name-group").children("span").addClass("has-error").text("")
                }

                if (server_data.errors.password) {
                    $("#password-group").children("span").addClass("has-error").html(
                        '<div class="help-block">' + server_data.errors.password + "</div>"
                    );
                }
                else {
                    $("#password-group").children("span").addClass("has-error").text("")
                }

            }
            else {
                $("form").html(
                    '<div class="alert alert-success">' + server_data.message + "</div>"
                );
            }

        });

        event.preventDefault();
    });
});