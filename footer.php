<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dlc_Designs
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<span class="site-info__copyright">
				<?php
				printf(
					/* translators: 1: Current year, 2: Site name. */
					esc_html__( '© %1$s %2$s', 'dlc-designs' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</span>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
