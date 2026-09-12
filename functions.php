<?php
/**
 * Dlc_Designs functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dlc_Designs
 */

if ( ! defined( 'DLC_DESIGNS_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'DLC_DESIGNS_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function dlc_designs_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a child theme based on this, use a find and replace
		* to change 'dlc-designs' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'dlc-designs', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'dlc-designs' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
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

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'dlc_designs_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
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
add_action( 'after_setup_theme', 'dlc_designs_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dlc_designs_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dlc_designs_content_width', 640 );
}
add_action( 'after_setup_theme', 'dlc_designs_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dlc_designs_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'dlc-designs' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'dlc-designs' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'dlc_designs_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function dlc_designs_scripts() {
	wp_enqueue_style( 'dlc-designs-fonts', dlc_designs_fonts_url(), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google Fonts URL should not be versioned.
	wp_enqueue_style( 'dlc-designs-style', get_stylesheet_uri(), array(), DLC_DESIGNS_VERSION );
	wp_style_add_data( 'dlc-designs-style', 'rtl', 'replace' );

	wp_enqueue_script( 'dlc-designs-navigation', get_template_directory_uri() . '/js/navigation.js', array(), DLC_DESIGNS_VERSION, true );

	if ( is_front_page() ) {
		wp_enqueue_script( 'dlc-designs-typewriter', get_template_directory_uri() . '/js/typewriter.js', array(), DLC_DESIGNS_VERSION, true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dlc_designs_scripts' );

/**
 * Builds the Google Fonts URL for JetBrains Mono + Inter, respecting the
 * "Don't use Google Fonts" translation opt-out convention used by _s.
 *
 * @return string
 */
function dlc_designs_fonts_url() {
	$fonts_url = '';

	/* translators: If there are characters in your language that are not supported by the fonts, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Google Fonts: on or off', 'dlc-designs' ) ) {
		$font_families = array(
			'JetBrains+Mono:wght@400;500;600;700',
			'Inter:wght@400;500;600;700',
		);

		$fonts_url = add_query_arg(
			array(
				'family'  => implode( '&family=', $font_families ),
				'display' => 'swap',
			),
			'https://fonts.googleapis.com/css2'
		);
	}

	return $fonts_url;
}

/**
 * Preconnect to Google Fonts for faster font loading.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array
 */
function dlc_designs_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && wp_style_is( 'dlc-designs-fonts', 'queue' ) ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'dlc_designs_resource_hints', 10, 2 );

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

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
