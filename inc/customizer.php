<?php
/**
 * Dlc_Designs Theme Customizer
 *
 * @package Dlc_Designs
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function dlc_designs_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'dlc_designs_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'dlc_designs_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'dlc_designs_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function dlc_designs_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function dlc_designs_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function dlc_designs_customize_preview_js() {
	wp_enqueue_script( 'dlc-designs-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), dlc_designs_asset_version( '/js/customizer.js' ), true );
}
add_action( 'customize_preview_init', 'dlc_designs_customize_preview_js' );

/**
 * Returns the default URL for the homepage hero button (the WooCommerce
 * shop page when available, otherwise the site home).
 *
 * @return string
 */
function dlc_designs_default_hero_button_url() {
	if ( function_exists( 'wc_get_page_id' ) ) {
		$shop_page_id = wc_get_page_id( 'shop' );

		if ( $shop_page_id && $shop_page_id > 0 ) {
			return get_permalink( $shop_page_id );
		}
	}

	return home_url( '/' );
}

/**
 * Returns the default list of homepage hero typewriter phrases.
 *
 * @return string[]
 */
function dlc_designs_default_hero_phrases() {
	return array(
		__( 'Shirts', 'dlc-designs' ),
		__( 'Patches', 'dlc-designs' ),
		__( 'Stickers', 'dlc-designs' ),
		__( 'Custom Apparel', 'dlc-designs' ),
	);
}

/**
 * Sanitizes the hero typewriter phrases textarea into a newline-separated list.
 *
 * @param string $input Raw textarea input.
 * @return string
 */
function dlc_designs_sanitize_hero_phrases( $input ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $input );
	$lines = array_map( 'sanitize_text_field', $lines );
	$lines = array_filter( $lines );

	return implode( "\n", $lines );
}

/**
 * Registers the "Homepage Hero" Customizer section and its controls.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function dlc_designs_customize_register_hero( $wp_customize ) {
	$wp_customize->add_section(
		'dlc_designs_hero_section',
		array(
			'title'       => __( 'Homepage Hero', 'dlc-designs' ),
			'description' => __( 'Configure the hero shown at the top of the homepage.', 'dlc-designs' ),
			'priority'    => 30,
		)
	);

	// Eyebrow text.
	$wp_customize->add_setting(
		'dlc_designs_hero_eyebrow',
		array(
			'default'           => __( 'Welcome to my world', 'dlc-designs' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'dlc_designs_hero_eyebrow',
		array(
			'label'   => __( 'Eyebrow text', 'dlc-designs' ),
			'section' => 'dlc_designs_hero_section',
			'type'    => 'text',
		)
	);

	// Heading.
	$wp_customize->add_setting(
		'dlc_designs_hero_heading',
		array(
			'default'           => __( "Hi, I'm DLC", 'dlc-designs' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'dlc_designs_hero_heading',
		array(
			'label'   => __( 'Heading', 'dlc-designs' ),
			'section' => 'dlc_designs_hero_section',
			'type'    => 'text',
		)
	);

	// Typewriter static prefix.
	$wp_customize->add_setting(
		'dlc_designs_hero_typewriter_prefix',
		array(
			'default'           => __( 'I make', 'dlc-designs' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'dlc_designs_hero_typewriter_prefix',
		array(
			'label'       => __( 'Typewriter prefix (static)', 'dlc-designs' ),
			'description' => __( 'Optional. Stays in place while the phrases below type and backspace after it. Leave blank to type the full phrases only, with no static text.', 'dlc-designs' ),
			'section'     => 'dlc_designs_hero_section',
			'type'        => 'text',
		)
	);

	// Typewriter phrases.
	$wp_customize->add_setting(
		'dlc_designs_hero_phrases',
		array(
			'default'           => implode( "\n", dlc_designs_default_hero_phrases() ),
			'sanitize_callback' => 'dlc_designs_sanitize_hero_phrases',
		)
	);
	$wp_customize->add_control(
		'dlc_designs_hero_phrases',
		array(
			'label'       => __( 'Typewriter phrases', 'dlc-designs' ),
			'description' => __( 'One phrase per line. These type out and backspace in a loop after the static prefix above (if any).', 'dlc-designs' ),
			'section'     => 'dlc_designs_hero_section',
			'type'        => 'textarea',
		)
	);

	// Intro paragraph.
	$wp_customize->add_setting(
		'dlc_designs_hero_intro',
		array(
			'default'           => __( 'I have too much free time - if you need or want custom apparel, patches, stickers, etc, let\'s make it happen!', 'dlc-designs' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'dlc_designs_hero_intro',
		array(
			'label'   => __( 'Intro paragraph', 'dlc-designs' ),
			'section' => 'dlc_designs_hero_section',
			'type'    => 'textarea',
		)
	);

	// Button label.
	$wp_customize->add_setting(
		'dlc_designs_hero_button_label',
		array(
			'default'           => __( 'Shop', 'dlc-designs' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'dlc_designs_hero_button_label',
		array(
			'label'   => __( 'Button label', 'dlc-designs' ),
			'section' => 'dlc_designs_hero_section',
			'type'    => 'text',
		)
	);

	// Button URL.
	$wp_customize->add_setting(
		'dlc_designs_hero_button_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'dlc_designs_hero_button_url',
		array(
			'label'       => __( 'Button link', 'dlc-designs' ),
			'description' => __( 'Leave blank to link to the shop page automatically.', 'dlc-designs' ),
			'section'     => 'dlc_designs_hero_section',
			'type'        => 'url',
		)
	);

	// Optional hero image.
	$wp_customize->add_setting(
		'dlc_designs_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'dlc_designs_hero_image',
			array(
				'label'       => __( 'Hero image', 'dlc-designs' ),
				'description' => __( 'Optional. Leave empty for a clean text-only hero.', 'dlc-designs' ),
				'section'     => 'dlc_designs_hero_section',
			)
		)
	);
}
add_action( 'customize_register', 'dlc_designs_customize_register_hero' );

/**
 * Registers the "Footer" Customizer section and its controls.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function dlc_designs_customize_register_footer( $wp_customize ) {
	$wp_customize->add_section(
		'dlc_designs_footer_section',
		array(
			'title'       => __( 'Footer', 'dlc-designs' ),
			'description' => __( 'Assign menus to the footer columns under Appearance > Menus. Social links (Footer Social menu) show an icon that\'s picked automatically from each link\'s URL.', 'dlc-designs' ),
			'priority'    => 130,
		)
	);

	// Optional footer logo.
	$wp_customize->add_setting(
		'dlc_designs_footer_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'dlc_designs_footer_logo',
			array(
				'label'       => __( 'Footer logo', 'dlc-designs' ),
				'description' => __( 'Optional and separate from the header logo. Falls back to the site name as text if left empty.', 'dlc-designs' ),
				'section'     => 'dlc_designs_footer_section',
			)
		)
	);
}
add_action( 'customize_register', 'dlc_designs_customize_register_footer' );
