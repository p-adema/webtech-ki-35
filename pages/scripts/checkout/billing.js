$(document).ready(function () {
    $('form').submit(function (event) {
        event.preventDefault();

        const user_data = {
            l_name: $("#l_name").val(),
            country: $("#country").val(),
            city: $("#city").val(),
            zipcode: $("#zipcode").val(),
            streetnum: $("#streetnum").val(),
        };

        const handler_options = {
            redirect: {
                link: '/checkout/payment',
            }
        }

        $.post("/api/account/billing", user_data, form_default_response(handler_options))
    })
})
