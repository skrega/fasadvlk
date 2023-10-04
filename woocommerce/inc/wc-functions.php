<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function custom_product_shortcode($atts)
{
    $output = '';

    // Параметры шорткода
    $atts = shortcode_atts(array(
        'type'    => 'variable', // Тип товара: variable (вариативные) или simple (простые)
        'city'    => 'Владивосток', // Название города
        'limit'   => -1, // Количество выводимых товаров (-1 для вывода всех товаров)
    ), $atts, 'custom_products');

    // Аргументы запроса для получения товаров
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $atts['limit'],
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'     => '_variation_city',
                    'value'   => $atts['city'],
                    'compare' => '!=',
                ),
                array(
                    'key'     => '_variation_city',
                    'value'   => null,
                    'compare' => 'NOT EXISTS',
                ),
            ),
        ),
        'meta_key'       => 'total_sales', // Сортировка по количеству продаж (популярности)
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );

    // Условие для выборки типа товара
    if ($atts['type'] === 'variable') {
        $args['meta_query'][] = array(
            'key'     => '_product_attributes',
            'value'   => 'a:', // Проверка наличия атрибутов у товара
            'compare' => 'LIKE',
        );
    } elseif ($atts['type'] === 'simple') {
        $args['meta_query'][] = array(
            'key'     => '_product_attributes',
            'compare' => 'NOT EXISTS',
        );
    }

    // Получение товаров
    $products = new WP_Query($args);

    // Генерация HTML-вывода
    if ($products->have_posts()) {
        ob_start(); // Начало буферизации вывода

        // Подключение шаблона loop-start.php
        wc_get_template('loop/loop-start.php');

        while ($products->have_posts()) {
            $products->the_post();
            global $product;
            if ($product->is_type('variable')) {
                $variations = $product->get_available_variations();
                foreach ($variations as $variation) {
                    // Проверка условий
                    if ($variation['max_qty'] !== '' && 'pod-zakaz' !== $variation["attributes"]["attribute_pa_city"] && 'moscow' !== $variation["attributes"]["attribute_pa_city"]) {
                        // Подключение шаблона content-product.php для вариаций, удовлетворяющих условиям
                        wc_get_template_part('content', 'product');
                        break;
                    }
                }
            } else {
                // Подключение шаблона content-product.php для простых товаров
                wc_get_template_part('content', 'product');
            }
        }
        // Подключение шаблона loop-end.php
        wc_get_template('loop/loop-end.php');

        $output = ob_get_clean(); // Конец буферизации вывода
    } else {
        $output .= 'Нет товаров, удовлетворяющих условиям.';
    }

    wp_reset_query();

    return $output;
}
add_shortcode('custom_products', 'custom_product_shortcode');

function ccustom_products_out_of_stock_shortcode($atts)
{
    $output = '';

    // Параметры шорткода
    $atts = shortcode_atts(array(
        'type'    => 'variable', // Тип товара: variable (вариативные) или simple (простые)
        'city'    => 'Под заказ', // Название города
        'limit'   => -1, // Количество выводимых товаров (-1 для вывода всех товаров)
    ), $atts, 'custom_products');

    // Аргументы запроса для получения товаров
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $atts['limit'],
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'     => '_variation_city',
                    'value'   => $atts['city'],
                    'compare' => '!=',
                ),
                array(
                    'key'     => '_variation_city',
                    'value'   => null,
                    'compare' => 'NOT EXISTS',
                ),
            ),
        ),
        'meta_key'       => 'total_sales', // Сортировка по количеству продаж (популярности)
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );

    // Условие для выборки типа товара
    if ($atts['type'] === 'variable') {
        $args['meta_query'][] = array(
            'key'     => '_product_attributes',
            'value'   => 'a:', // Проверка наличия атрибутов у товара
            'compare' => 'LIKE',
        );
    } elseif ($atts['type'] === 'simple') {
        $args['meta_query'][] = array(
            'key'     => '_product_attributes',
            'compare' => 'NOT EXISTS',
        );
    }

    // Получение товаров
    $products = new WP_Query($args);

    // Генерация HTML-вывода
    if ($products->have_posts()) {
        ob_start(); // Начало буферизации вывода

        // Подключение шаблона loop-start.php
        wc_get_template('loop/loop-start.php');

        while ($products->have_posts()) {
            $products->the_post();
            global $product;
            $count = 0;
            if ($product->is_type('variable')) {
                $variations = $product->get_available_variations();
                foreach ($variations as $variation) {
                    // Проверка условий
                    if ($variation['max_qty'] !==  '' && 'pod-zakaz' !== $variation["attributes"]["attribute_pa_city"]) {
                        // echo $variation['availability_html']
                        $count += 1;
                    } else {
                        // Подключение шаблона content-product.php для вариаций, удовлетворяющих условиям
                        wc_get_template_part('content', 'product');
                    }
                    break;
                }
            } else {
                // Подключение шаблона content-product.php для простых товаров
                wc_get_template_part('content', 'product');
            }
        }
        // Подключение шаблона loop-end.php
        wc_get_template('loop/loop-end.php');

        $output = ob_get_clean(); // Конец буферизации вывода
    } else {
        $output .= 'Нет товаров, удовлетворяющих условиям.';
    }

    wp_reset_query();

    return $output;
}
add_shortcode('custom_products_out_of_stock', 'ccustom_products_out_of_stock_shortcode');
