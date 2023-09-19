<?php

// Регистрируем тип поста "Контейнер"
add_action('init', 'register_container_post_type');
function register_container_post_type()
{
	register_post_type('container', array(
		'labels' => array(
			'name'               => 'Контейнеры', // основное название для типа записи
			'singular_name'      => 'Контейнер', // название для одной записи этого типа
			'add_new'            => 'Добавить Контейнер', // для добавления новой записи
			'add_new_item'       => 'Добавление Контейнера', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование контейнера', // для редактирования типа записи
			'new_item'           => 'Новый Контейнер', // текст новой записи
			'view_item'          => 'Смотреть Контейнер', // для просмотра записи этого типа.
			'search_items'       => 'Искать Контейнер', // для поиска по этим типам записи
			'not_found'          => 'Контейнер не найдена', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Контейнер не найдена в корзине', // если не было найдено в корзине
			'menu_name'          => 'Контейнеры', // название меню
		),
		'description'         => 'Основные Контейнеры REFKINGS',
		'public'              => true,
		'show_in_menu'        => true, // показывать ли в меню адмнки
		'show_in_rest'        => false, // добавить в REST API. C WP 4.7
		'publicly_queryable'  => true,
		'menu_position'       => 21,
		'menu_icon'           => 'dashicons-archive',
		'hierarchical'        => true,
		'supports'            => array('title', 'thumbnail', 'page-attributes'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => array('post_tag'),
		'has_archive'         => true,
		'rewrite'             => array('slug' => 'container'), //array( 'slug' => 'container' )
		'query_var'           => true,
	));
}

// Добавляем иерархическую таксономию "Категория Контейнеры"
add_action('init', 'register_taxonomy_for_container');
function register_taxonomy_for_container()
{
	register_taxonomy('container_category', array('container'), array(
		'labels'                => array(
			'name'              => 'Категории',
			'singular_name'     => 'Категория',
			'search_items'      => 'Искать категории',
			'all_items'         => 'Все категории',
			'view_item '        => 'Посмотреть категории',
			'parent_item'       => null,
			'parent_item_colon' => null,
			'edit_item'         => 'Редактирование категории',
			'update_item'       => 'Обновление категории',
			'add_new_item'      => 'Добавить новую категорию',
			'new_item_name'     => 'Новая категория',
			'menu_name'         => 'Категории',
		),
		'description'           => 'Таксономия для указания категории Контейнера', // описание таксономии
		'public'                => true,
		'show_tagcloud'         => true, // равен аргументу show_ui
		'show_in_rest'          => true, // добавить в REST API
		'rest_base'             => '', // $taxonomy
		'hierarchical'          => true,
		'update_count_callback' => '',
		'rewrite'               => true,
		'capabilities'          => array(),
		'meta_box_cb'           => null, // callback функция. Отвечает за html код метабокса (с версии 3.8): post_categories_meta_box или post_tags_meta_box. Если указать false, то метабокс будет отключен вообще
		'show_admin_column'     => false, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
		'_builtin'              => false,
	));
}


// Регистрируем колонки
add_filter('manage_edit-container_columns', 'columns_container'); // manage_edit-CUSTOM-POST-TYPE_columns

function columns_container($columns)
{
	$columns = array_slice($columns, 0, 2, true) +
		array("container_category" => "Категория") +
		// array("container_sub_category" => "Подкатегория") + 
		array_slice($columns, 2, count($columns) - 1, true);
	return $columns;
}

// Наполняем колонки
add_action('manage_container_posts_custom_column', 'populate_container_columns', 10, 2); // manage_CUSTOM-POST-TYPE_posts_custom_column

function populate_container_columns($column)
{
	$container_id = get_the_ID();
	$terms = get_the_terms($container_id, 'container_category');

	$category = array_filter($terms, function ($var) {
		return $var->parent == '0';
	});

	$category = reset($category);

	// $sub_category = array_filter($terms, function($var) {
	//     return $var->parent != '0';
	// });

	// $sub_category = reset($sub_category);

	if ('container_category' == $column) {
		print_r($category->name);
	}

	// if ( 'container_sub_category' == $column ) {
	// 	print_r($sub_category->name);
	// }
}

add_filter('manage_edit-container_sortable_columns', 'my_sortable_container_column');
function my_sortable_container_column($columns)
{
	$columns['container_category'] = 'container_category';
	// $columns['container_sub_category'] = 'container_sub_category';
	return $columns;
}

// Удаляем слаг таксономии из URL кастомной таксономии, категории или тега
add_filter('request', 'rudr_change_term_request', 1, 1);

function rudr_change_term_request($query)
{

	$tax_name = 'container_category'; // specify you taxonomy name here, it can be also 'category' or 'post_tag'

	// Request for child terms differs, we should make an additional check
	if ($query['attachment']) :
		$include_children = true;
		$name = $query['attachment'];
	else :
		$include_children = false;
		$name = $query['name'];
	endif;


	$term = get_term_by('slug', $name, $tax_name); // get the current term to make sure it exists

	if (isset($name) && $term && !is_wp_error($term)) : // check it here

		if ($include_children) {
			unset($query['attachment']);
			$parent = $term->parent;
			while ($parent) {
				$parent_term = get_term($parent, $tax_name);
				$name = $parent_term->slug . '/' . $name;
				$parent = $parent_term->parent;
			}
		} else {
			unset($query['name']);
		}

		switch ($tax_name):
			case 'category': {
					$query['category_name'] = $name; // for categories
					break;
				}
			case 'post_tag': {
					$query['tag'] = $name; // for post tags
					break;
				}
			default: {
					$query[$tax_name] = $name; // for another taxonomies
					break;
				}
		endswitch;

	endif;

	return $query;
}

add_filter('term_link', 'rudr_term_permalink', 10, 3);

function rudr_term_permalink($url, $term, $taxonomy)
{

	$taxonomy_name = 'container_category'; // your taxonomy name here
	$taxonomy_slug = 'container_category'; // the taxonomy slug can be different with the taxonomy name (like 'post_tag' and 'tag' )

	// exit the function if taxonomy slug is not in URL
	if (strpos($url, $taxonomy_slug) === FALSE || $taxonomy != $taxonomy_name) return $url;

	$url = str_replace('/' . $taxonomy_slug, '', $url);

	return $url;
}

//301 redirect
add_action('template_redirect', 'rudr_old_term_redirect');

function rudr_old_term_redirect()
{

	$taxonomy_name = 'container_category';
	$taxonomy_slug = 'container_category';

	// exit the redirect function if taxonomy slug is not in URL
	if (strpos($_SERVER['REQUEST_URI'], $taxonomy_slug) === FALSE)
		return;

	if ((is_category() && $taxonomy_name == 'category') || (is_tag() && $taxonomy_name == 'post_tag') || is_tax($taxonomy_name)) :

		wp_redirect(site_url(str_replace($taxonomy_slug, '', $_SERVER['REQUEST_URI'])), 301);
		exit();

	endif;
}
