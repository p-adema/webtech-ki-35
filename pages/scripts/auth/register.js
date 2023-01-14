$(document).ready(function () {
    $("form").submit(function (event) {
        let user_data = {
            name: $("#name").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            full_name: $("#full_name").val(),
        };

        $.post("/api/register.php", user_data, function (server_data_raw) {
            const server_data = JSON.parse(server_data_raw);
            console.log(server_data);

            if (!server_data.success) {
                for (let form_elem in server_data.errors) {
                    if (server_data.errors[form_elem].length !== 0) {
                        $(`#${form_elem}-group`).addClass("has-error").children("span").html(
                            server_data.errors[form_elem].join("<br/>")
                        );
                    } else {
                        $(`#${form_elem}-group`).removeClass("has-error").children("span").text("")
                       }
                   }
               }
               else {
                   $("form").html(
                       '<span class="form-success">' + server_data.message + "</span>"
                   )
                   setTimeout(function () {
                       // Example redirect, TODO: make auto redirect on already logged in user (to home)
                       $(location).attr('href', '/')
                   }, 1500)
               }

            });

        event.preventDefault();
    });
});
