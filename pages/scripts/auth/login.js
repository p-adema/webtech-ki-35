$(document).ready(function () {
    $("form").submit(function (event) {
        let userData = {
            name: $("#name").val(), password: $("#password").val(),
        };

        $.post("/api/login.php", userData, function (data) {
            const server_data = JSON.parse(data);
            console.log(server_data);

            if (!server_data.success) {
                for (let elem in server_data.errors) {
                    if (server_data.errors.name) {
                        $(`#${elem}-group`).addClass("has-error").children("span").html(
                            '<div class="help-block">' + server_data.errors[elem] + "</div>");
                    } else {
                        $(`#${elem}-group`).removeClass("has-error").children("span").text("")
                    }
                }

            } else {
                $("form").html('<div class="alert alert-success">' + server_data.message + "</div>");
            }

        });

        event.preventDefault();
    });
});