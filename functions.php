<?php
/**
 * movies functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package movies
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

function movies_setup() {
	
	load_theme_textdomain( 'movies', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'movies' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-background',
		apply_filters(
			'movies_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'movies_setup' );

/**

 * @global int $content_width
 */
function movies_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'movies_content_width', 640 );
}
add_action( 'after_setup_theme', 'movies_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function movies_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'movies' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'movies' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'movies_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function movies_scripts() {
	wp_enqueue_style( 'movies-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'style', get_template_directory_uri() . '/dist/assets/css/style.min.css' );

	wp_style_add_data( 'movies-style', 'rtl', 'replace' );

	wp_enqueue_script( 'scripts', get_template_directory_uri() . '/dist/assets/js/main.min.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'movies-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'movies_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/*

add_action( 'init', 'register_post_types' );

function register_post_types(){

	register_post_type( 'movies', [
		'label'  => 'movies',
		'labels' => [
			'name'               => 'фильмы', // основное название для типа записи
			'singular_name'      => 'фильм', // название для одной записи этого типа
			'add_new'            => 'Добавить фильм', // для добавления новой записи
			'add_new_item'       => 'Добавление фильма', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование фильма', // для редактирования типа записи
			'new_item'           => 'Новый фильм', // текст новой записи
			'view_item'          => 'Смотреть фильм', // для просмотра записи этого типа.
			'search_items'       => 'Искать фильм', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'фильмы', // название меню
		],
		'description'            => '',
		'public'                 => true,
		'show_in_menu'           => null, // показывать ли в меню админки
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => null,
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor','thumbnail','excerpt','custom-fields' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => [],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );

}


add_action( 'init', 'create_taxonomy' );
function create_taxonomy(){

	register_taxonomy( 'taxonomy', [ 'movies' ], [
		'label'                 => 'genres', 
		'labels'                => [
			'name'              => 'Жанры',
			'singular_name'     => 'Жанр',
			'search_items'      => 'Искать жанры',
			'all_items'         => 'Все жанры',
			'view_item '        => 'Просмотреть жанры',
			'parent_item'       => 'Родительский жанр',
			'parent_item_colon' => 'Родительский жанр:',
			'edit_item'         => 'Редактировать жанр',
			'update_item'       => 'Обновить жанр',
			'add_new_item'      => 'Добавить новый жанр',
			'new_item_name'     => 'Новый жанр',
			'menu_name'         => 'Жанр',
			'back_to_items'     => '← Назад к жанрам',
		],
		'description'           => 'Жанры фильмов', // описание таксономии
		'public'                => true,
		'hierarchical'          => false,
		'rewrite'               => true,
		'capabilities'          => array(),
		'meta_box_cb'           => true, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy

	] );
}



*/