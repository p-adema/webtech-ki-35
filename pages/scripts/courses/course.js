$(document).ready(function () {
    const course_tag = $('.course_tag').attr('data-tag')
    bind_stars()
    $(".shop").submit(function (event) {
        event.preventDefault();

        if (this.id === 'add') {

            $('button.form-submit').addClass('pressed').removeClass('error')

            const user_data = {
                type: 'add',
                item: course_tag
            };

            const handler_options = {
                success_handler: form_custom_success
            }

            $.post('/api/cart/modify', user_data, form_default_response(handler_options));
        } else {

            $('button.form-submit').addClass('pressed').removeClass('error')

            window.location.href = "/checkout/review"

        }
    })
})


function form_custom_success(data, __) {
    $('form#add').hide()
    $('form#cart').show()
    $('.cart-item-anchor').remove();
    $('.sidebar-close').after(data.html);
    $('.cart-item-delete').click(cart_item_delete);
}
