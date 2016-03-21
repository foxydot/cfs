<?php
/**
 * Implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Forever
 * @since Forever 1.1
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses forever_header_style()
 * @uses forever_admin_header_style()
 * @uses forever_admin_header_image()
 * @uses forever_fonts()
 *
 * * @since Forever 1.1
 */
function forever_custom_header_setup() {

	add_theme_support( 'custom-header', apply_filters( 'forever_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '1982d1',
		'width'                  => 885,
		'height'                 => 252,
		'flex-height'            => true,
		'random-default'         => true,
		'wp-head-callback'       => 'forever_header_style',
		'admin-head-callback'    => 'forever_admin_header_style',
		'admin-preview-callback' => 'forever_admin_header_image',
	) ) );

	add_action( 'admin_print_styles-appearance_page_custom-header', 'forever_fonts' );
}
add_action( 'after_setup_theme', 'forever_custom_header_setup' );

if ( ! function_exists( 'forever_header_style' ) ) :
/**
 * Custom styles for our blog header
 */
function forever_header_style() {
	// If no custom options for text are set, let's bail
	$header_image = get_header_image();
	if ( empty( $header_image ) && '' == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	#masthead img {
		margin: 1.615em 0 0;
	}
	.custom-header {
		display: block;
		text-align: center;
	}
	<?php
		// Has the text been hidden? Let's hide it then.
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#site-title {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
		#masthead img {
			margin: -0.8075em 0 0;
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // forever_header_style()

if ( ! function_exists( 'forever_admin_header_style' ) ) :
/**
 * Custom styles for the custom header page in the admin
 */
function forever_admin_header_style() {
?>
	<style type="text/css">
	#headimg {
		background: #fff;
		text-align: center;
		max-width: 885px;
	}
	#headimg .masthead {
		padding: 48px 0 0;
	}
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1 {
		font-family: Raleway, "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
		line-height: 1.17;
		margin-bottom: 0;
		padding-top: 0;
		font-size: 60px;
		font-weight: normal;
		line-height: 1.212;
	}
	#headimg h1 a {
		text-decoration: none;
	}
	#headimg h1 a:hover,
	#headimg h1 a:focus,
	#headimg h1 a:active {
	}
	#headimg #desc {
	}
	#headimg img {
		background: #fff;
		border: 1px solid #bbb;
		float: left;
		height: auto;
		margin: 21px 0 0;
		max-width: 100%;
		padding: 1px;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#headimg h1 a {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
	#headimg .masthead {
		display: none;
	}
	<?php endif; ?>
	</style>
<?php
}
endif; // forever_admin_header_style

if ( ! function_exists( 'forever_admin_header_image' ) ) :
/**
 * Custom markup for the custom header admin page
 */
function forever_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<div class="masthead">
			<h1><a id="name" onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		</div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // forever_admin_header_image