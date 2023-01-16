$(document).ready(function (){
    $("form").submit(function (event) {
        let userData = {
            email : $("#email").val(),
    };
        $.post("/api/forgot_password.php", userData, function(response_raw) {

            const response = JSON.parse(response_raw);
            console.log(response);



            if (!response.success){
                if (response.errors.email) {
                    $("#email-group").children("span").addClass("has-error").html(
                        '<div class="help-block">' + response.errors.email + "</div>"
                    );
                }
                else {
                    $("#email-group").children("span").addClass("has-error").text("")
                }
            }
            else {
                $("form").html(
                    '<div class="alert alert-success">' + response.message + "</div>"
                );
            }

        });
        event.preventDefault();

    });
});
