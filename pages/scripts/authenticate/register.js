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

               $('div').append(`<span style="font-size: x-small" style= "color: red"><b>${server_data.errors[1]}</b></span>`)
            });
        
        event.preventDefault();
    });
});
