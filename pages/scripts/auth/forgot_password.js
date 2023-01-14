$(document).ready(function (){
    $("form").submit(function (event) {
        let userData = {
            email : $("#email").val(),
    };
        $.post("/api/forgot_password.php", userData, function(data) {
            const server_data = JSON.parse(data);
            console.log(server_data);

            if (!server_data.success){
                if (server_data.errors.email) {
                    $("#email-group").children("span").addClass("has-error").html(
                        '<div class="help-block">' + server_data.errors.email + "</div>"
                    );
                }
                else {
                    $("#email-group").children("span").addClass("has-error").text("")
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
