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

	wp_enqueue_script( 'scripts', get_template_directory_uri() . '/dist/assets/js/main.min.js', array('jquery'), _S_VERSION, true );

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





function kama_excerpt( $args = '' ){
	global $post;

	if( is_string( $args ) ){
		parse_str( $args, $args );
	}

	$rg = (object) array_merge( [
		'maxchar'           => 350,
		'text'              => '',
		'autop'             => true,
		'more_text'         => 'Читать дальше...',
		'ignore_more'       => false,
		'save_tags'         => '<strong><b><a><em><i><var><code><span>',
		'sanitize_callback' => static function( string $text, object $rg ){
			return strip_tags( $text, $rg->save_tags );
		},
	], $args );

	$rg = apply_filters( 'kama_excerpt_args', $rg );

	if( ! $rg->text ){
		$rg->text = $post->post_excerpt ?: $post->post_content;
	}

	$text = $rg->text;
	// strip content shortcodes: [foo]some data[/foo]. Consider markdown
	$text = preg_replace( '~\[([a-z0-9_-]+)[^\]]*\](?!\().*?\[/\1\]~is', '', $text );
	// strip others shortcodes: [singlepic id=3]. Consider markdown
	$text = preg_replace( '~\[/?[^\]]*\](?!\()~', '', $text );
	// strip direct URLs
	$text = preg_replace( '~(?<=\s)https?://.+\s~', '', $text );
	$text = trim( $text );

	// <!--more-->
	if( ! $rg->ignore_more && strpos( $text, '<!--more-->' ) ){

		preg_match( '/(.*)<!--more-->/s', $text, $mm );

		$text = trim( $mm[1] );

		$text_append = sprintf( ' <a href="%s#more-%d">%s</a>', get_permalink( $post ), $post->ID, $rg->more_text );
	}
	// text, excerpt, content
	else {

		$text = call_user_func( $rg->sanitize_callback, $text, $rg );
		$has_tags = false !== strpos( $text, '<' );

		// collect html tags
		if( $has_tags ){
			$tags_collection = [];
			$nn = 0;

			$text = preg_replace_callback( '/<[^>]+>/', static function( $match ) use ( & $tags_collection, & $nn ){
				$nn++;
				$holder = "~$nn";
				$tags_collection[ $holder ] = $match[0];

				return $holder;
			}, $text );
		}

		// cut text
		$cuted_text = mb_substr( $text, 0, $rg->maxchar );
		if( $text !== $cuted_text ){

			// del last word, it not complate in 99%
			$text = preg_replace( '/(.*)\s\S*$/s', '\\1...', trim( $cuted_text ) );
		}

		// bring html tags back
		if( $has_tags ){
			$text = strtr( $text, $tags_collection );
			$text = force_balance_tags( $text );
		}
	}

	// add <p> tags. Simple analog of wpautop()
	if( $rg->autop ){

		$text = preg_replace(
			[ "/\r/", "/\n{2,}/", "/\n/" ],
			[ '', '</p><p>', '<br />' ],
			"<p>$text</p>"
		);
	}

	$text = apply_filters( 'kama_excerpt', $text, $rg );

	if( isset( $text_append ) ){
		$text .= $text_append;
	}

	return $text;
}

	// include custom jQuery
	function shapeSpace_include_custom_jquery() {
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
	   }
	   add_action('wp_enqueue_scripts', 'shapeSpace_include_custom_jquery');




add_action('wp_ajax_myfilter', 'misha_filter_function'); // wp_ajax_{ACTION HERE} 
add_action('wp_ajax_nopriv_myfilter', 'misha_filter_function');

function misha_filter_function(){
	$args = array(
		'post_type'   => 'movies',
		'order' => 'DESC',
	);
 
 
	// create $args['meta_query'] array if one of the following fields is filled
	if( isset( $_POST['price_min'] ) && $_POST['price_min'] || isset( $_POST['price_max'] ) && $_POST['price_max'] || isset( $_POST['date_min'] ) && $_POST['date_max'] )
		$args['meta_query'] = array( 'relation'=>'AND' ); // AND means that all conditions of meta_query should be true
 
	// if both minimum price and maximum price are specified we will use BETWEEN comparison
	if( isset( $_POST['price_min'] ) && $_POST['price_min'] && isset( $_POST['price_max'] ) && $_POST['price_max'] ) {
		$args['meta_query'][] = array(
			"meta_key" => "stoimost",  
			'value' => array( $_POST['price_min'], $_POST['price_max'] ),
			'type' => 'numeric',
			'compare' => 'between'
		);
	} else {
		// if only min price is set
		if( isset( $_POST['price_min'] ) && $_POST['price_min'] )
			$args['meta_query'][] = array(
				'key' => 'stoimost',
				'value' => $_POST['price_min'],
				'type' => 'numeric',
				'compare' => '>='
			);
 
		// if only max price is set
		if( isset( $_POST['price_max'] ) && $_POST['price_max'] )
			$args['meta_query'][] = array(
				'key' => 'stoimost',
				'value' => $_POST['price_max'],
				'type' => 'numeric',
				'compare' => '<='
			);
	}


	 if( isset( $_POST['date_min'] ) && $_POST['date_min'] && isset( $_POST['date_max'] ) && $_POST['date_max'] ) {
		$args['meta_query'][] = array(
			"meta_key" => "vremya_seansa",  
			'value' => array( $_POST['date_min'], $_POST['date_max'] ),
			'type' => 'date',
			'compare' => 'between'
		);
	} else {
		// if only min date is set
		if( isset( $_POST['date_min'] ) && $_POST['date_min'] )
			$args['meta_query'][] = array(
				'key' => 'vremya_seansa',
				'value' => $_POST['date_min'],
				'type' => 'date',
				'compare' => '>='
			);
 
		// if only max date is set
		if( isset( $_POST['date_max'] ) && $_POST['date_max'] )
			$args['meta_query'][] = array(
				'key' => 'vremya_seansa',
				'value' => $_POST['date_max'],
				'type' => 'date',
				'compare' => '<='
			);
	}
 // Определяем, по какому параметру нужно сортировать фильмы
 $sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : '';
 switch ($sort_by) {
   case 'date_asc':
	$args['meta_key'] = 'vremya_seansa';
	$args['orderby'] = 'meta_value_num';
	 $args['order'] = 'ASC';
	 break;
   case 'date_desc':
	$args['meta_key'] = 'vremya_seansa';
	$args['orderby'] = 'meta_value_num';
	 $args['order'] = 'DESC';
	 break;
   case 'price_asc':
	 $args['meta_key'] = 'stoimost';
	 $args['orderby'] = 'meta_value_num';
	 $args['order'] = 'ASC' ;
	 break;
	 case 'price_desc':
	 $args['meta_key'] = 'stoimost';
	 $args['orderby'] = 'meta_value_num';
	 $args['order'] = 'DESC';
	 break;
	 default:
	 break;
	 }

	 if( isset( $_POST['genres'] ) && $_POST['genres'] == 'on' && isset( $_POST['taxonomy_term'] ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'genres',
				'field' => 'slug',
				'terms' => sanitize_text_field( $_POST['taxonomy_term'] )
			)
		);
	}

$query = new WP_Query( $args );
	
if( $query->have_posts() ) :
    while( $query->have_posts() ): $query->the_post();
        echo '<div class="catalog__item-card">';
        $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
		echo '<a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), array(300, 400) ) . '</a>';
		echo '<h2><a href="' . get_permalink() . '">' . $query->post->post_title . '</a></h2>';
        echo '<div class="catalog__item-description">' . kama_excerpt( [ 'maxchar'=>100 ] ) . '</div>';
        $termini = get_the_terms( $post, array('lands') );
        if ( $termini && ! is_wp_error( $termini ) ) {
            $termini_massiv = array();
            foreach ( $termini as $termin ) {
                // добавление элемента в массив
                $termini_massiv[] = '<a href="' . get_term_link( $termin ) . '" title="Перейти к ' . esc_attr( $termin->name ) .  '">' . $termin->name . '</a>';
            }
            $termini_a_hrefs = join( ", ", $termini_massiv );
            echo '<div class="catalog__item-title">Страны: <span class="catalog__item-text">' . $termini_a_hrefs . '</span></div>';
        } 
        echo '<h3 class="catalog__item-title">Актёры:</h3>';
        $actors = get_the_terms( get_the_ID(), 'actors' );
        if ( $actors && ! is_wp_error( $actors ) ) :
            ?>
            <ul class="vendors">
                <?php foreach ( $actors as $actor ) : ?>
                    <li><a href="<?php echo esc_url( get_term_link( $actor ) ); ?>"><?php echo esc_html( $actor->name ); ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif;
        echo '<h3 class="catalog__item-title">Дата выхода:</h3>';
        the_field('vremya_seansa');
        echo '<h3 class="catalog__item-title">Стоимость:</h3>';
        echo '<span>';
        the_field('stoimost');
        echo ' рублей</span>';
        echo '</div>';
    endwhile;
    wp_reset_postdata();
else :
    echo 'Ниччего не найдено';
endif;

die();
}



