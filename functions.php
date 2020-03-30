<?php

/**
 * kmc functions and definitions
 *
 * @package kmc
 */

if (!function_exists('kmc_setup')) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kmc_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on kmc, use a find and replace
	 * to change 'kmc' to the name of your theme in all the template files
	 */
	load_theme_textdomain('kmc', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support('post-thumbnails');

	// Register Nav Menus
	register_nav_menus(array(
		'primary' => esc_html__('Primary Menu', 'kmc'),
		'social' => esc_html__('Social Menu', 'kmc'),
		'cart' => esc_html__('Cart Menu', 'kmc'),
	));

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	));

	/*
	 * Add Editor Style for adequate styling in text editor.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_editor_style
	 */
	add_editor_style('editor-style.css');

}
endif; // kmc_setup
add_action('after_setup_theme', 'kmc_setup');

/**
 * Add WooCommerce support.
 */
add_action('after_setup_theme', 'KayMozCeramics_add_woocommerce_support');
function KayMozCeramics_add_woocommerce_support() {
	add_theme_support('woocommerce', array(
		'thumbnail_image_width' => 400,
		'single_image_width'    => 1200,

		'product_grid'          => array(
			'default_rows'    => 4,
			'min_rows'        => 2,
			'min_columns'     => 3,
			'max_columns'     => 4,
		),
	));
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function kmc_content_width()
{
	$GLOBALS['content_width'] = apply_filters('kmc_content_width', 640);
}
add_action('after_setup_theme', 'kmc_content_width', 0);

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function kmc_widgets_init()
{
	register_sidebar(array(
		'name' => esc_html__('Sidebar', 'kmc'),
		'id' => 'sidebar-1',
		'description' => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	));
}
add_action('widgets_init', 'kmc_widgets_init');

/*
 * Add preconnect to header for Google Fonts, unpkg, FontAwesome, and s.w.org
 */
function kmc_add_dns_preconnect_to_head(){
	echo '<link rel="preconnect" href="https://use.fontawesome.com/" crossorigin>';
	echo '<link rel="preconnect" href="https://unpkg.com/" crossorigin>';
	echo '<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>';
	echo '<link rel="preconnect" href="https://s.w.org/" crossorigin>';
}
add_action( 'wp_head', 'kmc_add_dns_preconnect_to_head', 2 );

/**
 * Enqueue scripts and styles.
 */
function kmc_scripts()
{
	wp_enqueue_style('kmc-style', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );

	wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">', array(), null );

	// wp_enqueue_style('video-js-css', '//vjs.zencdn.net/7.5.4/video-js.min.css', array(), '7.5.4' );
	
	wp_enqueue_style('aos-css', '//unpkg.com/aos@next/dist/aos.css', array(), null );
	
	wp_enqueue_style('paymentfont-css', get_template_directory_uri() . '/inc/paymentfont/css/paymentfont.min.css', array(), '1.2.5' );

	wp_enqueue_script('font-awesome', '//use.fontawesome.com/releases/v5.7.2/js/all.js', array(), null );

	// wp_enqueue_script('video-js-ie', '//vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js', array(), '1.1.2', true );

	// wp_enqueue_script('video-js', '//vjs.zencdn.net/7.5.4/video.min.js', array(), '7.5.4', true );
	
	wp_enqueue_script('aos-js', '//unpkg.com/aos@next/dist/aos.js', array(), null, true );

	wp_enqueue_script('kmc-navigation', get_template_directory_uri() . '/js/navigation-min.js', array(), filemtime( get_template_directory() . '/js/navigation-min.js' ), true );

	wp_enqueue_script('kmc-site-scripts', get_template_directory_uri() . '/js/site-scripts-min.js', array('jquery'), filemtime( get_template_directory() . '/js/site-scripts-min.js' ), true );
	
	if( is_page() ){ //Check if we are viewing a page
		global $wp_query;
		//Check which template is assigned to current page we are looking at
		$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );
		if($template_name == 'page-home.php'){
			
			wp_enqueue_script('kmc-homepage-scripts', get_template_directory_uri() . '/js/homepage-scripts-min.js', array('jquery'), filemtime( get_template_directory() . '/js/homepage-scripts-min.js' ), true );
		
		}
	}
	
	if( is_checkout() ){
		
		wp_enqueue_script('kmc-checkout-scripts', get_template_directory_uri() . '/js/checkout-scripts-min.js', array('jquery'), filemtime( get_template_directory() . '/js/checkout-scripts-min.js' ), true );
		
	}

	wp_enqueue_script('kmc-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), filemtime( get_template_directory() . '/js/skip-link-focus-fix.js' ), true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'kmc_scripts');

/**
 * Filter the HTML script tags to add attributes.
 *
 * @param string $tag    The <script> tag for the enqueued script.
 * @param string $handle The script's registered handle.
 * @param string $src    The script's uri source.
 *
 * @return   Filtered HTML script tag.
 */
add_filter( 'script_loader_tag', 'add_attribs_to_scripts', 10, 3 );

function add_attribs_to_scripts( $tag, $handle, $src ) {

	// The handles of the enqueued scripts we want to defer
	$async_scripts = array(
		'kmc-scripts',
	);

	$defer_scripts = array( 
		'kmc-scripts',
		'google-fonts',
		'aos-css',
	);

	$fontawesome = array(
		'font-awesome',
	);

	$jquery = array(
		'jquery'
	);
	
	$googlefonts = array(
		'google-fonts'
	);

    if ( in_array( $handle, $defer_scripts ) ) {
		return '<script defer src="' . $src . '" type="text/javascript"></script>' . "\n";
	}
	if ( in_array( $handle, $async_scripts ) ) {
		return '<script async src="' . $src . '" async="async" type="text/javascript"></script>' . "\n";
	}
	if ( in_array( $handle, $fontawesome ) ) {
		return '<script data-search-pseudo-elements defer src="' . $src . '" integrity="sha384-0pzryjIRos8mFBWMzSSZApWtPl/5++eIfzYmTgBBmXYdhvxPc+XcFEk+zJwDgWbP" crossorigin="anonymous"></script>' . "\n";
	}
	if ( in_array( $handle, $jquery ) ) {
		return '<script async defer src="' . $src . '" crossorigin="anonymous" type="text/javascript"></script>' . "\n";
	}
	if ( in_array( $handle, $googlefonts ) ) {
		return '<link rel="preload" id="google-fonts-css" href="' . $src . '" type="text/css" media="all">';
	}
	return $tag;
}

/**
 * ------------------------------------------------------------------------
 *  Remove JQuery migrate
 * ------------------------------------------------------------------------
 */
function kmc_remove_jquery_migrate($scripts)
{
	if (!is_admin() && isset($scripts->registered['jquery'])) {
		$script = $scripts->registered['jquery'];

		if ($script->deps) { // Check whether the script has any dependencies
			$script->deps = array_diff($script->deps, array(
				'jquery-migrate'
			));
		}
	}
}
add_action('wp_default_scripts', 'kmc_remove_jquery_migrate');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


/**
 * ------------------------------------------------------------------------
 *  Adds "no-sidebar" class to body tag for select pages without a sidebar
 * ------------------------------------------------------------------------
 */
add_filter('body_class', 'kmc_add_no_sidebar_class_to_body');
function kmc_add_no_sidebar_class_to_body( $classes ) {
	if ( is_page_template( 'page-home.php' ) || is_shop() || is_product() ) {
		$noSidebarClass = 'no-sidebar';
		$classes[] = $noSidebarClass;
	}
	return $classes;
}

/**
 * ------------------------------------------------------------------------
 *  Modifies JetPack lazy-load placeholder image to a custom gif
 * ------------------------------------------------------------------------
 */
function kmc_jetpack_lazyload_placeholder_image( $image ) {
	$template_directory = get_template_directory_uri();
	return $template_directory . '/inc/imgs/Rolling-1s-200px.gif';
}
add_filter( 'lazyload_images_placeholder_image', 'kmc_jetpack_lazyload_placeholder_image' );

/**
 * ------------------------------------------------------------------------
 *  Adds site-wide notice to top of page (after body tag)
 * ------------------------------------------------------------------------
 */
function kmc_add_free_shipping_notice(){ ?>
	<div id="site-wide-notice">Free shipping on orders over $50!<a class="btn" href="/shop/">Go shop!</a></div>
<?php }
add_action( 'wp_body_open', 'kmc_add_free_shipping_notice' );

/**************************************************************************
 *
 *  WooCommerce
 *
 **************************************************************************/
/**
 * Include WooCommerce functions
 */
if( class_exists( 'woocommerce' ) ) {
	require_once __DIR__ . '/inc/kmc-functions/woocommerce-functions.php';
}

/**************************************************************************
 *
 *  Custom Post Types
 *
 **************************************************************************/

/**
 * Include Custom Post Type functions
 */
// require_once __DIR__ . '/inc/kmc-functions/custom-post-types.php';

/**************************************************************************
 *
 *  CMB2
 *
 **************************************************************************/

/**
 * Include CMB2 custom metabox functions
 */
// require_once __DIR__ . '/inc/kmc-functions/cmb2-functions.php';

/**************************************************************************
 *
 *  Add some reusable SVGs when wp_head loads
 *
 **************************************************************************/

function kmc_svg_sprites() {
	echo '<div id="svg-icons" class="visually-hidden">

			

		</div>';
}
// add_action( 'wp_head', 'kmc_svg_sprites' );
