<?php

/** WP Latest Posts views configuration class * */
class WPLPViewsConfig
{
    protected $version = false;

    /**
     * WPLPViewsConfig constructor.
     * @param $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Content config tab
     *
     */
    public function displayConfigTab()
    {
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
        wp_register_style('wplpAbout', plugins_url('css/wplp_about.css', dirname(__FILE__)));
        wp_enqueue_style('wplpAbout');
        ?>

        <div id="wplpnavtabs" class="wplptabs">
            <ul class="tabs z-depth-1">
                <li class="tab"><a href="#tab-1"><?php _e('Translation', 'wp-latest-posts'); ?></a></li>
                <li class="tab"><a href="#tab-2"><?php _e('About', 'wp-latest-posts'); ?></a></li>
            </ul>

            <div id="tab-1" class="metabox_tabbed_content wplptabs">
                <?php echo \Joomunited\WPLatestPosts\Jutranslation\Jutranslation::getInput(); ?>
            </div>

            <div id="tab-2" class="metabox_tabbed_content">
                <?php echo $this->displayAboutTab(); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Wp Latest Posts Widget About tab
     *
     */
    public function displayAboutTab()
    {

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui');
        wp_enqueue_script(
            'javascript',
            plugins_url('/js/wplp_about.js', dirname(__FILE__)),
            array('jquery'),
            '1.0.0',
            true
        );


        echo '<div class="about_content">';

        echo '<p> </p>';

        /** Support information * */
        if (!class_exists('WPLPAddonAdmin')) {
            echo '<div class="card wplp_notice light-blue" style="margin-right:20px">
<div class="card-content white-text" >';
            echo '<span class="card-title">' . __('Get Pro version', 'wp-latest-posts') . '</span>';
            echo '<p><em>' . __('Optional add-on is currently not installed or not enabled', 'wp-latest-posts') .
                '&rarr; <a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">' .
                __('get it here !', 'wp-latest-posts') . '</a></em></p>';
            /** Marketing * */
            echo '<iframe src="//player.vimeo.com/video/77775570" width="485" height="281" frameborder="0" 
webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> <p><a href="http://vimeo.com/77775570">';


            echo '<table class="feature-listing">
				<tbody>
				<tr class="header-feature"><th class="feature col1"><strong></strong></th><th class="feature col2">
				<strong>FREE </strong></th><th class="feature col2"><strong>PRO </strong></th></tr>
				
				<tr class="ligne2">
				<td>
				<p>5 pro responsive themes</p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/no.png', dirname(__FILE__)) . '" alt="no" width="16" height="16"></p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/yes.png', dirname(__FILE__)) . '" alt="yes" width="16" height="15"></p>
				</td>
				</tr>
				<tr class="ligne1">
				<td>
				<p>Visual custom theme design</p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/no.png', dirname(__FILE__)) . '" alt="no" width="16" height="16"></p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/yes.png', dirname(__FILE__)) . '" alt="yes" width="16" height="15"></p>
				</td>
				</tr>
				<tr class="ligne2">
				<td>
				<p>Automatically crop text content</p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/no.png', dirname(__FILE__)) . '" alt="no" width="16" height="16"></p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/yes.png', dirname(__FILE__)) . '" alt="yes" width="16" height="15"></p>
				</td>
				</tr>
				<tr class="ligne1">
				<td>
				<p>Advanced transition effect</p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/no.png', dirname(__FILE__)) . '" alt="no" width="16" height="16"></p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/yes.png', dirname(__FILE__)) . '" alt="yes" width="16" height="15"></p>
				</td>
				</tr>
				<tr class="ligne2">
				<td>
				<p>Advanced content filters</p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/no.png', dirname(__FILE__)) . '" alt="no" width="16" height="16"></p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/yes.png', dirname(__FILE__)) . '" alt="yes" width="16" height="15"></p>
				</td>
				</tr>
				<tr class="ligne1">
				<td>
				<p>Private ticket help support</p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/no.png', dirname(__FILE__)) . '" alt="no" width="16" height="16"></p>
				</td>
				<td class="feature-text">
				<p style="text-align: center;"><img style="margin: 0px;" 
				src="' . plugins_url('img/yes.png', dirname(__FILE__)) . '" alt="yes" width="16" height="15"></p>
				</td>
				</tr>
				<tr>
				<td colspan="3"><br/>
				<i>And more...</i>
				<td>
				</tbody>
				</table><br/>';
            echo '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts" 
                    target="_blank" class="getthepro">'
                . __('Get the Pro version now !', 'wp-latest-posts')
                . '</a>';
            echo '</div></div>';
        } else {
            do_action('wplp_display_about', $this->version);
        }

        echo '<div class="card wplp_notice light-blue"><div class="card-content white-text">';
        echo '<p>' . __('Initially released in october 2013 by 
<a href="http://www.joomunited.com/">JoomUnited</a>', 'wp-latest-posts') . '</p>';
        echo '<p>WP Latest Posts WordPress plugin version ' . $this->version . '</p>';
        echo '<p>' . __('Author: ', 'wp-latest-posts') . ' JoomUnited</p>';
        echo '<p>' . __('Your current version of WordPress is: ', 'wp-latest-posts') . get_bloginfo('version') . '</p>';
        echo '<p>' . __('Your current version of PHP is: ', 'wp-latest-posts') . phpversion() . '</p>';
        echo '<p>' . __('Your hosting provider\'s web server currently runs: ', 'wp-latest-posts') .
            $_SERVER['SERVER_SOFTWARE'] . '</p>';
        echo '<p><em>' .
            __('Please specify all of the above information when contacting us for support.', 'wp-latest-posts') .
            '</em></p>';

        echo '<p><a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">
WP Latest Posts official support site</a></p>';
        echo '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">';
        echo '<img src="' . plugins_url('img/wplp-logo-white.png', dirname(__FILE__)) .
            '" alt="JoomUnited Logo" /></a>';
        echo '</div></div>';
        echo '</div>';
    }
}