<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10); // удаление sidebar
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10); // удаляем скидку
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10); // удаляем price
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20); // удаляем вывод кооличества товара на странице
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30); // удаляем сортировку 
remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10); // удаляем вывод описания категории
remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10); // удаляем вывод описания категории
