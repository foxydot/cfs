<?php
/**
 * Homepage Greeting.
 *
 * This template is displayed below the featured post slider
 * and above the posts list on the posts page. By default it
 * will display the site's tagline.
 *
 * @since Forever 1.1
 */
?>

<?php

$description = get_bloginfo( 'description' );

if ( ! empty ( $description ) ) : ?>
	<div id="description">
		<h2 id="site-description"><?php echo esc_html( $description ); ?></h2>
	</div><!-- #description -->
<?php endif; ?>