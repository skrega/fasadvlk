<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package estore
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body>
    <?php $header = is_front_page() ? ' transparent' : ''; ?>
    <header class="header<?php echo $header; ?>">
        <div class="header-top">
            <div class="container">
                <div class="header__inner">
                    <?php
                    wp_nav_menu(array(
                        'menu'            => '890', // id menu
                        'container'       => 'div',
                        'container_class' => 'header-top-menu',
                        'container_id'    => 'header-top-menu',
                        'menu_class'      => 'header-menu-pages header-col header-menu',
                        'menu_id'         => 'header-menu-pages',
                        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth'           => 0,
                    ));
                    ?>
                    <div class="header__info header-col">
                        <div class="header__text">Владивосток</div>
                        <?php $officce = get_field('officce', 'options'); ?>
                        <a class="header__link header__text" href="tel:<?php echo getPhoneHref($officce['phones']['phone']); ?>"><?php echo $officce['phones']['phone']; ?></a>
                    </div>
                </div>
            </div>
        </div>
        <nav class="header-bottom">
            <div class="container">
                <div class="header__inner">
                    <div class="header-col">
                        <div class="header-logo">
                            <?php if (is_front_page() || is_home()) { ?>
                                <a href="<?php echo get_home_url(); ?>">
                                    <img src="<?php the_post_thumbnail_url(); ?>" alt="logo">
                                </a>
                            <?php } else {
                                the_custom_logo();
                            } ?>
                        </div>
                        <?php
                        wp_nav_menu(array(
                            'menu'            => '48', // id menu
                            'container'       => 'div',
                            'container_class' => 'header-bottom-menu',
                            'container_id'    => 'header-bottom-menu',
                            'menu_class'      => 'header-menu-category header-menu header-col',
                            'menu_id'         => 'header-menu-category',
                            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            'depth'           => 0,
                        ));
                        ?>
                    </div>
                    <div class="header-col header-search-cart">
                        <button class="header-search-btn" type="button">
                            <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.9569 9.93531C24.2248 13.2032 24.2248 18.5015 20.9569 21.7694C17.689 25.0373 12.3907 25.0373 9.12281 21.7694C5.8549 18.5015 5.8549 13.2032 9.12281 9.93531C12.3907 6.6674 17.689 6.6674 20.9569 9.93531" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M25.3398 26.1523L20.9531 21.7656" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <?php store_woocommerce_cart_link(); ?>
                        <button class="btn-menu visible-md" type="button">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <div class="wrapper">
        <?php $main_class = is_front_page() ? '' : ' inside'; ?>
        <div class="main<?php echo $main_class; ?>">
            <?php get_template_part('includes/components/navbar'); ?>