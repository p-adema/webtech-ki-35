$(document).ready(function () {

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
            on: video_tag
        }
        stars.removeClass().addClass(['stars', `perm-star-${star_count}`])
        jQuery.post('/api/courses/stars', {star: star_count, tag: video_data})
    })
})

