<?php
/*
Template Name: home
Template Post Type: post, page
*/
?>
<?php get_header(); ?>

<section class="top section" style="background-image: url(<?php the_field('top_img'); ?>);">
    <div class="container">
        <div class="top__wrapper">
            <div class="top__inner">
                <h1 class="top__title"><?php the_field('top_title'); ?></h1>
                <p class="top__text text-l"><?php the_field('top_text'); ?></p>
            </div>
            <div class="top-categories">
                <a class="top-category-item" href="#">
                    <div class="top-category-item__img">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/category/category-stock.svg " alt="stock">
                    </div>
                    <div class="top-category-item__name">В наличии</div>
                </a>
                <?php
                $prod_cat_args = array(
                    'taxonomy'    => 'product_cat',
                    'hide_empty'  => true, // скрывать категории без товаров или нет
                    'parent'      => 0 // id родительской категории
                );
                $woo_categories = get_categories($prod_cat_args);
                foreach ($woo_categories as $woo_cat) {
                    $woo_cat_id = $woo_cat->term_id; //category ID
                    $woo_cat_name = $woo_cat->name; //category name
                    $woo_cat_slug = $woo_cat->slug; //category slug
                    $category_thumbnail_id = get_term_meta($woo_cat_id, 'thumbnail_id', true);
                    $thumbnail_image_url = wp_get_attachment_url($category_thumbnail_id);  ?>
                    <a class="top-category-item" href="<?php echo get_term_link($woo_cat_id, 'product_cat'); ?>">
                        <div class="top-category-item__img">
                            <img src="<?php echo $thumbnail_image_url; ?>" alt="<?php echo $woo_cat->name; ?>">
                        </div>
                        <div class="top-category-item__name"><?php echo $woo_cat_name; ?></div>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<div class="home-catalog section">
    <div class="container">
        <div class="home-catalog__head block-head mb-4">
            <h2 class="home-catalog__title title">Каталог товаров</h2>
            <a class="home-catalog__link btn-sm btn outline" href="">Запросить прайс-лист</a>
        </div>
        <div class="home-catalog__inner tabs-block">
            <div class="home-catalog-sidebar tabs">
                <div class="home-catalog-sidebar__item tab active" data-id="1">Распродажа</div>
                <div class="home-catalog-sidebar__item tab" data-id="2">В наличии</div>
                <?php foreach ($woo_categories as $woo_cat) {
                    $woo_cat_id = $woo_cat->term_id; //category ID
                    $woo_cat_name = $woo_cat->name; //category name
                    $woo_cat_slug = $woo_cat->slug; //category slug
                ?>
                    <div class="home-catalog-sidebar__item tab" data-id="<?php echo $woo_cat_id; ?>"><?php echo $woo_cat_name; ?></div>
                <?php } ?>
            </div>
            <div class="catalog-tab_content tab_content">
                <div class="home-catalog__items tab-item active-tab" id="1">
                    <?php
                    // Сначала нужно получить список всех категорий товаров
                    $all_categories = get_terms('product_cat', array(
                        'hide_empty' => true,
                    ));
                    // Затем перебираем каждую категорию и проверяем наличие товаров со скидкой
                    $categories_with_discount = array();
                    foreach ($all_categories as $category) {
                        $category_id = $category->term_id;
                        // Получаем товары в данной категории
                        $args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => -1,
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field'    => 'term_id',
                                    'terms'    => $category_id,
                                ),
                            ),
                        );
                        $products = new WP_Query($args);
                        // Проверяем, есть ли товары со скидкой в данной категории
                        $has_discount = false;
                        $all_available = true;
                        while ($products->have_posts()) {
                            $products->the_post();
                            // Проверяем, есть ли у товара скидка
                            $product = wc_get_product(get_the_ID());
                            if ($product->is_on_sale()) {
                                $has_discount = true;
                                break;
                            }
                            if (!$product->is_in_stock()) {
                                $all_available = false;
                                break;
                            }
                        }
                        if ($all_available) {
                            $available_categories[] = $category;
                        }
                        // Если есть товары со скидкой в данной категории, добавляем ее в список
                        if ($has_discount) {
                            $categories_with_discount[] = $category;
                        }
                    }

                    // Выводим список категорий, в которых есть товары со скидкой
                    foreach ($categories_with_discount as $category) {
                        $category_thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $thumbnail_image_url = wp_get_attachment_url($category_thumbnail_id);
                    ?>
                        <a class="home-catalog-item" href="<?php echo get_term_link($category->term_id, 'product_cat'); ?>">
                            <div class="home-catalog-item__img">
                                <img src="<?php echo $thumbnail_image_url; ?>" alt="">
                            </div>
                            <h5 class="home-catalog-item__name"><?php echo $category->name; ?></h5>
                        </a>
                    <?php } ?>
                </div>
                <div class="home-catalog__items tab-item" id="2" style="display: none;">
                    <?php foreach ($available_categories as $category) {
                        $category_thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $thumbnail_image_url = wp_get_attachment_url($category_thumbnail_id); ?>
                        <a class="home-catalog-item" href="<?php echo get_term_link($category->term_id, 'product_cat'); ?>">
                            <div class="home-catalog-item__img">
                                <img src="<?php echo $thumbnail_image_url; ?>" alt="">
                            </div>
                            <h5 class="home-catalog-item__name"><?php echo $category->name; ?></h5>
                        </a>
                    <?php } ?>
                </div>
                <?php foreach ($woo_categories as $woo_cat) { ?>
                    <div class="home-catalog__items tab-item" id="<?php echo $woo_cat->term_id; ?>" style="display: none;">
                        <?php
                        // Задайте ID категории товаров
                        $category_id = $woo_cat->term_id;

                        // Получите объект категории на основе ID
                        $category = get_term_by('id', $category_id, 'product_cat');

                        // Проверьте, существует ли категория
                        if ($category) {
                            // Создайте пустой массив для атрибутов и их значений
                            $attributes = array();

                            // Получите все товары в данной категории
                            $args = array(
                                'post_type'      => 'product',
                                'posts_per_page' => -1,
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field'    => 'term_id',
                                        'terms'    => $category->term_id,
                                    ),
                                ),
                            );
                            $products = get_posts($args);

                            // Переберите все товары
                            foreach ($products as $product) {
                                // Получите все атрибуты товара
                                $product_attributes = wc_get_product($product->ID)->get_attributes();

                                // Переберите все атрибуты товара
                                foreach ($product_attributes as $attribute) {
                                    // Проверьте, существует ли атрибут в массиве атрибутов
                                    if (!isset($attributes[$attribute->get_name()])) {
                                        // Если атрибут не существует, добавьте его в массив атрибутов
                                        $attributes[$attribute->get_name()] = array();
                                    }

                                    // Получите значения атрибута
                                    $attribute_values = $attribute->get_terms();

                                    // Переберите все значения атрибута и добавьте их в массив атрибутов
                                    foreach ($attribute_values as $value) {
                                        $attributes[$attribute->get_name()][] = $value->name;
                                    }
                                }
                            }

                            $exclude_attributes = array('pa_city', 'pa_dopolnitelnaya-zashhita', 'pa_dopolnitelno',  'pa_tolshhina', 'pa_series', 'pa_tsvet', 'pa_vid');
                            // Выведите список атрибутов и их значений
                            $arry_attr = [];
                            foreach ($attributes as $attribute_name => $attribute_values) {
                                if (!in_array($attribute_name, $exclude_attributes)) {
                                    // echo '<h4>' . $attribute_name . '</h4>';
                                    // echo '<ul>';
                                    foreach ($attribute_values as $value) {
                                        array_push($arry_attr, $value);
                                        // echo '<li>' . $value . '</li>';
                                    }
                                    // echo '</ul>';
                                    echo '<pre>';
                                    $distinct = array_unique($arry_attr);
                                    print_r($distinct);
                                    echo '</pre>';
                                }
                            }
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="section services">
    <div class="container">
        <div class="services__inner">
            <h2 class="services__title title mb-4"><?php the_field('services_title'); ?></h2>
            <div class="services__items">
                <div class="services-item">
                    <?php $information = get_field('information'); ?>
                    <h3 class="services-item__title mb-2 title-md">Информация</h3>
                    <nav class="services__nav">
                        <ul class="services__list">
                            <?php foreach ($information['links'] as $key => $item) { ?>
                                <li class="services__li">
                                    <a class="services__link" href="<?php echo $item['link']['url']; ?>">
                                        <span><?php echo $item['link']['title']; ?></span>
                                        <?php echo file_get_contents($item['icon']['url']); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
                <div class="services-item">
                    <?php $services = get_field('services'); ?>
                    <h3 class="services-item__title mb-2 title-md">Услуги</h3>
                    <nav class="services__nav">
                        <ul class="services__list">
                            <?php foreach ($services['links'] as $key => $item) { ?>
                                <li class="services__li">
                                    <a class="services__link" href="<?php echo $item['link']['url']; ?>">
                                        <span><?php echo $item['link']['title']; ?></span>
                                        <?php echo file_get_contents($item['icon']['url']); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="popular section">
    <div class="container">
        <div class="block-head">
            <h2 class="home-catalog__title title"></h2>
            <a class="home-catalog__link btn-sm btn outline" href="">Смотреть все в категории</a>
        </div>
        <div class="popular__inner">
            <div class="tabs-block">
                <div class="popular-head">
                    <div class="popular-tab tabs">
                        <span class="tab active" data-id="1">Хит</span>
                        <span class="tab" data-id="2">в наличии</span>
                        <span class="tab" data-id="3">под заказ</span>
                    </div>
                    <div class="popular-buttons">
                        <div class="popular-btn-prev swiper-button-prev swiper-btn"></div>
                        <div class="popular-btn-next swiper-button-next swiper-btn"></div>
                    </div>
                </div>
                <div class="popular-tab_content tab_content">
                    <div class="tab-item active-tab" id="1">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">Slide 1</div>
                            </div>
                        </div>
                        <div class="tab-item" id="2" style="display: none;"></div>
                        <div class="tab-item" id="3" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="about section">
    <div class="container">
        <div class="about__inner d-flex">
            <div class="about__content">
                <h3 class="about__title title mb-4"><?php the_field('about_title'); ?></h3>
                <div class="about__text"><?php the_field('about_text'); ?></div>
            </div>
            <ul class="about__items">
                <?php $benefits = get_field('benefits');
                foreach ($benefits as $key => $item) { ?>
                    <li class="about-item text-l"><?php echo $item['text']; ?></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</section>
<div class="section home-gallary">
    <div class="container">
        <h3 class="title gallary__title mb-4">Галерея выполненных проектов</h3>
        <div class="tabs-block">
            <div class="home-gallary-head">
                <div class="tabs gallary-tabs">
                    <span class="tab active tab-btn" data-id="1">Фасады</span>
                    <span class="tab tab-btn" data-id="2">Террасная доска</span>
                </div>
                <div class="gallary-buttons">
                    <div class="gallary-btn-prev swiper-button-prev swiper-btn"></div>
                    <div class="gallary-btn-next swiper-button-next swiper-btn"></div>
                </div>
            </div>
            <div class="tab_content">
                <div class="tab-item active-tab" id="1">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">Slide 1</div>
                        </div>
                    </div>
                    <div class="tab-item" id="2" style="display: none;">2</div>
                </div>
            </div>
        </div>
    </div>
    <?php get_footer(); ?>