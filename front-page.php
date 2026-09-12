<?php
/**
 * The template for displaying the homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Dlc_Designs
 */

get_header();

$dlc_designs_hero_eyebrow           = get_theme_mod( 'dlc_designs_hero_eyebrow', __( 'Welcome to my world', 'dlc-designs' ) );
$dlc_designs_hero_heading           = get_theme_mod( 'dlc_designs_hero_heading', __( "Hi, I'm DLC", 'dlc-designs' ) );
$dlc_designs_hero_intro             = get_theme_mod( 'dlc_designs_hero_intro', '' );
$dlc_designs_hero_button_label      = get_theme_mod( 'dlc_designs_hero_button_label', __( 'Shop', 'dlc-designs' ) );
$dlc_designs_hero_button_url        = get_theme_mod( 'dlc_designs_hero_button_url', '' );
$dlc_designs_hero_image             = get_theme_mod( 'dlc_designs_hero_image', '' );
$dlc_designs_hero_typewriter_prefix = get_theme_mod( 'dlc_designs_hero_typewriter_prefix', __( 'I make', 'dlc-designs' ) );

if ( ! $dlc_designs_hero_button_url ) {
	$dlc_designs_hero_button_url = dlc_designs_default_hero_button_url();
}

$dlc_designs_hero_phrases_raw = get_theme_mod( 'dlc_designs_hero_phrases', implode( "\n", dlc_designs_default_hero_phrases() ) );
$dlc_designs_hero_phrases     = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $dlc_designs_hero_phrases_raw ) ) );
$dlc_designs_hero_phrases     = array_values( $dlc_designs_hero_phrases );

$dlc_designs_hero_classes = 'hero';
if ( $dlc_designs_hero_image ) {
	$dlc_designs_hero_classes .= ' hero--with-image';
}
?>

	<main id="primary" class="site-main">

		<section class="<?php echo esc_attr( $dlc_designs_hero_classes ); ?>">

			<?php if ( $dlc_designs_hero_image ) : ?>
				<div class="hero__media">
					<img src="<?php echo esc_url( $dlc_designs_hero_image ); ?>" alt="<?php echo esc_attr( $dlc_designs_hero_heading ); ?>" loading="eager">
				</div>
			<?php endif; ?>

			<div class="hero__content">

				<?php if ( $dlc_designs_hero_eyebrow ) : ?>
					<p class="hero__eyebrow"><?php echo esc_html( $dlc_designs_hero_eyebrow ); ?></p>
				<?php endif; ?>

				<h1 class="hero__heading"><?php echo esc_html( $dlc_designs_hero_heading ); ?></h1>

				<?php if ( ! empty( $dlc_designs_hero_phrases ) ) : ?>
					<p class="hero__typewriter">
						<?php if ( $dlc_designs_hero_typewriter_prefix ) : ?>
							<span class="hero__typewriter-prefix"><?php echo esc_html( $dlc_designs_hero_typewriter_prefix ); ?></span>
						<?php endif; ?>
						<span class="hero__typewriter-text" data-typewriter-phrases="<?php echo esc_attr( wp_json_encode( $dlc_designs_hero_phrases ) ); ?>"><?php echo esc_html( $dlc_designs_hero_phrases[0] ); ?></span><span class="hero__cursor" aria-hidden="true">&#9608;</span>
					</p>
				<?php endif; ?>

				<?php if ( $dlc_designs_hero_intro ) : ?>
					<p class="hero__intro"><?php echo esc_html( $dlc_designs_hero_intro ); ?></p>
				<?php endif; ?>

				<p class="hero__cta">
					<a class="button" href="<?php echo esc_url( $dlc_designs_hero_button_url ); ?>"><?php echo esc_html( $dlc_designs_hero_button_label ); ?></a>
				</p>

			</div><!-- .hero__content -->

		</section><!-- .hero -->

	</main><!-- #main -->

<?php
get_footer();
