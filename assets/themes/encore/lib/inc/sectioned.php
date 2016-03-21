<?php
if(class_exists('MSDSectionedPage')){
    class D2LSectionedPage extends MSDSectionedPage{
    function default_output($section,$i){
        //ts_data($section);
        global $parallax_ids;
        $eo = ($i+1)%2==0?'even':'odd';
        $title = apply_filters('the_title',$section['content-area-title']);
        $section_name = $section['section-name']!=''?$section['section-name']:$title;
        $slug = sanitize_title_with_dashes(str_replace('/', '-', $section_name));
        $background = '';
        if($section['background-color'] || $section['background-image']){
            if($section['background-color'] && $section['background-image']){
               $background = 'style="background-image: url('.$section['background-image'].');background-color: '.$section['background-color'].';"';
            } elseif($section['background-image']){
               $background = 'style="background-image: url('.$section['background-image'].');"';
            } else{
               $background = 'style="background-color: '.$section['background-color'].';"';
            }
            if($section['background-image'] && $section['background-image-parallax']){
                $parallax_ids[] = $slug;
            }
        }
        $wrapped_title = trim($title) != ''?'<div class="section-title">
            <h3 class="wrap">
                '.$title.'
            </h3>
        </div>':'';
        $subtitle = $section['content-area-subtitle'] !=''?'<h4 class="section-subtitle">'.$section['content-area-subtitle'].'</h4>':'';
        $content = apply_filters('the_content',$section['content-area-content']);
        $float = $section['feature-image-float']!='none'?' class="align'.$section['feature-image-float'].'"':'';
        $featured_image = $section['content-area-image'] !=''?'<img src="'.$section['content-area-image'].'"'.$float.' />':'';
        $classes = array(
            'section',
            'section-'.$slug,
            $section['css-classes'],
            'section-'.$eo,
            'clearfix',
        );
        //think about filtering the classes here
        if(stripos($section['css-classes'],'expandable')!==false){
        $ret = '
        <div id="'.$slug.'" class="'.implode(' ', $classes).'"'.$background.'>
            <div class="section-body">
                <div class="bkg-white wrap">
                    '.$featured_image.'
                    '.$wrapped_title.'
                    '.$subtitle.'
                    <div class="content entry-content">
                    '.$content.'
                    </div>
                </div>
                <div class="wrap more-wrap">
                    <div class="expand">MORE <i class="fa fa-angle-down"></i></div>
                </div>
            </div>
        </div>
        ';        } else {
        $ret = '
        <div id="'.$slug.'" class="'.implode(' ', $classes).'"'.$background.'>
        
                '.$wrapped_title.'
            <div class="section-body">
                <div class="wrap">
                    '.$featured_image.'
                    '.$subtitle.'
                    '.$content.'
                </div>
            </div>
        </div>
        ';
        }
        return $ret;
    }

    function sectioned_page_output(){
        wp_enqueue_script('sticky',WP_PLUGIN_URL.'/'.plugin_dir_path('msd-specialty-pages/msd-specialty-pages.php'). '/lib/js/jquery.sticky.js',array('jquery'),FALSE,TRUE);
        
        global $post,$subtitle_metabox,$sectioned_page_metabox,$nav_ids;
        $i = 0;
        $meta = $sectioned_page_metabox->the_meta();
        if(is_object($sectioned_page_metabox)){
        while($sectioned_page_metabox->have_fields('sections')){
            $layout = $sectioned_page_metabox->get_the_value('layout');
            switch($layout){
                case "three-boxes":
                    break;
                default:
                    $sections[] = self::default_output($meta['sections'][$i],$i);
                    break;
            }
            $i++;
        }//close while
        print '<div class="sectioned-page-wrapper">';
        print implode("\n",$sections);
        print '</div>';
        }//clsoe if
    }
  }
}