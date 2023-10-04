<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Добавляем две таблицы в корзину
add_action('woocommerce_before_cart_table', 'custom_split_cart_table', 10);

function custom_split_cart_table()
{
    echo '<table class="cart-table-1">';
    echo '<tr><th>Товар</th><th>Цена</th></tr>';

    $zero_price_products = array();

    // Перебираем товары в корзине
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $price = $product->get_price();

        if ($price > 0) {
            // Выводим товары с ценой > 0
            echo '<tr>';
            echo '<td>' . $product->get_title() . '</td>';
            echo '<td>' . wc_price($price) . '</td>';
            echo '</tr>';
        } else {
            // Сохраняем товары с ценой 0 для вывода во второй таблице
            $zero_price_products[] = $cart_item;
        }
    }

    echo '</table>';

    if (!empty($zero_price_products)) {
        echo '<table class="cart-table-2">';
        echo '<tr><th>Товар</th><th>Цена</th></tr>';

        foreach ($zero_price_products as $cart_item) {
            $product = $cart_item['data'];

            echo '<tr>';
            echo '<td>' . $product->get_title() . '</td>';
            echo '<td>Индивидуальный расчет</td>';
            echo '</tr>';
        }

        echo '</table>';
    }
}
