$(document).ready(function () {
    $('.cart-item-delete').unbind('click').click(cart_item_delete_update_total)
})

function cart_item_delete_update_total(event) {
    event.preventDefault();

    const user_data = {
        type: 'remove',
        item: $(this).attr('data-tag')
    };

    const handler_options = {
        error_handler: function (errors, _) {
            console.log(errors)
        },
        success_handler: function (data, __) {
            $(`a[data-tag=${data.tag}]`).fadeOut('fast', function () {
                $(this).remove()
                $('.review-payment-wrapper .review-item-price').text('€' + data.total.toFixed(2));
            })
        }
    };

    $.post('/api/cart/modify', user_data, form_default_response(handler_options));
}
