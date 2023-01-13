$(document).ready(function () {
    $("form").submit(function (event) {
        let userData = {
            name : $("#name").val(),
            email : $("#email").val(),
            password : $("#password").val(),
            full_name : $("#full_name").val(),
        };

        $.post("/api/register.php", userData, function(data) {
               const server_data =  JSON.parse(data);
               console.log(server_data);

               if (!server_data.success) {
                   for (let group_type in server_data.errors) {
                       if (server_data.errors[group_type].length !== 0) {
                           $(`#${group_type}-group`).addClass("has-error").children("span").html(
                               '<div class="help-block">' + server_data.errors[group_type].join("<br>") + "</div>"
                           );
                       } else {
                           $(`#${group_type}-group`).removeClass("has-error").children("span").text("")
                       }
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

