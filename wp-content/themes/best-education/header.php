<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package best-education
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php if (best_education_get_option('page_loader_setting') == 1){ ?>
<div class="preloader">
    <div class="preloader-wrapper">
        <div class="loader">
            <div class="loader-inner">
                <div class="loader-inner">
                    <div class="loader-inner">
                        <div class="loader-inner">
                            <div class="loader-inner">
                                <div class="loader-inner"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- full-screen-layout/boxed-layout -->
<?php if (best_education_get_option('homepage_layout_option') == 'full-width') {
    $best_education_homepage_layout = 'full-screen-layout';
} elseif (best_education_get_option('homepage_layout_option') == 'boxed') {
    $best_education_homepage_layout = 'boxed-layout';
}
if (best_education_get_option('show_footer_page_section') == 1) {
    $best_education_footer_fix_cta = 'footer-cta-enable';
} else {
    $best_education_footer_fix_cta = '';
}
?>
<div id="page" class="site <?php echo esc_attr($best_education_homepage_layout);?> <?php echo esc_attr($best_education_footer_fix_cta);?>">
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'best-education'); ?></a>
    <header id="masthead" class="site-header site-header-second" role="banner">
        <div class="top-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-xs-12">
                        <?php wp_nav_menu(array(
                            'theme_location' => 'top',
                            'menu_id' => 'top-menu',
                            'container' => 'div',
                            'depth' => 1,
                            'container_class' => 'menu top-menu'
                        )); ?>
                    </div>
                    <div class="col-md-4 col-xs-12 pull-right">
                        <div class="tm-top-right">
                            <?php if (best_education_get_option('social_icon_style') == 'circle') {
                                $best_education_social_icon = 'bordered-radius';
                            } else {
                                $best_education_social_icon = '';
                            } ?>
                            <div class="social-icons <?php echo esc_attr($best_education_social_icon); ?>">
                                <?php
                                wp_nav_menu(
                                    array('theme_location' => 'social',
                                        'link_before' => '<span class="screen-reader-text">',
                                        'link_after' => '</span>',
                                        'menu_id' => 'social-menu',
                                        'fallback_cb' => false,
                                        'menu_class' => false
                                    )); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php
        $best_education_contact_dissable = '';
        $best_education_logo_center = '';
        if (best_education_get_option('enable_header_contact_section') != 1) {
            $best_education_contact_dissable = 'no-contact';
        }
        if (best_education_get_option('enable_header_logo_center') == 1) {
            $best_education_logo_center = 'logo-center';
        } ?>
        <div class="header-middle <?php echo esc_attr($best_education_contact_dissable); ?><?php echo esc_attr($best_education_logo_center); ?>">
            <div class="container">
                <div class="row equal-row">
                    <div class="col-md-4 col-sm-12 col-xs-12 col-xs-12">
                        <div class="site-branding">
                            <div class="table-align-cell v-align-middle">
                                <?php
                                if (is_front_page() && is_home()) : ?>
                                    <span class="site-title">
                                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                            <?php bloginfo('name'); ?>
                                        </a>
                                    </span>
                                <?php else : ?>
                                    <span class="site-title">
                                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                            <?php bloginfo('name'); ?>
                                        </a>
                                    </span>
                                <?php endif;
                                best_education_the_custom_logo();
                                $description = get_bloginfo('description', 'display');
                                if ($description || is_customize_preview()) : ?>
                                    <p class="site-description"><?php echo $description; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (best_education_get_option('enable_header_contact_section') == 1) { ?>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <div class="header-links">
                                <ul class="link-list">
                                    <?php
                                    $best_education_top_header_telephone = esc_html(best_education_get_option('top_header_telephone'));
                                    if (!empty($best_education_top_header_telephone)) { ?>
                                        <li class="link-tel">
                                            <div class="link-wrapper">
                                        <span class="link-icon">
                                            <svg id="svg-icon icon-phone" viewBox="0 0 42.062 42.062" width="100%" height="100%">
                                                <path d="M38.26,29.35l-1.327-1.327c4.337-4.337,4.336-11.394-0.002-15.731l1.329-1.328C43.329,16.032,43.329,24.281,38.26,29.35z
     M35.964,13.259l-1.327,1.328c3.07,3.072,3.07,8.069,0,11.139l1.327,1.33C39.767,23.251,39.767,17.062,35.964,13.259z
     M33.594,15.627l-1.327,1.328c1.768,1.765,1.768,4.639,0,6.403l1.328,1.328C36.093,22.189,36.093,18.125,33.594,15.627z
     M3.802,29.35l1.327-1.327c-4.337-4.337-4.336-11.394,0.002-15.731l-1.329-1.328C-1.267,16.032-1.267,24.281,3.802,29.35z
     M6.098,27.055l1.327-1.328c-3.07-3.07-3.07-8.068,0-11.14l-1.327-1.328C2.295,17.062,2.295,23.251,6.098,27.055z M8.467,24.687
    l1.328-1.328c-1.766-1.765-1.766-4.638,0.001-6.403l-1.328-1.328C5.969,18.125,5.969,22.189,8.467,24.687z M29.745,8.057v25.949
    c0,1.113-0.912,2.025-2.026,2.025H14.345c-1.114,0-2.026-0.912-2.026-2.025V8.057c0-1.114,0.912-2.026,2.026-2.026h13.374
    C28.833,6.031,29.745,6.943,29.745,8.057z M18.646,7.737c0,0.135,0.11,0.245,0.246,0.245h4.278c0.136,0,0.245-0.11,0.245-0.245
    c0-0.136-0.109-0.246-0.245-0.246h-4.278C18.757,7.491,18.646,7.601,18.646,7.737z M22.045,34.006c0-0.561-0.454-1.014-1.014-1.014
    c-0.56,0-1.013,0.454-1.013,1.014c0,0.559,0.453,1.012,1.013,1.012C21.591,35.018,22.045,34.565,22.045,34.006z M28.335,9.244
    H13.728v23.037h14.607V9.244z"></path>
                                            </svg>
                                        </span>
                                                <span class="link-detail">
                                            <strong><?php esc_html_e( 'Phone us', 'best-education' ); ?></strong>
                                            <a href="tel:<?php echo preg_replace( '/\D+/', '', esc_attr( best_education_get_option('top_header_telephone') ) ); ?>">
                                                        <?php echo esc_html( best_education_get_option('top_header_telephone') ); ?>
                                                </a>
                                        </span>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php
                                    $best_education_top_header_email = esc_html(best_education_get_option('top_header_email'));
                                    if (!empty($best_education_top_header_email)) { ?>
                                        <li class="link-email">
                                            <div class="link-wrapper">
                                        <span class="link-icon">
                                           <svg id="svg-icon icon-email" viewBox="0 0 81.751 81.751" width="100%" height="100%">
                                               <path d="M78.863,24.446H49.882V5.522c0-3.04-2.483-5.522-5.52-5.522H7.914C4.875,0,2.393,2.483,2.393,5.522v70.707
    c0,3.038,2.483,5.522,5.521,5.522h36.448c3.036,0,5.52-2.484,5.52-5.522V59.915h13.226h2.964l0.161-0.44L78.286,27.31l1.072-2.866
    L78.863,24.446L78.863,24.446z M31.133,29.305l9.653,10.607L20.99,56.375L31.133,29.305z M33.211,27.011h41.4L47.877,43.117
    L33.211,27.011z M20.306,3.982h11.661c0.368,0,0.67,0.299,0.67,0.668c0,0.366-0.302,0.667-0.67,0.667H20.306
    c-0.368,0-0.666-0.301-0.666-0.667C19.64,4.281,19.938,3.982,20.306,3.982z M26.136,78.994c-1.522,0-2.761-1.234-2.761-2.763
    c0-1.521,1.236-2.76,2.761-2.76s2.764,1.233,2.764,2.76C28.9,77.759,27.661,78.994,26.136,78.994z M46.038,71.537H6.233V8.759
    h39.805v15.689H30.877h-0.49l-0.696,1.854H10.454v4.236h17.651l-1.414,3.772H16.242v3.176h9.259l-1.196,3.191h-6.087v2.58h5.12
    l-6.075,16.209l-0.167,0.447h2.96h25.987L46.038,71.537L46.038,71.537z M23.139,57.355L42.22,41.486l4.441,4.879l8.094-4.879
    l7.191,15.869H23.139z M64.83,56.376l-7.462-16.465l17.605-10.607L64.83,56.376z"></path>
                                           </svg>
                                        </span>
                                                <span class="link-detail">
                                            <strong><?php esc_html_e( 'Email us', 'best-education' ); ?></strong>
                                            <a href="mailto:<?php echo esc_attr( best_education_get_option('top_header_email') ); ?>"><?php echo esc_attr( antispambot(best_education_get_option('top_header_email'))); ?></a>
                                        </span>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php
                                    $best_education_top_header_location = esc_html(best_education_get_option('top_header_location'));
                                    if (!empty($best_education_top_header_location)) { ?>
                                        <li class="link-address">
                                            <div class="link-wrapper">
                                        <span class="link-icon">
                                           <svg id="svg-icon icon-address" viewBox="0 0 539.077 539.077" width="100%" height="100%">
                                               <path d="M460.462,527.846H325.692c-6.208,0-11.231-5.023-11.231-11.231v-146h-89.846v146c0,6.208-5.023,11.231-11.231,11.231
                H78.615c-6.208,0-11.231-5.023-11.231-11.231V292c0-6.208,5.023-11.231,11.231-11.231c6.208,0,11.231,5.023,11.231,11.231v213.385
                h112.308v-146c0-6.208,5.023-11.231,11.231-11.231h112.308c6.208,0,11.231,5.023,11.231,11.231v146h112.308V303.231
                c0-6.208,5.023-11.231,11.231-11.231c6.208,0,11.231,5.023,11.231,11.231v213.385
                C471.692,522.823,466.669,527.846,460.462,527.846z M527.846,292c-2.873,0-5.747-1.097-7.94-3.29L269.538,38.343L19.171,288.71
                c-4.387,4.387-11.494,4.387-15.881,0c-4.387-4.387-4.387-11.494,0-15.881L261.598,14.521c4.387-4.387,11.494-4.387,15.881,0
                l258.308,258.308c4.387,4.387,4.387,11.494,0,15.881C533.593,290.903,530.72,292,527.846,292z M438,134.769
                c-6.208,0-11.231-5.023-11.231-11.231V56.154h-67.385c-6.208,0-11.231-5.023-11.231-11.231s5.023-11.231,11.231-11.231H438
                c6.208,0,11.231,5.023,11.231,11.231v78.615C449.231,129.746,444.208,134.769,438,134.769z"></path>
                                           </svg>
                                        </span>
                                                <span class="link-detail">
                                            <strong><?php esc_html_e( 'Our Location', 'best-education' ); ?></strong>
                                                    <?php echo esc_html(best_education_get_option('top_header_location')); ?>
                                        </span>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
            if (best_education_get_option('enable_nav_overlay') == 1) {
                $enable_nav_overlay = 'top-header-overlay';
            } else {
                $enable_nav_overlay = '';
            }
        ?>
        <div class="top-header <?php echo esc_attr($enable_nav_overlay); ?>">
            <div class="container">
                <nav class="main-navigation" role="navigation">
                    <span class="toggle-menu" aria-controls="primary-menu" aria-expanded="false">
                         <span class="screen-reader-text">
                            <?php esc_html_e('Primary Menu', 'best-education'); ?>
                        </span>
                        <i class="ham"></i>
                    </span>

                    <?php wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'container' => 'div',
                        'container_class' => 'menu main-menu'
                    )); ?>
                </nav>

                <div class="icon-search pull-right">
                    <i class="fa fa-search"></i>
                </div>
            </div>
        </div>
    </header>

    <div class="popup-search">
        <div class="table-align">
            <div class="table-align-cell v-align-middle">
                <?php get_search_form(); ?>
            </div>
        </div>
        <div class="close-popup"></div>
    </div>

    <!-- #masthead -->
    <!-- Innerpage Header Begins Here -->
    <?php
    if (is_front_page() && !is_home()) {
    } else {
        do_action('best-education-page-inner-title');
    }
    ?>
    <?php
    if (is_front_page()) {
        do_action('best_education_action_slider_section');
    }
    ?>
    <!-- Innerpage Header Ends Here -->
    <div id="content" class="site-content">

