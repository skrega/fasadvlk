<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('COEF_PRICE_SORT_META_ID', 'coef_price');
define('COEF_PRICE_META_ID', 'coef_price');
define('COEF_PRICE_META_ID_PREFIX', 'coef_');
define('EMPTY_PRICE_STRING', 'Цену уточняйте у менеджера');


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
                            $('.single_variation_wrap').hide();
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
                            $('.single_variation_wrap').hide();
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
        jQuery(function($) {
            var sale_span = ($('.zonsale.zn_badge_sale').length > 0) ? $('.zn_badge_sale_template').html() : '';
            var default_city = '<?php echo $default_city; ?>'
            var coef = parseFloat('<?php echo $coef; ?>');
            var cur_sym = '<?php echo get_woocommerce_currency_symbol(); ?>';
            var cur_suf = '<?php echo $cur_suffix; ?>';
            var currency = cur_sym + cur_suf;
            var def_cur = cur_sym + '<?php echo $default_cur_suffix; ?>';
            var ppms, ppmsr, ep, epr, pbig_content, min_price = 0,
                min_rprice,
                var_data = jQuery.parseJSON(jQuery('.variations_form').attr('data-product_variations'));

            // hide default price
            $('p.price').hide();

            // add custom price tag
            $('.controls-data form .variations').after('<p class="price-big" attr-before="Цена:"></p>');
            // $('div.product_meta').after('<p class="price-big" attr-before="Цена:"></p>');
            var $price_big = $('p.price-big');

            // prepare quantity change html
            $('.single_variation_wrap').append('<div id="product_total_price" style="display:none"><span class="total_price_label">Итого</span><span class="total_price price"><span class="amount" style="white-space:nowrap;"></span></span>');

            <?php if ($is_panel) { ?>

                if ($('#pa_city').val() == 'novosibirsk' || $('#pa_city').val() == 'pod-zakaz' /*|| $('#pa_city').val() == 'vladivostok'*/ ) {
                    $('.single_variation_wrap').hide();
                    $('.request-call-button-wrapper').show();
                    $price_big.html('<?php echo EMPTY_PRICE_STRING; ?>').attr('attr-before', '').css('padding-left', '0em');
                }
                if ($('#pa_city').val() == 'vladivostok' || $('#pa_city').val() == 'moscow') {
                    $('.request-call-button-wrapper').hide();
                    $('.single_variation_wrap').show();
                }
                if ( /*$('#pa_city').val() == 'vladivostok' || */ $('#pa_city').val() == '') {
                    $('.request-call-button-wrapper').hide();
                    $('.single_variation_wrap').hide();
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
                        $('.single_variation_wrap').hide();
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
                        $('.single_variation_wrap').hide();
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

                        pbig_content = '<del>' + epr.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + ppmsr.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></del>';
                        pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + ppms.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></ins>';
                    } else {
                        ep = ppms / coef;

                        pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + ppms.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span>';
                    }

                    $price_big.html(pbig_content).attr('attr-before', 'Цена: ').show();
                    $('.single_variation span.price').html(pbig_content);
                } else {
                    if (min_price != min_rprice) {
                        ep = min_price / coef;
                        epr = min_rprice / coef;

                        pbig_content = '<del>' + epr.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + min_rprice.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></del>';
                        pbig_content = pbig_content + '<ins>' + ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + min_price.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span></ins>';
                    } else {
                        ep = min_price / coef;

                        pbig_content = ep.toFixed(2).replace('.', ',') + '&nbsp;' + currency + ' <span style="font-size: 0.7em;white-space: nowrap;">(' + min_price.toFixed(2).replace('.', ',') + '&nbsp;' + def_cur + ')</span>';
                    }

                    $price_big.html(pbig_content).attr('attr-before', 'Цена от ').show();
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
                    $('.single_variation_wrap').hide();
                    $('.request-call-button-wrapper').show();
                    $price_big.html('<?php echo EMPTY_PRICE_STRING; ?>').attr('attr-before', '').css('padding-left', '0em');
                    return;
                }
                if ($('#pa_city').val() == 'vladivostok' || $('#pa_city').val() == 'moscow') {
                    $('.request-call-button-wrapper').hide();
                    $('.single_variation_wrap').show();
                }
                if ($('#pa_city').val() == '') {
                    $('.request-call-button-wrapper').hide();
                    $('.single_variation_wrap').hide();
                }
            <?php } else { ?>
                $('.request-call-button-wrapper').hide();
                $('.single_variation_wrap').show();
            <?php } ?>


            // set the city dropdown default value
            $('#pa_city').val(default_city);
            $('#pa_city').change();

        });
    </script>
<?php
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

        $price_string = $default_city_variation->get_price_html();

        if ($is_panel) {
            remove_filter('woocommerce_get_price_html', 'fasad_loop_price_per_sqm_html', 99);
        }

        remove_filter('woocommerce_get_price_suffix', 'fasad_price_suffix', 99);
    } else {

        return EMPTY_PRICE_STRING;
    }

    return $price_string;
}
