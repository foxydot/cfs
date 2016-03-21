<?php
/*
Template Name: Sectioned Page
*/
if(function_exists('genesis')){
    //this is a genesis themed site\
    add_action('genesis_before_footer',array('D2LSectionedPage','sectioned_page_output'), 0);
    add_action('wp_print_footer_scripts',array('MSDSectionedPage','sectioned_page_footer_js'));
    genesis();
} else {
    //not genesis. Do things kind of the old fashioend way.
}
