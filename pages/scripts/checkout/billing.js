$(document).ready(function () {
    $('review-item-delete').click(function (_) {

        const user_data = {
            type: 'remove',
            item: $(this).attr('tag')
        };

        const handler_options = {
            redirect: {
                link: '/checkout/payment',
                delay: 3000
            }
        }

        $.post("/api/cart/modify", user_data, form_default_response(handler_options))
    })
})
