$(document).ready(function () {
    $('form').submit(function (event) {
        event.preventDefault();

        const user_data = {};

        const handler_options = {}

        $.post("/api/cart/pay", user_data, form_default_response(handler_options))
    })
})
