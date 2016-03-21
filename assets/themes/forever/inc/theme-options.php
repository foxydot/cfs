<?php
/**
 * Forever Theme Options
 *
 * @package Forever
 * @since Forever 1.0
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 */
function forever_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'forever-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-12-19' );
	wp_enqueue_script( 'forever-theme-options', get_template_directory_uri() . '/inc/theme-options.js', array( 'farbtastic' ), '2011-12-19' );
	wp_enqueue_style( 'farbtastic' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'forever_admin_enqueue_scripts' );

/**
 * Register the form setting for our forever_options array.
 */
function forever_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === forever_get_theme_options() )
		add_option( 'forever_theme_options', forever_get_default_theme_options() );

	register_setting(
		'forever_options',       // Options group, see settings_fields() call in forever_theme_options_render_page()
		'forever_theme_options', // Database option, see forever_get_theme_options()
		'forever_theme_options_validate' // The sanitization callback, see forever_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see forever_theme_options_add_page()
	);

	// Register our individual settings fields
	add_settings_field( 'link_color', __( 'Link Color', 'forever' ), 'forever_settings_field_link_color', 'theme_options', 'general' );
	add_settings_field( 'posts_in_columns', __( 'Posts In Columns', 'forever' ), 'forever_settings_field_posts_in_columns', 'theme_options', 'general' );
}
add_action( 'admin_init', 'forever_theme_options_init' );

/**
 * Change the capability required to save the 'forever_options' options group.
 */
function forever_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_forever_options', 'forever_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 */
function forever_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'forever' ),   // Name of page
		__( 'Theme Options', 'forever' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'forever_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;
}
add_action( 'admin_menu', 'forever_theme_options_add_page' );

/**
 * Returns the default options for Forever.
 */
function forever_get_default_theme_options() {
	$default_theme_options = array(
		'link_color'   => '#1982d1',
		'posts_in_columns' => 'off',
	);

	return apply_filters( 'forever_default_theme_options', $default_theme_options );
}

/**
 * Returns the options array for Forever.
 */
function forever_get_theme_options() {
	return get_option( 'forever_theme_options', forever_get_default_theme_options() );
}

/**
 * Renders the Link Color setting field.
 */
function forever_settings_field_link_color() {
	$options = forever_get_theme_options();
	?>
	<input type="text" name="forever_theme_options[link_color]" id="link-color" value="<?php echo esc_attr( $options['link_color'] ); ?>" />
	<a href="#" class="pickcolor hide-if-no-js" id="link-color-example"></a>
	<input type="button" class="pickcolor button hide-if-no-js" value="<?php esc_attr_e( 'Select a Color', 'forever' ); ?>" />
	<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
	<br />
	<span><?php printf( __( 'Default color: %s', 'forever' ), '<span id="default-color">#1982d1</span>' ); ?></span>
	<?php
}

/**
 * Renders the Posts-in-columns setting field.
 */
function forever_settings_field_posts_in_columns() {
	$options = forever_get_theme_options();
	?>
	<label for"posts-in-columns">
		<input type="checkbox" name="forever_theme_options[posts_in_columns]" id="posts-in-columns" <?php checked( 'on', $options['posts_in_columns'] ); ?> />
		<?php _e( 'Show four of your latest posts in columns on your blog page.', 'forever' );  ?>
	</label>
	<?php
}

/**
 * Returns the options array for Forever.
 */
function forever_theme_options_render_page() {
	?>
	<div class="wrap">
		<h2><?php printf( __( '%s Theme Options', 'forever' ), wp_get_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'forever_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 */
function forever_theme_options_validate( $input ) {
	$output = $defaults = forever_get_default_theme_options();

	// Link color must be 3 or 6 hexadecimal characters
	if ( isset( $input['link_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['link_color'] ) )
		$output['link_color'] = '#' . strtolower( ltrim( $input['link_color'], '#' ) );

	// The posts in columns option should either be on or off
	if ( ! isset( $input['posts_in_columns'] ) )
		$input['posts_in_columns'] = 'off';
	$output['posts_in_columns'] = ( $input['posts_in_columns'] == 'on' ? 'on' : 'off' );


	return apply_filters( 'forever_theme_options_validate', $output, $input, $defaults );
}

/**
 * Add a style block to the theme for the current link color.
 */
function forever_print_link_color_style() {
	$options = forever_get_theme_options();
	$link_color = $options['link_color'];

	$default_options = forever_get_default_theme_options();

	// Don't do anything if the current link color is the default.
	if ( $default_options['link_color'] == $link_color )
		return;
?>
	<style>
		/* Link color */
		a,
		a:visited,
		#site-title a,
		.recent-title a:hover,
		.recent-title a:focus,
		.recent-title a:active,
		.entry-title a:hover,
		.entry-title a:focus,
		.entry-title a:active,
		.comment-meta a:hover,
		.comment-meta a:focus,
		.comment-meta a:active {
			color: <?php echo $link_color; ?>;
		}
	</style>
<?php
}
add_action( 'wp_head', 'forever_print_link_color_style' );

/**
 * Maybe we'll show the latest four posts in a grid!
 *
 * This function is never called in the theme. Its return
 * value can be accessed via the custom "forever-recent-posts"
 * filter.
 *
 * @return array An array of integers representing post IDs.
 * @since Forever 1.0
 */
function forever_recent_four_posts() {

	// Return early if this feature has been disabled.
	$options = forever_get_theme_options();
	if ( 'on' != $options['posts_in_columns'] )
		return false;

	if ( false === ( $latest_post_ids = get_transient( 'latest_post_ids' ) ) ) {
		$args = array(
			'order'               => 'DESC',
			'ignore_sticky_posts' => 1,
			'post__not_in'        => forever_featured_posts(),
			'posts_per_page'      => '4',
			'tax_query'           => array( array(
				'taxonomy'        => 'post_format',
				'terms'           => array( 'post-format-status', 'post-format-quote', 'post-format-gallery', 'post-format-image' ),
				'field'           => 'slug',
				'operator'        => 'NOT IN',
			) )
		);
		$latest = new WP_Query();
		$latest->query( $args );

		while ( $latest->have_posts() ) {
			$latest->the_post();
			$latest_post_ids[] = $latest->post->ID;
		}
		wp_reset_postdata();
	}

	return $latest_post_ids;
}
add_filter( 'forever-recent-posts', 'forever_recent_four_posts' );

/**
 * Flush out the transients used in forever_featured_posts()
 */
function forever_latest_post_checker_flusher() {
	// Vvwooshh!
	delete_transient( 'latest_post_ids' );
}
add_action( 'save_post', 'forever_latest_post_checker_flusher' );

