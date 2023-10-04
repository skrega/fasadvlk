<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Контенейр для изображения и описания (summary)
add_filter('woocommerce_before_single_product_summary', 'wrapper_single_product_top_strat', 5);
function wrapper_single_product_top_strat()
{
    echo '<div class="product__inner">';
}
add_filter('woocommerce_after_single_product_summary', 'wrapper_single_product_top_end', 5);
function wrapper_single_product_top_end()
{
    echo '</div>';
}

// скрываем всю мету  
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// Вывод атрибутов
add_action('woocommerce_single_product_summary', 'wc_show_attributes', 40);
function wc_show_attributes()
{
    global $product;
    echo '<div class="product-attributes">';
    $product->list_attributes();
    echo '<a class="summary-btn btn-sm btn outline text-s" href="#">Все характеристики</a>';
    echo '</div>';
}

// вывод дополнительных кнопок для в summary
add_action('woocommerce_single_product_summary', 'wc_product_buttons', 45);
function wc_product_buttons()
{
    // global $product;
    echo '<div class="product-buttons">';
    echo '<a class="product-btn btn" href="#">
            Примерить этот фасад
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.7137 8.97219V3.99219C19.7137 3.43219 19.2637 2.99219 18.7137 2.99219H16.8538H16.8438C16.2837 2.99219 15.8438 3.43219 15.8438 3.99219V5.66219" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 10.0711L10.698 3.47313H10.688C11.428 2.82313 12.538 2.82313 13.288 3.46313L20.978 10.0531" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.5 8.78906V18.9891C4.5 20.0891 5.39 20.9791 6.5 20.9791H8.99" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19.05 12.385L18.5 12.935L19.04 12.375" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.5233 16.215L16.9933 20.625L16.9833 20.615C17.0733 20.895 17.3833 21.045 17.6633 20.955C17.7833 20.905 17.8833 20.825 17.9533 20.715L18.8533 19.195V19.185C18.9333 19.045 19.0433 18.925 19.1833 18.845L20.6933 17.935V17.925C20.9433 17.765 21.0233 17.435 20.8733 17.175C20.8033 17.055 20.6933 16.975 20.5733 16.935L16.1533 15.455L16.1433 15.445C15.8533 15.345 15.5433 15.495 15.4533 15.785C15.4133 15.895 15.4133 16.015 15.4433 16.125L15.5233 16.215Z" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.6719 20.6719L19.1719 19.1719" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.7109 11L15.7209 11.78" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.3772 12.3828L12.9272 12.9228L12.3672 12.3828" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.0022 15.7075L11.7722 15.6875L10.9922 15.6975" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.3772 19.05L12.9272 18.5L12.3672 19.04" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>';
    echo '<button class="product-btn btn" data-src="#popup-sales" data-fancybox>
            Связаться с отделом продаж
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.3235 13.363C15.4685 13.363 14.6595 13.174 13.9325 12.837L10.4375 13.579L11.1685 10.077C10.8295 9.348 10.6395 8.535 10.6395 7.678C10.6395 4.543 13.1815 2 16.3185 2C19.4555 2 21.9965 4.543 21.9965 7.678C21.9965 10.813 19.4535 13.356 16.3185 13.356" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.2001 18.368L14.2301 17.398C13.7451 16.913 12.9601 16.913 12.4761 17.398L11.7141 18.16C11.5441 18.33 11.2861 18.387 11.0661 18.291C9.9611 17.807 8.8701 17.074 7.8971 16.101C6.9281 15.132 6.1971 14.046 5.7131 12.945C5.6121 12.718 5.6711 12.451 5.8471 12.274L6.5301 11.591C7.0851 11.036 7.0851 10.252 6.6001 9.767L5.6291 8.797C4.9831 8.151 3.9371 8.151 3.2911 8.797L2.7521 9.336C2.1391 9.949 1.8841 10.833 2.0491 11.709C2.4571 13.869 3.7131 16.235 5.7371 18.26C7.7611 20.285 10.1271 21.54 12.2881 21.948C13.1641 22.113 14.0481 21.858 14.6611 21.245L15.2001 20.707C15.8451 20.061 15.8451 19.014 15.2001 18.368Z" stroke="#6CAEFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.9259 7.08196C14.3164 7.47248 14.3164 8.10565 13.9259 8.49617C13.5353 8.88669 12.9022 8.88669 12.5116 8.49617C12.1211 8.10565 12.1211 7.47248 12.5116 7.08196C12.9022 6.69143 13.5353 6.69143 13.9259 7.08196Z" fill="#6CAEFF"/><path d="M16.9259 7.08196C17.3164 7.47248 17.3164 8.10565 16.9259 8.49617C16.5353 8.88669 15.9022 8.88669 15.5116 8.49617C15.1211 8.10565 15.1211 7.47248 15.5116 7.08196C15.9022 6.69143 16.5353 6.69143 16.9259 7.08196Z" fill="#6CAEFF"/><path d="M19.9259 7.08196C20.3164 7.47248 20.3164 8.10565 19.9259 8.49617C19.5353 8.88669 18.9022 8.88669 18.5116 8.49617C18.1211 8.10565 18.1211 7.47248 18.5116 7.08196C18.9022 6.69143 19.5353 6.69143 19.9259 7.08196Z" fill="#6CAEFF"/></svg>
          </button>';
    echo '</div>';
}


// Отображаем процент скидки в карточке товара
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

// add_action('woocommerce_format_sale_price', 'truemisha_discount_percentage', 10, 3);

// function truemisha_discount_percentage($price, $regular_price, $sale_price)
// {
//     // вычисляем процент скидки
//     $percentage = round(($regular_price - $sale_price) / $regular_price * 100) . '%';
//     // сообщение об экономии, можете стилизовать его при помощи CSS
//     $percentage_message = '<span class="tag sale-tag">' . $percentage . '</span>';
//     // возвращаем результат
//     return $percentage_message;
// }

add_action('woocommerce_single_product_summary', 'wc_tag_single_product', 7);
function wc_tag_single_product()
{
    global $product;
    $stock_status = $product->get_stock_status();
    $stock_status_html = ($stock_status == 'instock') ? '<span class="tag is-stock-tag">В наличии</span>' : '<span class="tag out-stock-tag">Под заказ</span>';
    // $stock_status_html = '<span class="tag is-stock-tag">' . $stock_status . '</span>';
    $sale_html = '';
    // вычисляем процент скидки
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    if ($sale_price) {
        $percentage = round(($regular_price - $sale_price) / $regular_price * 100) . '%';
        // сообщение об экономии, можете стилизовать его при помощи CSS
        $sale_html = '<span class="tag sale-tag">-' . $percentage . '</span>';
    }

    echo '<div class="single-product-tag">
    ' . $stock_status_html . $sale_html . '
    </div>';
}



// Удаляем стандартный вывод изображения и заменяем на свой 
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

add_action('woocommerce_before_single_product_summary', 'wc_product_images', 20);
function wc_product_images()
{
    // Получаем объект товара по его ID
    $product_id = get_the_ID(); // ID текущего товара
    $product = wc_get_product($product_id);
    // Получаем галерею изображений товара
    $attachment_ids = $product->get_gallery_image_ids();
    $main_img = wp_get_attachment_url($product->get_image_id());
    // Выводим изображения товара
    if (count($attachment_ids) > 0) { ?>
        <div class="product-images">
            <div class="product-image product-images-swiper swiper">
                <div class="swiper-wrapper">
                    <?php
                    echo ' <div class="swiper-slide"><img class="product-img" src="' . $main_img . '" alt="' . $product->get_image_id() . '" /></div>';
                    foreach ($attachment_ids as $attachment_id) {
                        // Получаем URL изображения
                        $image_url = wp_get_attachment_url($attachment_id);
                        // Выводим HTML-код изображения
                        echo ' <div class="swiper-slide"><img class="product-img" src="' . $image_url . '" alt="' . $attachment_id . '" /></div>';
                    } ?>
                </div>
                <div class="swiper-button-prev product-swiper-btn">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.5 1L3.5 6L8.5 11" stroke="#127FFF" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="swiper-button-next product-swiper-btn">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.5 1L8.5 6L3.5 11" stroke="#127FFF" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
            <!-- thumbs -->
            <div class="product-thumbs swiper" thumbsSlider="">
                <div class="swiper-wrapper">
                    <?php
                    echo ' <div class="swiper-slide product-thumb"><img class="product-img" src="' . $main_img . '" alt="' . $product->get_image_id() . '" /></div>';
                    foreach ($attachment_ids as $attachment_id) {
                        $image_url = wp_get_attachment_url($attachment_id);
                        echo ' <div class="swiper-slide product-thumb"><img class="product-img" src="' . $image_url . '" alt="' . $attachment_id . '" /></div>';
                    } ?>
                </div>
            </div>
        </div>
    <?php
    } else {
        echo '<figure class="product-image">
                <div class="product-main-img">
                    <img src="' . $main_img . '" alt="' . $product->get_image_id() . '"/>
                </div>';
        echo '</figure>';
    }
}

// изменить текст кнопки в корзину 
add_filter('add_to_cart_text', 'woo_custom_single_add_to_cart_text');                // < 2.1
add_filter('woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text');  // 2.1 +

function woo_custom_single_add_to_cart_text()
{
    return __('Добавить в корзину', 'woocommerce');
}

// удаляем вывод related products
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

// изменяме количетсво выводимиых товаров в карточке товара
add_filter('woocommerce_output_related_products_args', 'jk_related_products_args');
function jk_related_products_args($args)
{

    $args['posts_per_page'] = 6; // количество "Похожих товаров"
    $args['columns'] = 6; // количество колонок
    return $args;
}

add_action('woocommerce_after_single_product', 'add_popup_single_product', 10);
function add_popup_single_product()
{ ?>
    <div id="popup-sales" class="popup popup-sales" style="display: none;">
        <h4 class="title-md mb-2">Связаться с отделом продаж</h4>
        <p class="mb-4">Оставьте заявку и мы вам презвоним</p>

    </div>
<?php
}

// Проверям товар в корзине
add_filter('woocommerce_product_add_to_cart_text', 'change_add_to_cart_text', 10, 2);
add_filter('woocommerce_product_single_add_to_cart_text', 'change_add_to_cart_text', 10, 2);

function change_add_to_cart_text($text, $product)
{
    // global $product;
    $product_id = get_the_ID(); // ID текущего товара
    // $product = wc_get_product($product_id);
    if (WC()->cart->find_product_in_cart(WC()->cart->generate_cart_id($product_id)) && $product->get_id() == $product_id) {
        $text = 'В корзине';
    }

    return $text;
}

add_filter('woocommerce_loop_add_to_cart_link', 'add_custom_class_to_add_to_cart_button', 10, 2);

function add_custom_class_to_add_to_cart_button($button, $product)
{
    $product_id = get_the_ID(); // ID текущего товара
    if (WC()->cart->find_product_in_cart(WC()->cart->generate_cart_id($product_id)) && $product->get_id() == $product_id) {
        $button = str_replace('add_to_cart_button', 'add_to_cart_button added', $button);
    }

    return $button;
}

