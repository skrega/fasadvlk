<?php
// правильный способ подключить стили и скрипты
add_action('wp_enqueue_scripts', function () {

    wp_enqueue_style('fonts-googleapis', 'https://fonts.googleapis.com');
    wp_enqueue_style('fonts-google', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
    wp_enqueue_style('style-woo', get_template_directory_uri() . '/woocommerce/assets/css/woocommerce.css?' . time(), array(), null);
    wp_enqueue_style('style-main', get_template_directory_uri() . '/assets/css/style.min.css?' . time(), array(), null);

    // wp_deregister_script('jquery');
    // wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
    // wp_enqueue_script( 'jquery' );

    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/main.min.js', array('jquery'), 'null', true);
});

add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');
add_filter('upload_mimes', 'svg_upload_allow');
add_theme_support('menus');
add_theme_support('woocommerce');

function svg_upload_allow($mimes)
{
    $mimes['svg']  = 'image/svg+xml';

    return $mimes;
}

add_filter('wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5);

function fix_svg_mime_type($data, $file, $filename, $mimes, $real_mime = '')
{

    // WP 5.1 +
    if (version_compare($GLOBALS['wp_version'], '5.1.0', '>='))
        $dosvg = in_array($real_mime, ['image/svg', 'image/svg+xml']);
    else
        $dosvg = ('.svg' === strtolower(substr($filename, -4)));

    // mime тип был обнулен, поправим его
    // а также проверим право пользователя
    if ($dosvg) {

        // разрешим
        if (current_user_can('manage_options')) {

            $data['ext']  = 'svg';
            $data['type'] = 'image/svg+xml';
        }
        // запретим
        else {
            $data['ext'] = $type_and_ext['type'] = false;
        }
    }

    return $data;
}

// Global ACF Options Page
include("includes/global/options.php");
include("includes/global/theme-functions.php");

// document title 
add_filter('document_title', 'wp_kama_document_title_filter');

/**
 * Function for `document_title` filter-hook.
 * 
 * @param string $title Document title.
 *
 * @return string
 */
function wp_kama_document_title_filter($title)
{
    // filter...
    return $title;
}

// include 'includes/post-types/post-types.php';

// include 'includes/functions/form.php';


/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
    //     require get_template_directory() . '/includes/woocommerce.php';
    require get_template_directory() . '/woocommerce/inc/wc-functions.php';
    require get_template_directory() . '/woocommerce/inc/wc-remove-functions.php';
    require get_template_directory() . '/woocommerce/inc/wc-functionc-mini-cart.php';
    require get_template_directory() . '/woocommerce/inc/zn-woocommerce-init.php';
    //     require get_template_directory() . '/woocommerce/inc/wc-functions-remove.php';
    require get_template_directory() . '/woocommerce/inc/wc-functions-archive.php';
    require get_template_directory() . '/woocommerce/inc/wc-functions-single-product.php';
        require get_template_directory() . '/woocommerce/inc/wc-functions-checkout-cart.php';
    //     require get_template_directory() . '/woocommerce/inc/wc-functions-checkout.php';
    //     // require get_template_directory() . '/woocommerce/includes/wc_functionc_cart.php';
    //     // require get_template_directory() . '/woocommerce/includes/wc-function-checkout.php';
}


/**
 * Добавление нового виджета Foo_Widget.
 */
// Registration sidebar
register_sidebar(array(
    'name' => __('Виджет фильтра', 'Фасад'),
    'id' => 'filter-widget',
    'description' => __('Область описания сайдбара', 'twentytwenty'),
    'before_widget' => '<li id="%1$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
));

//Удаляем category из УРЛа категорий

// function true_remove_category_from_category($cat_url) {
// 	$cat_url = str_replace('/category', '', $cat_url);
// 	return $cat_url;
// }
// add_filter('category_link', 'true_remove_category_from_category', 1, 1);


add_action('wp_enqueue_scripts', 'true_no_contact_form_css_and_js', 999);

function true_no_contact_form_css_and_js()
{
    wp_dequeue_style('woocommerce-general');
}
