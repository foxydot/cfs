<?php
/*
 * Add styles and scripts
*/
add_action('wp_enqueue_scripts', 'msdlab_add_styles');
add_action('wp_enqueue_scripts', 'msdlab_add_scripts');

function msdlab_add_styles() {
    global $is_IE;
    if(!is_admin()){
        //use cdn        
            wp_enqueue_style('bootstrap-style','//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css');
            wp_enqueue_style('font-awesome-style','//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css',array('bootstrap-style'));
        //use local
           // wp_enqueue_style('bootstrap-style',get_stylesheet_directory_uri().'/lib/bootstrap/css/bootstrap.css');
           // wp_enqueue_style('font-awesome-style',get_stylesheet_directory_uri().'/lib/font-awesome/css/font-awesome.css',array('bootstrap-style'));
            $queue[] = 'bootstrap-style';
            $queue[] = 'font-awesome-style';
        wp_enqueue_style('msd-style',get_stylesheet_directory_uri().'/lib/css/style.css',$queue);
        $queue[] = 'msd-style';
        if(is_front_page()){
            wp_enqueue_style('msd-homepage-style',get_stylesheet_directory_uri().'/lib/css/homepage.css',$queue);
            $queue[] = 'msd-homepage-style';
        }    
        if($is_IE){
            wp_enqueue_style('ie-style',get_stylesheet_directory_uri().'/lib/css/ie.css',$queue);
            $queue[] = 'ie-style';
            
            wp_enqueue_style('ie8-style',get_template_directory_uri() . '/lib/css/ie8.css');
            global $wp_styles;
            $wp_styles->add_data( 'ie8-style', 'conditional', 'lte IE 8' );
        }    
    }
}

function msdlab_add_scripts() {
    global $is_IE;
    if(!is_admin()){
        //use cdn
            wp_enqueue_script('bootstrap-jquery','//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js',array('jquery'));
        //use local
            //wp_enqueue_script('bootstrap-jquery',get_stylesheet_directory_uri().'/lib/bootstrap/js/bootstrap.min.js',array('jquery'));
        wp_enqueue_script('msd-jquery',get_stylesheet_directory_uri().'/lib/js/theme-jquery.js',array('jquery','bootstrap-jquery'));
        wp_enqueue_script('nav-scripts',get_stylesheet_directory_uri().'/lib/js/nav-scripts.js',array('jquery','bootstrap-jquery'));
        wp_enqueue_script('equalHeights',get_stylesheet_directory_uri().'/lib/js/jquery.equal-height-columns.js',array('jquery'));
        wp_deregister_script('greensock');
        wp_enqueue_script('tweenlite',get_stylesheet_directory_uri().'/lib/js/greensock/TweenLite.min.js');
        wp_enqueue_script('tweenmax',get_stylesheet_directory_uri().'/lib/js/greensock/TweenMax.min.js');
        wp_enqueue_script('timelinelite',get_stylesheet_directory_uri().'/lib/js/greensock/TimelineLite.min.js');
        wp_enqueue_script('greensock-easepack',get_stylesheet_directory_uri().'/lib/js/greensock/easing/EasePack.min.js');
        wp_enqueue_script('greensock-css',get_stylesheet_directory_uri().'/lib/js/greensock/plugins/CSSPlugin.min.js');
        wp_enqueue_script('tweenmax-jquery',get_stylesheet_directory_uri().'/lib/js/greensock/jquery.gsap.min.js',array('jquery','tweenmax'));
        
        wp_enqueue_script('scroll-magic',get_stylesheet_directory_uri().'/lib/js/jquery.scrollmagic.min.js',array('jquery','tweenmax'));
        wp_enqueue_script('scroll-magic-debug',get_stylesheet_directory_uri().'/lib/js/jquery.scrollmagic.debug.js',array('jquery','tweenmax','scroll-magic'));
        if($is_IE){
            wp_enqueue_script('columnizr',get_stylesheet_directory_uri().'/lib/js/jquery.columnizer.js',array('jquery'));
            wp_enqueue_script('background-size',get_stylesheet_directory_uri().'/lib/js/jquery.backgroundSize.js',array('jquery'));
            wp_enqueue_script('shim','https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',array('jquery'));
            wp_enqueue_script('media-queries','https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js',array('jquery'));
            wp_enqueue_script('ie-fixes',get_stylesheet_directory_uri().'/lib/js/ie-jquery.js',array('jquery'));
        }
        if(is_front_page()){
            wp_enqueue_script('msd-homepage-jquery',get_stylesheet_directory_uri().'/lib/js/homepage-jquery.js',array('jquery','bootstrap-jquery'));
        } else {
            wp_enqueue_script('msd-page-jquery',get_stylesheet_directory_uri().'/lib/js/page-jquery.js',array('jquery','bootstrap-jquery','scroll-magic'));
        }
    }
}