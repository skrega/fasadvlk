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


    /** swiper start */
    //products-swiper
    const productsSwiper = new Swiper('.products-swiper', {
        spaceBetween: 16,
        slidesPerView: 4,
        navigation: {
            nextEl: ".popular-btn-next",
            prevEl: ".popular-btn-prev",
        },
    });
    const portfolioSwiper = new Swiper('.portfolio-swiper ', {
        spaceBetween: 16,
        slidesPerView: 4,
        navigation: {
            nextEl: ".gallary-btn-next",
            prevEl: ".gallary-btn-prev",
        },
    });

    const productThumbs = new Swiper(".product-thumbs", {
        spaceBetween: 10,
        slidesPerView: 4,
        // freeMode: true,
        // watchSlidesProgress: true,
    });
    const productImagesSwiper = new Swiper(".product-images-swiper", {
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: productThumbs,
        },
    });
    /** swiper end */
    // productsSwiper.forEach(swiper => {
    //     swiper.destroy(true, true)
    // });
    // productsSwiper.init()

    // productsSwiper[0].init(true)

    $(".tabs-block .tab").on("click", function (event) {
        let id = $(this).attr("data-id");
        $(this).closest('.tabs-block').find(".tab-item").removeClass("active-tab").hide();
        $(this).closest('.tabs-block').find(".tab").removeClass("active");
        $(this).addClass("active");
        let activeSwiper = $("#" + id).find('.portfolio-swiper')
        //    activeSwiper.destroy(true, true)
        $("#" + id)
            .addClass("active-tab")
            .fadeIn();

        return false;
    });


    if ($('.post__items').length > 0) {
        let mixer = mixitup('.post__items');
    }
    if ($('.posts-tabs').length > 0) {
        let firstTab = $('.tab-btn')[0];
        firstTab.classList.add('mixitup-control-active')
    }
    // var mixer = mixitup('.post__items', {
    //     animation: {
    //         effects: 'fade scale(0.5)'
    //     }
    // });
    $('.filter-btn').on('click', function () {
        $('.filter-nav').hide()
        $('.products').toggleClass('is-filter')
        $('.catalog-wrapper').toggleClass('is-filter')
        // $('.catalog-filter').fadeToggle();
    })
    $('.filter-btn__is-filter').on('click', function () {
        $('.filter-nav').show()
        // $('.products').toggleClass('is-filter')
        // $('.catalog-wrapper').toggleClass('is-filter')
        // $('.catalog-filter').fadeToggle();
    })

    $('#btn-colums-2').on('click', function () {
        $(this).addClass('active')
        $('#btn-colums-4').removeClass('active')
        $('.catalog-wrapper').removeClass('colums-4')
        $('.catalog-wrapper').addClass('colums-2')
    })
    $('#btn-colums-4').on('click', function () {
        $(this).addClass('active')
        $('#btn-colums-2').removeClass('active')
        $('.catalog-wrapper').removeClass('colums-2')
        $('.catalog-wrapper').addClass('colums-4')
    })

    $('.filter-category__link ').on('click', function () {
        let categoryLink = $(this).attr('href')
        console.log(categoryLink)
        window.location.href = categoryLink
    })


})