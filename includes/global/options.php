<?php

/* Создаем глобальные настройки */
function ea_acf_options_page()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => 'Общие настройки',
            'menu_title' => 'Общие настройки',
            'menu_slug'  => 'global-options',
            'capability' => 'edit_posts',
            'icon_url'   => 'dashicons-superhero-alt',
            'redirect'   => false,
            'position'   => 8,
        ));
    }
}

add_action('init', 'ea_acf_options_page');
