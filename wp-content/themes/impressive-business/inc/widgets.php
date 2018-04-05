<?php 
function impressive_business_register_custom_widgets() {
    register_widget( 'impressive_business_widget_recent_posts' );
}
add_action( 'widgets_init', 'impressive_business_register_custom_widgets' );
class impressive_business_widget_recent_posts extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'impressive_business_widget_recent_posts', 'description' => esc_html__( "The most recent posts on your site", 'impressive-business') );
        parent::__construct('recent-posts', esc_html__('Recent Postsa', 'impressive-business'), $widget_ops);
        $this->alt_option_name = 'widget_recent_entries';
        add_action( 'save_post', array($this, 'impressive_business_flush_widget_cache') );
        add_action( 'deleted_post', array($this, 'impressive_business_flush_widget_cache') );
        add_action( 'switch_theme', array($this, 'impressive_business_flush_widget_cache') );
    }
    function widget($args, $instance) {
        $impressive_business_cache = wp_cache_get('impressive_business_widget_recent_posts', 'widget');
        if ( !is_array($impressive_business_cache) )
            $impressive_business_cache = array();

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $impressive_business_cache[ $args['widget_id'] ] ) ) {
            echo $impressive_business_cache[ $args['widget_id'] ];
            return;
        }

        ob_start();
        extract($args);

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Posts', 'impressive-business' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 10;
        if ( ! $number )
            $number = 10;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false; ?>
        <ul class="latest-post">
    <?php    $impressive_business_r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
        if ($impressive_business_r->have_posts()) :
        echo $args['before_widget'];
        if ( $title ) echo $args['before_title'] . $title . $args['after_title']; ?>
        <?php while ( $impressive_business_r->have_posts() ) : $impressive_business_r->the_post(); ?>
                <li>
                    <div class="media">
                    <div class="media-left">
                    <a href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) {
                        the_post_thumbnail('medium',array('class' => 'img-responsive'));
                    } ?>
                    </a>
                    </div>
                    <div class="media-body">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <p><?php echo esc_html(get_the_date( 'd F Y')); ?></p>
                    </div>
                    </div>
                </li>
        <?php endwhile; ?>
        <?php echo $args['after_widget'];
        wp_reset_postdata();
        endif; ?>
        </ul>
      <?php  $impressive_business_cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('impressive_business_widget_recent_posts', $impressive_business_cache, 'widget');
    }
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field(strip_tags($new_instance['title']));
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = (bool) $new_instance['show_date'];
        $this->impressive_business_flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['impressive_business_widget_recent_posts']) )
            delete_option('impressive_business_widget_recent_posts');
        return $instance;
    }
    function impressive_business_flush_widget_cache() {
        wp_cache_delete('impressive_business_widget_recent_posts', 'widget');
    }
    function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false; ?>        
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:','impressive-business' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e( 'Number of posts to show:', 'impressive-business' ); ?></label>
        <input id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        <p><input class="checkbox" type="checkbox" <?php checked( esc_attr($show_date) ); ?> id="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_date' )); ?>" />
        <label for="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>"><?php esc_html_e( 'Display post date?', 'impressive-business' ); ?></label></p>
<?php }
}