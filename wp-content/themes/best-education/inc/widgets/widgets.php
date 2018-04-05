<?php
/**
 * Theme widgets.
 *
 * @package Best Education
 */

// Load widget base.
require_once get_template_directory() . '/inc/widgets/widget-base-class.php';

if (!function_exists('best_education_load_widgets')) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function best_education_load_widgets()
    {

        register_widget('Best_Education_widget_social');

        // Auther widget.
        register_widget('Best_Education_Author_Post_widget');

    }
endif;
add_action('widgets_init', 'best_education_load_widgets');

/*author widget*/
if (!class_exists('Best_Education_Author_Post_widget')) :

    /**
     * Author widget Class.
     *
     * @since 1.0.0
     */
    class Best_Education_Author_Post_widget extends Best_Education_Widget_Base
    {

        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $opts = array(
                'classname' => 'best_education_author_widget',
                'description' => __('Displays message details in post.', 'best-education'),
                'customize_selective_refresh' => true,
            );
            $fields = array(
                'title' => array(
                    'label' => __('Title:', 'best-education'),
                    'type' => 'text',
                    'class' => 'widefat',
                ),
                'author-name' => array(
                    'label' => __('Name:', 'best-education'),
                    'type' => 'text',
                    'class' => 'widefat',
                ),
                'discription' => array(
                    'label' => __('Discription:', 'best-education'),
                    'type' => 'text',
                    'class' => 'widget-content widefat'
                ),
                'image_url' => array(
                    'label' => __('Image:', 'best-education'),
                    'type' => 'image',
                ),
                'signature_image_url' => array(
                    'label' => __('Signature Image:', 'best-education'),
                    'type' => 'image',
                ),
                'url-fb' => array(
                    'label' => __('Facebook URL:', 'best-education'),
                    'type' => 'url',
                    'class' => 'widefat',
                ),
                'url-tw' => array(
                    'label' => __('Twitter URL:', 'best-education'),
                    'type' => 'url',
                    'class' => 'widefat',
                ),
                'url-gp' => array(
                    'label' => __('Googleplus URL:', 'best-education'),
                    'type' => 'url',
                    'class' => 'widefat',
                ),
            );

            parent::__construct('best-education-author-layout', __('BE: Message Widget', 'best-education'), $opts, array(), $fields);
        }

        /**
         * Outputs the content for the current widget instance.
         *
         * @since 1.0.0
         *
         * @param array $args Display arguments.
         * @param array $instance Settings for the current widget instance.
         */
        function widget($args, $instance)
        {

            $params = $this->get_params($instance);

            echo $args['before_widget'];

            if (!empty($params['title'])) {
                echo $args['before_title'] . $params['title'] . $args['after_title'];
            } ?>

            <!--cut from here-->
            <div class="author-info">
                <div class="author-image">
                    <?php if (!empty($params['image_url'])) { ?>
                        <div class="profile-image bg-image">
                            <img src="<?php echo esc_url($params['image_url']); ?>">
                        </div>
                    <?php } ?>
                </div> <!-- /#author-image -->

                <div class="author-social">
                    <?php if (!empty($params['url-fb'])) { ?>
                        <a href="<?php echo esc_url($params['url-fb']); ?>" target="_blank">
                            <i class="tm-ion fa fa-facebook"></i>
                        </a>
                    <?php } ?>
                    <?php if (!empty($params['url-tw'])) { ?>
                        <a href="<?php echo esc_url($params['url-tw']); ?>" target="_blank">
                            <i class="tm-ion fa fa-twitter"></i>
                        </a>
                    <?php } ?>
                    <?php if (!empty($params['url-gp'])) { ?>
                        <a href="<?php echo esc_url($params['url-gp']); ?>" target="_blank">
                            <i class="tm-ion fa fa-google-plus"></i>
                        </a>
                    <?php } ?>
                </div>

                <div class="author-details">
                    <?php if (!empty($params['author-name'])) { ?>
                        <h3 class="author-name"><?php echo esc_html($params['author-name']); ?></h3>
                    <?php } ?>
                    <?php if (!empty($params['discription'])) { ?>
                        <p><?php echo wp_kses_post($params['discription']); ?></p>
                    <?php } ?>
                </div> <!-- /#author-details -->

                <?php if (!empty($params['signature_image_url'])) { ?>
                    <div class="signature-image">
                        <img src="<?php echo esc_url($params['signature_image_url']); ?>">
                    </div>
                <?php } ?>


            </div>
            <?php echo $args['after_widget'];
        }
    }
endif;

/*author widget*/
if (!class_exists('Best_Education_widget_social')) :

    /**
     * Author widget Class.
     *
     * @since 1.0.0
     */
    class Best_Education_widget_social extends Best_Education_Widget_Base
    {

        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $opts = array(
                'classname' => 'best_education_social_widget',
                'description' => __('Displays social menu if you have set it(social menu)', 'best-education'),
                'customize_selective_refresh' => true,
            );
            $fields = array(
                'title' => array(
                    'label' => __('Title:', 'best-education'),
                    'description' => __('Note: Displays social menu if you have set it(social menu)', 'best-education'),
                    'type' => 'text',
                    'class' => 'widefat',
                ),
                'description' => array(
                    'label' => __('Description:', 'best-education'),
                    'type' => 'text',
                    'class' => 'widefat',
                ),
            );
            parent::__construct('best-education-social-layout', __('BE: Social Menu Widget', 'best-education'), $opts, array(), $fields);
        }

        /**
         * Outputs the content for the current widget instance.
         *
         * @since 1.0.0
         *
         * @param array $args Display arguments.
         * @param array $instance Settings for the current widget instance.
         */
        function widget($args, $instance)
        {

            $params = $this->get_params($instance);

            echo $args['before_widget'];

            echo "<div class='widget-header-wrapper'>";
            if (!empty($params['title'])) {
                echo $args['before_title'] . $params['title'] . $args['after_title'];
            }
                if (!empty($params['description'])) {
            echo "<p class='widget-description'>";
                    echo esc_html($params['description']);
            echo "</p>";
                }
            echo "</div>";
            ?>

            <!--cut from here-->
            <div class="social-widget-menu">
                <?php
                if ( has_nav_menu( 'social' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'social',
                        'link_before'    => '<span>',
                        'link_after'     => '</span>',
                    ) );
                } ?>
            </div>
            <?php if ( ! has_nav_menu( 'social' ) ) : ?>
            <p>
                <?php esc_html_e( 'Social menu is not set. You need to create menu and assign it to Social Menu on Menu Settings.', 'best-education' ); ?>
            </p>
            <?php endif; ?>
            <?php echo $args['after_widget'];
        }
    }
endif;

