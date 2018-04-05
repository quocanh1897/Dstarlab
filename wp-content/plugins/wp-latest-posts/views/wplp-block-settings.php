<?php

/** WP Latest Posts views class * */
class WPLPBlockSettings
{
    /** Drop-down menu values * */
    private $pagination_values = array(
        'None',
        'Arrows',
        'Arrows with bullets',
        'Bullets'
    );

    private $thumb_img_values = array(
        'Use featured image',
        //'Use first attachment',
        'Use first image'
    );

    public $width_unit_values = array(
        '%',
        'em',
        'px'
    );

    private $field_defaults = false;

    /**
     * WPLPBlockSettings constructor.
     * @param $default
     */
    public function __construct($default)
    {
        $this->field_defaults = $default;
    }

    /**
     * Create navigation tabs in the main configuration screen
     *
     */
    public function editorTabs()
    {
        wp_nonce_field(CUSTOM_POST_NONCE_NAME, CUSTOM_POST_NONCE_NAME . '_nonce');

        //TODO: externalize js, cleanup obsolete/commented code
        ?>

        <div style="background:#fff; border: none;" class="ui-tabs ui-widget ui-widget-content ui-corner-all">

            <script type="text/javascript">
                (function ($) {
                    $(document).ready(function () {
                        $("#wplp_spinner").hide();
                        $("#wplpnavtabs").show();


                        $('#tab-1 ul.hidden').hide();

                        $('.source_type_sel').click(function (e) {
                            $(".wplp_source_type_section").hide();
                            $('#div-' + $(this).val()).show();
                        });

                        $('#div-' + $('input[name=wplp_source_type]:checked').val()).show();

                        /** You can check the all box or any other boxes, but not both **/
                        $('#cat_all').click(function (e) {
                            if ($(this).is(':checked')) {
                                $('.cat_cb').attr('checked', false);
                            }
                        });
                        $('.cat_cb').click(function (e) {
                            if ($(this).is(':checked')) {
                                $('#cat_all').attr('checked', false);
                            }
                        });


                        $('.slider').slider({
                            min: 0,
                            max: 50,
                            slide: function (event, ui) {
                                field = event.target.id.substr(7);
                                $("#" + field).val(ui.value);
                            }
                        });
                        $('.slider').each(function () {
                            var field = this.id.substr(7);
                            $(this).slider({
                                min: 0,
                                max: 50,
                                value: $("#" + field).val(),
                                slide: function (event, ui) {
                                    $("#" + field).val(ui.value);
                                }
                            });
                        });
                        $('#margin_sliders input').change(function () {
                            $('#slider_' + this.id).slider('value', $(this).val());
                        });

                        $('form').attr('enctype', 'multipart/form-data');

                    });
                })(jQuery);
                function console_log(msg) {
                    if (window.console) {
                        window.console.log(msg);
                    }
                }
            </script>
            <span class="spinner" id="wplp_spinner" style="visibility:visible;float:left;margin-top: -8px;"></span>
            <div id="wplpnavtabs" class="wplptabs" style="display: none">
                <ul class="tabs z-depth-1">
                    <li class="tab"><a href="#tab-1" id="content-source"
                                       data-tab-id="content-source">
                            <?php _e('Content source', 'wp-latest-posts'); ?></a>
                    </li>
                    <li class="tab"><a href="#tab-2" id="display-theme"
                                       data-tab-id="display-theme">
                            <?php _e('Display and theme', 'wp-latest-posts'); ?></a>
                    </li>
                    <li class="tab"><a href="#tab-3" id="image-source"
                                       data-tab-id="image-source">
                            <?php _e('Images source', 'wp-latest-posts'); ?></a>
                    </li>
                    <li class="tab"><a href="#tab-4" id="advanced"
                                       data-tab-id="advanced">
                            <?php _e('Advanced', 'wp-latest-posts'); ?></a></li>
                </ul>

                <div id="tab-1" class="metabox_tabbed_content wplptabs">
                    <?php $this->displayContentSourceTab();
                    ?>
                </div>

                <div id="tab-2" class="metabox_tabbed_content">
                    <?php $this->displayDisplayThemeTab(); ?>
                </div>

                <div id="tab-3" class="metabox_tabbed_content">
                    <?php $this->displayImageSourceTab(); ?>
                </div>

                <div id="tab-4" class="metabox_tabbed_content">
                    <?php $this->displayAdvancedTab(); ?>
                </div>

            </div>

        </div>
        <?php
    }


    /**
     * Wp Latest Posts Widget Image source Settings tab
     *
     */
    private function displayImageSourceTab()
    {
        global $post;
        $settings = get_post_meta($post->ID, '_wplp_settings', true);
        if (empty($settings)) {
            $settings = $this->field_defaults;
        }


        if (isset($settings['thumb_img'])) {
            $thumb_selected[$settings['thumb_img']] = ' selected="selected"';
        }


        $class_enabled_default = "";
        $disabled_image_postion = "";
        if (strpos($settings["theme"], 'masonry-category') !== false ||
            strpos($settings["theme"], 'masonry') !== false ||
            strpos($settings["theme"], 'smooth-effect') !== false ||
            strpos($settings["theme"], 'portfolio') !== false ||
            strpos($settings["theme"], 'timeline') !== false) {
            $class_enabled_default = " disabled";
            $disabled_image_postion = "disabled = 'disabled'";
        }

        echo '<ul class="fields">';

        /** Thumbnail image src drop-down * */
        echo '<li class="field input-field input-select width33"><label for="thumb_img" class="coltab">' .
            __('Select Image', 'wp-latest-posts') . '</label>' .
            '<select id="thumb_img" name="wplp_thumb_img">';
        foreach ($this->thumb_img_values as $value => $text) {
            echo '<option value="' . $value . '" ' .
                (isset($thumb_selected[$value]) ? $thumb_selected[$value] : '') . '>';
            echo htmlspecialchars(__($text, 'wp-latest-posts'));
            echo '</option>';
        }
        echo '</select></li>';

        /**
         * selected
         */
        $imageThumbSizeSelected = '';
        $imageMediumSizeSelected = '';
        $imageLargeSizeSelected = '';

        /**
         * fix notice when update from old version
         */
        if (!isset($settings['image_size'])) {
            $settings['image_size'] = "";
        }

        if ($settings['image_size'] == "thumbnailSize") {
            $imageThumbSizeSelected = 'selected="selected"';
        } elseif ($settings['image_size'] == "mediumSize") {
            $imageMediumSizeSelected = 'selected="selected"';
        } elseif ($settings['image_size'] == "largeSize") {
            $imageLargeSizeSelected = 'selected="selected"';
        }
        /** image Size field * */
        echo '<li class="field input-field input-select width33"><label for="thumb_width" class="coltab">' .
            __('Image size', 'wp-latest-posts') . '</label>' .
            '<select id="wplp_imageThumbSize" name="wplp_image_size">
				<option  ' . $imageThumbSizeSelected . ' value="thumbnailSize" >' .
            __('Thumbnail', 'wp-latest-posts') . '</option>

				<option  ' . $imageMediumSizeSelected . ' value="mediumSize" >' .
            __('Medium', 'wp-latest-posts') . '</option>

                <option  ' . $imageLargeSizeSelected . ' value="largeSize" >' .
            __('Large', 'wp-latest-posts') . '</option>

			</select></li>';
        /*
        * display image width for default theme
        */
        echo '<li class="field ' . $class_enabled_default . '">
        <label for="image_position_width" class="coltab wplp_tooltip" alt="' .
            __('The image width in %, used when the image is loaded on left position', 'wp-latest-posts') . '">' .
            __('Image position width', 'wp-latest-posts') . '</label>' .
            '<input id="image_position_width" ' . $disabled_image_postion .
            ' size="20" type="text" style="width:50px" name="wplp_image_position_width" value="' .
            htmlspecialchars(isset($settings['image_position_width']) ? $settings['image_position_width'] : '') .
            '" class="short-text ' . $class_enabled_default . '" />';

        /** Width units drop-down * */
        echo '</span><span class="width_settings">%</span></li>';

        /** Add an image option to load image with their full height **/
        if (!isset($settings['full_height'])) {
            $settings['full_height'] = 0;
        }
        if (isset($settings['full_height'])) {
            $full_height_checked[$settings['full_height']] = ' checked="checked"';
        }

        echo '<li class="field"><label class="coltab">' .
            __('Full height images', 'wp-latest-posts') . '</label>' .
            '<span class="radioset">' .
            '<input id="full_height1" type="radio" name="wplp_full_height" value="0" ' .
            (isset($full_height_checked[0]) ? $full_height_checked[0] : '') . '/>' .
            '<label for="full_height1">' . __('Off', 'wp-latest-posts') . '</label>' .
            '<input id="full_height2" type="radio" name="wplp_full_height" value="1" ' .
            (isset($full_height_checked[1]) ? $full_height_checked[1] : '') . '/>' .
            '<label for="full_height2">' . __('On', 'wp-latest-posts') . '</label>' .
            '</span></li>';

        do_action('wplp_displayimagesource_crop_add_fields', $settings);
        /** Sliders * */
        // -block---------------------------------- //
        echo '<div id="margin_sliders" class="wpcu-inner-admin-block with-title with-border">';
        echo '<h4>Image margin</h4>';
        echo '<ul class="fields">';
        echo '<li class="field"><label for="margin_left" class="coltab">' .
            __('Margin left', 'wp-latest-posts') . '</label>' .
            '<span id="slider_margin_left" class="slider"></span>' .
            '<input id="margin_left" type="text" name="wplp_margin_left" value="' .
            htmlspecialchars(isset($settings['margin_left']) ? $settings['margin_left'] : '') .
            '" class="short-text" /></li>';
        echo '<li class="field"><label for="margin_top" class="coltab">' .
            __('Margin top', 'wp-latest-posts') . '</label>' .
            '<span id="slider_margin_top" class="slider"></span>' .
            '<input id="margin_top" type="text" name="wplp_margin_top" value="' .
            htmlspecialchars(isset($settings['margin_top']) ? $settings['margin_top'] : '') .
            '" class="short-text" /></li>';
        echo '<li class="field"><label for="margin_right" class="coltab">' .
            __('Margin right', 'wp-latest-posts') . '</label>' .
            '<span id="slider_margin_right" class="slider"></span>' .
            '<input id="margin_right" type="text" name="wplp_margin_right" value="' .
            htmlspecialchars(isset($settings['margin_right']) ? $settings['margin_right'] : '') .
            '" class="short-text" /></li>';
        echo '<li class="field"><label for="margin_bottom" class="coltab">' .
            __('Margin bottom', 'wp-latest-posts') . '</label>' .
            '<span id="slider_margin_bottom" class="slider"></span>' .
            '<input id="margin_bottom" type="text" name="wplp_margin_bottom" value="' .
            htmlspecialchars(isset($settings['margin_bottom']) ? $settings['margin_bottom'] : '') .
            '" class="short-text" /></li>';
        echo '</ul>'; //fields
        echo '</div>'; //wpcu-inner-admin-block
        // ---------------------------------------- //

        if (!class_exists('WPLPAddonAdmin')) {
            echo '<div class="card wplp_notice light-blue notice notice-success is-dismissible below-h2">' .
                '<div class="card-content white-text">' .
                __(
                    'Additional advanced customization features are available with the optional ' .
                    '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts"
 target="_blank" >pro add-on</a>.'
                ) .
                '</div></div>';
        } else {
            do_action('wplp_imagesource_add_fields', $settings);
        }
    }


    /**
     * Wp Latest Posts Widget Advanced Settings tab
     *
     */
    private function displayAdvancedTab()
    {
        global $post;
        $settings = get_post_meta($post->ID, '_wplp_settings', true);
        if (empty($settings)) {
            $settings = $this->field_defaults;
        }
        echo '<ul class="fields">';

        echo '<li class="field"><label for="date_fmt" class="coltab">' .
            __('Date format', 'wp-latest-posts') . '</label>' .
            '<input id="date_fmt" type="text" name="wplp_date_fmt" value="' .
            htmlspecialchars(isset($settings['date_fmt']) ? $settings['date_fmt'] : '') .
            '" class="short-text" />
			<a id="wplp_dateFormat" target="_blank" href="http://php.net/manual/en/function.date.php"> ' .
            __('Date format', 'wp-latest-posts') . ' </a>
			</li>';

        echo '<li class="field"><label for="text_content" class="coltab">' .
            __('Text Content', 'wp-latest-posts') . '</label>' .
            '<select name="wplp_text_content" class="browser-default" id="text_content">' .
            '<option value="0" ' . ((isset($settings['text_content']) &&
                $settings['text_content'] == "0") ? "selected" : '') .
            ' class="short-text">' . __('Full content', 'wp-latest-posts') . '</option>' .
            '<option value="1" ' . ((isset($settings['text_content']) &&
                $settings['text_content'] == "1") ? "selected" : '') .
            ' class="short-text">' . __('Excerpt content', 'wp-latest-posts') . '</option>' .
            '</select> </li>';

        echo '<li class="field"><label for="no_post_text" class="coltab">' .
            __('No post text', 'wp-latest-posts') . '</label>' .
            '<input id="no_post_text" type="text" name="wplp_no_post_text" value="' .
            htmlspecialchars(isset($settings['no_post_text']) ? $settings['no_post_text'] : '') .
            '" class="short-text" /></li>';


        echo '</ul>'; //fields

        echo '<hr/><div><label for="custom_css" class="coltab" style="vertical-align:top">' .
            __('Custom CSS', 'wp-latest-posts') . '</label>' .
            '<textarea id="wplp_custom_css" cols="100" rows="5" name="wplp_custom_css">' .
            (isset($settings['custom_css']) ? $settings['custom_css'] : '') . '</textarea></div>';

        if (class_exists('WPLPAddonAdmin')) {
            do_action('wplp_displayadvanced_add_fields', $settings);
        }

        if (isset($post->ID) && isset($post->post_title) && (!empty($post->post_title))) {
            echo '<hr style="clear:both"/><div><label for="phpCodeInsert" class="coltab" style="margin:10px 0 5px">' .
                __('Copy & paste this code into a template file to display this WPLP block', 'wp-latest-posts') .
                '</label>' .
                '<br><textarea readonly id="phpCodeInsert" cols="100" rows="2" name="wplp_phpCodeInsert">' .
                'echo do_shortcode(\'[frontpage_news widget="' . $post->ID . '" name="' .
                $post->post_title . '"]\');' . '</textarea></div>';
        }

        if (!class_exists('WPLPAddonAdmin')) {
            echo '<div class="card wplp_notice light-blue notice notice-success is-dismissible below-h2">' .
                '<div class="card-content white-text">';
            echo '<p>' . __('Looking out for more advanced features?', 'wp-latest-posts') . '</p>';
            echo '<p>' . __('Check out our optional <a 
href="http://www.joomunited.com/wordpress-products/wp-latest-posts" 
target="_blank" style="text-decoration:none;s">Pro add-on</a>.
', 'wp-latest-posts') . '</p>';
            echo '</div></div>';
        }
    }


    /**
     * Wp Latest Posts Widget Display and theme Settings tab
     *
     */
    private function displayDisplayThemeTab()
    {
        global $post;
        $settings = get_post_meta($post->ID, '_wplp_settings', true);
        if (empty($settings)) {
            $settings = $this->field_defaults;
        }
        if (isset($settings['show_title'])) {
            $show_title_checked[$settings['show_title']] = ' checked="checked"';
        }
        if (isset($settings['pagination'])) {
            $pagination_selected[$settings['pagination']] = ' selected="selected"';
        }
        if (isset($settings['total_width_unit'])) {
            $units_selected[$settings['total_width_unit']] = ' selected="selected"';
        }
        /*
         *
         * Specific parameters with Mansonry
         *
         */
        $classdisabled = "";
        if (strpos($settings["theme"], 'masonry') || strpos($settings["theme"], 'portfolio')) {
            $classdisabled = " disabled";
        }

        $classdisabledsmooth = "";
        if (strpos($settings["theme"], 'timeline')) {
            $classdisabledsmooth = " disabled";
        }

        echo '<div class="wpcu-inner-admin-col">';

        // -block---------------------------------- //
        echo '<div class="wpcu-inner-admin-block">';
        echo '<ul class="fields">';

        /** Show title radio button set * */
        echo '<li class="field"><label class="coltab">' . __('Show title', 'wp-latest-posts') . '</label>' .
            '<span class="radioset">' .
            '<input id="show_title1" type="radio" name="wplp_show_title" value="0" ' .
            (isset($show_title_checked[0]) ? $show_title_checked[0] : '') . '/>' .
            '<label for="show_title1">' . __('Off', 'wp-latest-posts') . '</label>' .
            '<input id="show_title2" type="radio" name="wplp_show_title" value="1" ' .
            (isset($show_title_checked[1]) ? $show_title_checked[1] : '') . '/>' .
            '<label for="show_title2">' . __('On', 'wp-latest-posts') . '</label>' .
            '</span>';
        echo '</li>';

        /*
         * display number of columns
         */
        echo '<li class="field ' . $classdisabledsmooth . '">
<label for="   amount_cols" class="coltab">' .
            __('Number of columns', 'wp-latest-posts') . '</label>' .
            '<input id="amount_cols" type="text" name="wplp_amount_cols" value="' .
            htmlspecialchars(isset($settings['amount_cols']) ? $settings['amount_cols'] : '3') .
            '" class="short-text" ' . $classdisabledsmooth . '/></li>';
        /*
         * display number of rows
         */
        echo '<li class="field ' . $classdisabled . $classdisabledsmooth . '">
<label for="amount_rows" class="coltab">' .
            __('Number of rows', 'wp-latest-posts') . '</label>' .
            '<input id="amount_rows" type="text" name="wplp_amount_rows" value="' .
            htmlspecialchars(isset($settings['amount_rows']) ? $settings['amount_rows'] : '') .
            '" class="short-text" ' . $classdisabled . $classdisabledsmooth . '/></li>';

        /** Pagination drop-down * */
        echo '<li class="field ' . $classdisabled . $classdisabledsmooth . '">
<label for="pagination" class="coltab">' . __('Pagination', 'wp-latest-posts') . '</label>' .
            '<select id="pagination" name="wplp_pagination" class="browser-default ' .
            $classdisabled . $classdisabledsmooth . '" >';
        foreach ($this->pagination_values as $value => $text) {
            echo '<option value="' . $value . '" ' .
                (isset($pagination_selected[$value]) ? $pagination_selected[$value] : '') . '>';
            echo htmlspecialchars(__($text, 'wp-latest-posts'));
            echo '</option>';
        }
        echo '</select></li>';
        /*
         * display max elements
         */
        echo '<li class="field"><label for="max_elts" class="coltab">' .
            __('Max number of elements', 'wp-latest-posts') . '</label>' .
            '<input id="max_elts" type="text" name="wplp_max_elts" value="' .
            htmlspecialchars(isset($settings['max_elts']) ? $settings['max_elts'] : '') .
            '" class="short-text" /></li>';
        /*
         * display total width
         */
        echo '<li class="field"><label for="total_width" class="coltab">' .
            __('Total width', 'wp-latest-posts') . '</label>' .
            '<input id="total_width" type="text" name="wplp_total_width" value="' .
            htmlspecialchars(isset($settings['total_width']) ? (int)$settings['total_width'] : '') .
            '" class="short-text" />';

        /** Width units drop-down * */
        echo '<select id="total_width_unit" class="browser-default" name="wplp_total_width_unit">';
        foreach ($this->width_unit_values as $value => $text) {
            echo '<option value="' . (isset($value) ? $value : '') . '" ' .
                (isset($units_selected[$value]) ? $units_selected[$value] : '') . '>' .
                $text . '</option>';
        }
        echo '</select></li>';
        /** offset number posts to skip */
        echo '<li class="field"><label for="off_set" class="coltab">' .
            __('Number of posts to skip:', 'wp-latest-posts') . '</label>' .
            '<input id="off_set" type="text" name="wplp_off_set" value="' .
            htmlspecialchars(isset($settings['off_set']) ? (int)$settings['off_set'] : '') . '" class="short-text" />';


        do_action('wplp_display_button_loadmore', $settings);

        do_action('wplp_display_force_hover_icon', $settings);

        do_action('wplp_display_open_link', $settings);

        echo '<div class="on_icon_selector">';
        do_action('wplp_display_icon_selector', $settings);
        echo '</div>';
        do_action('wplp_displayandtheme_add_fields', $settings);
        echo '</ul>'; //fields
        echo '</div>'; //wpcu-inner-admin-block
        // ---------------------------------------- //

        if (class_exists('WPLPAddonAdmin')) {
            do_action('wplp_displaytheme_col1_add_fields', $settings);
        }

        echo '</div>'; //wpcu-inner-admin-col
        echo '<div class="wpcu-inner-admin-col">';

        if (isset($settings['theme'])) {
            $theme_selected[$settings['theme']] = ' selected="selected"';
        }
        // -block---------------------------------- //
        echo '<div class="wpcu-inner-admin-block with-title with-border">';
        echo '<h4>Theme choice and preview</h4>';
        echo '<ul class="fields">';

        /** Theme drop-down * */
        echo '<li class="field input-field input-select">
<label for="theme" class="coltab">' . __('Theme', 'wp-latest-posts') . '</label>' .
            '<select id="theme" name="wplp_theme">';
        $all_themes = (array)WPLPAdmin::themeLister();
        wp_localize_script('wplp-back', 'themes', $all_themes);
        //var_dump( $all_themes );	//Debug
        foreach ($all_themes as $dir => $theme) {
            echo '<option  value="' . $dir . '" ' . (isset($theme_selected[$dir]) ? $theme_selected[$dir] : '') . '>';
            echo $theme['name'];
            echo '</option>';
        }
        echo '</select></li>';

        echo '</ul>'; //fields
        echo '<div class="wplp-theme-preview">';

        /** enforce default (first found theme) * */
        if (!isset($settings['theme']) || 'default' == $settings['theme']) {
            reset($all_themes);
            $settings['theme'] = key($all_themes);
        }

        if (isset($all_themes[$settings['theme']]['theme_url'])) {
            $screenshot_file_url = $all_themes[$settings['theme']]['theme_url'] . '/screenshot.png';
            $screenshot_file_path = $all_themes[$settings['theme']]['theme_root'] . '/screenshot.png';
        }
        if (isset($screenshot_file_path) && file_exists($screenshot_file_path)) {
            echo '<img alt="preview" src="' . $screenshot_file_url .
                '" style="width:100%;height:100%;" />';
        }
        echo '</div>';
        echo '</div>'; //wpcu-inner-admin-block
        /**
         * check WPLP Block
         */
        include_once(dirname(plugin_dir_path(__FILE__)) . '/themes/default/default.php');

        if (!class_exists('WPLPAddonAdmin')) {
            echo '<div class="card wplp_notice light-blue notice notice-success is-dismissible below-h2">' .
                '<div class="card-content white-text">' .
                __(
                    'Additional advanced customization features<br/> and various beautiful ' .
                    'pre-configured templates and themes<br/> are available with the optional ' .
                    '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts" 
                        target="_blank" >pro add-on</a>.'
                ) .
                '</div></div>';
        }
    }

    /**
     * Wp Latest Posts Widget Content source Settings tab
     *
     */
    private function displayContentSourceTab()
    {
        global $post;
        if (!function_exists('pll_languages_list') && function_exists('icl_object_id')) {
            $active_languages = apply_filters('wpml_active_languages', null, 'orderby=name&order=asc');
        }
        $poly_languages = array();
        if (function_exists('pll_languages_list')) {
            foreach (pll_languages_list(array('fields' => 'slug')) as $pll_language) {
                $code = $pll_language;
                if (strpos($code, '_') != false) {
                    $code = substr($code, 0, strpos($code, '_'));
                }
                $poly_languages[$code] = $pll_language;
            }
        }
        $settings = get_post_meta($post->ID, '_wplp_settings', true);
        if (empty($settings)) {
            $settings = $this->field_defaults;
        }

        if (!isset($settings['source_type']) || !$settings['source_type']) {
            $settings['source_type'] = 'src_category';
        }
        $source_type_checked[$settings['source_type']] = ' checked="checked"';

        $selected_content_language = '';
        if (isset($settings['content_language'])) {
            $selected_content_language = $settings['content_language'];
        }

        $tabs = array(
            'tab-1-0' => array(
                'id' => 'tab-src_category_list',
                'name' => __('Category list', 'wp-latest-posts'),
                'value' => 'src_category_list',
                'method' => array($this, 'displayContentSourceCategoryListTab')
            ),
            'tab-1-1' => array(
                'id' => 'tab-src_category',
                'name' => __('Posts from category', 'wp-latest-posts'),
                'value' => 'src_category',
                'method' => array($this, 'displayContentSourceCategoryTab')
            ),
            'tab-1-2' => array(
                'id' => 'tab-src_page',
                'name' => __('Pages', 'wp-latest-posts'),
                'value' => 'src_page',
                'method' => array($this, 'displayContentSourcePageTab')
            )
        );
        $tabs = apply_filters('wplp_src_type', $tabs);
        ?>
        <!--<ul class="hidden">
        <?php foreach ($tabs as $tabhref => $tab) : ?>
                                    <li><a href="#<?php echo $tabhref; ?>"
                                    id="<?php echo $tab['id']; ?>"><?php echo $tab['name']; ?></a></li>
        <?php endforeach; ?>
                        </ul>-->
        <?php if (function_exists('icl_object_id') || function_exists('pll_languages_list')) : ?>
        <!-- CHECK WPML or Polylang is INSTALLED AND ACTIVED -->
        <?php if (!empty($active_languages)) : ?>
            <div class="content-source-language">
                <label for="content_language"
                       class="content-language-label"><?php _e('Content language', 'wp-latest-posts'); ?></label>
                <select id="content_language" class="content-language-select browser-default"
                        name="wplp_content_language">
                    <?php foreach ($active_languages as $k => $languages) :
                        $check = ($settings['content_language'] == $k) ? ' selected="selected"' : '';
                        ?>
                        <option value="<?php echo $k; ?>" <?php echo $check; ?>>
                            <?php echo apply_filters(
                                'wpml_display_language_names',
                                null,
                                $languages['native_name'],
                                $languages['translated_name']
                            ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <hr>
        <?php elseif (!empty($poly_languages)) : ?>
            <div class="content-source-language">
                <label for="content_language"
                       class="content-language-label"><?php _e('Content language', 'wp-latest-posts'); ?></label>
                <select id="content_language" class="content-language-select browser-default"
                        name="wplp_content_language">
                    <?php foreach ($poly_languages as $code => $languages) :
                        $check = ($settings['content_language'] == $code) ? ' selected="selected"' : '';
                        ?>
                        <option value="<?php echo $code; ?>" <?php echo $check; ?>><?php echo $languages; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <hr>
        <?php endif; ?>
        <?php endif; ?>
        <input type="hidden" value="<?php echo $selected_content_language; ?>" id="selected_content_language">
        <input type="hidden" value="" id="selected_source_type"/>
        <ul class="horizontal">
            <?php $idx = 0; ?>
            <?php foreach ($tabs as $tabhref => $tab) : ?>
                <li><input type="radio" name="wplp_source_type" id="sct<?php echo ++$idx; ?>"
                           value="<?php echo $tab['value']; ?>"
                           class="source_type_sel with-gap"
                        <?php echo(isset($source_type_checked[$tab['value']]) ?
                            $source_type_checked[$tab['value']] : ""); ?> />
                    <label for="sct<?php echo $idx; ?>" class="post_radio"><?php echo $tab['name']; ?></label></li>
            <?php endforeach; ?>
        </ul>

        <?php foreach ($tabs as $tabhref => $tab) : ?>
        <div id="div-<?php echo $tab['value']; ?>" class="wplp_source_type_section">
            <?php call_user_func($tab['method']); ?>
        </div>
        <?php endforeach; ?>

        <?php
    }

    /**
     * Content source tab for categories list
     */
    private function displayContentSourceCategoryListTab()
    {
        global $post;
        $source_cat_list_checked = array();
        $settings = get_post_meta($post->ID, '_wplp_settings', true);
        if (empty($settings)) {
            $settings = $this->field_defaults;
        }
        if (!isset($settings['source_category_list']) ||
            empty($settings['source_category_list']) ||
            !$settings['source_category_list']) {
            $settings['source_category_list'] = array('_all');
        }

        foreach ($settings['source_category_list'] as $cat_list) {
            $source_cat_list_checked[$cat_list] = ' checked="checked"';
        };

        if (isset($settings['cat_list_source_order'])) {
            $source_catlist_order_selected[$settings['cat_list_source_order']] = ' selected="selected"';
        }
        if (isset($settings['cat_list_source_asc'])) {
            $source_catlist_asc_selected[$settings['cat_list_source_asc']] = ' selected="selected"';
        }

        if (is_multisite()) {
            if (!isset($settings['mutilsite_cat_list']) ||
                empty($settings['mutilsite_cat_list']) ||
                !$settings['mutilsite_cat_list']) {
                $settings['mutilsite_cat_list'] = "";
            }
            $all_blog = get_sites();
            echo '<ul>';
            echo '<li class="field "> 
                        <div class="width33 input-field input-select">
			            <label for="mutilsite_cat_list_select" class="cat_list_cb">' .
                __('Multisite selection', 'wp-latest-posts') . ' : </label>		
		                <select id="mutilsite_cat_list_select" class="mutilsite_select" name="wplp_mutilsite_cat_list">
				             <option value="all_blog">' . __('All blog', 'wp-latest-posts') . '</option>' . '';
            foreach ($all_blog as $val) {
                $detail = get_blog_details((int)$val->blog_id);
                $check = ($settings['mutilsite_cat_list'] == $val->blog_id) ? ' selected="selected"' : '';
                echo '<option value="' . $val->blog_id . '" ' . $check . '> ' . $detail->blogname . ' </option>';
            }
            echo '</select></div></li>';
            echo '</ul>';
        }
        $mutilsite_selected_post = '';
        if (isset($settings['mutilsite_cat_list'])) {
            $mutilsite_selected_post = $settings['mutilsite_cat_list'];
        }

        echo '<input type="hidden" value="' . $mutilsite_selected_post . '" id="selected_multisite_cat_list_post_type" />';


        echo '<ul class="fields">';

        echo '<li class="field catlistcat">';
        echo '<ul  class="catlist_field">';
        echo '<li><input id="cat_list_all" type="checkbox" name="wplp_source_category_list[]" 
                value="_all" ' . (isset($source_cat_list_checked['_all']) ? $source_cat_list_checked['_all'] : '') . ' />' .
            '<label for="cat_list_all" class="cat_list_cb">All</li>';
        if (is_multisite()) {
            if ('all_blog' == $settings['mutilsite_cat_list']) {
                $blogs = get_sites();
                foreach ($blogs as $blog) {
                    switch_to_blog((int)$blog->blog_id);
                    $allcats = get_categories(array('hide_empty' => false));
                    if (isset($settings['content_language'])) {
                        $allcats = apply_filters('wplp_get_category_by_language', $allcats, $settings['content_language']);
                    }
                    foreach ($allcats as $allcat) {
                        $allcat->blog = (int)$blog->blog_id;
                        $cats[] = $allcat;
                    }
                    restore_current_blog();
                }
            } else {
                switch_to_blog((int)$settings['mutilsite_cat_list']);
                $cats = get_categories(array('hide_empty' => false));
                if (isset($settings['content_language'])) {
                    $cats = apply_filters('wplp_get_category_by_language', $cats, $settings['content_language']);
                }
                foreach ($cats as $cat) {
                    $cat->blog = (int)$settings['mutilsite_cat_list'];
                }

                restore_current_blog();
            }

            foreach ($cats as $k => $cat) {
                echo '<li><input id="cl_' . $k . '" type="checkbox" name="wplp_source_category_list[]" value="' .
                    $k . '_' . $cat->term_id . '_blog' . $cat->blog .'" ' .
                    (isset($source_cat_list_checked[$k . '_' . $cat->term_id . '_blog' . $cat->blog ]) ?
                        $source_cat_list_checked[$k . '_' . $cat->term_id . '_blog' . $cat->blog ] : "") .
                    ' class="cat_list_cb" />';
                echo '<label for="cl_' . $k . '" class="cat_list_cb">' . $cat->name . '</label></li>';
            }
        } else {
            $cats = get_categories(array('hide_empty' => false));

            if (isset($settings['content_language'])) {
                $cats = apply_filters('wplp_get_category_by_language', $cats, $settings['content_language']);
            }

            foreach ($cats as $k => $cat) {
                echo '<li><input id="cl_' . $k . '" type="checkbox" name="wplp_source_category_list[]" value="' .
                    $cat->term_id . '" ' .
                    (isset($source_cat_list_checked[$cat->term_id]) ? $source_cat_list_checked[$cat->term_id] : "") .
                    ' class="cat_list_cb" />';
                echo '<label for="cl_' . $k . '" class="cat_list_cb">' . $cat->name . '</label></li>';
            }
        }
        echo '</ul></li>';

        echo '<li class="order_field field input-field input-select">';
        echo '<label for="cat_list_source_order" class="coltab">' . __('Order by', 'wp-latest-posts') . '</label>';
        echo '<select name="wplp_cat_list_source_order" id="cat_list_source_order" >';
        echo '<option value="id" ' .
            (isset($source_catlist_order_selected['id']) ? $source_catlist_order_selected['id'] : "") . '>' .
            __('By id', 'wp-latest-posts') . '</option>';
        echo '<option value="name" ' .
            (isset($source_catlist_order_selected['name']) ? $source_catlist_order_selected['name'] : "") . '>' .
            __('By name', 'wp-latest-posts') . '</option>';
        echo '<option value="description" ' .
            (isset($source_catlist_order_selected['description']) ? $source_catlist_order_selected['description'] : "") .
            '>' . __('By description', 'wp-latest-posts') . '</option>';

        echo '</select>';
        echo '</li>'; //field

        echo '<li class="order_dir field input-field input-select">';
        echo '<label for="cat_list_source_asc" class="coltab">' .
            __('Posts sort order', 'wp-latest-posts') . '</label>';
        echo '<select name="wplp_cat_list_source_asc" id="cat_list_source_asc">';
        echo '<option value="asc" ' .
            (isset($source_catlist_asc_selected['asc']) ? $source_catlist_asc_selected['asc'] : "") . '>' .
            __('Ascending', 'wp-latest-posts') . '</option>';
        echo '<option value="desc" ' .
            (isset($source_catlist_asc_selected['desc']) ? $source_catlist_asc_selected['desc'] : "") . '>' .
            __('Descending', 'wp-latest-posts') . '</option>';
        echo '</select>';
        echo '</li>'; //field
        echo '</ul>'; //fields

        // Notifications
        if (!class_exists('WPLPAddonAdmin')) {
            echo '<div class="card wplp_notice light-blue notice notice-success is-dismissible below-h2" >' .
                '<div class="card-content white-text">' .
                __(
                    'Additional content source options are available with the optional ' .
                    '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts" 
                    target="_blank" >pro addon</a>.'
                ) .
                '</div></div>';
        }

    }

    /**
     * Content source tab for post categories
     *
     */
    private function displayContentSourceCategoryTab()
    {
        global $post;
        $source_cat_checked = array();
        $settings = get_post_meta($post->ID, '_wplp_settings', true);
        if (empty($settings)) {
            $settings = $this->field_defaults;
        }
        if (!isset($settings['source_category']) ||
            empty($settings['source_category']) ||
            !$settings['source_category']) {
            $settings['source_category'] = array('_all');
        }
        foreach ($settings['source_category'] as $cat) {
            $source_cat_checked[$cat] = ' checked="checked"';
        };

        if (isset($settings['cat_post_source_order'])) {
            $source_order_selected[$settings['cat_post_source_order']] = ' selected="selected"';
        }
        if (isset($settings['cat_post_source_asc'])) {
            $source_asc_selected[$settings['cat_post_source_asc']] = ' selected="selected"';
        }
        if (is_multisite()) {
            if (!isset($settings['mutilsite_cat']) ||
                empty($settings['mutilsite_cat']) ||
                !$settings['mutilsite_cat']) {
                $settings['mutilsite_cat'] = "";
            }

            $all_blog = get_sites();
            echo '<ul>';
            echo '<li class="field "> 
                        <div class="width33 input-field input-select">
			            <label for="mutilsite_select_post" class="post_cb">' .
                __('Multisite selection', 'wp-latest-posts') . ' : </label>		
		                <select id="mutilsite_select_post" class="mutilsite_select" name="wplp_mutilsite_cat">
				             <option value="all_blog">' . __('All blog', 'wp-latest-posts') . '</option>' . '';
            foreach ($all_blog as $val) {
                $detail = get_blog_details((int)$val->blog_id);
                $check = ($settings['mutilsite_cat'] == $val->blog_id) ? ' selected="selected"' : '';
                echo '<option value="' . $val->blog_id . '" ' . $check . '> ' . $detail->blogname . ' </option>';
            }
            echo '</select></div></li>';
            echo '</ul>';
        }
        $mutilsite_selected_post = '';
        if (isset($settings['mutilsite_cat'])) {
            $mutilsite_selected_post = $settings['mutilsite_cat'];
        }

        echo '<input type="hidden" value="' . $mutilsite_selected_post . '" id="selected_multisite_post_type" />';
        echo '<ul class="fields">';

        echo '<li class="field postcat">';
        echo '<ul  class="post_field">';
        echo '<li><input id="cat_all" type="checkbox" name="wplp_source_category[]" 
value="_all" ' . (isset($source_cat_checked['_all']) ? $source_cat_checked['_all'] : '') . ' />' .
            '<label for="cat_all" class="post_cb">All</li>';

        if (is_multisite()) {
            if ('all_blog' == $settings['mutilsite_cat']) {
                $blogs = get_sites();
                foreach ($blogs as $blog) {
                    switch_to_blog((int)$blog->blog_id);
                    $allcats = get_categories(array('hide_empty' => false));
                    if (isset($settings['content_language'])) {
                        $allcats = apply_filters(
                            'wplp_get_category_by_language',
                            $allcats,
                            $settings['content_language']
                        );
                    }
                    foreach ($allcats as $allcat) {
                        $cats[] = $allcat;
                    }
                    restore_current_blog();
                }
            } else {
                switch_to_blog((int)$settings['mutilsite_cat']);
                $cats = get_categories(array('hide_empty' => false));
                if (isset($settings['content_language'])) {
                    $cats = apply_filters('wplp_get_category_by_language', $cats, $settings['content_language']);
                }
                restore_current_blog();
            }

            foreach ($cats as $k => $cat) {
                echo '<li><input id="ccb_' . $k . '" type="checkbox" name="wplp_source_category[]" value="' . $k . '_' .
                    $cat->term_id . '" ' .
                    (isset($source_cat_checked[$k . '_' . $cat->term_id]) ?
                        $source_cat_checked[$k . '_' . $cat->term_id] : "") .
                    ' class="post_cb" />';
                echo '<label for="ccb_' . $k . '" class="post_cb">' . $cat->name . '</label></li>';
            }
        } else {
            $cats = get_categories(array('hide_empty' => false));
            if (isset($settings['content_language'])) {
                $cats = apply_filters('wplp_get_category_by_language', $cats, $settings['content_language']);
            }

            foreach ($cats as $k => $cat) {
                echo '<li><input id="ccb_' . $k . '" type="checkbox" name="wplp_source_category[]" value="' .
                    $cat->term_id . '" ' .
                    (isset($source_cat_checked[$cat->term_id]) ? $source_cat_checked[$cat->term_id] : "") .
                    ' class="post_cb" />';
                echo '<label for="ccb_' . $k . '" class="post_cb">' . $cat->name . '</label></li>';
            }
        }

        echo '</ul></li>';
        if (class_exists('WPLPAddonAdmin') && is_plugin_active('advanced-custom-fields/acf.php')) {
            $display = false;
            $rule_customs = apply_filters('wplp_get_rules_custom_fields', $settings);
            foreach ($rule_customs as $rule_custom) {
                foreach ($rule_custom as $rule) {
                    if ('post' == $rule['value'] && '==' == $rule['operator']) {
                        $display = true;
                    }
                }
            }
            //Advanced custom fields
            if ($display) {
                do_action('wplp_display_advanced_custom_fields', $settings, 'post');
            } else {
                echo '<li><input type="hidden" name="wplp_advanced_custom_fields" value=""/></li>';
            }
        }
        echo '<li class="order_field field input-field input-select">';
        echo '<label for="cat_post_source_order" class="coltab">' . __('Order by', 'wp-latest-posts') . '</label>';
        echo '<select name="wplp_cat_post_source_order" id="cat_post_source_order" >';
        echo '<option value="date" ' .
            (isset($source_order_selected['date']) ? $source_order_selected['date'] : "") .
            '>' . __('By date', 'wp-latest-posts') . '</option>';
        echo '<option value="title" ' .
            (isset($source_order_selected['title']) ? $source_order_selected['title'] : "") . '>' .
            __('By title', 'wp-latest-posts') . '</option>';
        echo '<option value="random" ' .
            (isset($source_order_selected['random']) ? $source_order_selected['random'] : "") .
            '>' . __('By random', 'wp-latest-posts') . '</option>';
        //echo '<option value="order" ' . $source_order_selected['order'] .
        // '>' . __( 'By order', 'wp-latest-posts' ) . '</option>';
        echo '</select>';
        echo '</li>'; //field

        echo '<li class="order_dir field input-field input-select">';
        echo '<label for="cat_post_source_asc" class="coltab">' .
            __('Posts sort order', 'wp-latest-posts') . '</label>';
        echo '<select name="wplp_cat_post_source_asc" id="cat_post_source_asc">';
        echo '<option value="asc" ' .
            (isset($source_asc_selected['asc']) ? $source_asc_selected['asc'] : "") . '>' .
            __('Ascending', 'wp-latest-posts') . '</option>';
        echo '<option value="desc" ' .
            (isset($source_asc_selected['desc']) ? $source_asc_selected['desc'] : "") . '>' .
            __('Descending', 'wp-latest-posts') . '</option>';
        echo '</select>';
        echo '</li>'; //field

        if (class_exists('WPLPAddonAdmin')) {
            do_action('wplp_source_category_add_fields', $settings);
            do_action('wplp_source_category_add_content_includesion', $settings);
        }
        echo '</ul>'; //fields

        // Notifications
        if (!class_exists('WPLPAddonAdmin')) {
            echo '<div class="card wplp_notice light-blue notice notice-success is-dismissible below-h2" >' .
                '<div class="card-content white-text">' .
                __(
                    'Additional content source options are available with the optional ' .
                    '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts" 
                    target="_blank" >pro addon</a>.'
                ) .
                '</div></div>';
        }
    }


    /**
     * Content source tab for pages
     *
     */
    private function displayContentSourcePageTab()
    {
        global $post;
        $settings = get_post_meta($post->ID, '_wplp_settings', true);
        if (empty($settings)) {
            $settings = $this->field_defaults;
        }
        if (isset($settings['pg_source_order'])) {
            $source_order_selected[$settings['pg_source_order']] = ' selected="selected"';
        }
        if (isset($settings['pg_source_asc'])) {
            $source_asc_selected[$settings['pg_source_asc']] = ' selected="selected"';
        }
        if (is_multisite()) {
            if (!isset($settings['mutilsite_page']) ||
                empty($settings['mutilsite_page']) ||
                !$settings['mutilsite_page']) {
                $settings['mutilsite_page'] = "";
            }

            $all_blog = get_sites();
            echo '<ul>';
            echo '<li class="field "> 
                        <div class="width33 input-field input-select">
			            <label for="mutilsite_select_page" class="page_cb">' .
                __('Multisite selection', 'wp-latest-posts') . ' : </label>		
		                <select id="mutilsite_select_page" class="mutilsite_select" name="wplp_mutilsite_page">
				             <option value="all_blog">' . __('All blog', 'wp-latest-posts') . '</option>' . '';
            foreach ($all_blog as $val) {
                $detail = get_blog_details((int)$val->blog_id);
                $check = ($settings['mutilsite_page'] == $val->blog_id) ? ' selected="selected"' : '';
                echo '<option value="' . $val->blog_id . '" ' . $check . '> ' . $detail->blogname . ' </option>';
            }
            echo '</select></div></li>';
            echo '</ul>';
        }
        $mutilsite_selected_page = '';
        if (isset($settings['mutilsite_page'])) {
            $mutilsite_selected_page = $settings['mutilsite_page'];
        }
        echo '<input type="hidden" value="' . $mutilsite_selected_page . '" id="selected_multisite_page_type" />';
        echo '<ul class="fields">';

        echo '<li class="field pagecat">';
        echo '<ul class="page_field">';

        if (!class_exists('WPLPAddonAdmin')) {
            echo '<li><input id="pages_all" type="checkbox" name="wplp_source_pages[]"
            value="_all" checked="checked"  disabled="disabled" />' .
                '<label for="pages_all" class="post_cb">All</li>';
        } else {
            do_action('wplp_source_page_add_fields', $settings);
        }

        echo '</ul>';
        if (class_exists('WPLPAddonAdmin') && is_plugin_active('advanced-custom-fields/acf.php')) {
            $display = false;
            $rule_customs = apply_filters('wplp_get_rules_custom_fields', $settings);
            foreach ($rule_customs as $rule_custom) {
                foreach ($rule_custom as $rule) {
                    if ('page' == $rule['value'] && '==' == $rule['operator']) {
                        $display = true;
//
                    }
                }
            }
            //Advanced custom fields
            if ($display) {
                do_action('wplp_display_advanced_custom_fields', $settings, 'page');
            } else {
                echo '<li><input type="hidden" name="wplp_advanced_custom_fields_page" value=""/></li>';
            }
        }
        echo '</li>'; //field

        echo '<li class="field order_field input-field input-select">';
        echo '<label for="pg_source_order" class="coltab">' . __('Order by', 'wp-latest-posts') . '</label>';
        echo '<select name="wplp_pg_source_order" id="pg_source_order" >';
        echo '<option value="order" ' .
            (isset($source_order_selected['order']) ? $source_order_selected['order'] : "") . '>' .
            __('By order', 'wp-latest-posts') . '</option>';
        echo '<option value="title" ' .
            (isset($source_order_selected['title']) ? $source_order_selected['title'] : "") . '>' .
            __('By title', 'wp-latest-posts') . '</option>';
        echo '<option value="date" ' .
            (isset($source_order_selected['date']) ? $source_order_selected['date'] : "") . '>' .
            __('By date', 'wp-latest-posts') . '</option>';
        echo '<option value="random" ' .
            (isset($source_order_selected['random']) ? $source_order_selected['random'] : "") . '>' .
            __('By random', 'wp-latest-posts') . '</option>';
        echo '</select>';
        echo '</li>'; //field

        echo '<li class="order_dir field input-field input-select">';
        echo '<label for="pg_source_asc" class="coltab">' . __('Pages sort order', 'wp-latest-posts') . '</label>';
        echo '<select name="wplp_pg_source_asc" id="pg_source_asc">';
        echo '<option value="asc" ' .
            (isset($source_asc_selected['asc']) ? $source_asc_selected['asc'] : "") . '>' .
            __('Ascending', 'wp-latest-posts') . '</option>';
        echo '<option value="desc" ' .
            (isset($source_asc_selected['desc']) ? $source_asc_selected['desc'] : "") . '>' .
            __('Descending', 'wp-latest-posts') . '</option>';
        echo '</select>';
        echo '</li>'; //field
        if (!class_exists('WPLPAddonAdmin')) {
            echo '<li><div class="card wplp_notice light-blue notice notice-success is-dismissible below-h2">' .
                '<div class="card-content white-text">' .
                __(
                    'Additional content source options are available with the optional ' .
                    '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts"
                    target="_blank" >pro addon</a>.'
                ) .
                '</div></div></li>';
        }
        echo '</ul>'; //fields
    }
}
