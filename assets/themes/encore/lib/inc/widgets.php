<?php
/**
 * Connected Class
 */
if(class_exists('MSDConnected')){
class KohlerConnected extends MSDConnected {
    function widget( $args, $instance ) {
        extract($args);
        extract($instance);
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
        $text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
        echo $before_widget;
        if ( !empty( $title ) ) { print $before_title.$title.$after_title; } 
        if ( !empty( $text )){ print '<div class="connected-text">'.$text.'</div>'; }
        print '<div class="wrap">';
        if(($address||$phone||$tollfree||$fax||$email||$social)&&$form_id > 0){
            print '<div class="col-md-7">';
        }
        if ( $form_id > 0 ){
            print '<div class="connected-form">';
            print do_shortcode('[gravityform id="'.$form_id.'" title="true" description="false" ajax="true"]');
            print '</div>';
            //add_action( 'wp_footer', array(&$this,'tabindex_javascript'), 60);
        }
        if(($address||$phone||$tollfree||$fax||$email||$social)&&$form_id > 0){
            print '</div>';
        }
        if(($address||$phone||$tollfree||$fax||$email||$social)&&$form_id > 0){
            print '<div class="col-md-5 align-right">';
        }
        if ( $address ){
            print '<h3>Address</h3>';
            $bizname = do_shortcode('[msd-bizname]'); 
            if ( $bizname ){
                print '<div class="connected-bizname">'.$bizname.'</div>';
            }
            $address = do_shortcode('[msd-address]'); 
            if ( $address ){
                print '<div class="connected-address">'.$address.'</div>';
            }
        }
        if ( $phone ){
            $phone = '';
            if((get_option('msdsocial_tracking_phone')!='')){
                if(wp_is_mobile()){
                  $phone .= 'Phone: <a href="tel:+1'.get_option('msdsocial_tracking_phone').'">'.get_option('msdsocial_tracking_phone').'</a> ';
                } else {
                  $phone .= 'Phone: <span>'.get_option('msdsocial_tracking_phone').'</span> ';
                }
              $phone .= '<span itemprop="telephone" style="display: none;">'.get_option('msdsocial_phone').'</span> ';
            } else {
                if(wp_is_mobile()){
                  $phone .= (get_option('msdsocial_phone')!='')?'Phone: <a href="tel:+1'.get_option('msdsocial_phone').'" itemprop="telephone">'.get_option('msdsocial_phone').'</a> ':'';
                } else {
                  $phone .= (get_option('msdsocial_phone')!='')?'Phone: <span itemprop="telephone">'.get_option('msdsocial_phone').'</span> ':'';
                }
            }
            if ( $phone ){ print '<div class="connected-phone">'.$phone.'</div>'; }
        }
        if ( $tollfree ){
            $tollfree = '';
            if((get_option('msdsocial_tracking_tollfree')!='')){
                if(wp_is_mobile()){
                  $tollfree .= 'Toll Free: <a href="tel:+1'.get_option('msdsocial_tracking_tollfree').'">'.get_option('msdsocial_tracking_tollfree').'</a> ';
                } else {
                  $tollfree .= 'Toll Free: <span>'.get_option('msdsocial_tracking_tollfree').'</span> ';
                }
              $tollfree .= '<span itemprop="telephone" style="display: none;">'.get_option('msdsocial_tollfree').'</span> ';
            } else {
                if(wp_is_mobile()){
                  $tollfree .= (get_option('msdsocial_tollfree')!='')?'Toll Free: <a href="tel:+1'.get_option('msdsocial_tollfree').'" itemprop="telephone">'.get_option('msdsocial_tollfree').'</a> ':'';
                } else {
                  $tollfree .= (get_option('msdsocial_tollfree')!='')?'Toll Free: <span itemprop="telephone">'.get_option('msdsocial_tollfree').'</span> ':'';
                }
            }
            if ( $tollfree ){ print '<div class="connected-tollfree">'.$tollfree.'</div>'; }
        }
        if ( $fax ){
            $fax = (get_option('msdsocial_fax')!='')?'Fax: <span itemprop="faxNumber">'.get_option('msdsocial_fax').'</span> ':'';
            if ( $fax ){ print '<div class="connected-fax">'.$fax.'</div>'; }
        }
        if ( $email ){
            $email = (get_option('msdsocial_email')!='')?'Email: <span itemprop="email"><a href="mailto:'.antispambot(get_option('msdsocial_email')).'">'.antispambot(get_option('msdsocial_email')).'</a></span> ':'';
            if ( $email ){ print '<div class="connected-email">'.$email.'</div>'; }
        }
        if ( $social ){
            $social = do_shortcode('[msd-social]');
            if( $social ){ print '<div class="connected-social">'.$social.'</div>'; }
        }   
        
        if(($address||$phone||$tollfree||$fax||$email||$social)&&$form_id > 0){
            print '</div>';
        }
        print '</div>';
        
        echo $after_widget;
    }
}

add_action('widgets_init', create_function('', 'return register_widget("KohlerConnected");'));
}