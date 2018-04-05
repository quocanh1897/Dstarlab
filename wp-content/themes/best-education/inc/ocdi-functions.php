<?php
/**
 * OCDI support.
 *
 * @package Best Education
 */

// Disable PT branding.
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

/**
 * OCDI after import.
 *
 * @since 1.0.0
 */
function best_education_ocdi_after_import() {

    // check for plugin using plugin name
    if (is_plugin_active('one-click-demo-import/one-click-demo-import.php')) {
        //plugin is activated
        function ocdi_after_import_setup() {
            // Assign menus to their locations.
            $main_menu   = get_term_by('name', 'Primary', 'nav_menu');
            $footer_menu = get_term_by('name', 'Secondary', 'nav_menu');
            $social_menu = get_term_by('name', 'Social', 'nav_menu');

            set_theme_mod('nav_menu_locations', array(
                    'primary' => $main_menu->term_id,
                    'top'  => $footer_menu->term_id,
                    'social'  => $social_menu->term_id,
                )
            );

            // Assign front page and posts page (blog page).
            $front_page_id = get_page_by_title('Homepage');
            $blog_page_id  = get_page_by_title('Blog');

            update_option('show_on_front', 'page');
            update_option('page_on_front', $front_page_id->ID);
            update_option('page_for_posts', $blog_page_id->ID);

        }
        add_action('pt-ocdi/after_import', 'ocdi_after_import_setup');
    }
}
add_action('admin_init', 'best_education_ocdi_after_import');