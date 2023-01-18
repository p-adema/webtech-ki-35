$(document).ready(function () {
    $('button.form-submit').click(function (_) {
        $(this).attr('clicked', true)
    })
    $("form").submit(function (event) {
        event.preventDefault()
        $('button.form-submit').addClass('pressed').removeClass('error')

        const parameter_list = new URLSearchParams(window.location.search)

        const user_data = {
            type : $('button.form-submit[clicked=true]').val(),
            tag: parameter_list.get('tag')
            }

        $.post("/api/bank.php", user_data, function (response_raw) {
            try {
                const response = JSON.parse(response_raw);
                console.log(response);
                if (!response.success) {
                    form_handle_errors(response.errors);
                    $("form").html('<div class="form-succes"><span>' + response.message + "</span></div>")
                } else {
                    $("form").html('<div class="form-success"><span>' + response.message + "</span></div>")
                    setTimeout(function () {
                        $(location).attr('href', '/bank')
                    }, 3000)
                }
            } catch (e) {
                console.log(response_raw);
                console.log(e);
                $('button.form-submit').addClass('error')
            } finally {
                $('button.form-submit').removeClass('pressed')
            }
        })
    })
})
