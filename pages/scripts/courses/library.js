$(document).ready(function () {
    let last_query = '';
    $('.search-input').focus().keypress(function (event) {
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
        last_query = val;
    })
})

function search(query) {
    const query_data = {
        query: query,
    }
    const $target = $('.videos-wrapper');
    const handler_options = {
        success_handler: function (data, _) {
            $target.html(data.html);
        },
        error_handler: function (errors, _) {
            console.log(errors);
            $target.text('There was a problem loading the results')
        }
    }

    $.post('/api/load/owned', query_data, form_default_response(handler_options));
}
