<?php
/**
 * CSS related hooks.
 *
 * This file contains hook functions which are related to CSS.
 *
 * @package best-education
 */

if (!function_exists('best_education_trigger_custom_css_action')) :

    /**
     * Do action theme custom CSS.
     *
     * @since 1.0.0
     */
    function best_education_trigger_custom_css_action()
    {
        $best_education_enable_banner_overlay = best_education_get_option('enable_overlay_option');
        ?>
        <style type="text/css">
            <?php
            /* Banner Image */
            if ( $best_education_enable_banner_overlay == 1 ){
                ?>
                    .inner-header-overlay,
                    .hero-slider.overlay .slide-item .bg-image:before {
                        background: #042738;
                        filter: alpha(opacity=65);
                        opacity: 0.65;
                    }
            <?php
        } ?>
        </style>

    <?php }

endif;