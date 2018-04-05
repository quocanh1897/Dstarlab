<?php 
/*
Plugin Name:Category Post list Widget
Author:Stark Digital
Author URI:http://www.starkdigital.net/
Description:This plugin is used to show post under particular category.
Version: 2.0
Author URI:http://www.starkdigital.net/
*/

require_once( dirname(__FILE__)."/cplw_ajax_functions.php" );
require_once( dirname(__FILE__)."/admin-settings.php" );
add_action('wp_ajax_show_CPLW_diaglogbox', '_fnCPLWShowDiaglogContent'); //dialog box contnt

/**
 * cplw_admin_menu() function add admin menu.
 *
 */

function cplw_admin_menu() {
	add_menu_page('Category Post list Widget', 'Category Post list Widget', 'manage_options', 'cplw-settings', 'get_cplw_settings');
}
add_action('admin_menu', 'cplw_admin_menu');

/**
 * cplw_scripts_method() function includes required jquery files.
 *
 */
function cplw_scripts_method() {
	wp_enqueue_script( 'jquery' );
	//wp_enqueue_script('cycle_js', plugins_url('/js/jquery.cycle.all.js', __FILE__));
	wp_enqueue_script('slick_js', plugins_url('/js/slick.min.js', __FILE__));
}

/** Tell WordPress to run cplw_scripts_method() when the 'wp_enqueue_scripts' hook is run. */
add_action('wp_enqueue_scripts', 'cplw_scripts_method'); 

/**
 * cplw_stylesheet() function includes required css files.
 *
 */
function cplw_stylesheet() {
    wp_register_style( 'main-style', plugins_url('/css/main.css', __FILE__) );
    wp_enqueue_style( 'main-style' );
    wp_register_style( 'slick-style', plugins_url('/css/slick.css', __FILE__) );
    wp_enqueue_style( 'slick-style' );
    wp_register_style( 'cplw-custom-style', plugins_url('/css/cplw-custom-style.css', __FILE__) );
    wp_register_style( 'slick-theme', plugins_url('/css/slick-theme.css', __FILE__) );
    wp_enqueue_style( 'slick-theme' );
    $strCss = get_option( 'custom_css');
    if(!empty($strCss))
    {
    	wp_enqueue_style( 'cplw-custom-style' );
    }
    
}

/** Tell WordPress to run cplw_scripts_method() when the 'cplw_stylesheet' hook is run. */
add_action( 'wp_enqueue_scripts', 'cplw_stylesheet' ); 


/**
 * cplw_required_css() function includes required css files for admin side.
 *
 */
add_action( 'admin_head', 'cplw_required_css' );

function cplw_required_css() {
    wp_register_style( 'cplw_css', plugins_url('/css/basic.css', __FILE__) );
    wp_enqueue_style( 'cplw_css' );
}

include_once("functions.php"); 
include_once("shortcode.php");  
class Category_Post_List_widget extends WP_Widget 
{
	function Category_Post_List_widget() {
		$control_ops = array( 'width' => 550, 'height' => 350,);
		parent::__construct(false,$name="Category Post List",array('description'=>'Display post under particular category'), $control_ops);
	}
	
	/**
	 * Displays category posts widget on blog.
	 *
	 * @param array $instance current settings of widget .
	 * @param array $args of widget area
	 */
	function widget($args,$instance) {
		//echo "<pre>"; print_r($instance); echo "</pre>";
		global $post;
		$post_old = $post; // Save the post object.
		extract($args);
		//print_r($instance);
		// If not title, use the name of the category.
		if( !$instance["widget_title"] ) 
		{
			$category_info = get_category($instance["cat"]);			
			if( $category_info)
			$instance["widget_title"] = $category_info->name;
  		}
		
		// Post type
		if( isset($instance['post_type']) && $instance['post_type'] != '' ) 
		{
			$post_type = $instance["post_type"];
		}

		// Taxonomy
		if( isset($instance['cat']) && $instance['cat'] != '' ) 
		{
            $taxonomy = $instance["cat"];
		}

		// Taxonomy term
		if(isset($instance['cat_term']) && $instance['cat_term'] != '' )
		{
			$taxonomy_term = $instance["cat_term"];
			if( $taxonomy_term[0] == '')
				$taxonomy_term = '';
		}
		
		// Posts per page
		$posts_per_page	= $instance["num"];		

		// sort by
		$valid_order_by = array('date', 'title', 'comment_count', 'rand');
		if ( in_array($instance['order_by'], $valid_order_by) ) 
		{
		    $order_by = $instance['order_by'];

		    $sort_order = isset($instance['sort_order']) ? $instance['sort_order'] : 'DESC'; 
		} 
		else 
		{
		    // by default, display latest first
		    $order_by = 'date';
		    $sort_order = 'DESC';
		}

		// Get effect for front end
		// slider effect
		//$dots 			= $instance['slider_dots'];
		$fade 			= ( isset( $instance['slider_fade'] ) ) ? $instance['slider_fade'] : '';
		$infinite 		= ( isset( $instance['slider_infinite'] ) ) ? $instance['slider_infinite'] : '';
		$autoplay 		= ( isset( $instance['slider_autoplay'] ) ) ? $instance['slider_autoplay'] : '';
		$arrows    		= ( isset( $instance['slider_arrows'] )) ? $instance['slider_arrows'] : '' ;
		$pauseOnHover   = ( isset( $instance['slider_pauseonhover'] ) ) ? $instance['slider_pauseonhover'] : '';
		//$vertical       = ( isset( $instance['slider_vertical'] ) ) ? $instance['slider_vertical'] : '';
		$draggable      = ( isset( $instance['slider_draggable'] ) ) ? $instance['slider_draggable'] : '';
		$slider_adaptiveheight      = ( isset( $instance['slider_adaptiveheight'] ) ) ? $instance['slider_adaptiveheight'] : '';
		$speed 			= $instance['slider_speed'];
		$slider_autoplay_speed	= $instance['slider_autoplay_speed'];
		// scrollbar effect
		$effects 		= $instance['effects'];

		$array_query = array();
		// build query
		$array_query = array(
			'post_type'			=> $post_type,
			'posts_per_page'	=> $posts_per_page,
			'orderby'			=> $order_by,
			'order'				=> $sort_order
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
            $aTaxQuery['relation']  = 'AND';
            $array_query['tax_query'] = $aTaxQuery; 
    	 }

		// Get  post info.
		$cat_posts = new WP_Query($array_query);		
		// Excerpt length 
		$new_excerpt_length = create_function('$length', "return " . $instance["excerpt_length"] . ";");
		if ( $instance["excerpt_length"] > 0 )
			add_filter('excerpt_length', $new_excerpt_length);
		$arrExlpodeFields = explode(',',$instance['display']);
		echo $before_widget; 
		// Widget title
		echo $before_title;		
		echo $instance["widget_title"];
		echo $after_title;

		$i = 0;
		global $wp_query;
		$total_posts = $wp_query->found_posts;
		$uniq = strtotime(date('D-m-Y')).'_'.rand(1,9999);
		// Post list
		?>
		<script type="text/javascript">
	        jQuery(document).ready(function($) {
        		var effect = '<?php echo $effects; ?>';
                if( effect == 'Slider' ) 
                {
                	$(".news_scroll_<?php echo $uniq; ?>").slick({
                		fade: <?php echo ( !empty($fade) ) ? $fade : 'false' ; ?>,
                		infinite: <?php echo ( !empty($infinite) ) ? $infinite : 'false' ; ?>,
                		autoplay : <?php echo ( !empty($autoplay)) ? $autoplay : 'false' ; ?>,
                		autoplaySpeed : <?php echo ( !empty( $slider_autoplay_speed )) ? $slider_autoplay_speed : 3000; ?>,
                		speed: <?php echo ( !empty( $speed )) ? $speed : 2000; ?>,
                		cssEase: 'linear',
                		slidesToShow: 1,
                		arrows : <?php echo ( !empty( $arrows ) ) ? $arrows : 'false' ; ?>,
                		draggable : <?php echo ( !empty( $draggable ) ) ? $draggable : 'false'; ?>,
                		pauseOnHover : <?php echo ( !empty( $pauseOnHover ) ) ? $pauseOnHover : 'false'; ?>,
                		adaptiveHeight: <?php echo ( !empty( $slider_adaptiveheight ) ) ? $slider_adaptiveheight : 'false'; ?>,
                		//vertical : <?php echo ( !empty( $vertical ) ) ? $vertical : 'false'; ?>,
                	});
                }
	        });
	    </script>
	    <div class="content_widget">
		   	<div class="post_content">
				<div class="ovflhidden news_scroll news_scroll_<?php echo $uniq; ?>">				
					<?php while ( $cat_posts->have_posts() )
						{
							$cat_posts->the_post(); ?>						
			            		<div class="fl newsdesc">
			            			<h2><a class="post-title" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent link to <?php the_title_attribute(); ?>"><?php echo  get_the_title(); ?></a></h2>

									<?php
									if (
											function_exists('the_post_thumbnail') &&
											current_theme_supports("post-thumbnails") &&
											in_array("thumb",$arrExlpodeFields) &&
											has_post_thumbnail()
										) :
									?>
									<div class="post_thumbnail">
									<?php 	$instance['thumb_w'] = 300;
											$instance['thumb_h'] = 300; ?>
										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
											<?php the_post_thumbnail( 'thumb', array($instance["thumb_w"],$instance["thumb_h"] )); ?>
										</a>
									</div>
									<?php 	
									endif; 
									?>
									<div class="dateAuth">
										<?php 
										if ( in_array("date",$arrExlpodeFields) ) : ?>
											<p class="post_date" ><?php the_time($instance['date_format']);
												if ( in_array("comment_num",$arrExlpodeFields) ) echo " | "; ?>
											</p>
										<?php
										endif;
										if ( in_array("comment_num",$arrExlpodeFields) ) : ?>
											<p class="comment-num"><a href="<?php comments_link(); ?>"><img src="<?php echo plugin_dir_url(__FILE__) ?>/images/icon-comment.png"><?php comments_number( '0', '1', '%' ); ?></a></p>
										<?php 
										 
										endif; 

										if ( in_array("author",$arrExlpodeFields) ) : ?>
											<p class="post_author" ><?php  echo "by " ;?><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></p>
											<?php 
										endif;
										?>
									</div>
									<div class="postExert">
										<?php 
										if ( in_array("excerpt",$arrExlpodeFields) ) :
											the_excerpt(); 
										endif; 
										
										?>
									</div>
										<?php 
									?>
								</div>												
							<?php	
						} 
						?>
				</div>
			</div>
			<div class="view_all_link">
				<?php
				if(!empty($instance['view_all_link']))
				{
					if( !empty( $instance['view_all_link_text'])) {
						echo '<a href="' . $instance['view_all_link_text'] . '">View all</a>';
					} else if($instance['cat'] != 0)
					{
						echo '<a href="' . get_category_link($instance["cat"]) . '">View all</a>';
					}
					else
					{
						if( get_option( 'show_on_front' ) == 'page' )							
							echo '<a href="' .get_permalink( get_option('page_for_posts' ) ). '">View all</a>' ;
						else 							
							echo '<a href="'.get_bloginfo('url'). '">View all</a>' ;
					}
				}
				?>
			</div>
		</div>
		<?php echo $after_widget; 
		remove_filter('excerpt_length', $new_excerpt_length);
		$post = $post_old; // Restore the post object.
	}
	function default_settings() {
		return array(
					'post_type' => 'post',
					'cat' => 'category',
					'num' => -1,
					'order_by' => 'date',
					'sort_order' => 'desc',
					'excerpt_length' => 10,
					'date_format' => 'F j, Y',
					'effects' => 'none',
				);
	}
	/**
	 * Form processing...
	 *
	 * @param array $new_instance of widget .
	 * @param array $old_instance of widget .
	 */
	
	function update($new_instance,$old_instance) 
	{
		global $wpdb;		
		$displayFields = array();
		if($_POST['display']){
			array_push($_POST['display'], 'title');
			$displayFields = array_unique($_POST['display']);
		}
		else
		{
			$displayFields = array('title');
		}
		$strImplodeFields = implode(',',$displayFields);
		$new_instance['display'] = $strImplodeFields;

		return $new_instance;
	}

	/**
	 * The configuration form.
	 *
	 * @param array $instance of widget to display already stored value .
	 * 
	 */
	function form($instance) 
	{ 	
		if( empty( $instance ) || count( $instance ) == 0 ) {
			$instance = $this->default_settings();
		}
		//echo "<pre>"; print_r( $instance ); echo "</pre>";
		$displayFields 				= array();		
		$displayFields				= isset ($instance['display'] ) ? $instance['display'] : 'title';
		$display 					= isset($instance['display']) ? $instance['display'] : array();
		$arrExlpodeFields 			= explode(',', $displayFields);
		$instance["excerpt_length"] = isset( $instance["excerpt_length"] ) ? $instance["excerpt_length"] : '10';
		$instance["date_format"] 	= isset( $instance["date_format"] ) ? $instance["date_format"] : 'F j, Y';
		$instance["slider_fade"] 	= isset($instance["slider_fade"]) ? $instance["slider_fade"] : '0';
		$instance["slider_arrows"] 	= isset($instance["slider_arrows"]) ? $instance["slider_arrows"] : '0';
		$instance["slider_autoplay"] = isset($instance["slider_autoplay"]) ? $instance["slider_autoplay"] : '0';
		$instance["slider_infinite"] = isset($instance["slider_infinite"]) ? $instance["slider_infinite"] : '0';
		$instance["slider_draggable"] = isset($instance["slider_draggable"]) ? $instance["slider_draggable"] : '0';

		$instance["slider_pauseonhover"] = isset($instance["slider_pauseonhover"]) ? $instance["slider_pauseonhover"] : '0';

		$instance["slider_adaptiveheight"] = isset($instance["slider_adaptiveheight"]) ? $instance["slider_adaptiveheight"] : '0';
		$instance["sort_order"] 	=  isset( $instance["sort_order"] ) ? $instance["sort_order"] : 'desc';
		$instance["view_all_link"] 	= isset( $instance["view_all_link"] ) ? $instance["view_all_link"] : '';
		$instance["num"] 			=  isset( $instance["num"] ) ? $instance["num"] : '-1';
		$instance["post_type"] 		= isset( $instance["post_type"] ) ? $instance["post_type"] : 'post';
		$instance["cat"] 			=  isset( $instance["cat"] ) ? $instance["cat"] : '';
		$instance["cat_term"] 		= isset( $instance["cat_term"] ) ? $instance["cat_term"] : '';
		?>
		<script>			
			var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";

			function getTaxonomy(val)
			{
				var post_type = val.value;
		            var data = {
		                'action': 'get_taxonomy_list',
		                'post_type': post_type,
		            };
		            //clear options
		            jQuery('p.p-taxonomy').show();
		            jQuery('p.p-taxonomy-term').show();
				    jQuery('#<?php echo $this->get_field_id('cat'); ?>').empty();
				    jQuery('#<?php echo $this->get_field_id('cat_term'); ?>').empty();
				    //reset the initial option
				    jQuery('#<?php echo $this->get_field_id('cat'); ?>').append('<option value="">Select Taxonomy</option>'); 
		            jQuery.post(ajaxurl, data, function(response) {
		                if(response)
		                {
			                jQuery('select#<?php echo $this->get_field_id('cat'); ?>').append(response);
			            }
			            else
			            {
			            	jQuery('p.p-taxonomy').hide();
			            	jQuery('p.p-taxonomy-term').hide();
			            }
		                return false;
		            });
			}

			function getTaxonomyTerm(val)
			{
				var taxonomy = val.value;

		            var data = {
		                'action': 'get_taxonomy_terms_list',
		                'taxonomy': taxonomy,
		            };
		            //clear options
				    jQuery('#<?php echo $this->get_field_id('cat_term'); ?>').empty();
				    //reset the initial option
				    jQuery('#<?php echo $this->get_field_id('cat_term'); ?>').append('<option value="">All</option>'); 
		            jQuery.post(ajaxurl, data, function(response) {
		                if(response)
		                {
			                jQuery('select#<?php echo $this->get_field_id('cat_term'); ?>').append(response);
			            }
		                return false;
		            });
			}
		</script>		
		<p>
			<label for="<?php echo $this->get_field_id("widget_title"); ?>">
				<?php _e( 'Title' ); ?>:
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id("widget_title"); ?>" name="<?php echo $this->get_field_name("widget_title"); ?>" type="text" value="<?php echo ( isset( $instance["widget_title"] ) )? esc_attr($instance["widget_title"]) : ''; ?>" />
		</p>
		<div class="post-widget-column post-left-box">
			<div class="post-widget-column-box post-widget-column-box-top">
				<p>
					<label for="<?php echo $this->get_field_id("post_type"); ?>">
						<?php _e( 'Post Type' ); ?>:
					</label>
					<select id="<?php echo $this->get_field_id("post_type"); ?>" class="widefat" name="<?php echo $this->get_field_name("post_type"); ?>" onchange="getTaxonomy(this);">
						<?php
							$args = array( 'public' => true);
							$post_types = get_post_types($args);
							$exclude = array( 'attachment');
							foreach ( $post_types as $type ) 
							{ 
								if ( in_array( $type, $exclude ) ) 
								{
							      unset( $post_types[$type] );
							    }
							}
							// Post types to exclude
							foreach ( $post_types as $type ) { 
						?>
								<option value="<?php echo $type; ?>" <?php selected( $instance["post_type"], $type ); ?>><?php echo ucwords(str_replace('_', ' ', $type)); ?></option>
						<?php } ?>
					</select>
				</p>
				<p class="p-taxonomy">
					<label>
						<?php _e( 'Taxonomy' ); ?>:
					</label>
					<select id="<?php echo $this->get_field_id("cat"); ?>" class="widefat" name="<?php echo $this->get_field_name("cat"); ?>" onchange="getTaxonomyTerm(this)">
						<?php $taxonomies = get_object_taxonomies( $instance["post_type"] );
							foreach($taxonomies as $taxonomy => $value){ ?>
		     					<option value="<?php echo $value; ?>" <?php selected( $instance["cat"], $value ); ?>><?php echo ucwords(str_replace('_', ' ', $value)); ?></option>
		     				<?php } ?>
					</select>
				</p>
				<p class="p-taxonomy-term">
					<label>
						<?php _e( 'Taxomony Term' ); ?>:
					</label>
					<select id="<?php echo $this->get_field_id("cat_term"); ?>" class="widefat" name="<?php echo $this->get_field_name("cat_term"); ?>[]" multiple>
						<option value="" 
						<?php echo ( is_array( $instance["cat_term"] ) && in_array( $term->slug, $instance["cat_term"] ) ) ? 'selected="selected"' : ''; ?>>All</option>
						<?php $term_args = array('hide_empty' => false,'orderby' => 'name','order' => 'ASC');
							  if($instance["cat"] != ''){
								  	$terms = get_terms($instance["cat"], $term_args);
			    				  	foreach($terms as $term){
			    				  	 ?>
			     						<option value="<?php echo $term->slug; ?>" <?php echo
			     							( is_array( $instance["cat_term"] ) && in_array( $term->slug, $instance["cat_term"] ) ) ? 'selected="selected"' : '';
			     							//selected( $instance["cat_term"], $term->slug ); ?>><?php echo ucwords(str_replace('_', ' ', $term->name)); ?></option>
			   				<?php 	} 
			   					}  ?>
					</select>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id("num"); ?>">
						<?php _e('Number of posts to show'); ?>:
					</label>
					<input class="digits" id="<?php echo $this->get_field_id("num"); ?>" name="<?php echo $this->get_field_name("num"); ?>" type="text" value="<?php echo $instance["num"]; ?>" size='4' maxlength="5"/>
					<br/>(-1 for all posts)  			
		    	</p>
			</div>
			<div class="post-widget-column-box">
				<p>
					<label for="<?php echo $this->get_field_id("display"); ?>">
		        		<?php _e('Select display option'); ?>:
		        	</label>		
				</p>
				<p>
					<?php
						$arrDisplay = array("title","excerpt","comment_num","date","thumb","author"); 
						$arrDisplayLabels = array("title" => "Post Title" , "excerpt" => "Short Description" ,"comment_num" => "Comment Count" ,"date" => "Post Date" ,"thumb" => "Post Thumbnail" ,"author" => "Post Author" );
						$i=1;
						foreach($arrDisplay as $strValue)
						{
					?>	
			        		<p><input id="<?php echo $this->get_field_id( 'display' ).$i; ?>" type="checkbox" name="display[]" value="<?php echo $strValue; ?>" <?php echo (in_array($strValue,$arrExlpodeFields) || $strValue == 'title') ? "checked=checked" : ''; ?>/> <label for="<?php echo $this->get_field_id( 'display' ).$i; ?>"><?php _e( $arrDisplayLabels[$strValue] ); ?></label></p>
			        <?php 
			        $i++;
			        } ?>
				</p>
			</div>
			<div class="post-widget-column-box post-widget-column-box-top">
				<!-- <p>
					<input type="checkbox" value="1" name="<?php echo $this->get_field_name("show_date"); ?>" id="<?php echo $this->get_field_id("date_format"); ?>" <?php echo ($instance["show_date"] == 1) ? 'checked' : ''; ?>> 
					<label for="widget-category_post_list_widget-2-date">Display Post Date</label>
				</p> -->
				<p>
					<label for="<?php echo $this->get_field_id("date_format"); ?>">
						<?php _e( 'Date Format :' ); ?>
					</label>
					<input class="" type="text" id="<?php echo $this->get_field_id("date_format"); ?>" name="<?php echo $this->get_field_name("date_format"); ?>" value="<?php echo $instance["date_format"]; ?>"  size='20' maxlength="20"/>
					<br/><?php echo date("F j, Y");?> (F j, Y)
				</p>
				<p>
					<a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Documentation on date and time formatting</a>
				</p>
			</div>
		</div>
		
		<div class="post-widget-column post-widget-column-right">
			<div class="post-widget-column-box">   	
				<p>
					<label for="<?php echo $this->get_field_id("order_by"); ?>">
		        		<?php _e('Order by'); ?>:
		        	</label>
			        <select id="<?php echo $this->get_field_id("order_by"); ?>" class="widefat" name="<?php echo $this->get_field_name("order_by"); ?>">
				        <option value="date"<?php selected( isset( $instance["order_by"] ) ? $instance["order_by"] : '', "date" ); ?>>Date</option>
				        <option value="title"<?php selected( isset( $instance["order_by"] ) ? $instance["order_by"] : '', "title" ); ?>>Title</option>
				        <option value="comment_count"<?php selected( isset( $instance["order_by"] ) ? $instance["order_by"] : '', "comment_count" ); ?>>Number of comments</option>
				        <option value="rand"<?php selected( isset( $instance["order_by"] ) ? $instance["order_by"] : '', "rand" ); ?>>Random</option>
			        </select>			
		    	</p>
		    	<p>
		            <label for="<?php echo $this->get_field_id("sort_order"); ?>" >
		                <?php _e('Sort order'); ?>:
		            </label>
		            <select id="<?php echo $this->get_field_id("sort_order"); ?>" class="widefat" name="<?php echo $this->get_field_name("sort_order"); ?>">
		                <option value="desc" <?php selected( $instance["sort_order"], "desc" ); ?>>DESC</option>
		                <option value="asc" <?php selected( $instance["sort_order"], "asc" ); ?>>ASC</option>                   
		            </select>           
		        </p>
		        <p>
					<label for="<?php echo $this->get_field_id("excerpt_length"); ?>">
						<?php _e( 'Excerpt length (in words):' ); ?>
					</label>
					<input class="digits" type="text" id="<?php echo $this->get_field_id("excerpt_length"); ?>" name="<?php echo $this->get_field_name("excerpt_length"); ?>" value="<?php echo $instance["excerpt_length"]; ?>" size="5" maxlength="4"/>
				</p>
			</div>
			<script type="text/javascript">
		        jQuery(document).ready(function($) {
	        		$(".widget-effect").change(function () {
        		        var current_option = this.value;
        		        if( current_option == "Slider"){
        		        	$(".slider-option").show();
        		        	$(".scrollbar-option").hide();	
        		        } else {
        		        	$(".slider-option").hide();
        		        	$(".scrollbar-option").hide();	
        		        }
        		        //var firstDropVal = $('#pick').val();
        		    });
		        });
		    </script>
			<div class="post-widget-column-box post-widget-column-box-top">
				<p>
					<label for="<?php echo $this->get_field_id("effects"); ?>">
		        		<?php _e('Effects'); ?>:
		        	</label>
		        	<select id="<?php echo $this->get_field_id("effects"); ?>" name="<?php echo $this->get_field_name("effects"); ?>" class="widefat effect widget-effect">
						<?php							
							$arrEffect = array("none", "Slider"); 
							foreach($arrEffect as $strKey => $strValue)
							{?>
								<option value="<?php echo $strValue; ?>" <?php selected( isset( $instance["effects"] ) ? $instance["effects"] : '', "$strValue" ); ?>><?php echo ucfirst($strValue); ?></option>
						<?php } ?>
					</select>	        
		    	</p>
		    	<style type="text/css">
		    		.slider-option .dimension-inner input[type='checkbox']{
		    			min-width: 20px;
		    		}
		    	</style>
		    	<div class="slider-option" style="display : <?php echo ($instance['effects'] == 'Slider') ? 'block' : 'none'; ?>">
		    		<p>
			    		<div class="dimension">
			    			<div class="dimension-inner" >
			    				<input type="checkbox" value="true" name="<?php echo $this->get_field_name("slider_fade"); ?>" id="<?php echo $this->get_field_id("slider_fade"); ?>" <?php echo ($instance["slider_fade"] == 'true') ? 'checked' : ''; ?>> 
					    		<label for="<?php echo $this->get_field_id("slider_fade"); ?>">Fade</label>
				    		</div>
			    			<div class="dimension-inner">
				    			<input type="checkbox" value="true" name="<?php echo $this->get_field_name("slider_arrows"); ?>" id="<?php echo $this->get_field_id("slider_arrows"); ?>" <?php echo ($instance["slider_arrows"] == 'true') ? 'checked' : ''; ?>> 
				    			<label for="<?php echo $this->get_field_name("slider_arrows"); ?>">Arrows</label>
				    		</div>
	    			    </div>
	    			</p>
    			    <p>
			    		<div class="dimension">
			    			<div class="dimension-inner">
				    			<input type="checkbox" value="true" name="<?php echo $this->get_field_name("slider_autoplay"); ?>" id="<?php echo $this->get_field_id("slider_autoplay"); ?>" <?php echo ($instance["slider_autoplay"] == 'true') ? 'checked' : ''; ?>> 
				    			<label for="<?php echo $this->get_field_id("slider_autoplay"); ?>">Autoplay</label>
				    		</div>
    		    			<div class="dimension-inner">
    			    			<input type="checkbox" value="true" name="<?php echo $this->get_field_name("slider_infinite"); ?>" id="<?php echo $this->get_field_id("slider_infinite"); ?>" <?php echo ($instance["slider_infinite"] == 'true') ? 'checked' : ''; ?>> 
    			    			<label for="<?php echo $this->get_field_id("slider_infinite"); ?>">Infinte</label>
    			    		</div>
			    		</div>
			    	</p>
		    		<p>
		    			<div class="dimension">
		    				<div class="dimension-inner">
		    					<input type="checkbox" value="true" name="<?php echo $this->get_field_name("slider_draggable"); ?>" id="<?php echo $this->get_field_id("slider_draggable"); ?>" <?php echo ($instance["slider_draggable"] == 'true') ? 'checked' : ''; ?>> 
		    					<label for="<?php echo $this->get_field_id("slider_draggable"); ?>">Draggable</label>	
		    				</div>
		    				<div class="dimension-inner">
				    			<input type="checkbox" value="true" name="<?php echo $this->get_field_name("slider_pauseonhover"); ?>" id="<?php echo $this->get_field_id("slider_pauseonhover"); ?>" <?php if( $instance['slider_pauseonhover'] == 'true' || $instance['slider_pauseonhover'] == true) echo "checked"; ?>
				    			> 
				    			<label for="<?php echo $this->get_field_id("slider_pauseonhover"); ?>">Pause On Hover</label>
				    		</div>
		    				<?php /*
		    				<div class="dimension-inner">
				    			<input type="checkbox" value="true" name="<?php echo $this->get_field_name("slider_vertical"); ?>" id="<?php echo $this->get_field_id("slider_vertical"); ?>" <?php echo ( isset( $instance["slider_vertical"] ) && $instance["slider_vertical"] == 'true') ? 'checked' : ''; ?>> 
				    			<label for="<?php echo $this->get_field_name("slider_vertical"); ?>">Vertical</label>
				    		</div>
				    		*/ ?>
				    	</div>
		    		</p>
		    		<p>
		    			<div class="dimension">
		    				<div class="dimension-inner">
				    			<input type="checkbox" value="true" name="<?php echo $this->get_field_name("slider_adaptiveheight"); ?>" id="<?php echo $this->get_field_id("slider_adaptiveheight"); ?>" <?php if( $instance['slider_adaptiveheight'] == 'true' || $instance['slider_adaptiveheight'] == true) echo "checked"; ?>
				    			> 
				    			<label for="<?php echo $this->get_field_id("slider_adaptiveheight"); ?>">Adaptive Height</label>
				    		</div>
		    			</div>
		    		</p>
		    		<div class="dimension">
						<div class="dimension-inner">
							<label for="<?php echo $this->get_field_id("slider_speed"); ?>">Speed: </label>
						</div>
						<div class="dimension-inner">
							<input type="text" maxlength="4" size="5" value="<?php echo (isset( $instance['slider_speed'] ) ) ? esc_attr( $instance['slider_speed'] ) : ''; ?>" name="<?php echo $this->get_field_name("slider_speed"); ?>" id="<?php echo $this->get_field_id("slider_speed"); ?>" class="widefat widget_dimension digits">
						</div>
					</div>
					<p>
			    		<div class="dimension">
							<div class="dimension-inner">
								<label for="<?php echo $this->get_field_id("slider_autoplay_speed"); ?>">Autoplay Speed: </label>
							</div>
							<div class="dimension-inner">
								<input type="text" maxlength="4" size="5" value="<?php echo isset( $instance['slider_autoplay_speed'] ) ? $instance['slider_autoplay_speed'] : ''; ?>" name="<?php echo $this->get_field_name("slider_autoplay_speed"); ?>" id="<?php echo $this->get_field_id("slider_autoplay_speed"); ?>" class="widefat widget_dimension digits">
							</div>
						</div>
					</p>
		    	</div>
				<p>
					<label for="<?php echo $this->get_field_id("view_all_link"); ?>"><?php _e('Show view all link'); ?>: </label>			
					<input type="checkbox" name="<?php echo $this->get_field_name("view_all_link"); ?>" value="1" class="link" id="<?php echo $this->get_field_id("view_all_link"); ?>" <?php echo ($instance["view_all_link"] == 1) ? 'checked' : ''; ?>> 
				</p>
				<p>
					<label for="<?php echo $this->get_field_id("view_all_link_text"); ?>"><?php _e('View all link'); ?>: </label>			
					<input type="text" name="<?php echo $this->get_field_name("view_all_link_text"); ?>" value="<?php echo isset( $instance['view_all_link_text'] ) ? esc_attr( $instance['view_all_link_text'] ) : ''; ?>" class="link-text" id="<?php echo $this->get_field_id("view_all_link_text"); ?>"> 
				</p>
			</div>
		</div>
		
		<?php 
	}
} 
add_action('widgets_init', create_function('', 'return register_widget("Category_Post_List_widget");'));

// Below code is to display tinymce button on page.
add_action( 'admin_init', 'cplw_addTinyMCEButtons' );
function cplw_addTinyMCEButtons() {
    add_filter("mce_external_plugins", "cplw_add_TMCEbutton");
    add_filter('mce_buttons', 'cplw_register_TMCEbutton');
} 

function cplw_register_TMCEbutton($buttons) {
    array_push( $buttons, "separator", 'CPLWPosts' ); 
    return $buttons;
}  
function cplw_add_TMCEbutton($plugin_array) {
    $plugin_array['cplwPosts'] = plugin_dir_url(__FILE__). '/js/tinymce_button.js';
    return $plugin_array;
}
add_shortcode( 'cplw-catlist', array( 'Category_Post_List_widget', 'cplw_catlist_function' ) );
function cplw_catlist_function( $atts, $content = NULL ) {
	$atts = shortcode_atts(array(
	                         'id' => '0',
	                       ), $atts);
}
?>