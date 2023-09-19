$(function () {
    // menu button
    $('.menu-btn').on('click', function () {
        $(this).toggleClass('open')
        $('.menu').toggleClass('open')
        $('.header__inner').toggleClass('open')
    })

    // вывод сообщения об успешной отправки
    document.addEventListener('wpcf7mailsent', function (event) {
        Fancybox.close();
        Fancybox.show([{
            src: "#success",
            type: "inline"
        }]);
    }, false);
})