<?php

/** WP Latest Posts main class * */
class WPLPAdmin
{
    //TODO: separate front-end and back-end methods, only include necessary code
    public $version = false;
    /** Specific field value properties to enforce * */
    private $enforce_fields = array(
        'amount_pages' => POSITIVE_INT_GT1,
        'amount_cols' => POSITIVE_INT_GT1,
        'amount_rows' => POSITIVE_INT_GT1,
        'max_elts' => POSITIVE_INT_GT1,
        'default_img' => FILE_UPLOAD,
        'box_top' => LI_TO_ARRAY,
        'box_left' => LI_TO_ARRAY,
        'box_right' => LI_TO_ARRAY,
        'box_bottom' => LI_TO_ARRAY,
    );

    public $width_unit_values = array(
        '%',
        'em',
        'px'
    );

    /** Field default values * */
    private $field_defaults = array(
        'source_type' => 'src_category',
        'cat_post_source_order' => 'date',
        'cat_post_source_asc' => 'desc',
        'cat_source_order' => 'date',
        'cat_source_asc' => 'desc',
        'pg_source_order' => 'order',
        'pg_source_asc' => 'desc',
        'show_title' => 1, // Wether or not to display the block title
        'amount_pages' => 1,
        'amount_cols' => 3,
        'pagination' => 2,
        'max_elts' => 5,
        'off_set' => 0, //number posts to skip
        'total_width' => 100,
        'total_width_unit' => 0, //%
        'crop_title' => 2,
        'crop_title_len' => 1,
        'crop_text' => 2,
        'crop_text_len' => 2,
        'autoanimation' => 0,
        'autoanimation_trans' => 1,
        'theme' => 'default',
        'box_top' => array(),
        'box_left' => array('Thumbnail'),
        'box_right' => array('Title', 'Date', 'Text'),
        'box_bottom' => array(),
        'thumb_img' => 1, // 0 == use featured image
        'image_size' => 'mediumSize',
        'thumb_width' => 150, // in px
        'thumb_height' => 150, // in px
        'crop_img' => 0, // 0 == do not crop (== resize to fit)
        'margin_left' => 0,
        'margin_top' => 0,
        'margin_right' => 4,
        'custom_css' => '',
        'margin_bottom' => 4,
        'date_fmt' => '',
        'no_post_text' => '',
        'read_more' => '',
        'default_img_previous' => '', // Overridden in constructor
        'default_img' => '', // Overridden in constructor
        'dfThumbnail' => 'Thumbnail',
        'dfTitle' => 'Title',
        'dfText' => 'Text',
        'dfDate' => 'Date',
        'dfCategory' => 'Category',
        'image_position_width' => '30',
        'content_language' => 'en',
        'content_include' => 1
    );


    /**
     * Headers for style.css files.
     *
     * @static
     * @access private
     * @var array
     */
    private static $file_headers = array(
        'Name' => 'Theme Name',
        'ThemeURI' => 'Theme URI',
        'Description' => 'Description',
        'Author' => 'Author',
        'AuthorURI' => 'Author URI',
        'Version' => 'Version',
        'Template' => 'Template',
        'Status' => 'Status',
        'Tags' => 'Tags',
        'TextDomain' => 'Text Domain',
        'DomainPath' => 'Domain Path',
    );

    /**
     * Counts how many widgets are being displayed
     * @var int
     */
    public $widget_count = 0;

    /**
     * Constructor
     * @param $opts
     */
    public function __construct($opts)
    {

        $this->version = $opts['version'];
        $this->tdomain = $opts['translation_domain'];
        $this->plugin_file = $opts['plugin_file'];
        $this->plugin_dir = dirname(plugin_basename($this->plugin_file));


        // $this->form_blocks = $opts['form_blocks']; // YD Legacy (was to avoid "backlinkware")

        /** Check PHP and WP versions upon install * */
        register_activation_hook(dirname(dirname(__FILE__)), array($this, 'activate'));

        //load language
        load_plugin_textdomain(
            $this->tdomain,
            WP_PLUGIN_URL . '/' . $this->plugin_dir . '/languages',
            $this->plugin_dir . '/languages'
        );
        //add_action('init', array($this, 'checkUsed'));

        /** Setup default image * */
        $this->field_defaults['default_img_previous'] = plugins_url(DEFAULT_IMG, dirname(__FILE__));

        $this->field_defaults['default_img'] = plugins_url(DEFAULT_IMG, dirname(__FILE__));

        /** Sets up custom post types * */
        add_action('init', array($this, 'setupCustomPostTypes'));
        //Update option when update plugin
        add_action('admin_init', array($this, 'wplpUpdateVersion'));
        /** Register our widget (implemented in separate wplp-widget.inc.php class file) * */
        add_action('widgets_init', function () {
            register_widget('WPLPWidget');
        });

        /** Register our shortcode * */
        add_shortcode('frontpage_news', array($this, 'applyShortcode'));


        add_filter('post_updated_messages', array($this, 'wplpCustomUpdateMessages'));
        if (is_admin()) {
            if (is_multisite()) {
                switch_to_blog(get_current_blog_id());
                $current_screen = $_SERVER['REQUEST_URI'];
                $post_type = isset($_GET['post']) ? get_post_type($_GET['post']) : "";
                if (isset($_GET['post_type']) && strpos($current_screen, 'post-new.php') !== false) {
                    $post_type = $_GET['post_type'];
                }

                restore_current_blog();
            } else {
                global $pagenow;
                $post_type = isset($_GET['post']) ? get_post_type($_GET['post']) : "";
                if (isset($_GET['post_type']) && $pagenow == "post-new.php") {
                    $post_type = $_GET['post_type'];
                }
            }
            if (CUSTOM_POST_NEWS_WIDGET_NAME == $post_type) {


                /** Load tabs ui + drag&drop ui * */
                add_action('admin_enqueue_scripts', array($this, 'loadAdminScripts'));

                /** Load admin css for tabs * */
                add_action('admin_init', array($this, 'addAdminStylesheets'));

                add_action('wp_print_scripts', array($this, 'dequeueAdminScripts'));
                // Add action after css of hueman theme
                add_action('ot_admin_styles_after', array($this, 'dequeueAdminHuemanStyles'));
            }

            /** Customize custom post editor screen * */
            //add_action( 'admin_head', array( $this, 'changeIcon' ) );	//Unused
            add_action('admin_menu', array($this, 'setupCustomMetaBoxes'));
            add_action('admin_menu', array($this, 'setupCustomMenu'));
            add_action('save_post', array($this, 'saveCustomPostdata'));

            /** Customize Tiny MCE Editor * */
            add_action('admin_init', array($this, 'setupTinyMce'));
            add_action('in_admin_footer', array($this, 'editorFooterScript'));

            /** Tiny MCE 4.0 fix * */
            if (get_bloginfo('version') >= 3.9) {
                add_action('media_buttons', array($this, 'editorButton'), 1000); //1000 = put it at the end
            }

            if (!class_exists('WPLPAddonAdmin')) {
                add_filter('plugin_row_meta', array($this, 'addProLink'), 10, 2);
            }
            //ajax of mutilsite
            add_action('wp_ajax_change_cat_multisite', array($this, 'changeCatMultisite'));
            // Ajax of content language
            add_action(
                'wp_ajax_change_source_type_by_language',
                array('WPLPLanguageContent', 'changeSourceTypeByLanguage')
            );
        } else {

            /** Load our theme stylesheet on the front if necessary * */
            add_action('wp_print_styles', array($this, 'addStylesheet'));

            /** Load our fonts on the front if necessary * */
            add_action('wp_print_styles', array($this, 'addFonts'));

            /** Load our front-end slide control script * */
            //add_action( 'wp_print_scripts', array( $this, 'addFrontScript' ),0 );
            add_action('the_posts', array($this, 'prefixEnqueue'), 100);
            //add_action( 'after_setup_theme', array( $this, 'child_theme_setup' ) );
        }
    }

    /**
     * change category of blog
     */
    public function changeCatMultisite()
    {
        $val_blog = $type = $content_language = '';
        if (isset($_POST['val_blog'])) {
            $val_blog = $_POST['val_blog'];
        }
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
        }
        if (isset($_POST['content_language'])) {
            $content_language = $_POST['content_language'];
        }
        $output = '';
        $cats = array();
        if ('all_blog' == $val_blog) {
            $blogs = get_sites();
            foreach ($blogs as $blog) {
                switch_to_blog((int)$blog->blog_id);
                if (strpos($type, 'post') !== false) {
                    $allcats = get_categories();
                    if (!empty($content_language)) {
                        $allcats = apply_filters('wplp_get_category_by_language', $allcats, $content_language);
                    }
                } elseif (strpos($type, 'page') !== false) {
                    $allcats = get_pages();
                    if (!empty($content_language)) {
                        $allcats = apply_filters('wplp_get_pages_by_language', $allcats, $content_language);
                    }
                } elseif (strpos($type, 'tag') !== false) {
                    $allcats = get_tags();
                    if (!empty($content_language) && !empty($allcats)) {
                        $allcats = apply_filters('wplp_get_tags_by_language', $allcats, $content_language);
                    }
                } elseif (strpos($type, 'cat_list') !== false) {
                    $allcats = get_categories();
                }

                foreach ($allcats as $allcat) {
                    $allcat->blog = (int)$blog->blog_id;
                    $cats[] = $allcat;
                }
                restore_current_blog();
            }
        } else {
            switch_to_blog((int)$val_blog);
            if (strpos($type, 'post') !== false) {
                $cats = get_categories();
                if (!empty($content_language)) {
                    $cats = apply_filters('wplp_get_category_by_language', $cats, $content_language);
                }
            } elseif (strpos($type, 'page') !== false) {
                $cats = get_pages();
                if (!empty($content_language)) {
                    $cats = apply_filters('wplp_get_pages_by_language', $cats, $content_language);
                }
            } elseif (strpos($type, 'tag') !== false) {
                $cats = get_tags();
                if (!empty($content_language) && !empty($cats)) {
                    $cats = apply_filters('wplp_get_tags_by_language', $cats, $content_language);
                }
            } elseif (strpos($type, 'cat_list') !== false) {
                $cats = get_categories();
                if (!empty($content_language)) {
                    $cats = apply_filters('wplp_get_category_by_language', $cats, $content_language);
                }
                foreach ($cats as $cat) {
                    $cat->blog = (int)$val_blog;
                }
            }
            restore_current_blog();
        }

        if (strpos($type, 'post') !== false) {
            $output .= '<ul  class="post_field">';
            $output .= '<li><input id="cat_all" type="checkbox" name="wplp_source_category[]" value="_all" />';
            $output .=  '<label for="cat_all" class="post_cb">All</li>';
            foreach ($cats as $k => $cat) {
                $output .= '<li><input id="ccb_' . $k . '" type="checkbox" name="wplp_source_category[]"';
                $output .= 'value="' . $k . '_' . $cat->term_id . '" class="post_cb" />';
                $output .= '<label for="ccb_' . $k . '" class="post_cb">' . $cat->name . '</label></li>';
            }
            $output .= '</ul>';
        } elseif (strpos($type, 'page') !== false) {
            $output .= '<ul class="page_field">';
            $output .= '<li><input id="page_all" type="checkbox" name="wplp_source_pages[]" value="_all" />' .
                '<label for="page_all" class="page_cb">All Pages</li>';

            foreach ($cats as $k => $page) {
                $output .= '<li><input id="pcb_' . $k . '" type="checkbox" name="wplp_source_pages[]"';
                $output .= 'value="' . $k . '_' . $page->ID . '" class="page_cb" />';
                $output .= '<label for="pcb_' . $k . '" class="page_cb">' . $page->post_title . '</label></li>';
            }
            $output .= '</ul>';    //fields
        } elseif (strpos($type, 'tag') !== false) {
            $output .= '<ul class="tag_field">';
            $output .= '<li><input id="tags_all" type="checkbox" name="wplp_source_tags[]" value="_all"  />' .
                '<label for="tags_all" class="tag_cb">All tags</li>';

            foreach ($cats as $k => $tag) {
                $output .= '<li><input id="tcb_' . $k . '" type="checkbox" name="wplp_source_tags[]"';
                $output .= 'value="' . $k . '_' . $tag->term_id . '"  class="tag_cb" />';
                $output .= '<label for="tcb_' . $k . '" class="tag_cb">' . $tag->name . '</label></li>';
            }
            $output .= '</ul>';
        } elseif (strpos($type, 'cat_list') !== false) {
            $output .= '<ul class="fields">';
            $output .= '<li><input id="cat_list_all" type="checkbox" name="wplp_source_category_list[]" value="_all" />';
            $output .= '<label for="cat_list_all" class="cat_list_cb">All</li>';
            foreach ($cats as $k => $cat) {
                $output .= '<li><input id="cl_' . $k . '" type="checkbox" name="wplp_source_category_list[]"';
                $output .= 'value="' . $k . '_' . $cat->term_id . '_blog' . $cat->blog . '" class="cat_list_cb" />';
                $output .= '<label for="cl_' . $k . '" class="cat_list_cb">' . $cat->name . '</label></li>';
            }
            $output .= '</ul>';
        }

        echo json_encode(array('output' => $output, 'type' => $type));
        exit;
    }

    public function dequeueAdminHuemanStyles()
    {
        //fix conflict with hueman theme
        wp_dequeue_style('ot-admin-css');
    }
    /**
     * Plugin Activation hook function to check for Minimum PHP and WordPress versions
     * @see http://wordpress.stackexchange.com/questions/76007/best-way-to-abort-plugin-in-case-of-insufficient-php-version
     *
     * @param string $wp Minimum version of WordPress required for this plugin
     * @param string $php Minimum version of PHP required for this plugin
     */
    public function activate($wp = '3.2', $php = '5.3.1')
    {
        global $wp_version;
        if (version_compare(PHP_VERSION, $php, '<')) {
            $flag = 'PHP';
        } elseif (version_compare($wp_version, $wp, '<')) {
            $flag = 'WordPress';
        } else {
            $this->checkUsed();
            return;
        }
        $version = 'PHP' == $flag ? $php : $wp;
        deactivate_plugins(basename(__FILE__));
        $text = '<p>The <strong>WP Latest Posts</strong> plugin requires ';
        $text .= $flag . '  version ' . $version . ' or greater.</p>';
        wp_die(
            $text,
            'Plugin Activation Error',
            array('response' => 200, 'back_link' => true)
        );
    }

    /**
     * check user
     * use new theme default for new users
     */
    public function checkUsed()
    {
        global $wpdb;
        $oldBlock = get_option("_wplp_onceLoad");
        if (empty($oldBlock)) {
            $meta_key = "_wplp_settings";
            $query = $wpdb->prepare(" SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s ", $meta_key);
            $postsId = $wpdb->get_results($query);
            if (!empty($postsId)) {
                foreach ($postsId as $postId) {
                    $postId = $postId->post_id;
                    $postMeta = get_post_meta($postId, "_wplp_settings", true);
                    if (strpos($postMeta['theme'], "default")) {
                        $postMeta['theme'] = addslashes($postMeta['theme']);
                        update_post_meta($postId, "_wplp_settings", $postMeta);
                    }
                }
            }
            $onceLoad = 1;
            add_option("_wplp_onceLoad", $onceLoad, "", "no");
        }
    }

    /**
     * Sets up WP custom post types
     *
     */
    public function setupCustomPostTypes()
    {
        $labels = array(
            'name' => __('WP Latest Posts Blocks', 'wp-latest-posts'),
            'singular_name' => __('WPLP Block', 'wp-latest-posts'),
            'add_new' => __('Add New', 'wp-latest-posts'),
            'add_new_item' => __('Add New WPLP Block', 'wp-latest-posts'),
            'edit_item' => __('Edit WPLP Block', 'wp-latest-posts'),
            'new_item' => __('New WPLP Block', 'wp-latest-posts'),
            'all_items' => __('All News Blocks', 'wp-latest-posts'),
            'view_item' => __('View WPLP Block', 'wp-latest-posts'),
            'search_items' => __('Search WPLP Blocks', 'wp-latest-posts'),
            'not_found' => __('No WPLP Block found', 'wp-latest-posts'),
            'not_found_in_trash' => __('No WPLP Block found in Trash', 'wp-latest-posts'),
            'parent_item_colon' => '',
            'menu_name' => __('Latest Posts', 'wp-latest-posts')
        );
        register_post_type(CUSTOM_POST_NEWS_WIDGET_NAME, array(
            'public' => false,
            'show_ui' => true,
            'menu_position' => 5,
            'labels' => $labels,
            'supports' => array(
                'title', 'author'
            ),
            'menu_icon' => 'dashicons-admin-page',
        ));
    }

    /**
     * change message Latest Posts updated
     * @param $messages
     * @return mixed
     */
    public function wplpCustomUpdateMessages($messages)
    {

        $messages['wplp-news-widget'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => __('Latest Posts updated.', 'wp-latest-posts'),
        );

        return $messages;
    }

    /**
     * Append our theme stylesheet if necessary
     *
     */
    public function addStylesheet()
    {
        /*
         TODO: is there a way to load our theme stylesheet only where necessary?
        */

        $myStyleUrl = plugins_url(MAIN_FRONT_STYLESHEET, dirname(__FILE__));
        $myStylePath = plugin_dir_path(dirname(__FILE__)) . MAIN_FRONT_STYLESHEET;

        if (file_exists($myStylePath)) {
            wp_register_style('myStyleSheets', $myStyleUrl);
            wp_enqueue_style('myStyleSheets');
        }
    }

    /**
     * Append our fonts if necessary
     *
     */
    public function addFonts()
    {
        /*
          TODO: is there a way to load our fonts only where necessary?
        */

        $myFontsUrl = 'https://fonts.googleapis.com/css?' .
            'family=Raleway:400,500,600,700,800,900|' .
            'Alegreya:400,400italic,700,700italic,900,900italic|' .
            'Varela+Round' .
            '&subset=latin,latin-ext';

        wp_register_style('myFonts', $myFontsUrl);
        wp_enqueue_style('myFonts');
    }

    /**
     * Append our front-end script if necessary
     *
     */
    public function addFrontScript()
    {
        //TODO: load only if necessary (is this possible ?)

        wp_enqueue_script(
            'wplp-front',
            plugins_url(MAIN_FRONT_SCRIPT, dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
    }

    /**
     * Save our custom setting fields in the WP database
     *
     * @param inc $post_id
     * @return inc $post_id (unchanged)
     */
    public function saveCustomPostdata($post_id)
    {
        global $post;

        if (CUSTOM_POST_NEWS_WIDGET_NAME != get_post_type($post_id)) {
            return $post_id;
        }
        if (!isset($_POST[CUSTOM_POST_NONCE_NAME . '_nonce'])) {
            return $post_id;
        }
        $nonce = $_POST[CUSTOM_POST_NONCE_NAME . '_nonce'];
        if (!wp_verify_nonce($nonce, CUSTOM_POST_NONCE_NAME)) {
            return $post_id;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        if (is_multisite()) {
            switch_to_blog(get_current_blog_id());
            $my_settings = get_post_meta($post->ID, '_wplp_settings', true);
            restore_current_blog();
        } else {
            $my_settings = get_post_meta($post->ID, '_wplp_settings', true);
        }
        $my_settings = wp_parse_args($my_settings, $this->field_defaults);

        /** File uploads * */
        foreach ($_FILES as $field_name => $field_value) {
            if (preg_match('/^wplp_/', $field_name)) {
                //error_log( 'matched wplp' );			//Debug
                $new_field_name = preg_replace('/^wplp_/', '', $field_name);
                if (is_uploaded_file($_FILES[$field_name]['tmp_name'])) {
                    $uploads = wp_upload_dir();
                    $upload_dir = ($uploads['path']) . '/';
                    $upload_url = ($uploads['url']) . '/';
                    if (preg_match('/(\.[^\.]+)$/', $_FILES[$field_name]['name'], $matches)) {
                        $ext = $matches[1];
                    }

                    $upload_file = DEFAULT_IMG_PREFIX . date("YmdHis") . $ext;
                    if (rename($_FILES[$field_name]['tmp_name'], $upload_dir . $upload_file)
                    ) {
                        chmod($upload_dir . $upload_file, 0664);
                        $my_settings[$new_field_name] = $upload_url . $upload_file;
                    } else {
                        $this->update_msg .= __('Processing of temporary uploader file has failed' .
                                ' please check for file directory ', 'wp-latest-posts') . $upload_dir;
                        //error_log( $this->update_msg );	//Debug
                    }
                } else {
                    /** keep the previous image * */
                    if (isset($_POST[$field_name . '_previous'])) {
                        $my_settings[$new_field_name] = $_POST[$field_name . '_previous'];
                    }
                }
            }
        }
        /** Normal fields * */
        foreach ($_POST as $field_name => $field_value) {
            if (preg_match('/^wplp_/', $field_name)) {
                if (preg_match('/_none$/', $field_name)) {
                    continue;
                }
                $field_name = preg_replace('/^wplp_/', '', $field_name);
                if (is_array($field_value)) {
                    $my_settings[$field_name] = $field_value;
                } else {
                    if (preg_match('/^box_/', $field_name)) {
                        /** No sanitizing for those fields that are supposed to contain html * */
                        $my_settings[$field_name] = $field_value;
                    } else {
                        $my_settings[$field_name] = sanitize_text_field($field_value);
                    }

                    /** Enforce specific field value properties * */
                    if (isset($this->enforce_fields[$field_name])) {
                        if (POSITIVE_INT_GT1 == $this->enforce_fields[$field_name]) {
                            $my_settings[$field_name] = intval($my_settings[$field_name]);
                            if ($my_settings[$field_name] < 1) {
                                $my_settings[$field_name] = 1;
                            }
                        }
                        if (BOOL == $this->enforce_fields[$field_name]) {
                            $my_settings[$field_name] = intval($my_settings[$field_name]);
                            if ($my_settings[$field_name] < 1) {
                                $my_settings[$field_name] = 0;
                            }
                            if ($my_settings[$field_name] >= 1) {
                                $my_settings[$field_name] = 1;
                            }
                        }
                        if (FILE_UPLOAD == $this->enforce_fields[$field_name]) {
                            //Do nothing I guess.
                        }
                        if (LI_TO_ARRAY == $this->enforce_fields[$field_name]) {
                            if ($field_value) {
                                $values = preg_split('/<\/li><li[^>]*>/i', $field_value);
                            } else {
                                $values = array();
                            }
                            if ($values) {
                                array_walk($values, function (&$value) {
                                    $value = strip_tags($value);
                                });
                            }
                            $my_settings[$field_name] = $values;
                        }
                    }
                }
            }
        }

        update_post_meta($post_id, '_wplp_settings', $my_settings);

        return $post_id;
    }

    /**
     * Loads js/ajax scripts
     * @param $hook
     * @return mixed
     */
    public function loadAdminScripts($hook)
    {

        /** Only load on post edit admin page * */
        if ('post.php' != $hook && 'post-new.php' != $hook) {
            return $hook;
        }
        if (CUSTOM_POST_NEWS_WIDGET_NAME != get_post_type()) {
            return $hook;
        }
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-button');
        wp_enqueue_script('jquery-ui-slider');

        wp_enqueue_script(
            'wplp-easing',
            plugins_url('js/materialize/jquery.easing.1.3.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script(
            'wplp-velocity',
            plugins_url('js/materialize/velocity.min.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script(
            'wplp-tabs',
            plugins_url('js/materialize/tabs.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script(
            'wplp-dropdown',
            plugins_url('js/materialize/dropdown.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script(
            'wplp-form',
            plugins_url('js/materialize/forms.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script(
            'wplp-dropify',
            plugins_url('js/dropify/js/dropify.min.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script(
            'wplp-picker',
            plugins_url('js/materialize/picker.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script(
            'wplp-picker-date',
            plugins_url('js/materialize/picker.date.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script(
            'wplp-back',
            plugins_url('js/wplp_back.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_script(
            'wplp-newColorPicker',
            plugins_url('js/wplp_newColorPicker.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );

        /** add codemirror js */
        wp_enqueue_script(
            'wplp-codemirror',
            plugins_url('codemirror/lib/codemirror.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        /** mode css */
        wp_enqueue_script(
            'wplp-codemirrorMode',
            plugins_url('codemirror/mode/css/css.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );

        wp_enqueue_script(
            'wplp-codemirrorAdmin',
            plugins_url('js/wplp_codemirrorAdmin.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );

        wp_enqueue_script(
            'wplp-content-language',
            plugins_url('js/wplp_content_language.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );
        wp_enqueue_script(
            'jquery-qtip',
            plugins_url('js/jquery.qtip.min.js', dirname(__FILE__)),
            array('jquery'),
            '2.2.1',
            true
        );
        wp_enqueue_style('jquery-qtip', plugins_url('css/jquery.qtip.css', dirname(__FILE__)), array(), '2.2.1');

        //set tokken ajax
        $token_name = array(
            'check_change_content_language' => wp_create_nonce("_change_content_language"),
        );
        $parameter = array(
            'plugin_dir' => WPLP_PLUGIN_DIR
        );
        wp_localize_script('wplp-content-language', '_token_name', $token_name);
        wp_localize_script('wplp-content-language', 'content_language_param', $parameter);
    }

    /**
     * Load additional admin stylesheets
     * of jquery-ui
     *
     */
    public function addAdminStylesheets()
    {
        /** add color picker css */
        wp_enqueue_style('wp-color-picker');

        wp_register_style('uiStyleSheet', plugins_url('css/jquery-ui-custom.css', dirname(__FILE__)));
        wp_enqueue_style('uiStyleSheet');

        wp_register_style('wplp_dropify', plugins_url('js/dropify/css/dropify.min.css', dirname(__FILE__)));
        wp_enqueue_style('wplp_dropify');

        wp_register_style('wplpAdmin', plugins_url('css/wplp_admin.css', dirname(__FILE__)));
        wp_enqueue_style('wplpAdmin');

        wp_register_style('unifStyleSheet', plugins_url('css/uniform/css/uniform.default.css', dirname(__FILE__)));
        wp_enqueue_style('unifStyleSheet');

        /** add codemirror css */
        wp_register_style('wplp_codemirror', plugins_url('codemirror/lib/codemirror.css', dirname(__FILE__)));
        wp_enqueue_style('wplp_codemirror');

        wp_register_style('wplp_codemirrorTheme', plugins_url('codemirror/theme/3024-day.css', dirname(__FILE__)));
        wp_enqueue_style('wplp_codemirrorTheme');
    }

    /*
     * Dequeue some js
     */

    public function dequeueAdminScripts()
    {
        wp_dequeue_script('sdf_bs_js_admin');
        //fix conflict with bootstrap theme
        wp_dequeue_script('bootstrap');
        wp_dequeue_script('cp_scripts_admin');
        //fix conflict with 'All In One Schema.org Rich Snippets' plugin
        wp_dequeue_script('bsf-scripts-media');
        wp_dequeue_style('admin_style');
        //fix conflict with Easy table plugin
        wp_dequeue_script('vc_bootstrap_dropdown');
    }

    /**
     * Customizes the default custom post type editor screen:
     * - removes default meta-boxes
     * - adds our own settings meta-boxes
     *
     */
    public function setupCustomMetaBoxes()
    {
        remove_meta_box('slugdiv', CUSTOM_POST_NEWS_WIDGET_NAME, 'core');
        remove_meta_box('authordiv', CUSTOM_POST_NEWS_WIDGET_NAME, 'core');

        add_meta_box(
            'wplpnavtabsbox',
            __('WP Latest Posts Block Settings', 'wp-latest-posts'),
            array(new WPLPBlockSettings($this->field_defaults), 'editorTabs'),
            CUSTOM_POST_NEWS_WIDGET_NAME,
            'normal',
            'core'
        );
    }

    /**
     * Adds our admin menu item(s)
     *
     */
    public function setupCustomMenu()
    {
        add_submenu_page(
            'edit.php?post_type=wplp-news-widget',
            __('Configuration', 'wp-latest-posts'),
            __('Configuration', 'wp-latest-posts'),
            'activate_plugins',
            'translation-wpfpn',
            array(new WPLPViewsConfig($this->version),
                    'displayConfigTab'
                )
        );
    }


    /**
     * Builds the drop-down list of available themes
     * for this plugin
     *
     */
    public static function themeLister()
    {
        $found_themes = array();
        $theme_root = dirname(dirname(__FILE__)) . '/themes';
        //echo 'theme dir: ' . $theme_root . '<br/>';	//Debug
        $dirs = @ scandir($theme_root);
        foreach ($dirs as $k => $v) {
            if (!is_dir($theme_root . '/' . $v) || $v[0] == '.' || $v == 'CVS') {
                unset($dirs[$k]);
            } else {
                $dirs[$k] = array(
                    'path' => $theme_root . '/' . $v,
                    'url' => plugins_url('themes/' . $v, dirname(__FILE__))
                );
            }
        }

        /** Load Pro add-on themes * */
        $dirs = apply_filters('wplp_themedirs', $dirs);

        if (!$dirs) {
            return false;
        }

        foreach ($dirs as $dir) {
            if (file_exists($dir['path'] . '/style.css')) {
                $headers = get_file_data($dir['path'] . '/style.css', self::$file_headers, 'theme');
                $name = $headers['Name'];
                if ('Default theme' == $name) {
                    $name = ' ' . $name; // <- this makes it sort always first
                }
                $found_themes[basename($dir['path'])] = array(
                    'name' => $name,
                    'dir' => basename($dir['path']),
                    'theme_file' => $dir['path'] . '/style.css',
                    'theme_root' => $dir['path'],
                    'theme_url' => $dir['url']
                );
            }
        }
        asort($found_themes);
        return $found_themes;
    }

    /**
     * Customize Tiny MCE Editor
     *
     */
    public function setupTinyMce()
    {
        if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
            add_filter('mce_buttons', array($this, 'filterMceButton'));
            add_filter('mce_external_plugins', array($this, 'filterMcePlugin'));
            add_filter('mce_css', array($this, 'pluginMceCss'));
        }
    }

    /**
     * Buttons/features from the WordPress's TinyMCE toolbar(s)
     * @param $buttons
     * @return mixed
     */
    public function filterMceButton($buttons)
    {
        array_push($buttons, '|', 'wplp_button');
        return $buttons;
    }

    /**
     * @param $plugins
     * @return mixed
     */
    public function filterMcePlugin($plugins)
    {
        if (get_bloginfo('version') < 3.9) {
            $plugins['wplp'] = plugins_url('js/wplp_tmce_plugin.js', dirname(__FILE__));
        } else {
            $plugins['wplp'] = plugins_url('js/wplp_tmce_plugin-3.9.js', dirname(__FILE__));
        }
        return $plugins;
    }

    /**
     * @param $mce_css
     * @return string
     */
    public function pluginMceCss($mce_css)
    {
        if (!empty($mce_css)) {
            $mce_css .= ',';
        }

        $mce_css .= plugins_url('css/wplp_tmce_plugin.css', dirname(__FILE__));

        return $mce_css;
    }

    /**
     * Add insert button above tinyMCE 4.0 (WP 3.9+)
     *
     */
    public function editorButton()
    {
        $args = "";

        $args = wp_parse_args($args, array(
            'text' => __('Add Latest Posts', 'wp-latest-posts'),
            'class' => 'button',
            'echo' => true
        ));

        /** Print button * */

        $button = '<a href="#TB_inline?height=150&width=150&inlineId=wplp-popup-wrap&modal=true" ' .
            'class="wplp-button thickbox ' . $args['class'] . '" ' .
            'title="' . $args['text'] . '">' .
            '<span style = "vertical-align: text-top" class="dashicons dashicons-admin-page"></span>' . $args['text'] .
            '</a>';

        /** Prepare insertion popup * */
        add_action('admin_footer', array($this, 'insertPopup'));

        if ($args['echo']) {
            echo $button;
        }

        return $button;
    }

    /**
     * Prepare block insertion popup for admin editor with tinyMCE 4.0 (WP 3.9+)
     *
     */
    public function insertPopup()
    {
        ?>

        <div id="wplp-popup-wrap" class="media-modal wp-core-ui" style="display:none">
            <a class="media-modal-close" href="#" onClick="javascript:tb_remove();" title="Close"><span
                        class="media-modal-icon"></span></a>
            <div id="wplp-select-content" class="media-modal-content">

                <div class="wplp-frame-title" style="margin-left: 30px;">
                    <h1><?php echo __('WP Latest Posts', 'wp-latest-posts'); ?></h1></div>

                <div id="wplp_widgetlist" style="margin:50px auto;">
                    <?php
                    $widgets = get_posts(
                        array('post_type' => CUSTOM_POST_NEWS_WIDGET_NAME, 'posts_per_page' => -1)
                    );
                    ?>
                    <?php if ($widgets) : ?>
                        <select id="wplp_widget_select">
                            <option><?php echo __('Select which block to insert:', 'wp-latest-posts'); ?></option>
                            <?php foreach ($widgets as $widget) : ?>
                                <option value="<?php echo $widget->ID; ?>"><?php echo $widget->post_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else : ?>
                        <p><?php echo __('No Latest Posts Widget has been created.', 'wp-latest-posts'); ?></p>
                        <p><?php echo __('Please create one to use this button.', 'wp-latest-posts'); ?></p>
                    <?php endif; ?>
                </div>

                <script>
                    (function ($) {
                        $('#wplp_widgetlist').on('change', function (e) {
                            insertShortcode($('option:selected', this).val(), $('option:selected', this).text());
                            $('#wplp_widgetlist').find('option:first').attr('selected', 'selected');
                            tb_remove();
                        });
                        function insertShortcode(widget_id, widget_title) {
                            var shortcode = '[frontpage_news';
                            if (null != widget_id)
                                shortcode += ' widget="' + widget_id + '"';
                            if (null != widget_title)
                                shortcode += ' name="' + widget_title + '"';
                            shortcode += ']';

                            /** Inserts the shortcode into the active editor and reloads display **/
                            //var ed = tinyMCE.activeEditor;
                            //
                            //                            ed.execCommand('mceInsertContent', 0, shortcode);
                            //                            setTimeout(function() { ed.hide(); }, 1);
                            //                            setTimeout(function() { ed.show(); }, 10);
                            //
                            wplp_send_to_editor(shortcode);
                        }

                        var wpActiveEditor, wplp_send_to_editor;

                        wplp_send_to_editor = function (html) {
                            var editor,
                                hasTinymce = typeof tinymce !== 'undefined',
                                hasQuicktags = typeof QTags !== 'undefined';

                            if (!wpActiveEditor) {
                                if (hasTinymce && tinymce.activeEditor) {
                                    editor = tinymce.activeEditor;
                                    wpActiveEditor = editor.id;
                                } else if (!hasQuicktags) {
                                    return false;
                                }
                            } else if (hasTinymce) {
                                editor = tinymce.get(wpActiveEditor);
                            }

                            if (editor && !editor.isHidden()) {
                                editor.execCommand('mceInsertContent', 0, html);
                                setTimeout(function () {
                                    editor.hide();
                                }, 1);
                                setTimeout(function () {
                                    editor.show();
                                }, 10);

                            } else if (hasQuicktags) {
                                QTags.insertContent(html);
                            } else {
                                document.getElementById(wpActiveEditor).value += html;
                            }

                            // If the old thickbox remove function exists, call it
                            if (window.tb_remove) {
                                try {
                                    window.tb_remove();
                                } catch (e) {
                                }
                            }
                        };
                    })(jQuery);
                </script>

                <style>
                    /** tinyMce button + widget selector **/
                    #wplp_widgetlist {
                        min-width: 150px;
                        max-width: 250px;
                        overflow: hidden;
                        border: 3px solid #eee;
                        background: #fff;
                        z-index: 100;
                    }

                    #wplp_widgetlist select {
                        min-height: 70px;
                        min-width: 250px;
                        padding: 5px;
                        margin-bottom: -5px;
                    }
                </style>
            </div>
        </div>
        <?php
    }

    /**
     * Adds a js script to the post and page editor screen footer
     * to configure our tinyMCE extension
     * with the list of available widgets
     *
     */
    public function editorFooterScript()
    {
        //TODO: return false if not page/post edit screen

        echo '<script>';
        echo "var wplp_widgets = new Array();\n";
        $widgets = get_posts(array('post_type' => CUSTOM_POST_NEWS_WIDGET_NAME, 'posts_per_page' => -1));
        foreach ($widgets as $widget) {
            echo "wplp_widgets['$widget->ID']='" . esc_html($widget->post_title) . "';\n";
        }
        echo '</script>';
    }

    /**
     * Add Style and script in head and footer
     * @param $posts
     * @return mixed
     */
    public function prefixEnqueue($posts)
    {
        if (empty($posts) || is_admin()) {
            return $posts;
        }
        $pattern = get_shortcode_regex();


        foreach ($posts as $post) {
            preg_match_all('/' . $pattern . '/s', $post->post_content, $matches);
            $widgetIDArray = array();
            foreach ($matches as $matchtest) {
                if (is_array($matchtest)) {
                    foreach ($matchtest as $matchtestsub) {
                        preg_match_all('/widget="(.*?)"/s', $matchtestsub, $widgetIDarray);
                        //print_r($widgetIDarray); die();
                        foreach ($widgetIDarray as $widgetID) {
                            if (!empty($widgetID)) {
                                if (is_array($widgetID)) {
                                    foreach ($widgetID as $widgetIDunique) {
                                        if (is_numeric($widgetIDunique) &&
                                            !in_array($widgetIDunique, $widgetIDArray, true)) {
                                            $widgetIDArray[] = $widgetIDunique;
                                        }
                                    }
                                } else {
                                    if (is_numeric($widgetIDunique) &&
                                        !in_array($widgetIDunique, $widgetIDArray, true)) {
                                        $widgetIDArray[] = $widgetIDunique;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    preg_match_all('/widget="(.*?)"/s', $matchtestsub, $widgetIDarray);
                    foreach ($widgetIDarray as $widgetID) {
                        if (!empty($widgetID)) {
                            if (is_array($widgetID)) {
                                foreach ($widgetID as $widgetIDunique) {
                                    if (is_numeric($widgetIDunique) &&
                                        !in_array($widgetIDunique, $widgetIDArray, true)) {
                                        $widgetIDArray[] = $widgetIDunique;
                                    }
                                }
                            } else {
                                if (is_numeric($widgetIDunique) && !in_array($widgetIDunique, $widgetIDArray, true)) {
                                    $widgetIDArray[] = $widgetIDunique;
                                }
                            }
                        }
                    }
                }
            }

            /*
              foreach ($matches[2] as $matche => $matchkey) {
              if ($matchkey == 'frontpage_news') {
              $widgetIDArray[]=$matche;
              }
              }
             */
            foreach ($widgetIDArray as $widgetIDitem) {
                $widget = get_post($widgetIDitem);
                if (isset($widget) && !empty($widget)) {
                    $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);

                    $front = new WPLPFront($widget);
                    add_action('wp_print_styles', array($front, "loadThemeStyle"));
                    add_action('wp_head', array($front, 'customCSS'));
                    add_action('wp_print_scripts', array($front, "loadThemeScript"));
                }
            }
        }
        return $posts;
    }

    /**
     * Returns content of our shortcode
     * @param $args
     * @return string
     */
    public function applyShortcode($args = array())
    {

        $html = '';

        $widget_id = $args['widget'];
        $widget = get_post($widget_id);
        if (isset($widget) && !empty($widget)) {
            $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);
            $front = new WPLPFront($widget);
            $front->loadThemeStyle();
            $front->loadThemeScript();
            $html .= $front->display(false);
        } else {
            $html .= "\n<!-- WPFN: this News Widget is not initialized -->\n";
        }

        return $html;
    }


    /**
     * Add pro link to joomunited product
     * @param $links
     * @param $file
     * @return array
     */
    public function addProLink($links, $file)
    {
        $base = plugin_basename($this->plugin_file);
        if ($file == $base) {
            $links[] = '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">'
                . __('Get "pro" add-on', 'wp-latest-posts') . '</a>';
            $links[] = '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">'
                . __('Support', 'wp-latest-posts') . '</a>';
        }
        return $links;
    }

    // \/------------------------------------------ Update version 4.0 ------------------------------------------\/
    /*
     * Change prefix option from wpcufpn to wplp
     */
    public function wplpUpdateVersion()
    {
        global $wpdb;
        $ver = 'wplp_db_version';
        $option_ver = get_option($ver, false);

        if (!$option_ver) {
            $meta_key = "_wpcufpn_settings";
            $query = $wpdb->prepare(" SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s ", $meta_key);
            $postsId = $wpdb->get_results($query);

            if (!empty($postsId)) {
                foreach ($postsId as $post) {
                    $settings = get_post_meta($post->post_id, $meta_key, true);
                    update_post_meta($post->post_id, "_wplp_settings", $settings);
                    set_post_type($post->post_id, CUSTOM_POST_NEWS_WIDGET_NAME);
                    delete_post_meta($post->post_id, "_wpcufpn_settings", $settings);
                }
            }

            update_option($ver, '4.0.0');
            $option_ver = '4.0.0';
        }

        //change prefix when update version to 4.0.1
        if (version_compare($option_ver, '4.0.2', '<')) {
            $meta_key = "_wplp_settings";
            $query = $wpdb->prepare(" SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s ", $meta_key);
            $postsId = $wpdb->get_results($query);

            if (!empty($postsId)) {
                foreach ($postsId as $post) {
                    $settings = get_post_meta($post->post_id, $meta_key, true);
                    if (isset($settings['custom_css']) && $settings['custom_css'] != '') {
                        $settings['custom_css'] = str_replace('wpcufpn', 'wplp', $settings['custom_css']);
                        update_post_meta($post->post_id, "_wplp_settings", $settings);
                    }
                }
            }
            update_option($ver, '4.0.2');
        }
    }
}

?>