$(document).ready(function () {
    $("form").submit(function (event) {
        let userData = {
            email: $("#email").val(),
        };
        $.post("/api/forgot_password.php", userData, function (response_raw) {
            const response = JSON.parse(response_raw);
            console.log(response);

            if (!response.success) {
                for (let form_elem in response.errors) {
                    if (response.errors[form_elem].length !== 0) {
                        $(`div#${form_elem}-group`).addClass("has-error")
                        $(`span#${form_elem}-error`).css('visibility', 'visible').html(response.errors[form_elem].join('<br/>'));
                    } else {
                        $(`div#${form_elem}-group`).removeClass("has-error")
                        $(`span#${form_elem}-error`).css('visibility', 'hidden').html('No error');
                    }
                }
            } else {
                $("form").html('<div class="alert alert-success">' + response.message + "</div>");
            }
        });
        event.preventDefault();
    });
});
