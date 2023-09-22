<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!function_exists('estore_woocommerce_cart_link_fragment')) {
    /**
     * Cart Fragments.
     *
     * Ensure cart contents update when products are added to the cart via AJAX.
     *
     * @param array $fragments Fragments to refresh via AJAX.
     * @return array Fragments to refresh via AJAX.
     */
    add_filter('woocommerce_add_to_cart_fragments', 'estore_woocommerce_cart_link_fragment');
    function estore_woocommerce_cart_link_fragment($fragments)
    {
        ob_start();
        store_woocommerce_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }
}



function store_woocommerce_cart_link()
{
?>
    <a class="header-cart header-cart-contents cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('Посмотреть корзину', 'estore'); ?>">
        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.41 15.0663L5.875 7.82031H18.501C19.152 7.82031 19.629 8.43131 19.471 9.06331L18.123 14.4553C17.918 15.2743 17.222 15.8763 16.382 15.9603L9.566 16.6423C8.55 16.7433 7.621 16.0643 7.41 15.0663Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M5.874 7.82031L5.224 4.82031H3.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M17.1074 20.0871C16.9054 20.0871 16.7414 20.2511 16.7434 20.4531C16.7434 20.6551 16.9074 20.8191 17.1094 20.8191C17.3114 20.8191 17.4754 20.6551 17.4754 20.4531C17.4744 20.2511 17.3104 20.0871 17.1074 20.0871" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M8.69382 20.0866C8.49182 20.0866 8.32782 20.2506 8.32982 20.4526C8.32782 20.6556 8.49282 20.8196 8.69482 20.8196C8.89682 20.8196 9.06082 20.6556 9.06082 20.4536C9.06082 20.2506 8.89682 20.0866 8.69382 20.0866" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span class="header-cart__text">Корзина</span>
        <?php if (WC()->cart->get_cart_contents_count() < 0) { ?>
            <span class="header-cart-subtotal">(<?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?>)</span>
        <?php } ?>
    </a>
    <?php
}


if (!function_exists('estore_woocommerce_header_cart')) {
    /**
     * Display Header Cart.
     *
     * @return void
     */
    function estore_woocommerce_header_cart()
    {
        if (is_cart()) {
            $class = 'current-menu-item';
        } else {
            $class = '';
        }
    ?>
        <ul id="site-header-cart" class="site-header-cart">
            <li class="<?php echo esc_attr($class); ?>">
                <?php store_woocommerce_cart_link(); ?>
            </li>
            <li>
                <?php
                $instance = array(
                    'title' => '',
                );

                the_widget('WC_Widget_Cart', $instance);
                ?>
            </li>
        </ul>
<?php
    }
}
