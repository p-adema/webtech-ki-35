$(document).ready(function () {
    const course_tag = $('.course_tag').attr('tag')
    const stars = $(".stars");

    stars.mousemove(function (event) {
            let x_move = event.pageX - event.currentTarget.offsetLeft;
            let tot_length = $(this).width();

            let star_count = Math.ceil(((x_move / tot_length) * 5))

            stars.removeClass(['star-1', 'star-2', 'star-3', 'star-4', 'star-5']).addClass([`star-${star_count}`]);
        }
    )

    stars.mouseleave(function (_) {
        stars.removeClass(['star-5', 'star-4', 'star-3', 'star-2', 'star-1'])
    });

    stars.click(function (event) {
        let x_cord = event.pageX - event.currentTarget.offsetLeft;
        let tot_length = $(this).width();

        let star_count = Math.ceil(((x_cord / tot_length) * 5))

        const video_data = {
            type: 'item',
            on: course_tag,
            star: star_count
        }
        stars.removeClass().addClass(['stars', `perm-star-${star_count}`])
        jQuery.post('/api/courses/stars', video_data)
    })
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

            $.post("/api/cart/modify", user_data, form_default_response(handler_options));
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
