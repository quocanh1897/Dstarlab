<?php
/**
 * Function cplw_widget_shortcode_output() is called in file shortcode.php to display data.
 * @param $category is used to show posts from particular category.
 * @param $height is the height of posts list content area.
 * @param $width is the width of posts list content area.
 * @param $posts_per_page is number of posts to show.
 * @param $orderby is to display posts by order of title, date etc.
 * @param $order is to display posts by order. 
 * @param $excerpt_length is the length of content to display.
 * @param $thumbnail_width is the thumbnail width.
 * @param $thumbnail_height is the thumbnail height.
 * @param $date_format is the date format.
*/ 
function cplw_widget_shortcode_output($post_type, $taxonomy, $taxonomy_term, $height, $width, $posts_per_page, $orderby, $order, $display, $excerpt_length, $thumbnail_width, $thumbnail_height, $date_format,$effects,$time){     
    global $post, $wpdb;
	$uniq = strtotime(date('D-m-Y')).'_'.rand(1,9999);
  	?>
	<script type="text/javascript">  
       (function(){           
            var strEffect = '<?php echo $effects; ?>';
            if(strEffect != 'none')
            {
              jQuery('.post_scroll_<?php echo $uniq; ?>').cycle({ 
                  fx: strEffect, 
                  timeout: '<?php echo $time; ?>'                
              }); 
            }
        })(jQuery);
    </script>
  	<?php

    if(!isset($orderby) && $orderby == '')
    {
      $order_by = 'date';
    }
    else
    {
      $order_by = $orderby;
    }    
    if(!isset($order) && $order == '')
    {
      $sort_order = 'DESC';
    }
    else
    {
      $sort_order = $order;
    }

   // build query
    $array_query = array(
          'post_type'       => $post_type,
          'posts_per_page'  => $posts_per_page,
          'orderby'         => $order_by,
          'order'           => $sort_order
      );

    //build tax_query
    if( isset($taxonomy) && $taxonomy != '' && isset($taxonomy_term) && $taxonomy_term != '')
    {
      $aTaxQuery = array();
      $aTaxQuery[] = array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'slug',
                        'terms'    => $taxonomy_term,
                    );
    }
    elseif(isset($taxonomy_term) && $taxonomy_term != '')
    {
      $array_query['category_name'] = $taxonomy_term;
    }

    //Set tax_query
    if( !empty( $aTaxQuery ) )
     {
            $array_query['tax_query'] = $aTaxQuery; 
     }

    // Get  post info.
    $cat_posts = new WP_Query($array_query);

    // Excerpt length 
    $new_excerpt_length = create_function('$length', "return " . $excerpt_length . ";");
    if ( $excerpt_length > 0 )
        add_filter('excerpt_length', $new_excerpt_length);
    $arrExlpodeFields = explode(',',$display);
   
    $arrExlpodeFields = explode(',',$display);
    ?>
        <div class="post_content" style="height:<?php echo $height; ?>px ; width:<?php echo $width; ?>px;">
            <div class="ovflhidden post_scroll post_scroll_<?php echo $uniq; ?>">                
                <?php while ( $cat_posts->have_posts() )
                    {
                        $cat_posts->the_post(); ?>                      
                            <div class="fl newsdesc">                              
                                <?php
                                if (
                                        function_exists('the_post_thumbnail') &&
                                        current_theme_supports("post-thumbnails") &&
                                        in_array("thumb",$arrExlpodeFields) &&
                                        has_post_thumbnail()
                                    ) :

                                ?>
                                        <div class="post_thumbnail">
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                                <?php the_post_thumbnail( array($thumbnail_width,$thumbnail_height )); ?>
                                            </a>
                                        </div>
                                <?php   
                                endif;
                                ?>
                                <div class="post_data">
                                    <!-- Post title -->
                                    <h2><a class="post-title" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent link to <?php the_title_attribute(); ?>"><?php echo get_the_title(); ?></a></h2>
                                    <?php 
                                    if ( in_array("date",$arrExlpodeFields) ) : ?>
                                         <!-- post Date -->
                                        <p class="post_date" ><?php echo the_time($date_format); ?></p>
                                    <?php 
                                    endif; 
                                    if ( in_array("author",$arrExlpodeFields) ) : ?>
                                        <p class="post_author" ><?php  echo "by " ;?><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></p>
                                    <?php 
                                    endif; 

                                    if ( in_array("excerpt",$arrExlpodeFields) ) :
                                        the_excerpt(); 
                                    endif; 
                                    if ( in_array("comment_num",$arrExlpodeFields) ) : ?>
                                        <p class="comment-num"><a href="<?php comments_link(); ?>">(<?php comments_number(); ?>)</a></p>
                                    <?php 
                                    endif; 
                                    ?>
                                </div>
                            </div>                                              
                        <?php   
                    } 
                    wp_reset_postdata();               
                ?>
            </div>
        </div>
    <?php 
    add_action('widgets_init', create_function('', 'return register_widget("Category_Post_List_widget");'));
}

/* Get Taxonomy List */
function get_taxonomy_list(){
   $post_type_slug = $_POST['post_type'];

   $taxonomies = get_object_taxonomies( $post_type_slug );
   ob_start();

   if(!empty($taxonomies)){
     foreach($taxonomies as $taxonomy => $value){ ?>
       <option value="<?php echo $value; ?>"><?php echo ucwords(str_replace('_', ' ', $value)); ?></option>
     <?php } 
   }

   $response = ob_get_contents();
   ob_end_clean();
   echo $response;
   die(1);
}

// create custom Ajax call for WordPress
add_action( 'wp_ajax_nopriv_get_taxonomy_list', 'get_taxonomy_list' );
add_action( 'wp_ajax_get_taxonomy_list', 'get_taxonomy_list' );

/* Get Taxonomy Term List */
function get_taxonomy_terms_list(){
    $taxonomy = $_POST['taxonomy'];
    $term_args=array(
      'hide_empty' => false,
      'orderby' => 'name',
      'order' => 'ASC'
    );
    $terms = get_terms($taxonomy,$term_args);

   ob_start();
   foreach($terms as $term){ ?>
     <option value="<?php echo $term->slug; ?>"><?php echo ucwords(str_replace('_', ' ', $term->name)); ?></option>
   <?php } 
   $response = ob_get_contents();
   ob_end_clean();
   echo $response;
   die(1);
}

// create custom Ajax call for WordPress
add_action( 'wp_ajax_nopriv_get_taxonomy_terms_list', 'get_taxonomy_terms_list' );
add_action( 'wp_ajax_get_taxonomy_terms_list', 'get_taxonomy_terms_list' );