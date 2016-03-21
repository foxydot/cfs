<?php
/**
 * Footer replacement with MSDSocial support
 */
function msdlab_do_social_footer(){
    global $msd_social;
    global $wp_filter;
    //ts_var( $wp_filter['genesis_after_endwhile'] );
    
    if(has_nav_menu('footer_menu')){$footer_menu .= wp_nav_menu( array( 'theme_location' => 'footer_menu','container_class' => 'menu genesis-nav-menu nav-footer','echo' => FALSE ) );}
    
    if($msd_social && get_option('msdsocial_street')!=''){
        $address = '| <span itemprop="streetAddress">'.get_option('msdsocial_street').'</span>, <span itemprop="streetAddress">'.get_option('msdsocial_street2').'</span> | <span itemprop="addressLocality">'.get_option('msdsocial_city').'</span>, <span itemprop="addressRegion">'.get_option('msdsocial_state').'</span> <span itemprop="postalCode">'.get_option('msdsocial_zip').'</span> | '.$msd_social->get_digits(true,'');
        $copyright .= '&copy; Copyright '.date('Y').' '.$msd_social->get_bizname().' &middot; All Rights Reserved ';
    } else {
        $copyright .= '&copy; Copyright '.date('Y').' '.get_bloginfo('name').' &middot; All Rights Reserved ';
    }
    print '<div class="row">';    
    print '<nav class="footer-menu" itemtype="http://schema.org/SiteNavigationElement" itemscope="itemscope" role="navigation">'.$footer_menu.'</nav>';
    print '<div class="social">'.$copyright.' '.$address.'</div>';
    print '</div>';
    //print '<div class="backtotop"><a href="#pre-header"><i class="fa fa-angle-up"></i></a></div>';
}
