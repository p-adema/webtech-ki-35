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
        }
        else {
            $('div.content').css('max-height', '0');
        }
    })

})
