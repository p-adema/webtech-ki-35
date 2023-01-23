$(document).ready(function () {
    $('review-item-delete').click(function (_) {

        const user_data = {
            type: 'remove',
            item: $(this).attr('tag')
        };

        const handler_options = {}

        $.post("/api/bank.php", user_data, form_default_response(handler_options))
    })
})

function remove_item_err(errors, _) {
    $(this).css('color', 'black')
    console.log(errors)
}

function remove_item_success(_, __) {

}
