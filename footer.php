</main> <!-- main end -->
<footer class="footer">
    <div class="container">
        <div class="footer__inner footer-top">
            <div class="footer-logo">
                <a href="<?php echo get_home_url(); ?>">
                    <?php $footer_logos = get_field('footer_logos', 'options'); ?>
                    <picture>
                        <source srcset="<?php echo $footer_logos['logo_mob']; ?>" media="(max-width: 767px)" />
                        <img src="<?php echo $footer_logos['logo_desktop']; ?>" alt="Footer logo">
                    </picture>
                </a>
                <div class="footer-name text-l">Группа компаний «Фасад»</div>
            </div>
            <div class="footer-contacts">
                <h6 class="footer-col__title text-l mb-2">
                    Контакты
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.5 1L8.5 6L3.5 11" stroke="#127FFF" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </h6>
                <?php $officce = get_field('officce', 'options'); ?>
                <div class="footer__items">
                    <div class="footer-item">
                        <p class="footer-item__text"><strong><?php echo $officce['address_name']; ?></strong></p>
                    </div>
                    <div class="footer-item">
                        <div class="footer-address footer-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 13V13C10.343 13 9 11.657 9 10V10C9 8.343 10.343 7 12 7V7C13.657 7 15 8.343 15 10V10C15 11.657 13.657 13 12 13Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 21C12 21 5 15.25 5 10C5 6.134 8.134 3 12 3C15.866 3 19 6.134 19 10C19 15.25 12 21 12 21Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Народный пр-т, д. 28 торговый центр «РОНДО», 3 этаж, кабинет 308</span>
                        </div>
                        <a class="footer-item__sublink link" href="#">Схема проезда</a>
                    </div>
                    <div class="footer-item footer-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.5 7V12.5H8" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span><?php echo $officce['worktime']; ?></span>
                    </div>
                    <div class="footer-item">
                        <div class="footer-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.8581 13.1463C9.6881 11.9763 8.8061 10.6663 8.2201 9.33731C8.0961 9.05631 8.1691 8.72731 8.3861 8.51031L9.2051 7.69231C9.8761 7.02131 9.8761 6.07231 9.2901 5.48631L8.1161 4.31231C7.3351 3.53131 6.0691 3.53131 5.2881 4.31231L4.6361 4.96431C3.8951 5.70531 3.5861 6.77431 3.7861 7.83431C4.2801 10.4473 5.7981 13.3083 8.2471 15.7573C10.6961 18.2063 13.5571 19.7243 16.1701 20.2183C17.2301 20.4183 18.2991 20.1093 19.0401 19.3683L19.6911 18.7173C20.4721 17.9363 20.4721 16.6703 19.6911 15.8893L18.5181 14.7163C17.9321 14.1303 16.9821 14.1303 16.3971 14.7163L15.4941 15.6203C15.2771 15.8373 14.9481 15.9103 14.6671 15.7863C13.3381 15.1993 12.0281 14.3163 10.8581 13.1463Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <a class="footer-link" href="tel:<?php echo getPhoneHref($officce['phones']['phone']); ?>"><?php echo $officce['phones']['phone'] ?></a>
                        </div>
                        <a class="footer-item__sublink footer-link" href="tel:<?php echo getPhoneHref($officce['phones']['additional_phone']); ?>"><?php echo $officce['phones']['additional_phone']; ?></a>
                    </div>
                    <div class="footer-item footer-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 12C16 14.209 14.209 16 12 16C9.791 16 8 14.209 8 12C8 9.791 9.791 8 12 8V8C14.209 8 16 9.791 16 12V13.5C16 14.881 17.119 16 18.5 16C19.881 16 21 14.881 21 13.5V12C21 7.029 16.971 3 12 3C7.029 3 3 7.029 3 12C3 16.971 7.029 21 12 21C13.149 21 14.317 20.782 15.444 20.315C16.052 20.063 16.614 19.747 17.133 19.386" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <a class="footer-link" href="mailto:<?php echo $officce['email']; ?>"><?php echo $officce['email']; ?></a>
                    </div>
                </div>
                <?php $storage = get_field('storage', 'options'); ?>
                <div class="footer__items">
                    <div class="footer-item">
                        <p class="footer-item__text"><strong><?php echo $storage['name']; ?></strong></p>
                    </div>
                    <div class="footer-item">
                        <div class="footer-address footer-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 13V13C10.343 13 9 11.657 9 10V10C9 8.343 10.343 7 12 7V7C13.657 7 15 8.343 15 10V10C15 11.657 13.657 13 12 13Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 21C12 21 5 15.25 5 10C5 6.134 8.134 3 12 3C15.866 3 19 6.134 19 10C19 15.25 12 21 12 21Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span><?php echo $storage['address']; ?></span>
                        </div>
                        <a class="footer-item__sublink link" href="#">Схема проезда</a>
                    </div>
                    <div class="footer-item footer-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.5 7V12.5H8" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span><?php echo $storage['worktime']; ?></span>
                    </div>
                    <div class="footer-item footer-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.8581 13.1463C9.6881 11.9763 8.8061 10.6663 8.2201 9.33731C8.0961 9.05631 8.1691 8.72731 8.3861 8.51031L9.2051 7.69231C9.8761 7.02131 9.8761 6.07231 9.2901 5.48631L8.1161 4.31231C7.3351 3.53131 6.0691 3.53131 5.2881 4.31231L4.6361 4.96431C3.8951 5.70531 3.5861 6.77431 3.7861 7.83431C4.2801 10.4473 5.7981 13.3083 8.2471 15.7573C10.6961 18.2063 13.5571 19.7243 16.1701 20.2183C17.2301 20.4183 18.2991 20.1093 19.0401 19.3683L19.6911 18.7173C20.4721 17.9363 20.4721 16.6703 19.6911 15.8893L18.5181 14.7163C17.9321 14.1303 16.9821 14.1303 16.3971 14.7163L15.4941 15.6203C15.2771 15.8373 14.9481 15.9103 14.6671 15.7863C13.3381 15.1993 12.0281 14.3163 10.8581 13.1463Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <a class="footer-link" href="tel:<?php echo getPhoneHref($storage['phone']); ?>"><?php echo $storage['phone']; ?></a>
                    </div>
                </div>
            </div>

            <nav class="footer-nav">
                <div class="footer-nav-item">
                    <h6 class="footer-col__title text-l mb-2">
                        Каталог
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.5 1L8.5 6L3.5 11" stroke="#127FFF" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </h6>
                    <?php
                    wp_nav_menu(array(
                        'menu'            => '', // id menu
                        'container'       => 'div',
                        'container_class' => 'footer-category',
                        'container_id'    => 'footer-category',
                        'menu_class'      => 'footer-menu-category ',
                        'menu_id'         => 'footer-menu-category',
                        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth'           => 0,
                    ));
                    ?>
                </div>
                <div class="footer-nav-item">
                    <h6 class="footer-col__title text-l mb-2">
                        Информация о компании
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.5 1L8.5 6L3.5 11" stroke="#127FFF" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </h6>
                    <?php
                    wp_nav_menu(array(
                        'menu'            => '', // id menu
                        'container'       => 'div',
                        'container_class' => 'footer-menu',
                        'container_id'    => 'footer-menu',
                        'menu_class'      => 'footer-menu-pages',
                        'menu_id'         => 'footer-menu-pages',
                        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth'           => 0,
                    ));
                    ?>
                </div>
            </nav>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer__inner footer-bottom__inner">
                <div class="footer-col-left">
                    <a class="footer-link" href="#">Политика конфиденциальности</a>
                    <a class="footer-link" href="#">Юридическая информация</a>
                </div>
                <div class="footer-col madein">
                    <a href="https://impulse-marketing.ru/" target="_blank">
                        <span class="text-s">Разработка сайта</span>
                        <svg width="127" height="26" viewBox="0 0 127 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 5.23438H2.94227V25.8159H0V5.23438Z" fill="black" />
                            <path d="M49.5628 7.07356C50.2974 7.69677 50.8786 8.48064 51.2613 9.36443C51.644 10.2482 51.8179 11.2082 51.7695 12.1699C51.8159 13.1279 51.641 14.0836 51.2583 14.9631C50.8757 15.8427 50.2955 16.6223 49.5628 17.2418C47.8153 18.5561 45.6574 19.2074 43.474 19.0794H37.5976V25.8174H36.0938V5.23593H43.4494C45.6411 5.10209 47.8087 5.75365 49.5628 7.07356ZM48.5085 16.2862C49.0894 15.7823 49.5475 15.1525 49.8478 14.4448C50.1481 13.7371 50.2826 12.9702 50.2412 12.2026C50.2859 11.4286 50.153 10.6547 49.8527 9.93989C49.5524 9.22508 49.0927 8.58828 48.5085 8.07813C47.0553 7.01279 45.2721 6.49498 43.474 6.6162H37.5976V17.7318H43.4821C45.2751 17.8516 47.0535 17.3401 48.5085 16.2862Z" fill="black" />
                            <path d="M57.9393 23.6842C56.5008 22.1733 55.7734 19.9681 55.7734 17.0687V5.23438H57.2773V17.0197C57.2773 19.5516 57.8548 21.4573 59.0099 22.7368C59.6673 23.3899 60.4562 23.8959 61.3242 24.2212C62.1922 24.5465 63.1194 24.6836 64.0445 24.6234C64.9646 24.6836 65.887 24.5464 66.7497 24.221C67.6124 23.8956 68.3955 23.3895 69.0464 22.7368C70.2069 21.4872 70.779 19.5924 70.779 17.0606V5.23438H72.2829V17.0851C72.2829 19.9817 71.5582 22.1869 70.1089 23.7005C69.3048 24.479 68.3476 25.0821 67.2981 25.4715C66.2485 25.861 65.1295 26.0283 64.0118 25.9629C62.8966 26.0267 61.7804 25.857 60.7347 25.4646C59.6889 25.0722 58.7368 24.4658 57.9393 23.6842Z" fill="black" />
                            <path d="M78.375 5.23438H79.8788V24.5009H91.7378V25.8567H78.375V5.23438Z" fill="black" />
                            <path d="M96.7816 25.1824C95.5753 24.7684 94.4815 24.0807 93.5859 23.1732L94.2643 22.0543C95.0899 22.8854 96.089 23.524 97.1902 23.9246C98.4254 24.4035 99.7393 24.6472 101.064 24.6433C102.625 24.7515 104.18 24.3595 105.502 23.5244C105.974 23.2018 106.358 22.7673 106.62 22.2599C106.883 21.7525 107.015 21.188 107.006 20.6169C107.024 20.2134 106.96 19.8105 106.82 19.4318C106.68 19.0532 106.465 18.7063 106.189 18.4117C105.644 17.8511 104.979 17.4211 104.244 17.154C103.203 16.787 102.141 16.4815 101.064 16.2392C99.8306 15.9528 98.6159 15.59 97.4272 15.153C96.5303 14.8087 95.7328 14.2474 95.1061 13.5195C94.4336 12.6855 94.093 11.6326 94.1499 10.563C94.1487 9.58819 94.4328 8.63432 94.9672 7.8188C95.569 6.92851 96.4226 6.23757 97.4191 5.83416C98.714 5.30364 100.107 5.05313 101.506 5.09911C102.641 5.0981 103.77 5.266 104.856 5.59731C105.874 5.89226 106.838 6.35012 107.709 6.95307L107.063 8.16999C106.229 7.60086 105.31 7.16547 104.342 6.87957C103.393 6.59189 102.407 6.44334 101.416 6.43854C99.8853 6.33426 98.3632 6.73606 97.084 7.58195C96.6228 7.92144 96.2494 8.36611 95.9949 8.87895C95.7404 9.3918 95.6122 9.95797 95.621 10.5303C95.6033 10.9338 95.6665 11.3367 95.8069 11.7154C95.9472 12.0941 96.1619 12.4409 96.4383 12.7355C96.9905 13.301 97.664 13.7339 98.408 14.0014C99.4581 14.3725 100.528 14.686 101.612 14.9406C102.841 15.2244 104.05 15.5873 105.232 16.0269C106.123 16.3796 106.915 16.9397 107.545 17.6603C108.216 18.473 108.557 19.5077 108.502 20.5597C108.507 21.5326 108.223 22.485 107.684 23.2957C107.063 24.1803 106.196 24.8643 105.192 25.264C103.894 25.7862 102.503 26.0364 101.105 25.9991C99.6257 25.9968 98.1597 25.7199 96.7816 25.1824Z" fill="black" />
                            <path d="M127.002 24.5009V25.8567H112.789V5.23438H126.561V6.58197H114.301V14.7002H125.277V16.0233H114.285V24.5009H127.002Z" fill="black" />
                            <path d="M30.3091 5.23438H27.8981L19.4227 19.6986L10.9146 5.36505L8.42188 10.0939V25.8159H11.2497V10.9106L18.6871 23.3167H20.0439L27.4895 10.8208L27.514 25.8159H30.3418L30.3091 5.23438Z" fill="black" />
                            <path d="M6.89325 6.60731H5.59375L6.72162 0H10.3668L6.89325 6.60731Z" fill="black" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
</div> <!-- wrapper end -->
<?php wp_footer(); ?>