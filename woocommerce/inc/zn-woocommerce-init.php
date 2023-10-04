<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('COEF_PRICE_SORT_META_ID', 'coef_price');
define('COEF_PRICE_META_ID', 'coef_price');
define('COEF_PRICE_META_ID_PREFIX', 'coef_');
define('EMPTY_PRICE_STRING', 'Цену уточняйте у менеджера');

function get_default_city_variation($product)
{

    static $default_city_variations;

    if (!isset($default_city_variations)) {

        $default_city_variations = [];
    }

    $product_id = $product->get_id();

    if ((!empty($product_id)) && (!empty($default_city_variations[$product_id]))) {

        return $default_city_variations[$product_id];
    }

    $default_city = get_default_city('price');

    $data_store = WC_Data_Store::load('product');

    $variation_id = $data_store->find_matching_product_variation($product, ['attribute_pa_city' => $default_city]);

    $default_city_variation = $variation_id ? wc_get_product($variation_id) : false;

    if (is_a($default_city_variation, 'WC_Product_Variation')) {

        $default_city_variations[$product_id] = $default_city_variation;

        return $default_city_variation;
    }

    return false;
}


function product_is_panel($product_id)
{

    $is_panel = false;

    $my_tags = [607];

    $terms = get_the_terms($product_id, 'product_cat');

    foreach ($terms as $term) {
        if (in_array($term->term_id, $my_tags)) {
            $is_panel = true;
            break;
        }
    }

    return $is_panel;
}

function fasad_price_suffix($html, $variation, $price, $qty)
{

    global $product;

    if (product_is_panel($product->get_id())) {

        return '<span class="loop-price-fasad-suffix">/ уп.</span>';
    }
}

add_action('woocommerce_single_product_summary', 'woocommerce_total_product_price', 31);
function woocommerce_total_product_price()
{
    global $product;

    add_action('wp_footer', 'woocommerce_footer_scripts', 1000);
}
add_action( 'woocommerce_after_variations_table', 'add_to_cart_info', 15 );
function add_to_cart_info() {

    wc_get_template(
			'single-product/add-to-cart/add_to_cart_info.php'
		);
}


function single_display_all_stock() {

	global $product;

 	if ( $product->get_type() == "simple" ) return;

	$is_panel = product_is_panel( $product->get_id() );

    $html = [ 'moscow' => '', 'vladivostok' => '', 'pod-zakaz' => ''];

	$count = 0;
	$qty_string = array (	'moscow' => 'В&nbsp;Москве',
				'vladivostok' => 'Во&nbsp;Владивостоке',
				'pod-zakaz' => 'Под&nbsp;заказ' );

	$available_variations= $product->get_available_variations();

	if ( $is_panel ) {
		$coef = get_coef( $product );
	}

	if ( $coef > 0 ) {
        $ed = ' за м<sup>2</sup>';
	}
	else {
        $ed = '';
        $coef = 1;
    }

	foreach ($available_variations as $variation) {
		if ( 'pod-zakaz' !== $variation["attributes"]["attribute_pa_city"] && ( $variation["current_stock"] > 0 ) && ($variation["display_price"] > 0)) {

		    ++$count;

			if ( 'moscow' === $variation["attributes"]["attribute_pa_city"] ) {
				$html[$variation["attributes"]["attribute_pa_city"]] .= '<span class="price-preview" style=""><span class="price-preview-city">'.$qty_string[$variation["attributes"]["attribute_pa_city"]].'</span><span class="price-preview-stock">'.$variation["current_stock"].' шт. по цене '.round((float)($variation["display_price"]/$coef),2).' руб.'.$ed.'</span></span>';
				$_REQUEST['moscow_in_stock'] = true;
			} elseif ( 'vladivostok' === $variation["attributes"]["attribute_pa_city"] ) {
				$html[$variation["attributes"]["attribute_pa_city"]] .= '<span class="price-preview" style=""><span class="price-preview-city">'.$qty_string[$variation["attributes"]["attribute_pa_city"]].'</span><span class="price-preview-stock">'.$variation["current_stock"].' шт. ' /* по цене '.round(floatval($variation["display_price"]/$coef),2).' руб.'.$ed */.'</span></span>';
				$_REQUEST['vladivostok_in_stock'] = true;
			} else {
				if ( $is_panel)
					$html[$variation["attributes"]["attribute_pa_city"]] .= '<span class="price-preview" style=""><span class="price-preview-city">'.$qty_string[$variation["attributes"]["attribute_pa_city"]].'</span><span class="price-preview-stock">'.$variation["current_stock"].' шт.</span></span>';
				else
					$html[$variation["attributes"]["attribute_pa_city"]] .= '<span class="price-preview" style=""><span class="price-preview-city">'.$qty_string[$variation["attributes"]["attribute_pa_city"]].'</span><span class="price-preview-stock">'.$variation["current_stock"].' шт. по цене '.round((float)($variation["display_price"]/$coef),2).' руб.'.$ed.'</span></span>';

			}
		}
		else {
			if ( 'pod-zakaz' !== $variation["attributes"]["attribute_pa_city"] && $variation["display_price"] > 0)
				if ( 'moscow' === $variation["attributes"]["attribute_pa_city"] && $is_panel)
					$html[$variation["attributes"]["attribute_pa_city"]] .= '<span class="price-preview" style=""><span class="price-preview-city">'.$qty_string[$variation["attributes"]["attribute_pa_city"]].'</span><span class="price-preview-stock">'.round((float)($variation["display_price"]/$coef),2).' руб.'.$ed.'</span></span>';
//				elseif ( 'vladivostok' === $variation["attributes"]["attribute_pa_city"] && $is_panel)
//					$html[$variation["attributes"]["attribute_pa_city"]] .= '<span class="price-preview" style=""><span class="price-preview-city">'.$qty_string[$variation["attributes"]["attribute_pa_city"]].'</span><span class="price-preview-stock">'.round((float)($variation["display_price"]/$coef),2).' руб.'.$ed.'</span></span>';
				else
					$html[$variation["attributes"]["attribute_pa_city"]] .= '<span class="price-preview" style=""><span class="price-preview-city">'.$qty_string[$variation["attributes"]["attribute_pa_city"]].'</span><span class="price-preview-stock">есть на складе!</span></span>';
			if ( 'pod-zakaz' === $variation["attributes"]["attribute_pa_city"] && $variation["display_price"] > 0)
				$html[$variation["attributes"]["attribute_pa_city"]] .= '<span class="price-preview" style=""><span class="price-preview-city">Товар доступен под заказ</span><span class="price-preview-stock">да</span></span>';
		}
		if ( $variation["display_price"] != $variation["display_regular_price"] ) { $onsale = true; $onsale_text .= (empty($onsale_text)?'':'<br/>').'Скидка&nbsp;'.(int)(round(($variation["display_regular_price"]-$variation["display_price"])/$variation["display_regular_price"]*100)).'%&nbsp;'.$qty_string[$variation["attributes"]["attribute_pa_city"]].'!';}
	}

	$html_start = '<div class="price-preview-wrapper clearfix">';
	$html_end = '</div>';

	if ( $count > 0 ) echo $html_start.($html['moscow']?:'').($html['vladivostok']?:'').($html['pod-zakaz']?:'').$html_end;
	if ( $onsale ) { $onsale = false; ?> <span class="zn_badge_sale_template" style="display: none;"><?php echo $onsale_text; ?></span><?php }
}

function custom_price_html() {

	global $product;

 	if ( $product->get_type() === "simple" ) {

	    return $product->get_price_html();
    }

    $coef = get_coef( $product );

    $min_price = $product->get_variation_price( 'min', true );
    $max_price = $product->get_variation_price( 'max', true );

    $html='';

    $default_city = get_default_city('price');

	if ( ! empty( $default_city ) ) {

	    $available_variations = $product->get_available_variations();

		$price_to_display='';
	}

	if( $available_variations ) {

		foreach ($available_variations as $variation) {

			if ( $variation["attributes"]["attribute_pa_city"] == $default_city ) {

				$price_to_display = $variation['display_price'];
			}
//			echo "<!-- QQQQQQQ attrib: ".$variation["attributes"]["attribute_pa_city"]." ARG: ".$args['pa_city'].' SETPRICE: '.$variation['display_price'].' PRICE:'.$price_to_display.'-->';
		}

		if ( $max_price && ( (int)$min_price === 0 ) ) {

			$min_price = $max_price;

			foreach ($available_variations as $variation) {

				if ($variation["display_price"] < $min_price && $variation["display_price"] > 0) {

					$min_price = $variation['display_price'];
				}
//			echo "<!-- QQQQQQQ attrib: ".$variation["attributes"]["attribute_pa_city"]." ARG: ".$args['pa_city'].' SETPRICE: '.$variation['display_price'].' PRICE:'.$price_to_display.'-->';
			}
		}
	}

//		echo '<!-- COEF '.$coef.' MIN '.$min_price.' MAX '.$max_price.' -->';
    if ($min_price && $max_price && ( $min_price > 0 ) ) {

        if ($coef > 0) {
                        if (($min_price != $max_price) && empty($price_to_display)) {

                            $html = '<span class="price noattr_pa_city ' . $default_city . '" style="font-size: 14px;"><span class="amount">' . round((float)(str_replace(',', '.', $min_price)) / $coef, 2) . '&nbsp;руб./м<sup>2</sup></span>–<span class="amount">' . round((float)(str_replace(',', '.', $max_price)) / $coef, 2) . 'руб./м<sup>2</sup></span></span>';
        //		return round((floatval(str_replace(',','.',$price))/$coef),2);
                        } else {

                            if ( empty( $price_to_display ) ) {

                                $html = '<span class="price single"><span class="amount">' . round((float)(str_replace(',', '.', $min_price)) / $coef, 2) . '&nbsp;руб./м<sup>2</sup></span></span>';
                            } else {

                                $html = '<span class="price single"><span class="amount">' . round($price_to_display / $coef, 2) . '&nbsp;руб./м<sup>2</sup></span></span>';
                            }
                        }
                    } else {

                        if ($min_price != $max_price) {

                            $html = '<span class="price" style="font-size: 14px;"><span class="amount">' . round( (float)(str_replace(',', '.', $min_price)), 2) . '&nbsp;руб.</span>–<span class="amount">' . round( (float)(str_replace(',', '.', $max_price) ), 2) . 'руб.</span></span>';
        //				return round((floatval(str_replace(',','.',$price))/$coef),2);
                        } else {

                            $html = '<span class="price"><span class="amount">' . round( (float)(str_replace(',', '.', $min_price)), 2 ) . '&nbsp;руб.</span></span>';
                        }
                    }
    }

	if( $available_variations ) {

		return $html;
	}

	return $product->get_price_html();
}



function unprepare_price_filter() {
	unset($_REQUEST['measure']);
	unset($_REQUEST['convertion_rate']);
}

function prepare_price_filter($product_id = false) {

	unprepare_price_filter();

	if ( is_admin () ) {

		return;
	}

	if ( $product_id ) {

		$measure = get_field('measure', $product_id, false);

		if ( $measure ) {
			$pst_id = $product_id;

			$_REQUEST['measure'] = $measure;
		} else {

			$terms = get_the_terms( $product_id, 'product_cat' );
			$pst_id = 'product_cat_'.$terms[0]->term_id;

			$measures = get_field('default_measure', $pst_id, false);
			if ( $measures ) {

				$_REQUEST['default_measure'] = $measures;
			}

			$measures = get_field('measures', $pst_id, false);

			if ( $measures ) {

				$_REQUEST['measure'] = $measures;
			} else {

				return false;
			}
		}
	} else {

		return false;
	}

	$convert_to_measure		 = get_field('convert_to_measure'		,$pst_id	,false);

	if ( $convert_to_measure ) {

		$convertion_rate_field = get_field('convertion_rate_field', $pst_id, false);

		if ($convertion_rate_field) {

			$conversion_rate = get_post_meta($product_id, $convertion_rate_field, true);

			if ( $conversion_rate ) {

				$_REQUEST['convertion_rate'] = $conversion_rate;
			} else {

				$convertion_rate_default = get_field('convertion_rate_default', $pst_id, false);

				if ($convertion_rate_default) {

					$_REQUEST['convertion_rate'] = $convertion_rate_default;
				}
			}
		}
	}
}

add_filter( 'woocommerce_currency_symbol', 'my_price_filter', 999, 2 );
function my_price_filter( $currency_symbol, $currency ) {
	return $currency_symbol.(( isset( $_REQUEST['measure'] ) && ( ! empty( $_REQUEST['measure'] ) ) )?$_REQUEST['measure']:'');
}

//add_filter( 'raw_woocommerce_price', 'my_price_converter', 999, 1);
function my_price_converter ( $price ) {
	return (float)($price / (( isset( $_REQUEST['convertion_rate'] ) && ( ! empty( $_REQUEST['convertion_rate'] ) ) )?$_REQUEST['convertion_rate']:1));
}

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
    $tabs['delivery'] = array(
        'title'    => 'Доставка и оплата',
        'priority' => 20,
        'callback' => 'fasad_delivery_tab'
    );
    $tabs['mounting'] = array(
        'title'    => 'Монтаж',
        'priority' => 20,
        'callback' => 'fasad_mounting_tab'
    );
    $tabs['additional_information'] = array(
        'title'    => 'Характеристики',
        'callback' => 'fasad_additional_information_tab'
    );
   
   
    unset( $tabs['reviews'] );
    // unset( $tabs['additional_information'] );  	// Remove the additional information tab
    return $tabs;
}
function fasad_delivery_tab() {
    wc_get_template( 'single-product/tabs/delivery.php' );
}
function fasad_additional_information_tab() {
    wc_get_template( 'single-product/tabs/additional-information.php' );
}
function fasad_mounting_tab() {
    wc_get_template( 'single-product/tabs/mounting.php' );
}


//add_filter('woocommerce_get_price_html', 'change_empty_or_zero_price',999,2);
function change_empty_or_zero_price($price, $product) {

    $is_panel = product_is_panel( $product->get_id() );

    $default_city = get_default_city('price');

	if ( $is_panel && ( ! empty( $default_city ) ) && ( $default_city != 'moscow' ) ) {

        return '<span class="amount">'.EMPTY_PRICE_STRING.'</span>';
    }

	if ( ( ! empty( $default_city ) ) && $product->get_type() == "variable") {

		$variations = $product->get_available_variations();

		$newprice = '';

		foreach ( $variations as $variation ) {

			if ( $variation['attributes']['attribute_pa_city'] == $default_city ) {

				$newprice = $variation['price_html'];
				break;
			}
		}

		return $newprice == ''?$price:preg_replace('/^\<span class\=\"price\"\>/', '<span class="price targeted">',$newprice);

	} else {

		$prices = $product->get_price();
		$a = $product->get_price();

		if (empty($prices['price']) || $a === '') {
			return '';
		}

		$price_done = preg_replace('/\&ndash\;\<span\ class\=\"amount\"\>.*?\<\/span\>/', '', $price);
		$price_done = str_replace('<ins data-now="NOW"><span class', '<ins data-now="NOW"><span style="width:4em; display: inline-block; text-align: right;">От:&nbsp;&nbsp;&nbsp;</span><span class', $price_done);
		$price_done = preg_replace('/^\<span class\=\"amount\"\>/', '<span style="width:4em; display: inline-block; text-align: right;">От:&nbsp;&nbsp;&nbsp;</span><span class="amount">', $price_done);
	}
	return $price_done;
}



add_filter( 'wp_title', 'custom_product_archive_title', 999, 3 );
add_filter( 'woocommerce_page_title', 'custom_product_archive_title', 999, 1);
function custom_product_archive_title ( $title, $sep = false, $sep_loc = false ) {

          if(is_product_category() || is_product_tag() || (is_woocommerce() && is_archive()) )
          {
                        global $wp_query;
                        $tax = $wp_query->get_queried_object();

                        $new_title = '';

                        switch ( $tax->term_id ) {
                                case 727:
                                        $new_title .= 'Металлопрофиль ';
                                        break;
                                case 641:
                                        $new_title .= 'Подсистема ';
                                        break;
                                case 663:
                                        $new_title .= 'Кронштейны ';
                                        break;
                                case 664:
                                        $new_title .= 'Кронштейны ККУ ';
                                        break;
                                case 662:
                                        $new_title .= 'Профили ';
                                        break;
                                case 709:
                                        $new_title .= 'Светильники ';
                                        break;
                                case 707:
                                        $new_title .= 'Садовые светильники ';
                                        break;
                                case 704:
                                        $new_title .= 'Фасадные светильники ';
                                        break;
                                case 686:
                                        $new_title .= 'Террасная доска ';
                                        break;
                                case 699:
                                        $new_title .= 'Декоративные элементы декинга ';
                                        break;
                                case 697:
                                        $new_title .= 'Крепеж декинга ';
                                        break;
                                case 607:
                                        $new_title .= 'Фасадные панели ';
                                        break;
                                case 439:
                                        $new_title .= 'Фасадные панели 14мм ';
                                        break;
                                case 478:
                                        $new_title .= 'Фасадные панели 16мм ';
                                        break;
                                case 565:
                                        $new_title .= 'Фасадные панели 18мм ';
                                        break;
                                case 570:
                                        $new_title .= 'Фасадные панели 35мм ';
                                        break;
                                case 581:
                                        $new_title .= 'Фасадные декоративные элементы ';
                                        break;
                                case 591:
                                        $new_title .= 'Фасадный крепеж ';
                                        break;
                                case 819:
                                        $new_title .= 'Искусственная изгородь ';
                                        break;
                                default:
                                        $new_title .= 'Товары ';
                                        break;
                        }

                        if (    isset($_REQUEST['pa_tekstura'])
                             || ( isset( $_REQUEST['pf_id'], $_REQUEST['pf_filters'][ $_REQUEST['pf_id'] ]['pa_tekstura'] ) && ($tax->slug === 'fasadnye-paneli') )
                        ) {

                            $_term = '';

                            if ( isset( $_REQUEST['pa_tekstura'] ) ) {

                                $_term = $_REQUEST['pa_tekstura'];
                            } elseif( isset( $_REQUEST['pf_id'], $_REQUEST['pf_filters'][ $_REQUEST['pf_id'] ]['pa_tekstura'] ) ) {

                                $_term = $_REQUEST['pf_filters'][ $_REQUEST['pf_id'] ]['pa_tekstura'];
                            }

                                switch ( $_term ) {
                                    case 'beton':
                                            $new_title .= 'под бетон ';
                                            break;
                                    case 'derevo':
                                            $new_title .= 'под дерево ';
                                            break;
                                    case 'kamen':
                                            $new_title .= 'под камень ';
                                            break;
                                    case 'kirpich':
                                            $new_title .= 'под кирпич ';
                                            break;
                                    case 'shtukaturka':
                                            $new_title .= 'под штукатурку ';
                                            break;
                                    case 'bez-shvov':
                                            $new_title .= 'FUGE ';
                                            break;
                                    case 'ekoartplyus':
                                            $new_title .= 'ЭкоАртПлюс ';
                                            break;
                                }
                        }

                        if ( isset( $_REQUEST['pa_city'] ) ) {

                            $default_city = $_REQUEST['pa_city'];
                        } elseif( isset( $_REQUEST['pf_id'], $_REQUEST['pf_filters'][ $_REQUEST['pf_id'] ]['pa_city'] ) ) {

                            $default_city = $_REQUEST['pf_filters'][ $_REQUEST['pf_id'] ]['pa_city'];
                        }

                        switch ( $default_city ) {
                            case 'moscow':
                                $new_title .= 'в Москве ';
                                break;
                            case 'novosibirsk':
                                $new_title .= 'в Новосибирске ';
                                break;
                            case 'vladivostok':
                                $new_title .= 'во Владивостоке ';
                                break;
                            case 'pod-zakaz':
                                $new_title .= 'под заказ ';
                                break;
                        }

                        $new_title = mb_strtoupper(mb_substr($new_title, 0, 1)).mb_substr($new_title, 1);

                        $title =  str_replace( '  ', ' ', (("" === $new_title)?$tax->name:$new_title) );
//                        $args['subtitle'] = ''; // Reset the subtitle for categories and tags
	}


	return $title;
}



function thankyou_page_custom_bacs( $order_id ) {
         echo wpautop( wptexturize( wp_kses_post( 'В ближайшее время наш сотрудник свяжется с Вами для уточнения деталей и выставления счёта.' ) ) );
         echo wpautop( wptexturize( wp_kses_post( 'Обращаем Ваше внимание - производите платеж непосредственно на наш банковский счет. Так же, пожалуйста, указывайте номер выставленного Вам счёта в описании перевода. Ваш заказ не будет выполнен до тех пор, пока денежные средства не поступят на наш счет и будут идентифицированы.' ) ) );

//         echo '<h2>' . __( 'Our Bank Details', 'woocommerce' ) . '</h2>' . PHP_EOL;

//         echo '<ul class="order_details bacs_details">' . PHP_EOL;

         $account_fields = array(      'reciever'=> array(
                                                'label' => 'Получатель платежа',
                                                'value' => 'Общество с ограниченной ответственностью «Фасад»'
                                        ),
                                        'innkpp'     => array(
                                                'label' => 'ИНН/КПП',
                                                'value' => '2543120671/254301001'
                                        ),
                                        'rs'          => array(
                                                'label' => 'Расчетный счет',
                                                'value' => '40702810310540000178'
                                        ),
                                        'bankname'           => array(
                                                'label' => 'Банк получателя',
                                                'value' => 'ФИЛИАЛ №2754 БАНКА ВТБ (ПАО)'
                                        ),
                                        'ks'           => array(
                                                'label' => 'Корр. счет',
                                                'value' => '30101810708130000713'
                                        ),
                                        'bic'           => array(
                                                'label' => 'Бик',
                                                'value' => '040813713'
                                        )
                                );

//          foreach ( $account_fields as $field_key => $field ) {
//                  if ( ! empty( $field['value'] ) ) {
//                         echo '<li class="' . esc_attr( $field_key ) . '">' . esc_attr( $field['label'] ) . ': <strong>' . wptexturize( $field['value'] ) . '</strong></li>' . PHP_EOL;
//                  }
//          }
//          echo '</ul>';

}

function render_fasad_advantages( $echo = true ) {

    if ( $echo !== true ) {

        ob_start();
    }

    wc_get_template_part('content', 'fasad-advantages');

    if ( $echo !== true ) {

        return ob_get_clean();
    }

    return null;
}

function render_fasad_advantages_action() {
    render_fasad_advantages();
}

function render_product_summary_cta_button_block( $echo = true ) {

    if ( $echo !== true ) {

        ob_start();
    }

//    echo "<!-- QQQQQQQ CTA_IN -->";
    wc_get_template_part('content', 'product-summary-cta-button-block');

    if ( $echo !== true ) {

        return ob_get_clean();
    }

    return null;
}

function render_product_summary_cta_button_block_action() {
    render_product_summary_cta_button_block();
}

function render_product_sale_info( $echo = true ) {

    if ( $echo !== true ) {

        ob_start();
    }

    wc_get_template_part('content', 'product-sale-info');

    if ( $echo !== true ) {

        return ob_get_clean();
    }

    return null;
}

function render_product_sale_info_action() {

    render_product_sale_info();
}

function woocommerce_footer_scripts()
{

    global $product;

    if ($product->get_type() == "composite") return;

    //	439 - 14мм, 478 - 16мм, 565 - 18мм, 570 - 35мм
    //	$cat_filter = array(439,478,565,570);

    $default_cur_suffix = ($_REQUEST['measure']) ? $_REQUEST['measure'] : '/шт.';

    $post_id = get_the_ID();

    $is_panel = product_is_panel($post_id);

    $default_city = get_default_city();

    prepare_price_filter($post_id);

    $cur_suffix = $_REQUEST['measure'];

    if ($is_panel) {

        $coef = get_coef($product);
    } else {

        $coef = $_REQUEST['convertion_rate'] ? $_REQUEST['convertion_rate'] : 1;
    }
    unprepare_price_filter();


    if (!$cur_suffix || ($cur_suffix == $default_cur_suffix)) {
        // if variable product _not_ panel
        if ($product->get_type() == "variable") { ?>
            <script>
                jQuery(function($) {
                    var sale_span = ($('.zonsale.zn_badge_sale').length > 0) ? $('.zn_badge_sale_template').html() : '';
                    var default_city = '<?php echo $default_city; ?>';
                    var coef = parseFloat('<?php echo $coef; ?>');
                    var cur_sym = '<?php echo get_woocommerce_currency_symbol(); ?>';
                    var cur_suf = '<?php echo $default_cur_suffix; ?>';
                    var currency = cur_sym + cur_suf;
                    var def_cur = cur_sym + '<?php echo $default_cur_suffix; ?>';
                    var ppms, ppmsr, ep, epr, pbig_content, min_price = 0,
                        min_rprice,
                        var_data = jQuery.parseJSON(jQuery('.variations_form').attr('data-product_variations'));

                    // hide default price
                    $('p.price').hide();

                    // add custom price tag
                    $('.controls-data form .variations').after('<p class="price-big" attr-before="Цена:"></p>');
                    var $price_big = $('p.price-big');

                    // prepare quantity change html
                    $('.single_variation_wrap').append('<div id="product_total_price" style="display:none"><span class="total_price_label">Итого</span><span class="total_price price"><span class="amount" style="white-space:nowrap;"></span></span>');

                    // set the city dropdown default value
                    if ($('#pa_city').find('[value="' + default_city + '"]').length > 0) {
                        $('#pa_city').val(default_city).change();
                    }

                    // hook to city dropdown change
                    $(document).on('change', '#pa_city', function() {
                        var $price_wrapper = $('.woocommerce div.product form.cart .single_variation_wrap .single_variation .price .amount'),
                            ppms, ppmsr, ep, epr, pbig_content,
                            var_data = jQuery.parseJSON(jQuery('.variations_form').attr('data-product_variations'));

                        $('#product_total_price span.amount').html('');
                        $('#product_total_price').hide();

                        // get minimal, regular and current prices
                        for (var i in var_data) {
                            if (var_data[i].attributes.attribute_pa_city == jQuery('#pa_city').val()) {
                                ppms = parseFloat(var_data[i].display_price);
                                ppmsr = parseFloat(var_data[i].display_regular_price);
                            }

                            if ((min_price == 0) || (parseFloat(var_data[i].display_price) < min_price)) {
                                min_price = parseFloat(var_data[i].display_price);
                                min_rprice = parseFloat(var_data[i].display_regular_price)
                            }
                        }

                        // if price != 0
                        if (ppms) {
                            if (ppms != ppmsr) {
                                ep = ppms;
                                epr = ppmsr;

                                pbig_content = '<del>' + epr.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</del>';
                                pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</ins>';
                            } else {
                                ep = ppms;

                                pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur;
                            }

                            $price_big.html(pbig_content).attr('attr-before', 'Цена: ').show();
                            $('.single_variation span.price').html(pbig_content);
                        } else {
                            if (min_price != min_rprice) {
                                ep = min_price;
                                epr = min_rprice;

                                pbig_content = '<del>' + epr.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</del>';
                                pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</ins>';
                            } else {
                                ep = min_price;

                                pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur;
                            }

                            $price_big.html(pbig_content).attr('attr-before', 'Цена от ').show();
                        }

                        if (jQuery(this).val() === 'vladivostok') {

                            jQuery('.single_variation_wrap').hide();
                        }
                    });

                    // hook to quantity change event
                    $(document).on('change', '[name=quantity]', function() {
                        if (!(this.value < 1)) {
                            var var_data = jQuery.parseJSON(jQuery('.variations_form').attr('data-product_variations'));
                            var price;
                            for (var i in var_data) {
                                if (var_data[i].attributes.attribute_pa_city == jQuery('#pa_city').val()) {
                                    price = parseFloat(var_data[i].display_price);
                                }
                            }

                            var product_total = parseFloat(price) * this.value;
                            var area_total = parseFloat(coef) * this.value;

                            total_cost = parseFloat(product_total).toFixed(2).replace('.', ',') + '&nbsp;' + cur_sym<?php if ($coef > 0 && ($cur_suffix != null)) { ?> + ' (' + area_total.toFixed(2).replace('.', ',') + '&nbsp;' + cur_suf + ')'
                        <?php } ?>;
                        $('#product_total_price .price .amount').html(total_cost);
                        }
                        $('#product_total_price').toggle(!(this.value < 1));
                    });


                    // Initial page setup

                    // get minimal, regular and current prices
                    for (var i in var_data) {
                        if (var_data[i].attributes.attribute_pa_city == jQuery('#pa_city').val()) {
                            ppms = parseFloat(var_data[i].display_price);
                            ppmsr = parseFloat(var_data[i].display_regular_price);
                        }

                        if ((min_price == 0) || (parseFloat(var_data[i].display_price) < min_price)) {
                            min_price = parseFloat(var_data[i].display_price);
                            min_rprice = parseFloat(var_data[i].display_regular_price)
                        }
                    }

                    // if price != 0
                    if (ppms) {
                        if (ppms != ppmsr) {
                            ep = ppms;
                            epr = ppmsr;

                            pbig_content = '<del>' + epr.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</del>';
                            pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</ins>';

                            $('.zonsale.zn_badge_sale').html($('.zn_badge_sale_template').html());
                        } else {
                            ep = ppms;

                            pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur;

                            $('.zonsale.zn_badge_sale').html(sale_span);
                        }

                        $price_big.html(pbig_content).show();
                        $('.single_variation span.price').html(pbig_content);

                        if ($('#pa_city').val() == 'vladivostok' || $('#pa_city').val() == 'moscow') {
                            $('.request-call-button-wrapper').hide();
                            $('.single_variation_wrap').show();
                        }
                        if ($('#pa_city').val() == '') {
                            $('.request-call-button-wrapper').hide();
                            // $('.single_variation_wrap').hide();
                        }
                    } else {
                        if (min_price != min_rprice) {
                            ep = min_price;
                            epr = min_rprice;

                            pbig_content = '<del>' + epr.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</del>';
                            pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</ins>';

                            $('.zonsale.zn_badge_sale').html($('.zn_badge_sale_template').html());
                        } else {
                            ep = min_price;

                            pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur;

                            $('.zonsale.zn_badge_sale').html(sale_span);
                        }

                        $price_big.html(pbig_content).attr('attr-before', 'Цена от ').show();
                        if ($('#pa_city').val() == 'vladivostok' || $('#pa_city').val() == 'moscow') {
                            $('.request-call-button-wrapper').hide();
                            $('.single_variation_wrap').show();
                        }
                        if ($('#pa_city').val() == '') {
                            $('.request-call-button-wrapper').hide();
                            // $('.single_variation_wrap').hide();
                        }
                    }
                });
            </script>
        <?php return;
        }
        // if single product _not_ panel
        else { ?>
            <script>
                jQuery(function($) {
                    var cur_sym = '<?php echo get_woocommerce_currency_symbol(); ?>';
                    var currency = cur_sym + '<?php echo $default_cur_suffix; ?>';
                    // prepare quantity change hmtl
                    $('form.cart').append('<div id="product_total_price" style="display:none"><span class="total_price_label">Итого</span><span class="total_price price"><span class="amount" style="white-space:nowrap;"></span></span>');
                    // hook to quantity change event
                    $(document).on('change', '[name=quantity]', function() {
                        if (!(this.value < 1)) {
                            var price = parseFloat('<?php $price = get_post_meta(get_the_ID(), '_price');
                                                    echo $price[0]; ?>');
                            var product_total = parseFloat(price) * this.value;

                            total_cost = parseFloat(product_total).toFixed(2).replace('.', ',') + '&nbsp;' + cur_sym;
                            $('#product_total_price .price .amount').html(total_cost);
                        }
                        $('#product_total_price').toggle(!(this.value < 1));
                    });
                    // hook to city dropdown change
                    $(document).on('change', '#pa_city', function() {
                        $('#product_total_price span.amount').html('');
                        $('#product_total_price').hide();
                    });
                });
            </script>
    <?php return;
        }
    };
    ?>
    <script>
        <?php if ($product->get_type() == "variable") { ?>
            jQuery(function($) {
                var sale_span = ($('.zonsale.zn_badge_sale').length > 0) ? $('.zn_badge_sale_template').html() : '';
                var default_city = '<?php echo $default_city; ?>'
                var coef = parseFloat('<?php echo $coef; ?>');
                var cur_sym = '<?php echo get_woocommerce_currency_symbol(); ?>';
                var cur_suf = '<?php echo $cur_suffix; ?>';
                var currency = cur_sym + cur_suf;
                var def_cur = cur_sym + '<?php echo $default_cur_suffix; ?>';
                var ppms, ppmsr, ep, epr, pbig_content, min_price = 0,
                    min_rprice
                var var_data = jQuery.parseJSON(jQuery('.variations_form').attr('data-product_variations'));

                // hide default price
                $('p.price').hide();

                // add custom price tag
                $('form .variations').after('<p class="price-big" attr-before="Цена:"></p>');
                // $('form .single_add_to_cart_inner').before('<p class="price-big" attr-before="Цена:"></p>');
                // $('div.product_meta').after('<p class="price-big" attr-before="Цена:"></p>');
                var $price_big = $('p.price-big');

                // prepare quantity change html
                $('.single_add_to_cart_inner').append('<div id="product_total_price" style="display:none"><span class="total_price_label">Итого</span><span class="total_price price"><span class="amount" style="white-space:nowrap;"></span></span>');

                <?php if ($is_panel) { ?>

                    if ($('#pa_city').val() == 'novosibirsk' || $('#pa_city').val() == 'pod-zakaz' /*|| $('#pa_city').val() == 'vladivostok'*/ ) {
                        // $('.single_variation_wrap').hide();
                        $('.request-call-button-wrapper').show();
                        $price_big.html('<?php echo EMPTY_PRICE_STRING; ?>').attr('attr-before', '').css('padding-left', '0em');
                    }
                    if ($('#pa_city').val() == 'vladivostok' || $('#pa_city').val() == 'moscow') {
                        $('.request-call-button-wrapper').hide();
                        $('.single_variation_wrap').show();
                    }
                    if ( /*$('#pa_city').val() == 'vladivostok' || */ $('#pa_city').val() == '') {
                        $('.request-call-button-wrapper').hide();
                        // $('.single_variation_wrap').hide();
                    }
                <?php } else { ?>
                    $('.request-call-button-wrapper').hide();
                    $('.single_variation_wrap').show();
                <?php        } ?>
                // hook to city dropdown change
                $(document).on('change', '#pa_city', function() {
                    var $price_wrapper = $('.woocommerce div.product form.cart .single_variation_wrap .single_variation .price .amount'),
                        ppms, ppmsr, ep, epr, pbig_content,
                        var_data = jQuery.parseJSON(jQuery('.variations_form').attr('data-product_variations'));

                    $('#product_total_price span.amount').html('');
                    $('#product_total_price').hide();

                    <?php if ($is_panel) { ?>

                        if ($('#pa_city').val() == 'novosibirsk' || $('#pa_city').val() == 'pod-zakaz' /* || $('#pa_city').val() == 'vladivostok'*/ ) {
                            // $('.single_variation_wrap').hide();
                            $('.request-call-button-wrapper').show();
                            $price_big.html('<?php echo EMPTY_PRICE_STRING; ?>').attr('attr-before', '').css('padding-left', '0em');
                            return;
                        }
                        if ($('#pa_city').val() == 'vladivostok' || $('#pa_city').val() == 'moscow') {
                            $('.request-call-button-wrapper').hide();
                            $('.single_variation_wrap').show();
                        }
                        if ( /*$('#pa_city').val() == 'vladivostok' || */ $('#pa_city').val() == '') {
                            $('.request-call-button-wrapper').hide();
                            // $('.single_variation_wrap').hide();
                        }
                    <?php } else { ?>
                        $('.request-call-button-wrapper').hide();
                        $('.single_variation_wrap').show();
                    <?php        } ?>

                    // get minimal, regular and current prices
                    ppms = 0;
                    min_price = 0;

                    for (var i in var_data) {
                        <?php if ($is_panel) { ?>
                            if (var_data[i].attributes.attribute_pa_city != 'moscow' && var_data[i].attributes.attribute_pa_city != 'vladivostok')
                                continue;
                        <?php        } ?>

                        if (var_data[i].attributes.attribute_pa_city == jQuery('#pa_city').val()) {
                            ppms = parseFloat(var_data[i].display_price);
                            ppmsr = parseFloat(var_data[i].display_regular_price);
                        }

                        if ((min_price == 0) || (parseFloat(var_data[i].display_price) < min_price)) {
                            min_price = parseFloat(var_data[i].display_price);
                            min_rprice = parseFloat(var_data[i].display_regular_price)
                        }
                    }

                    if (ppms == 0 && min_price == 0) {
                        $price_big.html('<?php echo EMPTY_PRICE_STRING; ?>').attr('attr-before', '').css('padding-left', '0em');
                        if ($('#pa_city').val() == '') {
                            $('.request-call-button-wrapper').hide();
                        } else {
                            $('.request-call-button-wrapper').show();
                        }
                        return;
                    }
                    // if price != 0
                    if (ppms) {
                        if (ppms != ppmsr) {
                            ep = ppms / coef;
                            epr = ppmsr / coef;
                            pbig_content = '<div class="price-box"><del>' + epr.toFixed(2).replace('.', ',') 
                                            + '&nbsp;' + `<span class="currency">${currency}</span>`  
                                            + '<span class="regula-price">' 
                                            + ppmsr.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</span></del>';

                            pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' 
                                            + `<span class="currency">${currency}</span>` 
                                            + ' <span class="regula-price">' + ppms.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</span></ins></div>';
                        } else {
                            ep = ppms / coef;
                            pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' 
                                        + `<span class="currency">${currency}</span>` + 
                                        ' <span>(' + ppms.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span>';
                        }

                        $price_big.html(pbig_content).attr('attr-before', 'Цена: ').show();
                        $('.single_variation span.price').html(pbig_content);
                    } else {
                        if (min_price != min_rprice) {
                            ep = min_price / coef;
                            epr = min_rprice / coef;

                            pbig_content = '<div><del>' + epr.toFixed(2).replace('.', ',') 
                                            + '&nbsp;' + currency + ' <span class="regula-price">' 
                                            + min_rprice.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></del>';
                            pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span>(' + min_price.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></ins>';
                        } else {
                            ep = min_price / coef;

                            pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span>(' + min_price.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span>';
                        }

                        $price_big.html(pbig_content).attr('attr-before', 'Цена от ').show();
                    }

                });
            
                $(document).on('click','.wp-element-button', function() {
                    $('#smntcswcb').trigger('change');
                });
                // hook to quantity change event
                $(document).on('change', '[name=quantity]', function() {
                    if (!(this.value < 1)) {
                        var var_data = jQuery.parseJSON(jQuery('.variations_form').attr('data-product_variations'));
                        var price;

                        for (var i in var_data) {
                            if (var_data[i].attributes.attribute_pa_city == jQuery('#pa_city').val()) {
                                price = parseFloat(var_data[i].display_price);
                            }
                        }

                        var product_total = parseFloat(price) * this.value;
                        var area_total = parseFloat(coef) * this.value;

                        total_cost = parseFloat(product_total).toFixed(2).replace('.', ',') + '&nbsp;' + cur_sym<?php if ($coef > 0) { ?> + ' (' + area_total.toFixed(2).replace('.', ',') + '&nbsp;' + cur_suf + ')'
                    <?php } ?>;
                    $('#product_total_price .price .amount').html(total_cost);
                    }
                    $('#product_total_price').toggle(!(this.value < 1));
                });
                // Initial page setup
                // get minimal, regular and current prices
                ppms = 0;
                min_price = 0;

                for (var i in var_data) {
                    <?php if ($is_panel) { ?>
                        if (typeof var_data[i].attributes === 'undefined' || (var_data[i].attributes.attribute_pa_city != 'moscow' && var_data[i].attributes.attribute_pa_city != 'vladivostok'))
                            continue;
                    <?php        } ?>

                    if (var_data[i].attributes.attribute_pa_city == jQuery('#pa_city').val()) {
                        ppms = parseFloat(var_data[i].display_price);
                        ppmsr = parseFloat(var_data[i].display_regular_price);
                    }

                    if ((min_price == 0) || (parseFloat(var_data[i].display_price) < min_price)) {
                        min_price = parseFloat(var_data[i].display_price);
                        min_rprice = parseFloat(var_data[i].display_regular_price)
                    }
                }

                if (ppms == 0 && min_price == 0) {
                    $price_big.html('<?php echo EMPTY_PRICE_STRING; ?>').attr('attr-before', '').css('padding-left', '0em');
                    if ($('#pa_city').val() == '') {
                        $('.request-call-button-wrapper').hide();
                    } else {
                        $('.request-call-button-wrapper').show();
                    }
                    return;
                }

                // if price != 0
                if (ppms) {
                    if (ppms != ppmsr) {
                        ep = ppms / coef;
                        epr = ppmsr / coef;

                        pbig_content = '<del>' + epr.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + ppmsr.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></del>';
                        pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + ppms.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></ins>';

                        $('.zonsale.zn_badge_sale').html($('.zn_badge_sale_template').html());
                    } else {
                        ep = ppms / coef;
                        pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + ppms.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span>';
                        $('.zonsale.zn_badge_sale').html(sale_span);
                    }

                    $price_big.html(pbig_content).show();
                    $('.single_variation span.price').html(pbig_content);
                } else {
                    if (min_price != min_rprice) {
                        ep = min_price / coef;
                        epr = min_rprice / coef;

                        pbig_content = '<del>' + epr.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + min_rprice.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></del>';
                        pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + min_price.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></ins>';

                        $('.zonsale.zn_badge_sale').html($('.zn_badge_sale_template').html());
                    } else {
                        ep = min_price / coef;

                        pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + min_price.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span>';

                        $('.zonsale.zn_badge_sale').html(sale_span);
                    }

                    $price_big.html(pbig_content).attr('attr-before', 'Цена ').show();
                }

                <?php if ($is_panel) { ?>

                    if ($('#pa_city').val() == 'novosibirsk' || $('#pa_city').val() == 'pod-zakaz') {
                        // $('.single_variation_wrap').hide();
                        // $('.request-call-button-wrapper').show();
                        $price_big.html('<?php echo EMPTY_PRICE_STRING; ?>').attr('attr-before', '').css('padding-left', '0em');
                        return;
                    }
                    if ($('#pa_city').val() == 'vladivostok' || $('#pa_city').val() == 'moscow') {
                        $('.request-call-button-wrapper').hide();
                        $('.single_variation_wrap').show();
                    }
                    if ($('#pa_city').val() == '') {
                        $('.request-call-button-wrapper').hide();
                        // $('.single_variation_wrap').hide();
                    }
                <?php } else { ?>
                    $('.request-call-button-wrapper').hide();
                    $('.single_variation_wrap').show();
                <?php } ?>


                // set the city dropdown default value
                $('#pa_city').val(default_city);
                $('#pa_city').change();

            });
        <?php } else{ ?>
            jQuery(function($) {
             
                $('p.price').hide()
                let cur_sym = '<?php echo get_woocommerce_currency_symbol(); ?>';
                let currency = cur_sym + '<?php echo $default_cur_suffix; ?>';
                let coef = parseFloat('<?php echo $coef; ?>');
                let cur_suf = '<?php echo $cur_suffix; ?>';
                let def_cur = cur_sym + '<?php echo $default_cur_suffix; ?>';

                let price = parseFloat(<?php $price = get_post_meta(get_the_ID(), '_price'); echo $price[0]; ?>);
                let regular_price = parseFloat(<?php $price = get_post_meta(get_the_ID(), '_regular_price'); echo $price[0]; ?>);
              
                // let total_cost = parseFloat(product_total).toFixed(2).replace('.', ',') + '&nbsp;' + cur_sym;
                let ep = price / coef;
                
          
                $('form.cart').before('<p class="price-big" attr-before="Цена:"></p>');
                let $price_big = $('p.price-big');
                let pbig_content  = null; 
                if(regular_price != price){
                    epr = regular_price / coef;
                    pbig_content = '<div class="price-box"><del>' + epr.toFixed(2).replace('.', ',') 
                                    + '&nbsp;' + `<span class="currency">${currency}</span>`  
                                    + '<span class="regula-price">' 
                                    + regular_price.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</span></del>';

                    pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' 
                                    + `<span class="currency">${currency}</span>` 
                                    + ' <span class="regula-price">' + regular_price.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</span></ins></div>';
                }else{
                     pbig_content = '<span>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + `<span class="currency">${currency}</span>`
                                    + '</span><span class="regula-price">' 
                                    + price.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + '</span>';
                }
                if(regular_price === 0 ){
                    $price_big.html('<?php echo EMPTY_PRICE_STRING; ?>').attr('attr-before', '').css('padding-left', '0em');
                }else{
                    $price_big.html(pbig_content).attr('attr-before', 'Цена: ').show();
                }
                
                // $('p.price').html(pbig_content);

                // prepare quantity change hmtl
                $('form.cart .single_add_to_cart-box').append('<div id="product_total_price" style="display:none"><span class="total_price_label"></span><span class="total_price price"><span class="amount" style="white-space:nowrap;"></span></span>');
                // hook to quantity change event
                $('.wp-element-button').on('click', function() {
                    console.log( $('#smntcswcb').val())
                    $('#smntcswcb').trigger('change');
                });
                $(document).on('change', '[name=quantity]', function() {
              
                    if (!(this.value < 1) && regular_price > 0) {
                        let product_total = price * this.value;
                        let area_total = parseFloat(coef) * this.value;
                        let total_cost = parseFloat(product_total).toLocaleString() + '\u202f' + cur_sym
                                    <?php if ($coef > 0) { ?>
                                        + '&nbsp;' + '<span class="text-s sub">(' + area_total.toFixed(2).replace('.', ',') + '\u202f' + cur_suf + ')</span> '
                                    <?php } ?>;
                        $('#product_total_price .price .amount').html(total_cost);                                        
                    }
                    $('#product_total_price').toggle(!(this.value < 1));
                });
             
               
                if($('.single_add_to_cart_inner').hasClass('added')){ 
                    $('.minus').trigger('click');
                }

            });
       <?php  } ?>
    </script>
    <?php
}
function set_default_city($context = 'default')
{

    $default_city = '';

    switch ($context) {
        case 'product_archive_description':

            if (!empty($_GET['pa_city'])) {

                $default_city = $_GET['pa_city'];
            }
            break;
        case 'price':

            if (isset($_COOKIE['pa_city'])) {

                $default_city = $_COOKIE['pa_city'];
            }

            if (!empty($_GET['pa_city'])) {

                $default_city = $_GET['pa_city'];
            }

            if (empty($default_city)) {

                $default_city = get_city_from_visitors_ip();
            }
            break;
        case 'default':
        default:

            if (!empty($_GET['pa_city'])) {

                $default_city = $_GET['pa_city'];
            } else {

                if (isset($_COOKIE['pa_city'])) {

                    $default_city = $_COOKIE['pa_city'];
                } else {

                    $refererr = wp_get_referer();
                    $url_part = explode('?', $refererr);

                    $args = isset($url_part[1]) ? wp_parse_args($url_part[1]) : [];

                    if (isset($args['pa_city'])) {

                        $default_city = $args['pa_city'];
                    }
                }
            }

            if (empty($default_city)) {

                $default_city = get_city_from_visitors_ip();
            }
    }


    return $default_city;
}

function get_default_city($context = 'default')
{

    static $default_city;

    if (!isset($default_city)) {

        $default_city = [];
    }

    if ((!isset($default_city[$context])) || (empty($default_city[$context]))) {

        $default_city[$context] = set_default_city($context);
    }

    return $default_city[$context];
}

function fasad_loop_price_per_sqm_html($content)
{

    global $product;

    $default_content = $content;

    $default_city_variation = get_default_city_variation($product);

    if (!$default_city_variation->is_purchasable()) {

        return EMPTY_PRICE_STRING;
    }

    if ($default_city_variation->is_on_sale()) {

        //        $content = wc_format_sale_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ) . $product->get_price_suffix(), wc_get_price_to_display( $product ) ) . $product->get_price_suffix();

        $content = '<del aria-hidden="true">'
            . wc_price(wc_get_price_to_display($default_city_variation, array('price' => $default_city_variation->get_regular_price())))
            . $product->get_price_suffix()
            . '</del> <ins>'
            . wc_price(wc_get_price_to_display($default_city_variation))
            . '</ins>' . $product->get_price_suffix();
    }

    $count = 0;

    if($default_city_variation){

        $per_package_price  = $default_city_variation->get_regular_price();
        print_r( $per_package_price);
        $per_package_price  = str_replace('.', wc_get_price_decimal_separator(), wc_format_decimal((float)str_replace([',', wc_get_price_decimal_separator()], '.', $per_package_price), 2));
        $coef               = get_coef($product);
        $per_sqm_price      = str_replace('.', wc_get_price_decimal_separator(), wc_format_decimal(floor($per_package_price / $coef), 2));

        $content = str_replace($per_package_price, $per_sqm_price, $content, $count);
        if ($count > 0) {

            $content = str_replace('уп.', 'м<sup>2</sup>', $content, $count);
        }

        if ($default_city_variation->is_on_sale()) {

            $per_package_price  = str_replace('.', wc_get_price_decimal_separator(), wc_format_decimal($default_city_variation->get_sale_price(), 2));

            $per_sqm_price      = str_replace('.', wc_get_price_decimal_separator(), wc_format_decimal(floor($per_package_price / $coef), 2));

            $content = str_replace($per_package_price, $per_sqm_price, $content, $count);

            if ($count > 0) {

                $content = str_replace('уп.', 'м<sup>2</sup>', $content, $count);
            }

        }
    }else{

        $per_package_price  = $product->get_regular_price();
        $per_package_price  = str_replace('.', wc_get_price_decimal_separator(), wc_format_decimal((float)str_replace([',', wc_get_price_decimal_separator()], '.', $per_package_price), 2));
        $coef               = get_coef($product);
        $per_sqm_price      = str_replace('.', wc_get_price_decimal_separator(), wc_format_decimal(floor($per_package_price / $coef), 2));
        $content = str_replace($per_package_price, $per_sqm_price, $content, $count);
        if ($count > 0) {

            $content = str_replace('уп.', 'м<sup>2</sup>', $content, $count);
        }

        if ($product->is_on_sale()) {

            $per_package_price  = str_replace('.', wc_get_price_decimal_separator(), wc_format_decimal($product->get_sale_price(), 2));

            $per_sqm_price      = str_replace('.', wc_get_price_decimal_separator(), wc_format_decimal(floor($per_package_price / $coef), 2));

            $content = str_replace($per_package_price, $per_sqm_price, $content, $count);

            if ($count > 0) {

                $content = str_replace('уп.', 'м<sup>2</sup>', $content, $count);
            }
        }
    }

    return $content;
}

function hide_pod_zakaz_price($price_string, $product)
{

    $is_panel = product_is_panel($product->get_id());

    $default_city_variation = get_default_city_variation($product);

 
    if (false !== $default_city_variation) {

        if (($default_city_variation->get_price() === '1') || ($default_city_variation->get_price() === '0') || ($default_city_variation->get_stock_quantity() === 0) || (!$default_city_variation->is_purchasable())) {

            return EMPTY_PRICE_STRING;
        }

        add_filter('woocommerce_get_price_suffix', 'fasad_price_suffix', 99, 4);

        if ($is_panel) {
            add_filter('woocommerce_get_price_html', 'fasad_loop_price_per_sqm_html', 99);
        }

        if($default_city_variation){
            $price_string = $default_city_variation->get_price_html();
        }else{
            $price_string = $product->get_price_html();
        }
        if ($is_panel) {
            remove_filter('woocommerce_get_price_html', 'fasad_loop_price_per_sqm_html', 99);
        }

        remove_filter('woocommerce_get_price_suffix', 'fasad_price_suffix', 99);
    } else {

        return EMPTY_PRICE_STRING;
    }

    return $price_string;
}

function get_coef($product)
{

    $coef = 1;

    if ($product->has_dimensions()) {

        $dimensions = $product->get_dimensions(false);

        $coef = $dimensions['width'] * $dimensions['length'] / 1000000;
    }

    return $coef;
}


function zn_woocommerce_post_thumbnail_html_add_stock_badge()
{

    if (is_archive() || is_front_page()) {
        ob_start();
        loop_product_qty();
        $badge_html = ob_get_clean();
        print_r($badge_html);
        $html = str_replace('<span class="kw-prodimage">', '<span class="kw-prodimage">' . $badge_html, $html);
    }
    return $html;
}

function loop_product_qty()
{
    global $product;

    if (empty($product)) {

        return;
    }

    if ($product->get_type() == "composite") return;
    if ($product->get_type() == "simple") return;

    //	echo '<!-- QQQQQQ';var_dump($product);echo'-->';

    $count = 0;
    $qty_string = array(
        'moscow' => 'В&nbsp;Москве',
        'vladivostok' => 'Во&nbsp;Владивостоке',
        'novosibirsk' => 'В&nbsp; Новосибирске',
        'pod-zakaz' => 'под&nbsp;заказ'
    );

    $available_variations = $product->get_available_variations();

    $onsale_text = '';

    $html = ['start' => '', 'middle' => '', 'end' => ''];

    $html['start'] = '<div class="product-stock-info-badge">';
    foreach ($available_variations as $variation) {
        //		if ( isset($_REQUEST['pa_city']) && $variation["attributes"]["attribute_pa_city"] != $_REQUEST['pa_city'] )
        //			continue;

        if ('pod-zakaz' !== $variation["attributes"]["attribute_pa_city"] && (($variation["max_qty"] === '') || ($variation["current_stock"] > 0)) && ($variation["display_price"] > 0)) {
            $count += 1;
            $html['middle'] .= '<span style="float:left;clear:both;">' . $qty_string[$variation["attributes"]["attribute_pa_city"]] . ' ' . $variation["current_stock"] . ' шт.' . '</span>';
        }
        if ($variation["display_price"] != $variation["display_regular_price"]) {
            $onsale = true;
            $onsale_text .= (empty($onsale_text) ? '' : '<br/>') . 'Скидка&nbsp;' . intval(round(($variation["display_regular_price"] - $variation["display_price"]) / $variation["display_regular_price"] * 100)) . '%&nbsp;' . $qty_string[$variation["attributes"]["attribute_pa_city"]] . '!';
        }
        if ($variation['max_qty'] !==  '' && 'pod-zakaz' !== $variation["attributes"]["attribute_pa_city"] && 'moscow' !== $variation["attributes"]["attribute_pa_city"]) {
            // echo $variation['availability_html']
            // echo $variation["attributes"]["attribute_pa_city"];
            $count += 1;
            echo  $html['middle'] . '<span class="flag-item stock" style="float:left;clear:both;">В наличии</span>';
        }
    }
    $html['end'] = '</div>';

    if ($count > 0) {
        echo $html['start'] . $html['middle'] . $html['end'];
    } else {
        echo $html['start'] . '<span class="flag-item out-of-stock" style="float:left;clear:both;">Под заказ</span>' . $html['end'];
    }

    if (false && $onsale) {
        $onsale = false; ?>
        <div class="zn_badge_productlist_container">
            <span class="zonsale zn_badge_sale" style="display: block;"><?php echo $onsale_text; ?></span>
        </div>
<?php
    }
}
