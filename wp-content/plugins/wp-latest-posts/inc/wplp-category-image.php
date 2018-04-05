<?php
/** WP Latest Posts Add-on category image class **/
//TODO: check if free (main) plugin is installed, generate installation error otherwise.

class WPLPCategoryImage
{
    /**
     * WPLPCategoryImage constructor.
     */
    public function __construct()
    {
        add_action('admin_init', array($this,'wplpAddonInit'));
        // save image while edit or save term
        add_action('edit_term', array($this,'wplpSaveCategoryImage'));
        add_action('create_term', array($this,'wplpSaveCategoryImage'));
        // Quick edit
        if (strpos($_SERVER['SCRIPT_NAME'], 'edit-tags.php') > 0) {
            add_action('quick_edit_custom_box', array($this,'wplpQuickEditCustomBox'), 10, 3);
        }
    }

    /**
     * Add image field to category by filter,action
     */
    public function wplpAddonInit()
    {
        $taxonomies = get_taxonomies();

        if (is_array($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy !== 'category') {
                    continue;
                }
                // Add category for taxonomy
                add_action($taxonomy.'_add_form_fields', array($this,'addImageTexonomyField'));
                add_action($taxonomy.'_edit_form_fields', array($this,'editImageTexonomyField'));
                add_filter('manage_edit-' . $taxonomy . '_columns', array($this,'taxonomyColumns'));
                add_filter('manage_' . $taxonomy . '_custom_column', array($this,'taxonomyColumn'), 10, 3);
            }
        }
    }

    /**
     * Thumbnail column added to category admin.
     */
    public function taxonomyColumns($columns)
    {
        $new_columns = array();

        if (isset($columns['cb'])) {
            $new_columns['cb'] = $columns['cb'];
            $new_columns['wplp_thumb'] = __('Image', 'wp-latest-posts');

            unset($columns['cb']);

            return array_merge($new_columns, $columns);
        }
    }

    /**
     * Thumbnail column value added to category admin.
     */
    public function taxonomyColumn($columns, $column, $id)
    {
        $style = "style='width:48px;height: 48px;'";
        // Get image of category by id
        if ($this->getImageUrl($id, 'full')) {
            $image_src = $image = $this->getImageUrl($id, 'full');
        } else {
            $image_src = plugins_url(DEFAULT_IMG, dirname(__FILE__));
        }
        //Display image for thumbnail
        if ($column == 'wplp_thumb') {
            $columns = '<span><img src="' . $image_src . '" alt="' .
                __('Thumbnail', 'wp-latest-posts') . '" '.$style.'class="wp-post-image" /></span>';
        }
        return $columns;
    }


    /**
     *  Add category image form
     */
    public function addImageTexonomyField()
    {
        // Enqueue script and style
        wp_enqueue_script(
            'wplp-category-script',
            plugins_url('/js/wplp_addon_category_image.js', dirname(__FILE__)),
            array(),
            1.0
        );
        wp_enqueue_style(
            'wplp-category-style',
            plugins_url('/css/wplp_addon_category_image.css', dirname(__FILE__)),
            array(),
            '1.0'
        );
        wp_enqueue_media();
        wp_localize_script(
            'wplp-category-script',
            'default_image',
            array('image' => plugins_url(DEFAULT_IMG, dirname(__FILE__)))
        );
        // Display form add image
        echo '<div class="form-field">';
        echo '<label for="wplp-category-image">'.__('Category image', 'wp-latest-posts').'</label>';
        echo '<input type="text" id="wplp-category-image" name="wplp-category-image" value="" />';
        echo '<input type="button" id="wplp-add-category-image"
         class="wplp-add-category-image wplp-category-image-button" value="'
            .__('Choose Image', 'wp-latest-posts').'" />';
        echo '<input type="button" id="wplp-remove-category-image"
         class="wplp-category-image-button wplp-remove-category-image" value="'
            .__('Remove Image', 'wp-latest-posts').'" />';
        echo '<p>'.__('Upload image for this category.', 'wp-latest-posts').'</p>';
        echo '</div>';
    }

    /**
     *  Edit category image form
     */
    public function editImageTexonomyField($taxonomy)
    {
        // Enqueue script and style
        wp_enqueue_script(
            'wplp-category-script',
            plugins_url('/js/wplp_addon_category_image.js', dirname(__FILE__)),
            array(),
            1.0
        );
        wp_enqueue_style(
            'wplp-category-style',
            plugins_url('/css/wplp_addon_category_image.css', dirname(__FILE__)),
            array(),
            '1.0'
        );
        wp_enqueue_media();
        wp_localize_script(
            'wplp-category-script',
            'default_image',
            array('image' => plugins_url(DEFAULT_IMG, dirname(__FILE__)))
        );

        $style = $image = "";
        // Get image of category by id
        if ($this->getImageUrl($taxonomy->term_id, 'full')) {
            $image_src = $image = $this->getImageUrl($taxonomy->term_id, 'full');
            $style = "style='max-width:300px;max-height: 300px;'";
        } else {
            $image_src = plugins_url(DEFAULT_IMG, dirname(__FILE__));
            $style = "style='width:150px;height: 150px;'";
        }
        echo '<tr class="form-field">';
        echo '<th scope="row"><label for="wplp-category-image">'.
            __('Category image', 'wp-latest-posts').'</label></th>';
        echo '<td>';
        echo '<img src="' . $image_src. '" class="wplp-category-image" '.$style.'/>';
        echo '<input type="text" id="wplp-category-image" name="wplp-category-image" value="'.$image.'" />';
        echo '<input type="button" id="wplp-add-category-image"
         class="wplp-add-category-image wplp-category-image-button" value="'
            .__('Choose Image', 'wp-latest-posts').'" />';
        echo '<input type="button" id="wplp-remove-category-image"
         class="wplp-category-image-button wplp-remove-category-image" value="'
            .__('Remove Image', 'wp-latest-posts').'" />';
        echo '<p class="description">'.__('Image for this category.', 'wp-latest-posts').'<p>';
        echo '</td>';
        echo '</tr>';
    }

    /**
     * Quick edit image in category
     * @param $column_name
     * @param $screen
     * @param $name
     */
    public function wplpQuickEditCustomBox($column_name, $screen, $name)
    {
        if ($column_name == 'wplp_thumb') {
            // Create quick edit image form
            echo '<fieldset>
                <div class="wplp_thumb inline-edit-col">
                    <label>
                        <span class="title">'.__('Thumbnail', 'wp-latest-posts').'</span>
                        <span class="input-text-wrap"><input type="text" name="wplp-category-image" value="" 
                        class="wplp-category-image-quick tax_list" /></span>
                        <span class="input-text-wrap">
                            <input type="button" id="wplp-add-category-image" 
                            class="wplp-add-category-image wplp-category-image-button" value="'
            .__('Choose Image', 'wp-latest-posts').'" />
                            <input type="button" id="wplp-remove-category-image" 
                            class="wplp-category-image-button wplp-remove-category-image" value="'
            .__('Remove Image', 'wp-latest-posts').'" />
                        </span>
                    </label>
                </div>
            </fieldset>';
        }
    }


    /**
     * @param $term_id
     * @param string $size
     * @return array|false|string
     */
    public function getImageUrl($term_id, $size = 'full')
    {
        $wplp_image = get_option('wplp_category_image');
        $image_url = $category_image_url ='';
        if (!empty($wplp_image)) {
            foreach ($wplp_image as $val) {
                if ($term_id == $val->term_id) {
                    $image_url = $val->image;
                }
            }
        }
        // Get image id from url
        if(!empty($image_url)) {
            $attachment_id = $this->getAttachmentIdByUrl($image_url);
            if(!empty($attachment_id)) {
                $category_image_url = wp_get_attachment_image_src($attachment_id, $size);
                $category_image_url = $category_image_url[0];
            }
        }

        if (!empty($category_image_url)) {
            return $category_image_url;
        } else {
            return '';
        }
    }


    /**
     * get attachment ID by image url
     * @param $image_src
     * @return null|string
     */
    public function getAttachmentIdByUrl($image_src)
    {
        global $wpdb;
        $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $image_src);
        $id = $wpdb->get_var($query);
        return (!empty($id)) ? $id : NULL;
    }
    /**
     * Save image for category when save term
     */
    public function wplpSaveCategoryImage($term_id)
    {

        $wplp_image = get_option('wplp_category_image');
        if (empty($wplp_image)) {
            $wplp_image = array();
        }
        $image = '';
        $check_addnew = true;
        if(isset($_POST['wplp-category-image'])) {
            $image = $_POST['wplp-category-image'];
        }
        //Edit
        if (!empty($wplp_image)) {
            foreach ($wplp_image as $val) {
                if ($term_id == $val->term_id) {
                    $check_addnew = false;
                    $val->image = $image;
                }
            }
        }
        if ($check_addnew) {
            $cat_img = new stdClass();
            $cat_img->term_id = $term_id;
            $cat_img->image = $image;

            array_push($wplp_image, $cat_img);
        }
        update_option('wplp_category_image', $wplp_image);
    }
}
