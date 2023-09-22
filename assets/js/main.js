$(function () {
    // menu button
    $('.menu-btn').on('click', function () {
        $(this).toggleClass('open')
        $('.menu').toggleClass('open')
        $('.header__inner').toggleClass('open')
    })

    $(".tabs-block .tab").on("click", function (event) {
        var id = $(this).attr("data-id");
        $(".tabs-block").find(".tab-item").removeClass("active-tab").hide();
        $(".tabs-block .tabs").find(".tab").removeClass("active");
        $(this).addClass("active");
        $("#" + id)
            .addClass("active-tab")
            .fadeIn();

        return false;
    });

    // вывод сообщения об успешной отправки
    document.addEventListener('wpcf7mailsent', function (event) {
        Fancybox.close();
        Fancybox.show([{
            src: "#success",
            type: "inline"
        }]);
    }, false);
})