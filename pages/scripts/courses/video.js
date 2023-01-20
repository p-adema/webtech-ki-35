// Description load more function
$(document).ready(function () {

    $('button.collapsible').click(function (_) {
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            $('div.content').css('max-height', $('div.content').prop('scrollHeight'));
        } else {
            $('div.content').css('max-height', '0');
        }
    })
    $("form").submit(function (event) {
        event.preventDefault();

        if (this.id === 'add') {

            $('button.form-submit').addClass('pressed').removeClass('error')

            let parameterList = new URLSearchParams(window.location.search)

            const user_data = {
                type: 'add',
                item: parameterList.get('tag')
            };

            const handler_options = {
                success_handler: form_custom_success
            }

            $.post("/api/cart/modify.php", user_data, form_default_response(handler_options));
        } else {

            $('button.form-submit').addClass('pressed').removeClass('error')

            window.location.href = "/show_cart.php"

        }
    });


    $(".stars").mousemove(function (event) {
            let x_move = event.pageX;
            let x_start = $(this).offset().left;
            let tot_length = $(this).width();

            if (x_move > 0.0000000001 * tot_length + x_start) {
                $('.stars').addClass('star-1')
                if (x_move > 0.2 * tot_length + x_start) {
                    $('.stars').addClass('star-2')
                    if (x_move > 0.4 * tot_length + x_start) {
                        $('.stars').addClass('star-3')
                        if (x_move > 0.6 * tot_length + x_start) {
                            $('.stars').addClass('star-4')
                            if (x_move > 0.8 * tot_length + x_start) {
                                $('.stars').addClass('star-5')
                            } else {
                                $('.stars').removeClass('star-5')
                            }
                        } else {
                            $('.stars').removeClass('star-4')
                        }
                    } else {
                        $('.stars').removeClass('star-3')
                    }
                } else {
                    $('.stars').removeClass('star-2')
                }
            }
        }
    )

    $(".stars").mouseleave(function (_) {
        $(".stars").removeClass(['star-5', 'star-4', 'star-3', 'star-2', 'star-1'])
    });

    $(".stars").click(function (event) {
        let x_cord = event.pageX

        if (623 < x_cord && x_cord < 641) {
            $(".stars").removeClass(['star-5', 'star-4', 'star-3', 'star-2', 'star-1', 'perm-star-2', 'perm-star-3', 'perm-star-4', 'perm-star-5'])
            $(".stars").addClass('perm-star-1')
            jQuery.post('pages/api/courses/video.php', {star : 1})
        }
        else if (641 < x_cord && x_cord < 659) {
            $(".stars").removeClass(['star-5', 'star-4', 'star-3', 'star-2', 'star-1', 'perm-star-1', 'perm-star-3', 'perm-star-4', 'perm-star-5'])
            $(".stars").addClass('perm-star-2')
            jQuery.post('pages/api/courses/video.php', {star : 2})
        }
        else if (659 < x_cord && x_cord < 677) {
            $(".stars").removeClass(['star-5', 'star-4', 'star-3', 'star-2', 'star-1', 'perm-star-1', 'perm-star-2', 'perm-star-4', 'perm-star-5'])
            $(".stars").addClass('perm-star-3')
            jQuery.post('pages/api/courses/video.php', {star : 3})
        }
        else if (677 < x_cord && x_cord < 695) {
            $(".stars").removeClass(['star-5', 'star-4', 'star-3', 'star-2', 'star-1', 'perm-star-1', 'perm-star-2', 'perm-star-3','perm-star-5'])
            $(".stars").addClass('perm-star-4')
            jQuery.post('pages/api/courses/video.php', {star : 4})
        }
        else if (695 < x_cord && x_cord < 713) {
            $(".stars").removeClass(['star-5', 'star-4', 'star-3', 'star-2', 'star-1', 'perm-star-1', 'perm-star-2', 'perm-star-3', 'perm-star-4'])
            $(".stars").addClass('perm-star-5')
            jQuery.post('pages/api/courses/video.php', {star : 5})
        }
    })

    function form_custom_success(_, __) {
        $('form#add').hide()
        $('form#cart').show()
    }

})
