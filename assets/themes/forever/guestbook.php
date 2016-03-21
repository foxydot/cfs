<?php
/**
 * Template name: Guestbook
 *
 * Using this template allows comments on a page to be displayed prominently
 *
 * @package Forever
 * @since Forever 1.0
 */

get_header(); ?>

		<div id="primary" class="guestbook">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>