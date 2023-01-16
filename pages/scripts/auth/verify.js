$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();

        const parameter_list = new URLSearchParams(window.location.search)
        const user_data = {
            tag: parameter_list.get('tag')
        };

        $.post("/api/verify.php", user_data, function (server_data_raw) {

            const server_data = JSON.parse(server_data_raw);
            console.log(server_data);


            $("form").html(
                '<span class="form-success">' + server_data.message + "</span>"
            )
            setTimeout(function () {
                // Example redirect, TODO: make auto redirect on already logged in user
                $(location).attr('href', '/')
            }, 15000)


        });
    });
});
