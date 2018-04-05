<?php
/**
 * Default theme options.
 *
 * @package best-education
 */

if (!function_exists('best_education_get_default_theme_options')):

/**
 * Get default theme options
 *
 * @since 1.0.0
 *
 * @return array Default theme options.
 */
function best_education_get_default_theme_options() {

	$defaults = array();
	// header section
	$defaults['enable_header_contact_section'] = 0;
	$defaults['enable_header_logo_center']     = 0;
	$defaults['top_header_location']           = '';
	$defaults['top_header_telephone']          = '';
	$defaults['top_header_email']              = '';
	$defaults['enable_nav_overlay']              = 0;
	// Slider Section.
	$defaults['show_slider_section']           = 1;
	$defaults['number_of_home_slider']         = 3;
	$defaults['number_of_content_home_slider'] = 30;
	$defaults['select_slider_from']            = 'from-category';
	$defaults['select_page_for_slider']        = 0;
	$defaults['select_category_for_slider']    = 1;
	$defaults['button_text_on_slider']         = esc_html__('Learn More', 'best-education');

	/*About Default Value*/
	$defaults['show_about_section']               = 0;
	$defaults['select_about_main_page']           = 2;
	$defaults['number_of_content_home_about']     = 30;
	$defaults['select_category_for_about']        = 1;
	$defaults['select_about_secondary_page']      = 2;
	$defaults['number_of_content_home_sec_about'] = 0;
	$defaults['select_category_sec_about']        = 1;

	/*callback section*/
	$defaults['show_our_callback_section']       = 0;
	$defaults['select_callback_page']            = 0;
	$defaults['number_of_content_home_callback'] = 30;
	$defaults['show_page_link_button']           = 1;
	$defaults['callback_button_text']            = esc_html__('Buy Now', 'best-education');
	$defaults['callback_button_link']            = '';

	/*blog/event*/
	$defaults['show_blog_event_tab_section']    = 0;
	$defaults['select_category_blog_event_tab'] = 1;
	$defaults['number_of_content_home_blog']    = 30;
	$defaults['number_of_content_home_event']   = 30;
	$defaults['select_category_event_tab']      = 1;
	$defaults['blog_title_text']                = esc_html__('Latest Blog', 'best-education');
	$defaults['event_title_text']               = esc_html__('Our Events', 'best-education');

	/*testimonial*/
	$defaults['show_testimonial_section']             = 0;
	$defaults['testimonial_section_background_image'] = '';
	$defaults['title_testimonial_section']            = esc_html__('Voice Of Students', 'best-education');
	$defaults['number_of_home_testimonial']           = 6;
	$defaults['number_of_content_home_testimonial']   = 30;
	$defaults['select_testimonial_from']              = 'from-category';
	$defaults['select_page_for_testimonial']          = 0;
	$defaults['select_category_for_testimonial']      = 1;

	/*team*/
	$defaults['show_team_section']           = 0;
	$defaults['sub_title_team_section']      = '';
	$defaults['title_team_section']          = esc_html__('Who are we?', 'best-education');
	$defaults['number_of_home_team']         = 3;
	$defaults['number_of_content_home_team'] = 20;
	$defaults['select_team_from']            = 'from-category';
	$defaults['select_page_for_team']        = 0;
	$defaults['select_category_for_team']    = 1;
//footer page section
    $defaults['show_footer_page_section'] = 0;
    $defaults['select_footer_page'] = 2;
    $defaults['number_of_content_home_footer_page'] = 30;
    $defaults['show_footer_fix_page_button'] = 1;
    $defaults['fix_page_button_text'] = esc_html__('View Our Courses', 'best-education');
    $defaults['fix_page_button_link'] = '';

	/*layout*/
	$defaults['home_page_content_status'] = 1;
	$defaults['enable_overlay_option']    = 1;
	$defaults['single_post_meta_data']    = 1;
	$defaults['homepage_layout_option']   = 'full-width';
	$defaults['global_layout']            = 'right-sidebar';
	$defaults['excerpt_length_global']    = 50;
	$defaults['archive_layout']           = 'excerpt-only';
	$defaults['archive_layout_image']     = 'full';
	$defaults['single_post_image_layout'] = 'full';
	$defaults['pagination_type']          = 'default';
	$defaults['copyright_text']           = esc_html__('Copyright All right reserved', 'best-education');
	$defaults['social_icon_style']        = 'circle';
	$defaults['number_of_footer_widget']  = 3;
	$defaults['page_loader_setting']  = 1;
	$defaults['breadcrumb_type']          = 'simple';
	$defaults['contact_form_shortcodes']          = '';
    if (class_exists('Education_Connect')) {
        $defaults['select_category_sec_about']        = 21;
        $defaults['select_category_for_team']    = 27;
        $defaults['select_category_for_testimonial']      = 26;
        $defaults['select_category_event_tab']      = 18;

    }
	// Pass through filter.
	$defaults = apply_filters('best_education_filter_default_theme_options', $defaults);

	return $defaults;

}

endif;
