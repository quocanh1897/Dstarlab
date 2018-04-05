<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function best_education_widgets_init()
{

    register_sidebar(array(
        'name' => esc_html__('Main Sidebar', 'best-education'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'best-education'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title"><span>',
        'after_title' => '</span></h2>',
    ));

    $best_education_footer_widgets_number = best_education_get_option('number_of_footer_widget');
    if ($best_education_footer_widgets_number > 0) {
        register_sidebar(array(
            'name' => esc_html__('Footer Column One', 'best-education'),
            'id' => 'footer-col-one',
            'description' => esc_html__('Displays items on footer section.', 'best-education'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title"><span>',
            'after_title' => '</span></h2>',
        ));
        if ($best_education_footer_widgets_number > 1) {
            register_sidebar(array(
                'name' => esc_html__('Footer Column Two', 'best-education'),
                'id' => 'footer-col-two',
                'description' => esc_html__('Displays items on footer section.', 'best-education'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h2 class="widget-title"><span>',
                'after_title' => '</span></h2>',
            ));
        }
        if ($best_education_footer_widgets_number > 2) {
            register_sidebar(array(
                'name' => esc_html__('Footer Column Three', 'best-education'),
                'id' => 'footer-col-three',
                'description' => esc_html__('Displays items on footer section.', 'best-education'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h2 class="widget-title"><span>',
                'after_title' => '</span></h2>',
            ));
        }
    }
}

add_action('widgets_init', 'best_education_widgets_init');
