<?php
/**
 * @package Forever
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php forever_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<p class="comments-link"><?php comments_popup_link( '<span class="no-reply">' . __( '0', 'forever' ) . '</span>', __( '1', 'forever' ), __( '%', 'forever' ) ); ?></p>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'forever' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'forever' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<?php
		// translators: used between list items, there is a space after the comma
		$categories_list = get_the_category_list( __( ', ', 'forever' ) );

		// translators: used between list items, there is a space after the comma
		$tags_list = get_the_tag_list( '', __( ', ', 'forever' ) );

		// Check to see if there is a need for an article footer
		if (
			'post' == get_post_type() || $categories_list && forever_categorized_blog()
		) :
	?>
	<footer class="entry-meta">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				if ( $categories_list && forever_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'forever' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				if ( $tags_list ) :
			?>
			<span class="sep"> | </span>
			<span class="tag-links">
				<?php printf( __( 'Tagged %1$s', 'forever' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php edit_post_link( __( 'Edit', 'forever' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
	</footer><!-- #entry-meta -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
