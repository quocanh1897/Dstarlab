<?php

/**
 * Adds WP Front Page News Widget widget.
 *
 * this class is instantiated by registering the widget
 * with WP in wplp-admin.inc.php's constructor
 *
 */
class WPLPWidget extends WP_Widget
{

    const PRO_VERSION_URL = 'http://www.joomunited.com/wordpress-products/wp-latest-posts';
    protected static $did_script = false;

    /**
     * Register widget with WordPress.
     *
     */
    public function __construct()
    {
        parent::__construct(
            'wplp_widget', // Base ID
            'WP Latest Posts Widget', // Name
            array('description' => __('WP Latest Posts Widget instance', 'wp-latest-posts'),) // Args
        );
        if (!is_admin()) {
            add_action('init', array($this, "addStyleScript"));
        }
    }

    /**
     * Add style and script in themes
     */
    public function addStyleScript()
    {
        $news_widget_id = $this->get_settings();

        foreach ($news_widget_id as $widgetfind) {
            if (isset($widgetfind["news_widget_id"]) && !empty($widgetfind["news_widget_id"])) {
                $widget = get_post($widgetfind["news_widget_id"]);
                if (isset($widget) && !empty($widget)) {
                    $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);
                    $front = new WPLPFront($widget);
                    add_action('wp_print_styles', array($front, "loadThemeStyle"));
                    add_action('wp_head', array($front, 'customCSS'));
                    add_action('wp_print_scripts', array($front, "loadThemeScript"));
                }
            }
        }
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];


        $widget = get_post($instance['news_widget_id']);
        if (isset($widget) && !empty($widget)) {
            $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);
            $front = new WPLPFront($widget);
            //$front->loadThemeStyle();

            if (isset($front->widget->settings['show_title']) &&
                $front->widget->settings['show_title'] == 1) {
                echo $args['before_title'] . $front->widget->post_title . $args['after_title'];
            }
            $front->display(true, true);

            echo $args['after_widget'];
        }
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     * @return bool|string
     */
    public function form($instance)
    {
        if (isset($instance['news_widget_id'])) {
            $news_widget_id = $instance['news_widget_id'];
        } else {
            if ($widget = $this->findFirstWidget()) {
                $news_widget_id = $widget->ID;
            } else {
                echo '<p>' . __('No Frontpage News Widget has been created.', 'wp-latest-posts') . '</p>';
                //TODO: add link to widget creation edit page
                echo '<p>' . __('Please create one to use this widget.', 'wp-latest-posts') . '</p>';
                return false;
            }
        }
        $selected[$news_widget_id] = ' selected="selected"';
        $widgets = get_posts(array('post_type' => CUSTOM_POST_NEWS_WIDGET_NAME, 'posts_per_page' => -1));
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('news_widget_id'); ?>">
                <?php _e('News Widget:', 'wp-latest-posts'); ?></label>
            <select id="<?php echo $this->get_field_id('news_widget_id'); ?>"
                    name="<?php echo $this->get_field_name('news_widget_id'); ?>">
                <?php foreach ($widgets as $widget) : ?>
                    <option value="<?php echo $widget->ID; ?>"
                        <?php echo(isset($selected[$widget->ID]) ? $selected[$widget->ID] : ''); ?>>
                        <?php echo $widget->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php

        wp_reset_postdata();
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['news_widget_id'] = (!empty($new_instance['news_widget_id'])) ?
            strip_tags($new_instance['news_widget_id']) : '';

        return $instance;
    }

    /**
     * TODO: CHECK: Is this really useful?
     *
     * @return mixed|boolean
     */
    private function findFirstWidget()
    {
        $widgets = get_posts(array('post_type' => CUSTOM_POST_NEWS_WIDGET_NAME));
        if (is_array($widgets) && !empty($widgets)) {
            return array_shift($widgets);
        } else {
            return false;
        }
        wp_reset_postdata();
    }
}

?>