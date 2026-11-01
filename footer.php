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

$dlc_designs_footer_logo        = get_theme_mod( 'dlc_designs_footer_logo', '' );
$dlc_designs_has_footer_columns = has_nav_menu( 'footer-social' ) || has_nav_menu( 'footer-menu-1' ) || has_nav_menu( 'footer-menu-2' ) || is_active_sidebar( 'footer-newsletter' ) || $dlc_designs_footer_logo;
?>

	<footer id="colophon" class="site-footer">

		<?php if ( $dlc_designs_has_footer_columns ) : ?>
			<div class="site-footer__columns">

				<div class="site-footer__column site-footer__column--social">
					<?php if ( $dlc_designs_footer_logo ) : ?>
						<a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<img src="<?php echo esc_url( $dlc_designs_footer_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						</a>
					<?php else : ?>
						<a class="site-footer__logo site-footer__logo--text" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
					<?php endif; ?>

					<?php
					if ( has_nav_menu( 'footer-social' ) ) :
						wp_nav_menu(
							array(
								'theme_location' => 'footer-social',
								'menu_class'     => 'footer-social-menu',
								'container'      => false,
								'depth'          => 1,
								'walker'         => new DLC_Designs_Social_Menu_Walker(),
							)
						);
					endif;
					?>
				</div><!-- .site-footer__column--social -->

				<div class="site-footer__column">
					<?php
					if ( has_nav_menu( 'footer-menu-1' ) ) :
						wp_nav_menu(
							array(
								'theme_location' => 'footer-menu-1',
								'menu_class'     => 'footer-menu',
								'container'      => false,
								'depth'          => 1,
							)
						);
					endif;
					?>
				</div><!-- .site-footer__column -->

				<div class="site-footer__column">
					<?php
					if ( has_nav_menu( 'footer-menu-2' ) ) :
						wp_nav_menu(
							array(
								'theme_location' => 'footer-menu-2',
								'menu_class'     => 'footer-menu',
								'container'      => false,
								'depth'          => 1,
							)
						);
					endif;
					?>
				</div><!-- .site-footer__column -->

				<?php if ( is_active_sidebar( 'footer-newsletter' ) ) : ?>
					<div class="site-footer__column site-footer__column--newsletter">
						<?php dynamic_sidebar( 'footer-newsletter' ); ?>
					</div><!-- .site-footer__column--newsletter -->
				<?php endif; ?>

			</div><!-- .site-footer__columns -->
		<?php endif; ?>

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

		<?php if ( function_exists( 'omegamino_core_giveaway_disclosure' ) ) { omegamino_core_giveaway_disclosure(); } // Sweepstakes disclosure, only while a giveaway is live. ?>

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
