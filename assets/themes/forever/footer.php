<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Forever
 * @since Forever 1.0
 */
?>

		<?php

		if ( is_home() ) {
			// Highlight the latest Status or Quote post
			$recent_formatted_args = array(
				'order' => 'DESC',
				'ignore_sticky_posts' => 1,
				'posts_per_page' => '1',
				'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'terms' => array( 'post-format-status', 'post-format-quote' ),
						'field' => 'slug',
						'operator' => 'IN',
					),
				),
				'no_found_rows' => true,
			);

			// Our new query for the latest Status or Quote section.
			$recent_formatted = new WP_Query( $recent_formatted_args );

			while ( $recent_formatted->have_posts() ) : $recent_formatted->the_post(); ?>
				<div id="latest-message" class="format-<?php echo get_post_format(); ?>">
					<div class="avatar"><?php echo get_avatar( $post->post_author, $size = '54' ); ?></div>
					<div class="message-content">
						<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'forever' ) ); ?>
					</div>
					<p class="permalink"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></p>
				</div><!-- #latest-message -->
			<?php endwhile;
		} ?>

	</div><!-- #main -->

	<?php
		/* The footer widget area should only be triggered if any of the areas
		 * have widgets. So let's check that first.
		 */
		if (   is_active_sidebar( 'sidebar-2'  )
			|| is_active_sidebar( 'sidebar-3' )
			|| is_active_sidebar( 'sidebar-4'  )
			|| is_active_sidebar( 'sidebar-5'  )
		) {
		// If we get this far, we have widgets. Let do this.
	?>
	<div id="supplementary" <?php forever_footer_sidebar_class(); ?>>
		<?php if ( is_active_sidebar( 'sidebar-2' ) ) { ?>
		<div id="first" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
		</div><!-- #first .widget-area -->
		<?php } ?>

		<?php if ( is_active_sidebar( 'sidebar-3' ) ) { ?>
		<div id="second" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-3' ); ?>
		</div><!-- #second .widget-area -->
		<?php } ?>

		<?php if ( is_active_sidebar( 'sidebar-4' ) ) { ?>
		<div id="third" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-4' ); ?>
		</div><!-- #third .widget-area -->
		<?php } ?>

		<?php if ( is_active_sidebar( 'sidebar-5' ) ) { ?>
		<div id="fourth" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-5' ); ?>
		</div><!-- #fourth .widget-area -->
		<?php } ?>
	</div><!-- #supplementary -->
	<?php } // is_active_sidebar() ?>

	<?php get_template_part( 'colophon' ); ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>