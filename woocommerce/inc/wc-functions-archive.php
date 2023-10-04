<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Добавление контенера
 */
add_action('woocommerce_before_main_content', 'wrapper_wc_before_shop_loop_start', 5);
function wrapper_wc_before_shop_loop_start()
{
    echo '<div class="container">';
}

add_action('woocommerce_after_main_content', 'wrapper_wc_no_products_found_end', 15);
function wrapper_wc_no_products_found_end()
{
    if (!is_product()) {
        add_seo_block();
    }
    echo '</div>';
}
/** 
 * Добавление контенера для товаров и пагинации
 */
add_filter('woocommerce_before_shop_loop', 'wc_before_shop_loop_wrapper_start', 40);
function wc_before_shop_loop_wrapper_start()
{
    echo '<div class="products-wrapper">';
}


/**
 * sidebar над каталог товаров
 */
add_filter('woocommerce_before_shop_loop', 'wc_products_sidebar_start', 25);
function wc_products_sidebar_start()
{
    echo '<div class="catalog-wrapper section">';
    echo filter_sidebar();
    echo '<div class="catalog-col">';
    echo '<div class="catalog-sidebar">';
    echo '<div class="catalog-sidebar-left">';
    echo '<div class="filter-nav catalog-sidebar__col">
            <button class="button filter-btn" type="button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 5H21" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 5H14" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 12H21" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 12H6" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18 19H21" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 19H14" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.4142 3.58579C18.1953 4.36684 18.1953 5.63317 17.4142 6.41422C16.6332 7.19527 15.3668 7.19527 14.5858 6.41422C13.8047 5.63317 13.8047 4.36684 14.5858 3.58579C15.3668 2.80474 16.6332 2.80474 17.4142 3.58579" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.41422 10.5858C10.1953 11.3668 10.1953 12.6332 9.41422 13.4142C8.63317 14.1953 7.36684 14.1953 6.58579 13.4142C5.80474 12.6332 5.80474 11.3668 6.58579 10.5858C7.36684 9.80474 8.63317 9.80474 9.41422 10.5858" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.4142 17.5858C18.1953 18.3668 18.1953 19.6332 17.4142 20.4142C16.6332 21.1953 15.3668 21.1953 14.5858 20.4142C13.8047 19.6332 13.8047 18.3668 14.5858 17.5858C15.3668 16.8047 16.6332 16.8047 17.4142 17.5858" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Фильтры
            </button>
            ' . do_shortcode('[fe_open_button]') . '
        </div>';
    echo '<div class="sorting catalog-sidebar__col catalog-sidebar-box"><span class="catalog-sidebar__text">Сортировка</span> ';
    // woocommerce_catalog_ordering();
    echo do_shortcode('[fe_sort id="3"]');
    echo '</div>';
    echo '<div class="filter-chips catalog-sidebar__col">' . do_shortcode('[fe_chips]') . '</div>';
    echo '</div>'; //.catalog-sidebar-left
    echo '<div class="view-mode catalog-sidebar-box">
            <span class="catalog-sidebar__text">Вид</span>
            <div class="view-mode-buttons">
                <button class="view-mode-btn active" id="btn-colums-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="5.4" height="5.4" rx="1" fill="black"/>
                        <rect x="9.29688" y="3" width="5.4" height="5.4" rx="1" fill="black"/>
                        <rect x="15.6016" y="3" width="5.4" height="5.4" rx="1" fill="black"/>
                        <rect x="3" y="9.29688" width="5.4" height="5.4" rx="1" fill="black"/>
                        <rect x="9.29688" y="9.29688" width="5.4" height="5.4" rx="1" fill="black"/>
                        <rect x="15.6016" y="9.29688" width="5.4" height="5.4" rx="1" fill="black"/>
                        <rect x="3" y="15.6016" width="5.4" height="5.4" rx="1" fill="black"/>
                        <rect x="9.29688" y="15.6016" width="5.4" height="5.4" rx="1" fill="black"/>
                        <rect x="15.6016" y="15.6016" width="5.4" height="5.4" rx="1" fill="black"/>
                    </svg>                
                </button>
                <button class="view-mode-btn" id="btn-colums-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="8.30769" height="8.30769" rx="1" fill="black"/><rect x="12.6953" y="3" width="8.30769" height="8.30769" rx="1" fill="black"/><rect x="3" y="12.6953" width="8.30769" height="8.30769" rx="1" fill="black"/><rect x="12.6953" y="12.6953" width="8.30769" height="8.30769" rx="1" fill="black"/>
                    </svg>
                </button>
            </div>
    </div>';
}
add_filter('woocommerce_after_shop_loop', 'wc__after_shop_loop_srapper_end', 20);
function wc__after_shop_loop_srapper_end()
{
    echo '</div>'; //.products-wrapper 
    echo '</div>'; // .catalog-wrapper
    echo '</div>'; // .catalog-col
}
add_filter('woocommerce_before_shop_loop', 'wc_products_sidebar_end', 33);
function wc_products_sidebar_end()
{
    echo '</div>';
}
/** 
 * product card
 */
add_action('woocommerce_shop_loop_item_title', 'wc_shop_loop_item_title_start', 5);
function wc_shop_loop_item_title_start()
{
    echo '<div class="product-card__content">';
}
add_action('woocommerce_after_shop_loop_item', 'wc_after_shop_loop_item_end', 15);
function wc_after_shop_loop_item_end()
{
    echo '</div>';
}

/** 
 * Замена текста кнопки для вариативного товара 
 */
add_filter('woocommerce_product_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text');
function custom_woocommerce_product_add_to_cart_text()
{
    global $product;
    // $product_type = $product->product_type;
    switch ($product->is_type('variable')) {
        case 'variable':
            return __('Подробнее', 'woocommerce');
            break;
    }
}



add_action('woocommerce_before_shop_loop_item_title', 'wc_flags_badge', 11);
function wc_flags_badge()
{
    ob_start();
    loop_product_qty();
    $badge_html = ob_get_clean();
    echo '<div class="card-flags">';
    echo  $badge_html;
    echo truemisha_sale_badge();
    echo '</div>';
}

/** 
 * Вывод процента скидки вместо sale_flash
 */

add_action('woocommerce_before_shop_loop_item_title', 'truemisha_sale_badge', 12);

function truemisha_sale_badge()
{
    // получаем объект текущего товара в цикле
    global $product;
    // есле не распродажа, ничего не делаем
    if (!$product->is_on_sale()) {
        return;
    }
    if ($product->is_type('simple')) { // простые товары
        // рассчитываем процент скидки
        $percentage = (($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100;
    } elseif ($product->is_type('variable')) { // вариативные товары
        $percentage = 0;
        // запускаем цикл для вариаций товара
        foreach ($product->get_children() as $variation_id) {
            // получаем объект вариации
            $variation = wc_get_product($variation_id);
            // не распродажа? пропускаем итерацию цикла
            if (!$variation->is_on_sale()) {
                continue;
            }
            // обычная цена вариации
            $regular_price = $variation->get_regular_price();
            // цена распродажи вариации
            $sale_price = $variation->get_sale_price();
            // процент скидки вариации
            $variation_percentage = ($regular_price - $sale_price) / $regular_price * 100;
            if ($variation_percentage > $percentage) {
                $percentage = $variation_percentage;
            }
        }
    }

    if ($percentage > 0) {
        $html = '<div class="flag-item sale">-' . round($percentage) . '%</div>';
    }
    return $html;
}

// Вывод кнопки "Добавить в корзину" для вариативных товаров на странице каталога
add_action('woocommerce_after_shop_loop_item', 'custom_add_to_cart_button', 11);
function custom_add_to_cart_button()
{
    global $product;
    if ($product && $product->is_type('variable')) {
        // Получение первой вариации для вариативного товара
        $variations = $product->get_available_variations();
        if (!empty($variations)) {
            $variation = reset($variations);
            $variation_id = $variation['variation_id'];
            // Вывод кнопки "Добавить в корзину" для первой вариации
            // echo '<a href="#" data-quantity="1" class="button add-to-cart-button" data-product-id="' . $product->get_id() . '" data-variation-id="' . $variation_id . '"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.5005 7H10.5H5.875L7.41 14.246C7.621 15.244 8.55 15.923 9.566 15.822L16.382 15.14C17.222 15.056 17.918 14.454 18.123 13.635L18.5961 11.7426" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.874 7L5.224 4H3.5" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.1074 19.2668C16.9054 19.2668 16.7414 19.4308 16.7434 19.6328C16.7434 19.8348 16.9074 19.9988 17.1094 19.9988C17.3114 19.9988 17.4754 19.8348 17.4754 19.6328C17.4744 19.4308 17.3104 19.2668 17.1074 19.2668" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.69382 19.2663C8.49182 19.2663 8.32782 19.4303 8.32982 19.6323C8.32782 19.8353 8.49282 19.9993 8.69482 19.9993C8.89682 19.9993 9.06082 19.8353 9.06082 19.6333C9.06082 19.4303 8.89682 19.2663 8.69382 19.2663" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19 5.25V8.75" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.75 7H17.25" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>';
        }
    }
}
// Добавление вариативного товара в корзину с выбранной первой вариацией
function add_variation_to_cart()
{
    if (isset($_POST['product_id']) && isset($_POST['variation_id'])) {
        $product_id = absint($_POST['product_id']);
        $variation_id = absint($_POST['variation_id']);

        if (wc_get_product($product_id) && wc_get_product($variation_id)) {
            WC()->cart->add_to_cart($product_id, 1, $variation_id);
            echo 'Товар успешно добавлен в корзину.';
        }
    }
}
add_action('wp_ajax_add_variation_to_cart', 'add_variation_to_cart');
add_action('wp_ajax_nopriv_add_variation_to_cart', 'add_variation_to_cart');

/** 
 * Вывод атрибутов в loop product
 */
add_filter('woocommerce_shop_loop_item_title', 'wc_shop_loop_item_title_wrapper_start', 6);
function wc_shop_loop_item_title_wrapper_start()
{

    echo '<div class="product-card__head">';
}

add_filter('woocommerce_shop_loop_item_title', 'wc_shop_loop_item_title_wrapper_end', 10);
function wc_shop_loop_item_title_wrapper_end()
{
    global $product;

    $product_tags = get_the_terms($product->get_id(), 'product_tag');
    echo '<div class="product-tags">';
    foreach ($product_tags as $key => $tag) {
        // Проверяем, принадлежит ли текущая метка заданному товару
        echo '<span class="product-tag">' . $tag->name . '</span>';
    }
    echo '</div>';
    echo '</div>';
}

// woocommerce_template_loop_price - 10

add_action('woocommerce_after_shop_loop_item', 'add_wc_template_loop_price', 6);
function add_wc_template_loop_price()
{
    echo '<div class="product-card__footer">';
    woocommerce_template_loop_price();
}

add_action('woocommerce_after_shop_loop_item', 'wc_wrapper_buttons_start', 9);
function wc_wrapper_buttons_start()
{
    global $product;
    echo '<div class="product-card-buttons">';
    echo '<a href="' . esc_url($product->get_permalink($product->id)) . '" class="button product_type_simple add_to_cart_button add_to_cart_button_link">Подробнее</a>';
}

add_action('woocommerce_after_shop_loop_item', 'add_wc_template_loop_wrapepr_end', 11);
function add_wc_template_loop_wrapepr_end()
{
    echo '</div>'; //.product-card__footer
    echo '</div>'; // .product-card-buttons
}




// filter sidebar
function filter_sidebar()
{ ?>
    <div class="catalog-filter">
        <div class="filter-head">
            <button class="button filter-btn filter-btn__is-filter" type="button">
                <span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 5H21" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M3 5H14" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M10 12H21" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M3 12H6" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M18 19H21" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M3 19H14" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M17.4142 3.58579C18.1953 4.36684 18.1953 5.63317 17.4142 6.41422C16.6332 7.19527 15.3668 7.19527 14.5858 6.41422C13.8047 5.63317 13.8047 4.36684 14.5858 3.58579C15.3668 2.80474 16.6332 2.80474 17.4142 3.58579" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.41422 10.5858C10.1953 11.3668 10.1953 12.6332 9.41422 13.4142C8.63317 14.1953 7.36684 14.1953 6.58579 13.4142C5.80474 12.6332 5.80474 11.3668 6.58579 10.5858C7.36684 9.80474 8.63317 9.80474 9.41422 10.5858" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M17.4142 17.5858C18.1953 18.3668 18.1953 19.6332 17.4142 20.4142C16.6332 21.1953 15.3668 21.1953 14.5858 20.4142C13.8047 19.6332 13.8047 18.3668 14.5858 17.5858C15.3668 16.8047 16.6332 16.8047 17.4142 17.5858" stroke="#127FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Фильтры
                </span>
                <span class="filter-btn-close text-s">Свернуть</span>
            </button>
            <?php echo do_shortcode('[fe_open_button]'); ?>
        </div>
        <div class="wpc-filters-section wpc-filters-section-27762 wpc-filter-product_cat wpc-filter-taxonomy wpc-filter-layout-radio wpc-filter-full-height wpc-filter-visible-term-names" data-fid="27762">
            <div class="wpc-filter-header">
                <div class="widget-title wpc-filter-title">
                    Категория </div>
            </div>
            <div class="wpc-filter-content wpc-filter-product_cat">
                <ul class="wpc-filters-ul-list wpc-filters-radio wpc-filters-list-27762">
                    <?php
                    $current_category = get_queried_object();

                    // Получить все категории товаров
                    $product_categories = get_terms('product_cat', array(
                        'hide_empty' => 1,
                    ));

                    // Отфильтровать только родительские категории
                    $parent_categories = array_filter($product_categories, function ($category) {
                        return $category->parent === 0;
                    });

                    // Вывести родительские категории
                    foreach ($parent_categories as $category) {
                        $cat_id = $category->term_id;
                        $category_link = get_term_link($category); // Получить ссылку на категорию
                        $active_class = ($current_category && $current_category->term_id == $category->term_id) ? 'active' : ''; // Добавить класс "active" для текущей категории
                    ?>
                        <li class="filter-category-item">
                            <a class="filter-category__link <?php echo $active_class; ?>" href="<?php echo $category_link; ?>">
                                <?php echo $category->name; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <?php echo do_shortcode('[fe_widget]'); ?>
    </div>
<?php }

require get_template_directory() . '/woocommerce/inc/wc-shop-per-page.php';

/** 
 * Добавление класса li.product
 */
add_filter('post_class', 'add_class_product_card', 10, 3);

function add_class_product_card($classes)
{
    if (is_front_page() || is_product()) {
        $classes[] = 'swiper-slide';
    }
    return $classes;
}

/** 
 * add seo block
 */
function add_seo_block()
{
    $category_id = get_queried_object()->term_id;
    $category_thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
    $thumbnail_image_url = wp_get_attachment_url($category_thumbnail_id);
    $seo_title = get_field('seo_title', get_queried_object());
    $seo_text = get_field('seo_text', get_queried_object());
?>
    <div class="seoblock section">
        <div class="seoblock__content">
            <h3 class="seoblock__title title mb-4"><?php echo $seo_title; ?></h3>
            <p class="seoblock__text text-l"><?php echo $seo_text; ?></p>
        </div>
        <div class="seoblock__img">
            <img src="<?php echo $thumbnail_image_url; ?>" alt="">
        </div>
    </div>

    <?php
}

// add_filter( 'woocommerce_loop_add_to_cart_link', 'custom_product_link' );
// function custom_product_link( $link ) {
// global $product;
//     echo '<a href="'.esc_url( $product->get_permalink( $product->id )).'" class="button product_type_simple add_to_cart_button add_to_cart_button_link">Подробнее</a>';
// }

// add_filter( 'woocommerce_loop_add_to_cart_link', 'custom_add_btn_class' ,10,2);
// function custom_add_btn_class( $args) {
//     $args['class'] = 'add-to-cart-button';
//     return $args;

// }
add_filter('woocommerce_loop_add_to_cart_link', 'custom_add_to_cart_link_class', 10, 3);
function custom_add_to_cart_link_class($link, $product, $args)
{

    // Добавить свой класс к ссылке
    $custom_class = 'add-to-cart-button';
    // Получить атрибуты из переданных аргументов
    $attributes = isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '';
    // Заменить текст ссылки на SVG-код
    $updated_link = str_replace(esc_html($product->add_to_cart_text()), '', $link);
    // Заменить класс в ссылке
    $updated_link = str_replace('class="', 'class="' . $custom_class . ' ', $updated_link);
    // Добавить другие атрибуты к обновленной ссылке (при необходимости)
    $updated_link = str_replace('>', ' ' . $attributes . '>', $updated_link);
    // Вернуть обновленную ссылку
    return $updated_link;
}



// remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
// add_action('woocommerce_after_shop_loop_item', 'wc_template_loop_custom_add_to_cart', 10);
// function wc_template_loop_custom_add_to_cart()
// {
//     global $product;
//     if ($product->get_type('simple')) {
//         $svg_path = get_template_directory() . '/assets/images/icons/basket-btn.svg';
//         $svg_code = file_get_contents($svg_path);
    // <a href="?add-to-cart=<?= $product->get_id(); "data-quantity="1" 
    //     class="button add-to-cart-button add-to-cart-button button wp-element-button product_type_simple add_to_cart_button ajax_add_to_cart" 
    //     data-product-id="<?= $product->get_id(); "
    //     data-product_sku="<?=$product->get_sku();"
    //     aria-label="Добавить «<?= $product->get_name();» в корзину"
    //     rel="nofollow">
    //         <?= $svg_code;
    //     </a> 

//     }
// }
