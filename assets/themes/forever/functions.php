<?php
/**
 * Forever functions and definitions
 *
 * @package Forever
 * @since Forever 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 560; /* pixels */

function forever_full_width() {
	global $content_width;
	if ( is_page_template( 'nosidebar-page.php' ) )
		$content_width = 890;
}
add_action( 'template_redirect', 'forever_full_width' );

if ( ! function_exists( 'forever_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @uses forever_add_image_sizes()
 */
function forever_setup() {
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 */
	load_theme_textdomain( 'forever', get_template_directory() . '/languages' );

	forever_maybe_enable_theme_options();

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'forever' ),
	) );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'gallery', 'image', 'status', 'quote' ) );

	/**
	 * Enqueue styles.
	 */
	function forever_styles() {
		wp_enqueue_style( 'forever-style', get_stylesheet_uri() );
	}
	add_action( 'wp_enqueue_scripts', 'forever_styles' );

	/**
	 * Enqueue Fonts.
	 */
	function forever_fonts() {
		$protocol = is_ssl() ? 'https' : 'http';
		wp_enqueue_style( 'raleway', "$protocol://fonts.googleapis.com/css?family=Raleway:100" );
	}
	add_action( 'wp_enqueue_scripts', 'forever_fonts' );

	/**
	 * Enqueue scripts
	 */
	function forever_scripts() {
		// enqueue comment reply script
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// enqueue masonry for the guestbook page template
		if ( is_page_template( 'guestbook.php') ) {
			wp_enqueue_script( 'jquery-masonry' );
			wp_enqueue_script( 'guestbook', get_template_directory_uri() . '/js/guestbook.js', array( 'jquery' ), '28-12-2011', true );
		}
	}
	add_action( 'wp_enqueue_scripts', 'forever_scripts' );

	/**
	 * This theme uses Featured Images (also known as post thumbnails)
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * Register custom image sizes.
	 */
	forever_add_image_sizes();

}
endif; // forever_setup

/**
 * Tell WordPress to run forever_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'forever_setup' );

/**
 * Maybe enable theme options.
 *
 * Theme options are enabled by default in Forever.
 * Child themes and plugins may disable all
 * options by using the following code before the
 * "after_theme_setup" action fires at priority 10.
 *
 * add_filter( 'forever-enable-theme-options', '__return_false' );
 *
 * @since Forever 1.1
 */
function forever_maybe_enable_theme_options() {
	if ( apply_filters( 'forever-enable-theme-options', true ) )
		require( get_template_directory() . '/inc/theme-options.php' );
}

/**
 * Add image sizes.
 *
 * @since Forever 1.1
 */
function forever_get_image_sizes() {
	$defaults = array(
		'large-feature' => array( 888, 355, true ),
		'small-feature' => array( 195, 124, true ),
	);

	$sizes = apply_filters( 'forever-image-sizes', $defaults );

	return $sizes;
}

/**
 * Add image sizes.
 *
 * @uses forever_get_image_sizes().
 * @since Forever 1.1
 */
function forever_add_image_sizes() {
	$sizes = forever_get_image_sizes();

	foreach ( (array) $sizes as $name => $size ) {
		add_image_size( $name, $size[0], $size[1], $size[2] );
	}
}

/**
 * Enables child themes and plugins to set a
 * custom image size for post thumbnail.
 *
 * @since Forever 1.1
 */
function forever_get_post_thumbnail_size() {
	return apply_filters( 'forever-post-thumbnail-size', 'thumbnail' );
}

/**
 * Setup the WordPress core custom background feature.
 *
 * Hooks into the after_setup_theme action.
 */
function forever_register_custom_background() {
	add_theme_support( 'custom-background', apply_filters( 'forever_custom_background_args', array(
		'default-color' => 'fff',
		'default-image' => get_template_directory_uri() . '/images/body-bg.png',
	) ) );
}
add_action( 'after_setup_theme', 'forever_register_custom_background' );

/**
 * Sets the post excerpt length to 40 words.
 */
function forever_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'forever_excerpt_length' );

/**
 * Returns a "Continue reading" link for excerpts
 */
function forever_continue_reading_link() {
	return ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'forever' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and forever_continue_reading_link().
 */
function forever_auto_excerpt_more( $more ) {
	return ' &hellip;' . forever_continue_reading_link();
}
add_filter( 'excerpt_more', 'forever_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 */
function forever_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= forever_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'forever_custom_excerpt_more' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function forever_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'forever_page_menu_args' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function forever_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'forever' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Widget Area One', 'forever' ),
		'description' => __( 'An optional footer widget area.', 'forever' ),
		'id' => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Widget Area Two', 'forever' ),
		'description' => __( 'An optional footer widget area.', 'forever' ),
		'id' => 'sidebar-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Widget Area Three', 'forever' ),
		'description' => __( 'An optional footer widget area.', 'forever' ),
		'id' => 'sidebar-4',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Widget Area Four', 'forever' ),
		'description' => __( 'An optional footer widget area.', 'forever' ),
		'id' => 'sidebar-5',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'forever_widgets_init' );

if ( ! function_exists( 'forever_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Forever 1.0
 */
function forever_content_nav( $nav_id ) {
	global $wp_query;

	?>
	<nav id="<?php echo esc_attr( $nav_id ); ?>">
		<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'forever' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'forever' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'forever' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'forever' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'forever' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // forever_content_nav


if ( ! function_exists( 'forever_comment' ) ) :
/**
 * Template for comments and pingbacks.
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Forever 1.0
 */
function forever_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'forever' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'forever' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php
					$comment_avatar_size = 54;
					if ( 0 != $comment->comment_parent )
						$comment_avatar_size = 28;

					echo get_avatar( $comment, $comment_avatar_size );
					?>
					<cite class="fn"><?php comment_author_link(); ?></cite>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'forever' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a class="comment-time" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'forever' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( 'Edit', 'forever' ), ' ' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for forever_comment()

if ( ! function_exists( 'forever_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Forever 1.0
 */
function forever_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'forever' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'forever' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Forever 1.0
 */
function forever_body_classes( $classes ) {
	// Adds a class of single-author to blogs with only 1 published author
	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	// Adds a class of index to views that are not posts or pages or search
	if ( ! is_singular() && ! is_search() ) {
		$classes[] = 'indexed';
	}

	return $classes;
}
add_filter( 'body_class', 'forever_body_classes' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function forever_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
		case '4':
			$class = 'four';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

/**
 * Returns true if a blog has more than 1 category
 *
 * @since Forever 1.0
 */
function forever_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so forever_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so forever_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in forever_categorized_blog
 *
 * @since Forever 1.0
 */
function forever_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'forever_category_transient_flusher' );
add_action( 'save_post', 'forever_category_transient_flusher' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function forever_enhanced_image_navigation( $url ) {
	global $post, $wp_rewrite;

	// Bail if there is no $post, like in the dashboard recent comments widget.
	if ( ! isset( $post ) )
		return $url;

	$id     = (int) $post->ID;
	$object = get_post( $id );
	if ( wp_attachment_is_image( $post->ID ) && ( $wp_rewrite->using_permalinks() && ( $object->post_parent > 0 ) && ( $object->post_parent != $id ) ) )
		$url = $url . '#main';

	return $url;
}
add_filter( 'attachment_link', 'forever_enhanced_image_navigation' );

/**
 * Filter the home page posts, and remove any featured post IDs from it. Hooked
 * onto the 'pre_get_posts' action, this changes the parameters of the query
 * before it gets any posts.
 *
 * @global array $featured_post_id
 * @param WP_Query $query
 * @return WP_Query Possibly modified WP_query
 */
function forever_home_posts( $query = false ) {
	// Bail if not home, not a query, not main query.
	if ( ! is_home() || ! is_a( $query, 'WP_Query' ) || ! $query->is_main_query() )
		return $query;

	$featured = forever_featured_posts();
	$recent   = apply_filters( 'forever-recent-posts', false );

	// Bail if no featured posts.
	if ( ! $featured && ! $recent )
		return $query;

	// Exclude featured posts from the main query.
	$query->query_vars['post__not_in'] = array_merge( (array) $featured, (array) $recent );

	return $query;
}
add_action( 'pre_get_posts', 'forever_home_posts' );

/**
 * Test to see if any posts meet our conditions for featuring posts.
 *
 * @return mixed Array of featured post ids on success, false on failure.
 */
function forever_featured_posts() {
	$featured_post_ids = get_transient( 'featured_post_ids' );

	// Return cached results.
	if ( ! empty( $featured_post_ids ) )
		return $featured_post_ids;

	$sticky_posts = get_option( 'sticky_posts' );

	// Proceed only if sticky posts exist.
	if ( empty( $sticky_posts ) || ! is_array( $sticky_posts ) )
		return false;

	// The Featured Posts query.
	$featured = new WP_Query( array(
		'post__in'            => $sticky_posts,
		'post_status'         => 'publish',
		'no_found_rows'       => true,
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => 50,
	) );

	// Proceed only if published posts exist.
	if ( ! $featured->have_posts() )
		return false;

	$sizes = forever_get_image_sizes();

	$min_width = 888;
	if ( isset( $sizes['large-feature'][0] ) )
		$min_width = $sizes['large-feature'][0];

	while ( $featured->have_posts() ) {
		$featured->the_post();

		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $featured->post->ID ), 'large-feature' );

		if ( $min_width <= $image[1] )
			$featured_post_ids[] = $featured->post->ID;
	}

	set_transient( 'featured_post_ids', $featured_post_ids );

	wp_reset_postdata();

	return $featured_post_ids;
}

/**
 * Flush out the transients used in forever_featured_posts()
 *
 * Hooks into the "save_post" and "update_option_sticky_posts" actions.
 *
 * Vvwooshh!
 */
function forever_featured_post_checker_flusher() {
	delete_transient( 'featured_post_ids' );
}
add_action( 'update_option_sticky_posts', 'forever_featured_post_checker_flusher' );
add_action( 'save_post', 'forever_featured_post_checker_flusher' );

require_once( 'inc/custom-header.php' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Forever 1.1
 */
function forever_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'forever' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'forever_wp_title', 10, 2 );

/*
 * Remove widont filter if Posts in Columns options is active
 *
 * @since Forever 1.2.2
 */
function forever_wido() {
	if ( ! function_exists( 'forever_get_theme_options' ) ) {
		return false;
	}

	$options = forever_get_theme_options();

	if ( 'on' != $options['posts_in_columns'] ) {
		return false;
	}

	remove_filter( 'the_title', 'widont' );
}
add_action( 'init', 'forever_wido' );
