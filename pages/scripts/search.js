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
    })

})

function search(query) {
    if (query !== '') {
        const query_data = {
            query: query,
            origin: 'main'
        }
        const $target = $('#main-search-results');
        const handler_options = {
            success_handler: function (data, _) {
                $target.html(data.html);
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
