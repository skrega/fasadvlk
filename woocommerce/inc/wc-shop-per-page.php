<?php

defined('ABSPATH') || exit;

function custom_wc_admin_shop_per_page()
{
    $per_page = filter_input(INPUT_GET, 'shop_per_page', FILTER_VALIDATE_INT);
    $allowed = array(10, 25, 50, 100, -1); // Допустимые значения количества на странице, -1 означает "все товары"

    echo '<div class="woocommerce-results-per-page">
        <label>' . __('Show', 'woocommerce') . ':</label>';

    foreach ($allowed as $limit) {
        printf(
            '<button type="button" class="per-page-button %s" data-value="%s">%s</button>',
            $limit == $per_page ? 'active' : '',
            esc_attr($limit),
            esc_html($limit === -1 ? __('All', 'woocommerce') : $limit)
        );
    }

    echo '</div>';

    // Добавляем обработчик события нажатия на кнопку
?>
    <script>
        jQuery(function($) {
            $(".per-page-button").click(function(event) {
                event.preventDefault();
                var value = $(this).data("value");
                updateShopPerPage(value);
            });

            function updateShopPerPage(perPage) {
                var data = {
                    action: "update_shop_per_page",
                    shop_per_page: perPage
                };

                $.ajax({
                    url: '<?php echo admin_url("admin-ajax.php") ?>',
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        });
    </script>
<?php
}

add_action('woocommerce_after_shop_loop', 'custom_wc_admin_shop_per_page', 20);

// Изменяем количество выводимых товаров на странице
function custom_wc_shop_per_page()
{
    $per_page = filter_input(INPUT_POST, 'shop_per_page', FILTER_VALIDATE_INT);

    if ($per_page === -1 || in_array($per_page, array(10, 25, 50, 100))) {
        setcookie('shop_per_page', $per_page, time() + 86400, '/');
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}

add_action('wp_ajax_update_shop_per_page', 'custom_wc_shop_per_page');
add_action('wp_ajax_nopriv_update_shop_per_page', 'custom_wc_shop_per_page');
