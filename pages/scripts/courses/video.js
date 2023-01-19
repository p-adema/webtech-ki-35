// Description load more function
$(document).ready(function () {
//     let coll = document.getElementsByClassName("collapsible");
//     let i;
//
//     for (i = 0; i < coll.length; i++) {
//         coll[i].addEventListener("click", function() {
//             this.classList.toggle("active");
//             let content = this.nextElementSibling;
//             if (content.style.maxHeight) {
//                 content.style.maxHeight = null;
//             }
//             else {
//                 $("div.content").css('max-height', '1000px')
//             }
//         });
//     }
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
        $('button.form-submit').addClass('pressed').removeClass('error')

        let parameterList = new URLSearchParams(window.location.search)

        const user_data = {
            type: 'add',
            item: parameterList.get('tag')
        };

        const handler_options = {
            success_handler : form_custom_success
        }

        $.post("/api/cart/modify.php", user_data, form_default_response(handler_options));
    });

    function form_custom_success(_, __) {
        $('form#cart').hide()
    }

})
