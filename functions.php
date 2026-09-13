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
	define( 'DLC_DESIGNS_VERSION', '1.0.1' );
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

	// Register nav menu locations: primary nav plus a 3-column footer.
	register_nav_menus(
		array(
			'menu-1'        => esc_html__( 'Primary', 'dlc-designs' ),
			'footer-social' => esc_html__( 'Footer Social', 'dlc-designs' ),
			'footer-menu-1' => esc_html__( 'Footer Column 2', 'dlc-designs' ),
			'footer-menu-2' => esc_html__( 'Footer Column 3', 'dlc-designs' ),
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
 * Registers an optional footer widget area for a newsletter signup (or
 * anything else). It's not wired to any specific form plugin — add
 * whatever widget you end up using (a Custom HTML widget with an embed
 * code, a plugin's own widget, etc). The footer's 4th column only appears
 * when this area actually has a widget in it.
 */
function dlc_designs_footer_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Newsletter', 'dlc-designs' ),
			'id'            => 'footer-newsletter',
			'description'   => esc_html__( 'Optional. Add a widget here (e.g. a newsletter signup form) to show a 4th footer column. Leave empty to hide it.', 'dlc-designs' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'dlc_designs_footer_widgets_init' );

/**
 * Returns a cache-busting version string for a theme file, based on its
 * last-modified time, so browsers/proxies never serve a stale copy after
 * a style or script changes during development.
 *
 * @param string $relative_path Path relative to the theme root, e.g. '/style.css'.
 * @return string
 */
function dlc_designs_asset_version( $relative_path ) {
	$file_path = get_template_directory() . $relative_path;

	return file_exists( $file_path ) ? (string) filemtime( $file_path ) : DLC_DESIGNS_VERSION;
}

/**
 * Enqueue scripts and styles.
 */
function dlc_designs_scripts() {
	wp_enqueue_style( 'dlc-designs-fonts', dlc_designs_fonts_url(), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google Fonts URL should not be versioned.
	wp_enqueue_style( 'dlc-designs-style', get_stylesheet_uri(), array(), dlc_designs_asset_version( '/style.css' ) );
	wp_style_add_data( 'dlc-designs-style', 'rtl', 'replace' );

	wp_enqueue_script( 'dlc-designs-navigation', get_template_directory_uri() . '/js/navigation.js', array(), dlc_designs_asset_version( '/js/navigation.js' ), true );

	if ( is_front_page() ) {
		wp_enqueue_script( 'dlc-designs-typewriter', get_template_directory_uri() . '/js/typewriter.js', array(), dlc_designs_asset_version( '/js/typewriter.js' ), true );
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		wp_enqueue_script( 'dlc-designs-cart', get_template_directory_uri() . '/js/cart.js', array(), dlc_designs_asset_version( '/js/cart.js' ), true );
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
 * Social icon detection for the "Footer Social" nav menu.
 */
require get_template_directory() . '/inc/social-icons.php';

/**
 * Nav walker for the "Footer Social" menu.
 */
require get_template_directory() . '/inc/class-dlc-designs-social-menu-walker.php';

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
