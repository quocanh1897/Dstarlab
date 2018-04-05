<?php
/**
 * Plugin Name: WP Latest Posts
 * Plugin URI: http://www.joomunited.com/wordpress-products/wp-latest-posts
 * Description: Advanced frontpage and widget news slider
 * Version: 4.3.0
 * Text Domain: wp-latest-posts
 * Domain Path: /languages
 * Author: JoomUnited
 * Author URI: http://www.joomunited.com
 * License: GPL2
 */

/**
 * @copyright 2014  Joomunited  ( email : contact _at_ joomunited.com )
 *
 *  Original development of this plugin was kindly funded by Joomunited
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
define('CUSTOM_POST_NEWS_WIDGET_NAME', 'wplp-news-widget');
define('CUSTOM_POST_NONCE_NAME', 'wplp_editor_tabs');
define('POSITIVE_INT_GT1', 'positive_integer_1+');  //Those fields need to have a positive integer value greater than 1
define('BOOL', 'bool');       //Booleans
define('FILE_UPLOAD', 'file_upload');    //File uploads
define('LI_TO_ARRAY', 'li_to_array');    //Convert sortable lists to array
define('DEFAULT_IMG_PREFIX', 'wplp_default_img_');  //Default uploaded image file prefix
define('MAIN_FRONT_STYLESHEET', 'css/wplp_front.css');  //Main front-end stylesheet
define('MAIN_FRONT_SCRIPT', 'js/wplp_front.js');  //Main front-end jQuery script
define('DEFAULT_IMG', 'img/default-image-fpnp.png'); //Default thumbnail image
//const   THEME_LIBRARY                   = 'themes/default/default.php';
define('USE_LOCAL_JS_LIBS', true);
define('WPLP_PLUGIN_DIR', plugin_dir_url(__FILE__));
//Check plugin requirements
if (version_compare(PHP_VERSION, '5.3', '<')) {
    if (!function_exists('wplp_disable_plugin')) {
        function wplp_disable_plugin()
        {
            if (current_user_can('activate_plugins') && is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(__FILE__);
                unset($_GET['activate']);
            }
        }
    }

    if (!function_exists('wplp_show_error')) {
        function wplp_show_error()
        {
            $echo = '<div class="error"><p><strong>WP Latest Posts</strong>';
            $echo .= 'need at least PHP 5.3 version, please update php before installing the plugin.</p></div>';
            echo $echo;
        }
    }

    //Add actions
    add_action('admin_init', 'wplp_disable_plugin');
    add_action('admin_notices', 'wplp_show_error');

    //Do not load anything more
    return;
}

//Include the jutranslation helpers
include_once('jutranslation' . DIRECTORY_SEPARATOR . 'jutranslation.php');
call_user_func(
    '\Joomunited\WPLatestPosts\Jutranslation\Jutranslation::init',
    __FILE__,
    'wp-latest-posts',
    'WP Latest Posts',
    'wp-latest-posts',
    'languages' . DIRECTORY_SEPARATOR . 'wp-latest-posts-en_US.mo'
);
/** Class includes **/
include_once(dirname(__FILE__) . '/inc/wplp-admin.inc.php');            // custom classes
include_once(dirname(__FILE__) . '/inc/wplp-widget.inc.php');        // custom classes
include_once(dirname(__FILE__) . '/inc/wplp-front.inc.php');            // custom classes


// WPML installed
// Polylang installed
include_once(dirname(__FILE__) . '/inc/compatibility/class.language_content_wpml.php');
new WPLPLanguageContent();
// Require add image for category
require_once(dirname(__FILE__) . '/inc/wplp-category-image.php');
new WPLPCategoryImage();

include_once(dirname(__FILE__) . '/views/wplp-block-settings.php'); //view wplp block settings
include_once(dirname(__FILE__) . '/views/wplp-configuration.php'); //view config page

/**
 * Just fill up necessary settings in the configuration array
 * to create a new custom plugin instance...
 *
 */
global $wpcu_wpfn;
$wpcu_wpfn = new WPLPAdmin(
    array(
        'version' => '4.3.0',
        'translation_domain' => 'wp-latest-posts', // must be copied in the widget class!!!
        'plugin_file' => __FILE__,
    )
);

