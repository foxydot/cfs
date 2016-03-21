<?php
/**
 * Displays the latest four posts in grid format.
 *
 * @package Forever
 * @since Forever 1.1
 */

$latest_four = apply_filters( 'forever-recent-posts', false );

if ( ! $latest_four )
	return;

$post_counter = 0;

$latest_content = new WP_Query( array(
	'order'               => 'DESC',
	'post__in'            => (array) $latest_four,
	'ignore_sticky_posts' => 1
) );

?>

<div id="recent-content">

	<?php while ( $latest_content->have_posts() ) :

		$latest_content->the_post();
		$post_counter++; ?>

		<article class="recent-post" id="recent-post-<?php echo $post_counter; ?>">

			<?php
				$thumbnail = get_the_post_thumbnail( null, 'small-feature' );
				if ( ! empty( $thumbnail ) ) {
					printf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
						esc_url( get_permalink() ),
						esc_attr( sprintf( __( 'Permalink to %s', 'forever' ), the_title_attribute( 'echo=0' ) ) ),
						$thumbnail
					 );
				}
			?>

			<header class="recent-header">
				<h1 class="recent-title"><?php
					printf( '<a href="%1$s" rel="bookmark">%2$s</a>',
						esc_url( get_permalink() ),
						get_the_title()
					 );
				?></h1>

				<div class="entry-meta">
					<?php forever_posted_on(); ?>
				</div><!-- .entry-meta -->
			</header><!-- .recent-header -->

			<div class="recent-summary">
				<?php the_excerpt(); ?>
			</div><!-- .recent-summary -->
		</article>

	<?php endwhile; ?>

</div>

<?php wp_reset_postdata();
