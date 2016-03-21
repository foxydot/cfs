<?php
/**
 * The template for displaying posts in the Gallery Post Format on index and archive pages
 *
 * @package Forever
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php forever_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<p class="comments-link"><?php comments_popup_link( '<span class="no-reply">' . __( '0', 'forever' ) . '</span>', __( '1', 'forever' ), __( '%', 'forever' ) ); ?></p>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php if ( ! post_password_required() ) :
			$pattern = get_shortcode_regex();
			preg_match( "/$pattern/s", get_the_content(), $match );
			$atts   = isset( $match[3] ) ? shortcode_parse_atts( $match[3] ) : array();
			$images = isset( $atts['ids'] ) ? explode( ',', $atts['ids'] ) : false;

			if ( ! $images ) :
				$images = get_posts( array(
					'post_parent'    => $post->ID,
					'fields'         => 'ids',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'numberposts'    => 999,
					'suppress_filters' => false
				) );
			endif;

			if ( $images ) :
				$total_images = count( $images );
				$image = array_shift( $images );
				$image_img_tag = wp_get_attachment_image( $image, forever_get_post_thumbnail_size() );
			?>
				<figure class="entry-thumb">
					<?php if ( '' != get_the_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( forever_get_post_thumbnail_size() ); ?></a>
					<?php elseif ( ! empty( $image_img_tag ) ) : ?>
						<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
					<?php endif; ?>
				</figure><!-- .gallery-thumb -->

				<?php if ( 0 < $total_images ) : ?>
					<p><em><?php
						printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'forever' ),
							'href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( sprintf( __( 'Permalink to %s', 'forever' ), the_title_attribute( 'echo=0' ) ) ) . '" rel="bookmark"',
							number_format_i18n( $total_images )
						);
					?></em></p>
				<?php endif; ?>
			<?php endif; ?>

		<?php endif; // if ( ! post_password_required() ) ?>

		<?php the_excerpt(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'forever' ), 'after' => '</div>' ) ); ?>

		<div class="entry-likes">
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'forever' ), 'after' => '</div>' ) ); ?>
					<?php if ( function_exists( 'sharing_display' ) ) {
			    sharing_display( '', true );
			}

			if ( class_exists( 'Jetpack_Likes' ) ) {
			    $custom_likes = new Jetpack_Likes;
			    echo $custom_likes->post_likes( '' );
			} ?>
		</div><!-- .entry-likes -->

	</div><!-- .entry-summary -->

	<?php
		// translators: used between list items, there is a space after the comma
		$categories_list = get_the_category_list( __( ', ', 'forever' ) );

		// translators: used between list items, there is a space after the comma
		$tags_list = get_the_tag_list( '', __( ', ', 'forever' ) );

		// Check to see if there is a need for an article footer
		if ( 'post' == get_post_type() || $categories_list && forever_categorized_blog() ) :
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
			<span class="tag-links">
				<?php printf( __( 'Tagged %1$s', 'forever' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php edit_post_link( __( 'Edit', 'forever' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- #entry-meta -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
