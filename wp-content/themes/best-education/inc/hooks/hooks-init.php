<?php
/**
 * Collection of other function file.
 */
require get_template_directory().'/inc/ocdi-functions.php';

/*load tgm plugin activation*/
require get_template_directory().'/assets/libraries/tgm/class-tgm-plugin-activation.php';

require get_template_directory().'/inc/custom-functions.php';

/*widget init*/
require get_template_directory().'/inc/widget-init.php';

/*layout meta*/
require get_template_directory().'/inc/layout-meta/layout-meta.php';

/*header css*/
require get_template_directory().'/inc/hooks/added-style.php';

/*widgets init*/
require get_template_directory().'/inc/widgets/widgets.php';

/*sidebar init*/
require get_template_directory().'/inc/hooks/slider-section.php';
require get_template_directory().'/inc/hooks/about-section.php';
require get_template_directory().'/inc/hooks/calback-section-1.php';
require get_template_directory().'/inc/hooks/team-section.php';
require get_template_directory().'/inc/hooks/blog-event-tab-section.php';
require get_template_directory().'/inc/hooks/testimonial-section.php';

/*section hook init*/
require get_template_directory().'/inc/hooks/breadcrumb.php';
require get_template_directory().'/inc/hooks/header-inner-page.php';

/* post-type-meta */
if (class_exists('Education_Connect')){
    require get_template_directory().'/inc/layout-meta/post-type-layout-meta.php';
}