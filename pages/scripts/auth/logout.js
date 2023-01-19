$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault()
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {}

        const handler_options = {
            redirect: {
                link: '/',
                delay: 5000
            }
        }

        $.post("/api/logout.php", user_data, form_default_response(handler_options))
        // TODO: not logged in users go to login page
    })
})
