<?php
/**
 * Widget template. This template can be overriden using the "sp_template_image-widget_widget.php" filter.
 * See the readme.txt file for more info.
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');
echo '<div class="image-widget-background" style="background-image: url('.$imageurl.')" id="'.$this->id.'">
    <div class="fuzzybubble">
';

if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

if ( !empty( $subtitle ) ) { $subtitle = '<span class="subtitle">'.$subtitle.'</span>'; }

echo '<div class="widget-content">';

if ( !empty( $description ) ) {
	echo '<div class="'.$this->widget_options['classname'].'-description" >';
	echo wpautop( $subtitle.$description );
	echo "</div>";
}
if ( $link ) {
	$linktext = $linktext != ''?$linktext:'Read More >';
	echo '<div class="link"><a class="'.$this->widget_options['classname'].'-link button" href="'.$link.'" target="'.$linktarget.'">'.$linktext.' ></a><div class="clear"></div></div>';
}
echo '      </div>
        <div class="clear"></div>
</div>';
echo '</div>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $("#'.$this->id.' .fuzzybubble").blurjs({
                radius: 10,
                source: ".image-widget-background",
            });
        });
    </script>
';