<?php
/**
 * phylo functions and definitions
 *
 * @package phylo
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

define( 'ACF_LITE', true );
include_once('advanced-custom-fields/acf.php');

add_filter( 'jetpack_development_mode', '__return_true' );

// require_once( get_template_directory(). '/acf/acf-lite.php');
require_once( get_template_directory(). '/card-api/card-api.php');

require_once( get_template_directory(). '/inc/convert-post-to-card.php');

if ( ! function_exists( 'phylo_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function phylo_setup() {

	require( get_template_directory() . '/inc/api.php' );
	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );


	/**
	 * Shortcodes
	 */
	 require( get_template_directory() . '/inc/shortcodes.php' );


	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on phylo, use a find and replace
	 * to change 'phylo' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'phylo', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'phylo' ),
		'loggedin'=>__('Loggedin Menu', 'phylo'),
	) );

	/**
	 * Enable support for Post Formats
	 */
	// add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
	add_filter( 'body_class' , 'phylo_body_class' );

	add_theme_support( 'infinite-scroll', array(
	    'type'           => 'click',
	    'footer_widgets' => false,
	    'container'      => 'post-shell',
	    'wrapper'        => false,
	    'render'         => 'phylo_infinite_scroll_render',
	    'posts_per_page' => 9,
	) );

}

endif; // phylo_setup
add_action( 'after_setup_theme', 'phylo_setup' );

add_action( 'wp_print_styles', 'phylo_deregister_styles', 100 );
function phylo_deregister_styles() {
	wp_deregister_style( 'wp-admin' );
}

function phylo_body_class( $classes ) {

	if( phylo_is_cards_list_view() )
		$classes[] = 'cards-list-view';

	return $classes;
}

function phylo_is_cards_list_view(){
	if( is_tax( 'deck')
	|| 	is_tax( 'type-of')
	|| 	is_tax( 'classification')
	||  is_post_type_archive( 'card' )
	||  is_post_type_archive( 'diy-card' )
	|| 	is_page_template( 'my-cards-page.php' )
	||  isset( $_GET['selected'] ) ) {
		return true;
	}
	return false;
}
/**
 * Setup the WordPress core custom background feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for WordPress 3.3
 * using feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * @todo Remove the 3.3 support when WordPress 3.6 is released.
 *
 * Hooks into the after_setup_theme action.
 */
function phylo_register_custom_background() {
	$args = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);

	$args = apply_filters( 'phylo_custom_background_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-background', $args );
	} else {
		define( 'BACKGROUND_COLOR', $args['default-color'] );
		if ( ! empty( $args['default-image'] ) )
			define( 'BACKGROUND_IMAGE', $args['default-image'] );
		add_custom_background();
	}
}
add_action( 'after_setup_theme', 'phylo_register_custom_background' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function phylo_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'phylo' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );


	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'phylo' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer', 'phylo' ),
		'id'            => 'footer',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

}
add_action( 'widgets_init', 'phylo_widgets_init' );

function phylo_custom_rewrite_rule() {
	add_rewrite_rule('^diy-decks/([^/]*)/page/?([0-9]{1,})/?','index.php?post_type=diy-card&author_name=$matches[1]&paged=$matches[2]','top');
	add_rewrite_rule('^diy-decks/([^/]*)/?','index.php?post_type=diy-card&author_name=$matches[1]','top');

}
add_action('init', 'phylo_custom_rewrite_rule', 10, 0);


function phylo_custom_rewrite_query( $query ) {

    if ( isset( $query->query_vars[ 'author_name'] ) && $query->query_vars[ 'post_type'] == 'diy-card' && $query->is_main_query() ) {
    	$user = get_user_by( 'slug', $query->query_vars[ 'author_name' ] );
    	$author = $user->ID;
    	if( function_exists( 'STR_get_all_authors' ) ) {
    		$authors = STR_get_all_authors( $user->user_email );
    		$author = implode(',', $authors);
    	}

    	$query->set( 'author_name', null );
    	$query->set( 'author', $author );
    }
}
add_action( 'parse_query', 'phylo_custom_rewrite_query' );
/**
 * Enqueue scripts and styles
 */
function phylo_scripts() {

	wp_enqueue_style( 'phylo-style', get_stylesheet_uri(), array(), '1.1' );
	wp_enqueue_script( 'phylo-cards', get_template_directory_uri() . '/js/cards.js', array('jquery', 'jquery-ui-autocomplete'), '20120206', true );

	$translation_array = array( 'ajaxurl' => admin_url('admin-ajax.php') );

    wp_localize_script( 'phylo-cards', 'Phylo', $translation_array );


	wp_enqueue_script( 'phylo-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'phylo-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'phylo-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	wp_register_script( 'iris-color-picker', get_template_directory_uri().'/js/iris.min.js', array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );

	wp_register_script( 'create-card', get_template_directory_uri().'/js/create-card.js',
		array(
		'jquery',
		'backbone',
		'underscore',
		'plupload',
		'iris-color-picker'
		), 1, true );
}
add_action( 'wp_enqueue_scripts', 'phylo_scripts' );

function phylo_infinite_scroll_render() {

	while( have_posts() ) {
    	the_post();
    	if( phylo_is_cards_list_view() || in_array( get_post_type(), array('card', 'diy-card') ) ) {
    		get_template_part( 'content-card', 'standard' );
    	} else {
    		get_template_part( 'content' );
    	}

	}

}
add_filter( 'infinite_scroll_ajax_url', 'phylo_infinate_select_paga_paramaters' );
function phylo_infinate_select_paga_paramaters( $parameter ) {

	if( isset( $_GET['selected'] ) ) {
		return add_query_arg( array( 'selected' => true ), $parameter );
	}
	return $parameter;
}

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );
