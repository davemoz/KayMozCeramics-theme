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
			<svg xmlns="http://www.w3.org/2000/svg">
				
				<symbol id="kmc-logo" viewBox="0 0 151 151">
					<g>
						<g id="outer-circle">
							<path fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="#fff" style="stroke: var(--logo-color);" d="M11.51 114.64a75 75 0 1 1 118.86 12M22.28 128.34a76.68 76.68 0 0 1-5.8-6.55M109.19 142.52a75 75 0 0 1-81.24-9M123.05 133.5a76.06 76.06 0 0 1-6.67 4.89"/>
						</g>
						<g id="inner-circle">
							<path fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="#fff" style="stroke: var(--logo-color);" d="M133 67.66a58.05 58.05 0 0 1-10.22 41.48M86.54 18.55a58.08 58.08 0 0 1 44.91 41.62M64.17 18.61A57.89 57.89 0 0 1 75.5 17.5M41.16 28.75a58.43 58.43 0 0 1 12.43-7M23 100.17a58.07 58.07 0 0 1 10.66-64.84M34.06 116.08a58.34 58.34 0 0 1-7.41-9.29M109.09 122.79a58 58 0 0 1-68.55-1"/>
						</g>
						<g id="ceramics">
							<path stroke="none" fill="#fff" style="fill: var(--logo-color);" d="M39.47 133.09a1.07 1.07 0 0 1 1.13-1.81 1.07 1.07 0 0 1-1.13 1.81zM43.36 136.17c-.67 1.34 1.09 2.31 1.7 1.09l.33-.63c.41-.83 1.74-.12 1.31.65s-.91 1.82-2 2a2.72 2.72 0 0 1-3.07-1.95 2.82 2.82 0 0 1 .42-1.76l2.21-4.43a3 3 0 0 1 1.16-1.41c1.41-.79 3.46.54 3.62 2a3.57 3.57 0 0 1-.61 2.05.77.77 0 0 1-1.35-.06c-.17-.41.18-.88.36-1.23.49-1.06-1.19-2.13-1.75-1-.81 1.56-1.59 3.12-2.33 4.68zM52.39 132.08c.71.27 1.43.51 2.13.8.19.07.57.14.7.31.38.5-.06 1.4-.74 1.19-.52-.2-1.51-.79-2.06-.5s-.66 1.38-.84 1.87c-.13.31-.1.68.27.8s2.52 1 1.14 1.68c-.68.35-2-1-2.34 0-.14.48-.84 1.56-.65 2.08s.65.5 1 .65.9.21 1.1.47c.41.52-.08 1.4-.75 1.18a18.66 18.66 0 0 1-2.56-.95c-1-.58-.42-1.48-.09-2.36l1.11-3c.38-.88 1.19-4.79 2.58-4.22zM55.88 138.87c.2-.8.76-5.26 2.13-4.75 1.31.33 2.84.4 3.35 1.86a4.39 4.39 0 0 1-.23 3.09c-.44 1-1.89 1.65-1.58 2.81l.37 2.23a.72.72 0 0 1-1.07.76c-.35-.22-.35-.68-.43-1-.19-.83-.33-1.67-.49-2.5s-.92-.51-1.21 0a5.34 5.34 0 0 0-.31 1.19 3.36 3.36 0 0 1-.37 1.24.72.72 0 0 1-1.25-.34c-.15-.57.36-1.66.49-2.18zm1.61-.57a.67.67 0 0 0 .54.83c1.27.39 2.09-.43 2.12-1.77a1.36 1.36 0 0 0-.85-1.47c-.48-.19-1.12-.2-1.3.4zM67.45 137c.28 2.92.57 5.84.84 8.76 0 .89-1.35.89-1.37 0 0-.48 0-1.31-.62-1.37l-1.45-.2c-.76-.15-1 .75-1.2 1.28-.32.79-1.51.23-1.22-.57l3.11-8.13c.46-1 1.86-1 1.91.23zm-1.66 2.87c-.21.62-1.09 2.33-.54 2.75a.88.88 0 0 0 1.36-.32 5.21 5.21 0 0 0-.12-1.57c-.02-.2-.28-1.88-.7-.84zM71.55 141.27v-2.88a5.35 5.35 0 0 1 0-1.06c.17-.71 1-1.14 1.39-.25l1.42 3.61c.61 1.42 1.36-1.21 1.6-1.76a15.22 15.22 0 0 1 .68-1.61c.32-.56 1.36-1.06 1.47 0l-.12 7.3a6.94 6.94 0 0 1 0 1.58c-.26 1-1.22.93-1.4 0a20.91 20.91 0 0 1 .06-4c0-.29-.28-.54-.4-.18s-1.48 3.74-2.18 1.83c-.15-.37-.47-1.87-.86-2s.26 5.13-1.08 5.1-.6-4.79-.58-5.68zM80.28 141.18l-.28-2.76c0-.54-.23-2.82 1-1.85.6.46.43 2.22.49 2.88s.12 1.32.19 2c.1 1.13.21 2.26.31 3.39 0 .56.18 2.65-1 1.69-.55-.43-.42-2.09-.47-2.66zM86.12 143.41c.25 1.43 2.25 1.23 2-.14l-.11-.71c-.17-.9 1.32-1.13 1.44-.26s.35 2-.42 2.82a2.73 2.73 0 0 1-3.62.28 2.94 2.94 0 0 1-.72-1.67c-.29-1.62-.58-3.25-.88-4.87a2.94 2.94 0 0 1 .09-1.83c.65-1.47 3.1-1.63 4.13-.52a3.67 3.67 0 0 1 .73 2 .76.76 0 0 1-1.11.76c-.38-.23-.39-.82-.46-1.21-.23-1.12-2.23-1-2 .23.27 1.71.58 3.43.93 5.12zM95.59 135.14c.57.84-.65 1.84-1.13 1-.78-1.22-2.29.06-1.75 1.25s2.58 1.08 3.49 1.7a2.88 2.88 0 0 1 .89 3.56 2.77 2.77 0 0 1-4.88.35c-.63-1 .43-1.52 1.16-1 .46.33.55.82 1.24.78a1.23 1.23 0 0 0 1.08-.84 1.59 1.59 0 0 0-.17-1.37c-.57-.89-2.1-.83-3-1.29a2.75 2.75 0 0 1-1.51-2.67c.16-2.31 3.23-3.49 4.58-1.47zM101.07 138.57a1.07 1.07 0 0 1-.81-2 1.07 1.07 0 0 1 .81 2z"/>
						</g>
						<g id="Moz">
							<path stroke="none" fill="#fff" style="fill: var(--logo-color);" d="M62.29 97.88c.17 3.51.06 7.6-2 10.62-1.28 1.92-4.12 3.28-6.14 1.61-2.65-2.17-1.36-6.31-.16-8.88a69.78 69.78 0 0 1 6.25-10c2.45-3.51 4.78-7.1 7.31-10.55 2.22-3 4.54-6.13 7.59-8.36a12.82 12.82 0 0 1 9.71-2.41c1.24.22 3.22.76 3.28 2.33A1.84 1.84 0 0 1 87 74c-.77.24-1.66-.41-2.48-.37-3.32.14-4.14 5.53-5 7.93-1.36 4-2.68 8-3.85 12a108.92 108.92 0 0 0-4.47 25.51 19.08 19.08 0 0 0 .36 5.49c.39 1.51 1.32 3.65-.56 4.53-3.21 1.51-3.46-4.53-3.57-6.29-.43-8.63 1.57-17.65 3.69-25.95 1.21-4.62 2.63-9.19 4.15-13.72.33-1 2.92-6.51-.17-5.47-1.32.44-2.22 2-3 3-1.24 1.62-2.47 3.24-3.6 5-1.35 2-2.64 4.09-4 6.14-1.15 1.85-2.32 3.8-2.21 6.08zm-5.76 9.94c2.19.2 3.4-7.23 2-7.51-1.53-.31-3.99 7.33-2 7.51zM137.19 100.07c-2.12-.43-2 2.61-2.2 3.88a9.49 9.49 0 0 1-2.39 5 16 16 0 0 1-5.08 3.28c-.89.41-3.32 2-4.24 1.66-.58-.22-1.16-1.35-1.62-1.77a16.17 16.17 0 0 0-2.43-1.76c-1.89-1.23-1.67-2.62-.82-4.51 1.67-3.69 2.7-8.28 1-12.14a6.63 6.63 0 0 0-9.49-3c-2.37 1.39-3.49 4.25-4.19 6.77-1.25 4.54-.84 11.52-5.78 14-1.87 1-4 .76-3.37-1.8a36.84 36.84 0 0 0 1.36-6.52c.18-3.65-1-8.21-5-9.17s-7.62 2.11-9.69 5.36a22 22 0 0 0-3.48 12.5c.18 3.87 2.08 7.76 6.38 7.92a7.79 7.79 0 0 0 5.38-2.14c1.54-1.32 2.38-2.92 4.6-2.73 4 .33 7.22-2.24 9.15-5.57s1.84-7.19 3-10.71a8.73 8.73 0 0 1 3-4.82 3.23 3.23 0 0 1 4.54.49c2.11 2.65.78 7.42-.44 10.15a29.76 29.76 0 0 1-3.54 5.78c-.6.78-1.2 1.55-1.85 2.29-.5.57-1.21 1.25-1.19 2.07 0 2 2.28 1.56 3.2.59 1.26-1.32 2.15-2.32 4.11-1.94 1.36.26 3 1.1 3.52 2.49-2.86 1-5.42 3.89-7.07 6.33a20.53 20.53 0 0 0-3 9.43c-.49 5 .36 11.89 4.69 15.25 3.71 2.88 9.13 1.3 11.5-2.45s2.84-8.75 2.71-13.09a46.41 46.41 0 0 0-.44-5.28 18.78 18.78 0 0 0-1.15-5.15c-.55-1.16-1.89-2-3-.91s-.23 2.5 0 3.66c1.25 5.12 1.69 11 .16 16.08-.63 2.08-1.72 4.54-3.93 5.35a4 4 0 0 1-4.58-1.44c-3.48-4-3.8-10.37-2.39-15.21 1.06-3.65 3.14-7.27 6.4-9.35 2.92-1.85 6.44-2.45 9.53-3.93a17.48 17.48 0 0 0 7.48-6.29c.86-1.43 3.69-8.02.65-8.65zm-42.78 4c-.14 1.17-.22 3-.88 4-.52.79-1 .37-1.85.46a2 2 0 0 0-1.67 2c-.05 1 .77 1.69.87 2.6.18 1.82-3.09 3.4-4.61 3.38-2.24 0-2.92-2.18-3.09-4.07a18.35 18.35 0 0 1 4.42-13.2c1.29-1.41 3.65-3 5.53-1.62s1.5 4.49 1.28 6.38zM29.74 102.23a38 38 0 0 0-13.31 14.36c-2.07 3.87-4.11 9.88-.1 13.35 4.4 3.81 9.39-.52 12.42-3.76a61.67 61.67 0 0 0 9-12.38c3.67-6.73 5.72-14.19 7.85-21.5 1.11-3.83 2.55-7.56 3.67-11.37.36-1.23.58-2.68-1-3-1.86-.31-4.23.37-6.07.71-3.27.6-7.54 1-10 3.56a8 8 0 0 0-2 7.82 5.08 5.08 0 0 0 7.53 2.45c1.1-.73 2.31-1.38 3.13.16s-.62 2.65-1.83 3.28a9 9 0 0 1-9.29-.47 9.27 9.27 0 0 1-3.55-8c.24-5.78 4.27-9.43 9.56-10.92 5-1.4 10.32-1.27 15.18-3.09a31.75 31.75 0 0 0 4.75-2.52c1.17-.67 3-1.62 3.93 0 .5.89 0 1.73-.21 2.61a9.79 9.79 0 0 0-.19 2.86 50.24 50.24 0 0 0 .84 5.85c.29 1.66 1.18 3.91.7 5.58-.24.85-1.35 2.31-2.41 1.77S57.13 85.84 57 84.7c-.13-1-.29-1.91-.46-2.85-.11-.66-.18-2-.8-2.42-2.17-1.55-4.24 6.41-4.64 7.62-1.32 4.08-2.49 8.2-3.83 12.28-3.64 11.14-8.82 22.1-17.44 30.33-3.19 3.05-7.89 6.59-12.55 4.9a10.21 10.21 0 0 1-6.44-8.7 20 20 0 0 1 2.64-10.64c3.09-5.89 8-10.71 13.22-14.73 1-.76 2.49-2.17 3.74-1 .96.86.27 2.16-.7 2.74z"/>
						</g>
						<g id="Kay">
							<path stroke="none" fill="#fff" style="fill: var(--logo-color);" d="M59.41 35.3c7.87-4.09 15.3-9.75 20.38-17.11a18.4 18.4 0 0 0 2.95-5.76c.32-1.3.35-3.06 1.37-4A2 2 0 0 1 87.4 10a19.52 19.52 0 0 1-2.23 6.57A39.09 39.09 0 0 1 77 27.17c-1.72 1.62-3.55 3.13-5.41 4.58-1.44 1.11-3 2.13-3.65 3.93-.71 2 .29 3.57.95 5.44a15.92 15.92 0 0 1 .57 6.88c-.46 4.65-2.79 9.62-2 14.32A7.43 7.43 0 0 0 70.57 67c1 .65 2.59 1.05 2.91 2.33.41 1.62-1.09 2.49-2.48 2.25a8.53 8.53 0 0 1-4.06-2.28c-7.19-6.7-1.34-15.89-1.45-23.94 0-1.82-.38-4.05-1.71-5.41s-3.43-.89-5.07-.24c-4.64 1.84-9.28 3.83-13.8 5.94A51.1 51.1 0 0 0 33 52.89c-2.62 2.27-5.79 5.67-5.76 9.4A3.79 3.79 0 0 0 30.11 66a7 7 0 0 0 4.68-1 25.24 25.24 0 0 0 8.32-8 45.65 45.65 0 0 0 3.58-6.17c.82-1.71 1.38-3.62 2.33-5.27.64-1.12 2.37-2.41 3.68-1.39 1 .78.14 2.45-.22 3.36C49.56 54.81 45.5 62.32 39 67c-2.91 2.09-6.65 3.77-10.31 3.14a7.12 7.12 0 0 1-5.92-7.92c.43-4.3 3.39-8.07 6.46-10.9a46.08 46.08 0 0 1 11.44-7.53c3.82-1.82 8-3.41 10.31-7.16s2.94-8.24 4.16-12.35c.32-1.07.68-2.14 1-3.2.28-.83.81-1.92.45-2.8-.55-1.4-2.59-1-3.77-1.09-4.25-.25-8.3-2.6-12.47-.58a9.76 9.76 0 0 0-5.31 8.18c-.19 3.34 1.82 6.26 5.43 6.05a7.19 7.19 0 0 0 3.06-.94c.74-.41 1.48-1.22 2.36-1.25a1.71 1.71 0 0 1 1.69 1.57 2.46 2.46 0 0 1-1.19 2.09 10.21 10.21 0 0 1-11 .73c-7.06-4.34-4.22-15.49 2-19.19 4.09-2.45 8.9-1.63 13.33-.82a15.89 15.89 0 0 0 7.19.16c2.06-.57 3.56-1.91 5.33-3 1.32-.81 4.33-1.28 4.21 1.19C67.32 13 65 14.22 64 15.15c-3.34 3.07-4.54 8-5.61 12.21-.45 1.8-1.41 4-1.3 5.89.08 1.23.91 2.75 2.32 2.05z"/>
							<path stroke="none" fill="#fff" style="fill: var(--logo-color);" d="M139.06 47.82a2.22 2.22 0 0 0-2.14-.32c-.95.56-.65 1.71 0 2.36 1.39 1.4 2.46 2.19 2.06 4.38-.75 4.18-5.37 7.09-8.78 9a47.07 47.07 0 0 1-12.88 5c-1.6.34-4.33 1.12-5.91.37s-.26-3.39.11-4.65q2.16-7.32 4.39-14.63c.56-1.84 1.15-3.66 1.8-5.47.38-1.07 1.07-2.88 0-3.8-2.9-2.54-4 4.81-4.4 6.09-1.11 3.87-2.06 8.09-4 11.63a10.29 10.29 0 0 1-3.65 4c-1.4.77-2.9.41-3.24-1.28-.45-2.18.36-4.8.88-6.92.56-2.31 1.32-4.56 2.1-6.81.38-1.1.77-2.2 1.19-3.29a3.68 3.68 0 0 0 .31-2.75c-.59-1.26-2.31-1-3.08-.09a8.41 8.41 0 0 0-1.08 2.65c-.41 1.23-.75 2.48-1.14 3.71a31.42 31.42 0 0 1-2.51 6.68 38.07 38.07 0 0 1-3.8 5.77c-.91 1.05-2.55 2.8-4.14 2.36-1.88-.51-1.43-3.31-1.14-4.7.45-2.23 1-4.45 1.67-6.63.72-2.35 1.56-4.66 2.35-7 .49-1.46.93-4.38-1.71-3.67-1 .27-1.58.89-2.65.53a16.52 16.52 0 0 0-3.5-1.16 7.67 7.67 0 0 0-5.19 1.37c-3.17 2.17-5.06 6.06-6.25 9.6-1.26 3.74-2 8.61.36 12.09a5.29 5.29 0 0 0 5.08 2.46 7.76 7.76 0 0 0 2.76-1 19.38 19.38 0 0 1 2.55-1.7c1.76-.62 2.49 2 4 2.59a5.91 5.91 0 0 0 5.34-1.1 17.39 17.39 0 0 0 4.13-4.74c0 2.08.25 4.6 2.15 5.85 2.18 1.43 5 .35 7-.9-.63 1.68-.69 4.11-2.28 5.25-.92.65-2.27.6-3.35.79a15.48 15.48 0 0 0-4 1.13c-4.68 2.15-8.7 8-8.28 13.27.5 6.09 7.16 7.79 11.62 4.37a15.67 15.67 0 0 0 4.46-6.11 31.35 31.35 0 0 0 2.44-7.28c.17-1-.21-2.06-1.38-2.14s-1.79 1.18-2.08 2.22a32.71 32.71 0 0 1-3.16 8.43c-1.13 1.82-2.89 3.66-5.18 3.78s-3.52-1.9-3.67-3.94c-.31-4.29 3.46-9 7.34-10.54 2.68-1 5.83-.8 8.65-1a64.69 64.69 0 0 0 9.9-1.42A45 45 0 0 0 136.74 62c2.2-1.78 4.51-4 5.2-6.83a6.81 6.81 0 0 0-2.88-7.35zm-50.94.35c-.53 4-1.8 8.24-4.61 11.29-1.13 1.23-3.24 3.08-5 1.84C77 60.23 77 58 77.15 56.36a23.72 23.72 0 0 1 1.61-6.77A15 15 0 0 1 81.92 44c1.34-1.3 3.67-2.25 5.24-.77 1.28 1.24 1.16 3.36.96 4.94z"/>
						</g>
					</g>
				</symbol>

				<symbol id="kmc-monogram" viewBox="0 0 154.57 154.57">
					<title>KM-monogram</title>
					<g id="Layer_2" data-name="Layer 2">
						<g id="monogram">
							<g id="KM-monogram">
								<path stroke="none" fill="#fff" style="fill: var(--logo-color);" d="M77.29,0a77.29,77.29,0,1,0,77.28,77.29A77.37,77.37,0,0,0,77.29,0Zm42.78,136.88a63.46,63.46,0,0,1-.53-13.56,149.56,149.56,0,0,1,2.06-15.49c1-5.45,2.65-11.91,4.23-17.29s3.21-10.18,5-15.41c.26-.77.58-1.56.84-2.41s.62-1.91.92-2.76a22.5,22.5,0,0,1,1.12-2.52A8.61,8.61,0,0,1,135,65.57a4,4,0,0,1,2.36-1.08,4.65,4.65,0,0,1,2.25.4c.64.22.87.29,1.39,0a2.15,2.15,0,0,0,1-1,2.48,2.48,0,0,0,.43-1.49,1.52,1.52,0,0,0-.6-1.41,6.45,6.45,0,0,0-2.33-1.16,15.71,15.71,0,0,0-2.89-.49,19.27,19.27,0,0,0-3,.06,18.43,18.43,0,0,0-2.82.45,14.78,14.78,0,0,0-4.63,1.91,27.55,27.55,0,0,0-4.15,3.3,57.5,57.5,0,0,0-5.64,6.31c-1.8,2.32-3.71,5-5.45,7.46s-3.58,5.18-5.27,7.68-3.22,4.69-5,7.18c-.83,1.19-1.65,2.5-2.46,3.87s-1.77,3.12-2.47,4.63a28.68,28.68,0,0,0-1.7,4.86,10.47,10.47,0,0,0-.12,4.51,5.39,5.39,0,0,0,1.7,3,4,4,0,0,0,3.14,1.22,6,6,0,0,0,4.52-2.13,13.09,13.09,0,0,0,2.49-4.85,28.76,28.76,0,0,0,1-5.72,48.46,48.46,0,0,0,.1-5,10.84,10.84,0,0,1,.54-3.78,15.66,15.66,0,0,1,1.7-3.4c1.13-1.82,2.35-3.62,3.45-5.36s2.1-3.3,3.23-5,2.2-3.2,3.47-4.83a54.94,54.94,0,0,1,4.28-4.88,2.12,2.12,0,0,1,2.29-.73c.85.31,1.12.74.82,1.72-1.11,3.6-2.34,6.93-3.54,10.69s-2.46,7.86-3.53,11.85-2.18,8.62-3,12.65-1.55,8.62-1.91,12.34a81.66,81.66,0,0,0-.5,9,61.23,61.23,0,0,0,1.07,11.76,73.74,73.74,0,1,1,4.79-3.18Zm-17.62-34.63c0,.63,0,1.5-.14,2.34s-.27,2.16-.48,3.07a9.59,9.59,0,0,1-1,2.73c-.46.75-.92,1.2-1.78,1.1s-1-.6-1-1.5a15.35,15.35,0,0,1,.53-3.11,23.8,23.8,0,0,1,1.17-3.19,7.77,7.77,0,0,1,1.13-2c.45-.48.6-.57,1.14-.48S102.47,101.61,102.45,102.25Z"/>
								<path stroke="none" fill="#fff" style="fill: var(--logo-color);" d="M104,77.85c-.26-1.52-.55-3.22-.76-4.71s-.36-3-.44-4.44a21.91,21.91,0,0,1,.1-3.82,3.31,3.31,0,0,1,.36-1.1,4.21,4.21,0,0,0,.36-1.12,2.6,2.6,0,0,0-.28-1.69,2.76,2.76,0,0,0-1.24-1.14,2.53,2.53,0,0,0-1.25-.13,4.06,4.06,0,0,0-1.13.28c-1.28.58-2.75,1.39-3.82,2s-1.49.94-2.51,1.44a13,13,0,0,1-2.85,1.11,28.46,28.46,0,0,1-4.57.83c-2.66.28-5.65.41-8.55,1a36.1,36.1,0,0,0-5,1.29c.18-.77.33-1.56.44-2.37a11,11,0,0,0-.35-3.89A15.19,15.19,0,0,0,71,57.62a3,3,0,0,1-.32-1.52,9.65,9.65,0,0,1,.26-1.88,7.91,7.91,0,0,1,.67-1.82,4.26,4.26,0,0,1,1-1.34c2.56-2,5.07-3.89,7.51-6a64.68,64.68,0,0,0,6.92-6.93,47,47,0,0,0,5.44-8A32.7,32.7,0,0,0,95.66,21a1.76,1.76,0,0,0-.54-1.63,2.44,2.44,0,0,0-1.58-.78,2.18,2.18,0,0,0-1.78.41A2.88,2.88,0,0,0,90.63,21a23.56,23.56,0,0,1-2.35,6.91,42.61,42.61,0,0,1-4.49,6.73,63.93,63.93,0,0,1-6,6.26,83.72,83.72,0,0,1-6.64,5.46,92.44,92.44,0,0,1-10.81,6.87,1.6,1.6,0,0,1-1.44.15,1.64,1.64,0,0,1-1-.84,3.57,3.57,0,0,1-.5-1.38,3.35,3.35,0,0,1,.06-1.5c.66-2.58,1.41-5.74,2-8.23.45-1.74.81-2.81,1.31-4.37a45.62,45.62,0,0,1,1.77-4.35,12.17,12.17,0,0,1,2.83-4.27c1.32-1.19,2.72-2.3,4.1-3.43a2.23,2.23,0,0,0,.79-1.52,2.43,2.43,0,0,0-.4-1.68,2.66,2.66,0,0,0-1.4-1.09,2.72,2.72,0,0,0-2.08.25A17.07,17.07,0,0,0,63,23.18a10.85,10.85,0,0,1-3.8,2,15.86,15.86,0,0,1-5.92.55c-2-.2-3.68-.55-5.62-.88a53.37,53.37,0,0,0-5.82-.78,18,18,0,0,0-6.41.85A14,14,0,0,0,31,27.3a16.6,16.6,0,0,0-3.21,3.5,18.12,18.12,0,0,0-2,4.15,16.35,16.35,0,0,0-.84,4.38,12.55,12.55,0,0,0,1.53,7.3A10.28,10.28,0,0,0,31.24,51a11.3,11.3,0,0,0,6.5.89,14.56,14.56,0,0,0,6.78-3,2.21,2.21,0,0,0,.94-1.57,2.27,2.27,0,0,0-.4-1.54,2,2,0,0,0-1.29-.84,2.13,2.13,0,0,0-1.71.4,11.21,11.21,0,0,1-4.71,2.19A6.72,6.72,0,0,1,33.12,47a5.53,5.53,0,0,1-2.83-3,8,8,0,0,1-.58-4.89,13.9,13.9,0,0,1,2.18-5.61,10.78,10.78,0,0,1,4.94-4,11.61,11.61,0,0,1,6.72-.64c2.37.48,4.75.93,7.24,1.44,1.5.31,3,.12,4.67.46s1.68,2.17,1,4S55,39.2,54.41,41.24c-.44,1.54-.87,3-1.29,4.63s-.77,3.14-1.3,4.81a19.85,19.85,0,0,1-2.56,4.88,14.71,14.71,0,0,1-4,4,41.94,41.94,0,0,1-5.8,3,55.89,55.89,0,0,0-6,3c-2.34,1.37-4.86,3-6.8,4.43a51.68,51.68,0,0,0-5.24,4.35,29.59,29.59,0,0,0-5.05,6.45,17,17,0,0,0-2.11,5.91,8.72,8.72,0,0,0,.44,5,7,7,0,0,0,2.56,3.68c2.22,1.56,3.9,2.36,7.11,2a18.25,18.25,0,0,0,9.33-3.66,44.73,44.73,0,0,0,9.91-10,74.3,74.3,0,0,0,8.69-16.92,2,2,0,0,0-.08-2A2,2,0,0,0,50.65,64a4.69,4.69,0,0,0-2.07.59,3.45,3.45,0,0,0-1.48,1.67,74,74,0,0,1-7,13.72,41,41,0,0,1-7.34,8.26,14.36,14.36,0,0,1-6.54,3.44c-2.19.48-3.16.46-4.65-.74s-1.8-2.46-1.57-4.83a13.77,13.77,0,0,1,3.69-7.35A39.84,39.84,0,0,1,31,72.48a83,83,0,0,1,8.83-5.09c3-1.47,6.34-3,9.41-4.25s6-2.48,9-3.78c.66-.28,1.27-.57,1.94-.84a8.23,8.23,0,0,1,2.12-.58,4.69,4.69,0,0,1,2,.1,3.54,3.54,0,0,1,1.82,1.1,6,6,0,0,1,1.36,2.58,8.26,8.26,0,0,1,.34,3.2,21.15,21.15,0,0,1-3.09,7.93c-.45.77-.82,1.44-1.17,2.07a29.38,29.38,0,0,0-1.4,2.72c-.22.47-.41,1-.6,1.51l-.26.62a24.37,24.37,0,0,0-1.58,7.65c0,.09,0,.18,0,.27a7.58,7.58,0,0,0,1.15,4.69,6.76,6.76,0,0,0,1.57,1.86,9.16,9.16,0,0,0,1.71,1.38,9.56,9.56,0,0,0,3.11,1.13,8.63,8.63,0,0,0,2.87,0c.35,0,.7,0,1-.1a12.45,12.45,0,0,0,6.28-3.45,2.53,2.53,0,0,0,.64-.79,1.48,1.48,0,0,0,.24-1,3,3,0,0,0-.64-1.54A2.25,2.25,0,0,0,76.3,89a1.91,1.91,0,0,0-1.71.63,7.64,7.64,0,0,1-3.28,1.88,2.73,2.73,0,0,0-.48.1,6.49,6.49,0,0,1-2.53-.06h0a7.24,7.24,0,0,1-.75-.24l-.18-.07L66.8,91a3.72,3.72,0,0,1-1.49-1.3A5.23,5.23,0,0,1,65,85.84c.2-.89.46-1.93.76-3l.39-1.29s0-.09,0-.13c.08-.25.16-.5.24-.72.19-.54.41-1.11.67-1.68a33.26,33.26,0,0,1,2.27-3.72l.25-.3a11.7,11.7,0,0,1,3.81-2.85,24.7,24.7,0,0,1,4.73-1.48,22.62,22.62,0,0,1,4.76-.56,22.29,22.29,0,0,1,4.11.13c2.52.52,2.82,1.64,1.88,4.29-.6,1.69-1.54,3.91-2.23,6.09S85.05,85.44,84.26,88s-1.72,5.42-2.59,8.11-1.87,5.76-2.89,8.46c-1.16,3.09-2.59,6.43-3.92,9.33a66.42,66.42,0,0,1-4.51,8.2,33.66,33.66,0,0,1-5.29,6.29,16.09,16.09,0,0,1-6.14,3.57,9.55,9.55,0,0,1-3.74.46,11.62,11.62,0,0,1-3.71-.92,2.12,2.12,0,0,0-1.87,0,2.55,2.55,0,0,0-1.25,1.43,2.68,2.68,0,0,0,0,2A2.77,2.77,0,0,0,50,136.49a14.31,14.31,0,0,0,5,1.08,16.13,16.13,0,0,0,5.06-.59,19.63,19.63,0,0,0,7.74-4.2,37.45,37.45,0,0,0,6.36-7.32,68.32,68.32,0,0,0,5.27-9.53c1.54-3.36,3.11-7.18,4.44-10.76,1-2.8,2.06-5.91,3-8.71s1.91-5.8,2.79-8.46,1.77-5.33,2.64-7.74a59.57,59.57,0,0,1,2.73-6.6c.49-1,.92-1.42,1.85-1.37s1.26.34,1.53,1.54c.79,3.55,1.46,8,2.24,11.69a1.21,1.21,0,0,0,1,1.11,1.61,1.61,0,0,0,1.5-.25A3.22,3.22,0,0,0,104.43,85a4.29,4.29,0,0,0,.43-2.46C104.66,81.05,104.29,79.36,104,77.85Z"/>
							</g>
						</g>
					</g>
				</symbol>

			</svg>
		</div>';
}
add_action( 'wp_head', 'kmc_svg_sprites' );
