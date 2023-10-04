<?php

/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if (!defined('ABSPATH')) {
	exit;
}

if ($upsells) : ?>

	<section class="up-sells upsells products section">
		<?php
		$heading = apply_filters('woocommerce_product_upsells_products_heading', __('С этим покупают', 'woocommerce'));

		if ($heading) :
		?>
			<div class="block-head mb-4">
				<h2 class="title"><?php echo esc_html($heading); ?></h2>
				<div class="popular-buttons swiper-buttons">
					<div class="popular-btn-prev swiper-button-prev swiper-btn">
						<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8.5 1L3.5 6L8.5 11" stroke="#127FFF" stroke-width="2" stroke-linecap="round" />
						</svg>
					</div>
					<div class="popular-btn-next swiper-button-next swiper-btn">
						<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3.5 1L8.5 6L3.5 11" stroke="#127FFF" stroke-width="2" stroke-linecap="round" />
						</svg>
					</div>
				</div>
			</div>


		<?php endif; ?>
		<div class="swiper products-swiper">
			<ul class="products swiper-wrapper">
				<?php //woocommerce_product_loop_start(); 
				?>

				<?php foreach ($upsells as $upsell) : ?>

					<?php
					$post_object = get_post($upsell->get_id());

					setup_postdata($GLOBALS['post'] = &$post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

					wc_get_template_part('content', 'product');
					?>

				<?php endforeach; ?>

				<?php //woocommerce_product_loop_end(); 
				?>
			</ul>
		</div>
	</section>

<?php
endif;

wp_reset_postdata();
