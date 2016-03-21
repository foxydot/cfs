<?php
/**
 * Colophon
 *
 * This template is displayed just before closing #page.
 * By default it displays generator link along with the
 * theme name and the author.
 *
 * @since Forever 1.1
 */
?>

<footer id="colophon" role="contentinfo">
	<div id="site-info">
		<?php do_action( 'forever_credits' ); ?>
		<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'forever' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'forever' ), 'WordPress' ); ?></a>
		<?php printf( __( 'Theme: %1$s by %2$s.', 'forever' ), 'Forever', '<a href="https://wordpress.com/themes/" rel="designer">WordPress.com</a>' ); ?>
	</div>
</footer><!-- #colophon -->