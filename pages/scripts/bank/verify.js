$(document).ready(function () {
    $('button.form-submit').click(function (_) {
        $(this).attr('clicked', true)
    })

    $("form").submit(function (event) {
        event.preventDefault()
        $('button.form-submit').addClass('pressed').removeClass('error')

        const parameter_list = new URLSearchParams(window.location.search)

        const user_data = {
            type: $('button.form-submit[clicked=true]').val(),
            tag: parameter_list.get('tag')
        }

        const handler_options = {
            redirect: {
                link: '/bank/',
                delay: 3000
            }
        }

        $.post("/api/bank", user_data, form_default_response(handler_options))
    })
})
