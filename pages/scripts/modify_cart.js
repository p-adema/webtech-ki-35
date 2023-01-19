$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $(`#${this.id} button.form-submit`).addClass('pressed').removeClass('error')

        const user_data = {
            type: this.id,
            item: 'example_paid'
        };

        const handler_options = {
            redirect: {
                link: '/',
                delay: 2000
            },
            multi_form: {
                target: this.id,
                clear_other: true
            }
        }

        $.post("/api/cart/modify.php", user_data, form_default_response(handler_options));
    });
});
