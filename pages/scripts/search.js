$(document).ready(function () {
    const $search = $('.search-input');
    let last_query = $search.focus().val();
    search(last_query)
    $search.val('').val(last_query).keypress(function (event) {
        const val = $(this).val() + String.fromCharCode(event.which);
        if (val !== last_query) {
            search(val)
        }
        last_query = val;
    }).keyup(function (_) {
        const val = $(this).val();
        if (val !== last_query) {
            search(val)
        }
        history.replaceState(null, '', '/search/' + val)
        last_query = val;
    }).keydown(function (event) {
        if (event.which === 27) {
            $(this).blur()
        }
    })
    $('#filter-buttons > span').click(function (_) {
        $('#filter-buttons > span').removeClass('active');
        $('.search-results').removeClass().addClass($(this).addClass('active').attr('data-class'));
        filter = $(this).attr('data-filter')
        check_empty()
    })
    $('#sort-buttons > span').click(function (_) {
        $('#sort-buttons > span').removeClass('active');
        sort = $(this).addClass('active').attr('data-sort');
        search($('.search-input').val())
    })
})

function search(query) {
    if (query !== '') {
        const query_data = {
            query: query,
            origin: 'main',
            sort: sort
        }
        const $target = $('#main-search-results');
        const handler_options = {
            success_handler: function (data, _) {
                $target.html(data.html);
                any_owned = data.any_owned;
                any_available = data.any_available;
                any = data.any;
                check_empty()
            },
            error_handler: function (errors, _) {
                console.log(errors);
                $target.text('There was a problem loading the results')
            }
        }

        $.post('/api/load/query', query_data, form_default_response(handler_options));
    } else {
        $('#main-search-results').html('')
    }
}

function check_empty() {
    $('.result-empty').hide();
    if (filter === 'owned' && !any_owned) {
        $('#owned-empty').css('display', 'flex')
    } else if (filter === 'available' && !any_available) {
        $('#available-empty').css('display', 'flex')
        if (any) {
            $('#available-empty-slot').text('You can view paid videos on this topic though!').parent().addClass('active').click(function (_) {
                $('.filter-all').click()
            })
        } else {
            $('#available-empty-slot').text('').parent().removeClass('active').unbind('click')
        }
    } else if (filter === 'all' && !any) {
        $('#all-empty').css('display', 'flex')
    }
}

let sort = 'views';
let filter = 'all';
let any_owned = true;
let any_available = true;
let any = true;
