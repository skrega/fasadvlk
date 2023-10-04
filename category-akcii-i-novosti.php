<?php
/*
Template Name: Акции и новости
Template Post Type: page
*/
get_header(); ?>
<div class="posts section">
    <div class="container">
        <?php echo bcn_display(); ?>
        <h1 class="title-lg mb-5"><?php single_cat_title(); ?></h1>
        <div class="tabs-block">
            <div class="posts-tabs mb-3">
                <?php $categories = get_categories([
                    'taxonomy'   => 'category',
                    'type'       => 'post',
                    'include'    => array(1, 8),
                    'hide_empty' => 1,
                ]); ?>
                <div class="tab-btn" data-filter=".all">Все</div>
                <?php foreach ($categories as $key => $cat) { ?>
                    <div class="tab-btn" data-filter=".<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></div>
                <?php } ?>
            </div>
            <?php
            $args = array(
                'post_type' => 'post',
                'cat' => '1,8', // ID категорий, разделенных запятой
            );
            $posts = new WP_Query($args); ?>
            <div class="post__items">
                <?php
                if ($posts->have_posts()) {
                    while ($posts->have_posts()) {
                        $posts->the_post();
                        $id = get_the_ID();
                        $categories = get_the_category($id);
                        $link = get_permalink($id);
                        $title = get_the_title($id);
                        $thumbnail = get_the_post_thumbnail_url($id);
                        $color = get_field('color', $posts);
                        if ($thumbnail == '') {
                            $img = 'style="background-color:' . $color . ';"';
                        } else {
                            $img = 'style="background: linear-gradient(0deg, rgba(2, 13, 38, 0.60) 0%, rgba(2, 13, 38, 0.60) 100%), url(' . $thumbnail . '),no-repeat, #127FFF;"';
                        }
                ?>

                        <div class="post-item mix all <?php foreach ($categories as $cat) {echo $cat->slug . ' '; }?>">
                            <a class="post-item__head" href="<?php echo $link; ?>" <?php echo $img; ?>>
                                <div class="post-item-tags text-s">
                                    <?php $tags = get_the_tags($id);
                                    foreach ($tags as $key => $tag) { ?>
                                        <span class="post-item-tag"><?php echo $tag->name; ?></span>
                                    <?php } ?>
                                </div>
                                <h5 class="post-item__title"><?php echo $title; ?></h5>
                            </a>
                            <div class="post-item__content">
                                <div class="post-item__text text-s"> <?php the_content(); ?></div>
                                <div class="post-item__subtext text-s">Время на чтение ~4 мин</div>
                                <a class="post-item__btn btn-sm btn outline" href="<?php echo $link; ?>">Подробнее</a>
                            </div>
                        </div>
                <?php }
                }
                wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>