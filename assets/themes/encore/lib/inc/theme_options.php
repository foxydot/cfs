<?php

/**
 * Theme Options
 *
 * @package      MSDLAB Blue
 * @author       MSDLab
 * @copyright    Copyright (c) 2015, Mad Science Dept.
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */


/**
 * Register Defaults
 * @author MSD Lab
 *
 * @param array $defaults
 * @return array modified defaults
 *
 */
 
add_action('admin_enqueue_scripts','msdlab_blue_options_scripts');
function msdlab_blue_options_scripts(){
    $screen = get_current_screen();
    if($screen->id == 'toplevel_page_genesis'){ //only do if on the options page
        // Include in admin_enqueue_scripts action hook
        wp_enqueue_media();
        wp_enqueue_script( 'custom-header' );
        wp_enqueue_style('bootstrap-style','//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css');       
    }
}
function msdlab_blue_defaults( $defaults ) {

    $defaults['color'] = '';
    $defaults['logo'] = '';
    return $defaults;
}
add_filter( 'genesis_theme_settings_defaults', 'msdlab_blue_defaults' );


/**
 * Sanitization
 * @author MSD Lab
 *
 */

function msdlab_register_blue_sanitization_filters() {
    genesis_add_option_filter( 'no_html', GENESIS_SETTINGS_FIELD,
        array(
            'color',
            'logo',
        ) );
}
add_action( 'genesis_settings_sanitizer_init', 'msdlab_register_blue_sanitization_filters' );


/**
 * Register Metabox
 * @author MSD Lab
 *
 * @param string $_genesis_theme_settings_pagehook
 */

function msdlab_register_blue_settings_box( $_genesis_theme_settings_pagehook ) {
    add_meta_box('msdlab-blue-settings', 'Child Theme Settings', 'msdlab_blue_settings_box', $_genesis_theme_settings_pagehook, 'main', 'high');
}
add_action('genesis_theme_settings_metaboxes', 'msdlab_register_blue_settings_box');

/**
 * Create Metabox
 * @author MSD Lab
 *
 */

function msdlab_blue_settings_box() {
    $color = esc_attr( genesis_get_option('color') );
    $logo = esc_attr( genesis_get_option('logo') );
    // Add to the top of our data-update-link page
    if (isset($_REQUEST['file'])) { 
        check_admin_referer("blue_options");
            // Process and save the image id
        $logo = absint($_REQUEST['file']);
    }
    ?>
    <div class="row">
        <label class="col-md-3">Logo</label>
        <div class="col-md-9"><?php
$modal_update_href = esc_url( add_query_arg( array(
    'page' => 'genesis',
    '_wpnonce' => wp_create_nonce('blue_options'),
), admin_url('admin.php') ) );
    if($logo != ''){
        print wp_get_attachment_image( $logo, array(100,200)).' ';
    }
?>
     <input name="<?php echo GENESIS_SETTINGS_FIELD; ?>[logo]" type="hidden" value="<?php print $logo; ?>" />
<a id="choose-from-library-link" href="#"
    class="button"
    data-update-link="<?php echo esc_attr( $modal_update_href ); ?>"
    data-choose="<?php esc_attr_e( 'Choose a Logo' ); ?>"
    data-update="<?php esc_attr_e( 'Set as site logo' ); ?>"><?php _e( 'Set site logo' ); ?>
</a> 
    </div>
    </div>
    <div class="row">
        <label class="col-md-3">Color Scheme</label>
        <div class="col-md-9">
        <select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[color]">
            <option value="blue"<?php print $color == 'blue'?' selected':''; ?>>Blue</option>
            <option value="green"<?php print $color == 'green'?' selected':''; ?>>Green</option>
            <option value="purple"<?php print $color == 'purple'?' selected':''; ?>>Purple</option>
        </select>
        </div>
    </div>
    
    
    <?php
}


add_filter('body_class','msdlab_blue_settings_body_class');
function msdlab_blue_settings_body_class($classes) {
    $classes[] = 'blue-'.esc_attr( genesis_get_option('color') );
    return $classes;
}

add_action('wp_head','msdlab_blue_logo');
function msdlab_blue_logo(){
    if(!is_admin()){
        $logo = wp_get_attachment_image_src( esc_attr( genesis_get_option('logo') ), 'full' );
        print '<style>
            .header-image .site-title a{
                background-image: url('.$logo[0].');
            }
        </style>';
    }
}
