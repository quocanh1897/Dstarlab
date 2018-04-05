<?php
/**
 * Function cplw_shortcode()  is used to create shortcode for plugin.
 * @param array $atts is to pass attributes to the function. 
*/
function cplw_shortcode($atts){
	extract(shortcode_atts(array(
      'post_type'       => 'post',
      'taxonomy'        => '',
   		'taxonomy_term'   => '',
   		'width'           => '',  
      'height'          => '',      
      'effects'         => '',
      'time'            => '',
      'order'           => '',
      'order_by'        => '',
      'posts_to_show'   => '',
      'display'         => '',
      'excerpt_length'  => '',
      'thumb_height'    => '',
      'thumb_width'     => '',
      'date_format'     => '',      
      ), $atts));  
  ob_start();
  ?>

        
	<?php
    cplw_widget_shortcode_output($post_type, $taxonomy, $taxonomy_term, $height, $width, $posts_to_show, $order_by, $order, $display, $excerpt_length, $thumb_width, $thumb_height, $date_format, $effects, $time);
    $shortcodeData = ob_get_contents();	
    ob_end_clean();
    return $shortcodeData;
    }

/**
 * Function cplw_register_shortcodes()  is used to register shortcode.
*/
function cplw_register_shortcodes(){
	add_shortcode('cplw', 'cplw_shortcode');
}
add_action( 'init', 'cplw_register_shortcodes');
?>