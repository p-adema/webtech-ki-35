$(document).ready(function () {
    $("form").submit(function (event) {
        let userData = {
            name: $("#name").val(), password: $("#password").val(),
        };

        $.post("/api/login.php", userData, function (data) {
            const server_data = JSON.parse(data);
            console.log(server_data);

            if (!server_data.success) {
                for (let form_elem in server_data.errors) {
                    if (server_data.errors[form_elem].length !== 0) {
                        $(`#${form_elem}-group`).addClass("has-error").children("span").html(
                            '<div class="help-block">' + server_data.errors[form_elem] + "</div>");
                    } else {
                        $(`#${form_elem}-group`).removeClass("has-error").children("span").text("")
                    }
                }

            } else {
                $("form").html(
                    '<div class="alert alert-success">' + server_data.message + "</div>"
                )
                setTimeout(function () {
                    $(location).attr('href', '/')
                }, 1500)


            }

        });

        event.preventDefault();
    });
});
