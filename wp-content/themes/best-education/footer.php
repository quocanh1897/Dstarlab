<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package best-education
 */

?>
</div>

<!-- #content -->
<footer id="colophon" class="site-footer">
    <?php $best_education_footer_widgets_number = best_education_get_option('number_of_footer_widget');
    if (1 == $best_education_footer_widgets_number) {
        $col = 'col-md-12';
    } elseif (2 == $best_education_footer_widgets_number) {
        $col = 'col-md-6';
    } elseif (3 == $best_education_footer_widgets_number) {
        $col = 'col-md-4';
    } elseif (4 == $best_education_footer_widgets_number) {
        $col = 'col-md-3';
    } else {
        $col = 'col-md-3';
    }
    if (is_active_sidebar('footer-col-one') || is_active_sidebar('footer-col-two') || is_active_sidebar('footer-col-three') || is_active_sidebar('footer-col-four')) { ?>
        <div class="footer-widget-area">
            <div class="container">
                <div class="row">
                    <?php if (is_active_sidebar('footer-col-one') && $best_education_footer_widgets_number > 0) : ?>
                        <div class="contact-list <?php echo esc_attr($col); ?>">
                            <?php dynamic_sidebar('footer-col-one'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('footer-col-two') && $best_education_footer_widgets_number > 1) : ?>
                        <div class="contact-list <?php echo esc_attr($col); ?>">
                            <?php dynamic_sidebar('footer-col-two'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('footer-col-three') && $best_education_footer_widgets_number > 2) : ?>
                        <div class="contact-list <?php echo esc_attr($col); ?>">
                            <?php dynamic_sidebar('footer-col-three'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('footer-col-four') && $best_education_footer_widgets_number > 3) : ?>
                        <div class="contact-list <?php echo esc_attr($col); ?>">
                            <?php dynamic_sidebar('footer-col-four'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="footer-bottom">
        <div class="container">
            <div class="row equal-row">
                <div class="col-sm-4 col-xs-12">
                    <div class="tm-social-share table-align">
                        <?php if (best_education_get_option('social_icon_style') == 'circle') {
                            $best_education_social_icon = 'bordered-radius';
                        } else {
                            $best_education_social_icon = '';
                        } ?>
                        <div class="social-icons table-align-cell v-align-middle <?php echo esc_attr($best_education_social_icon); ?>">
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

                <div class="col-sm-4 col-xs-12">
                    <?php best_education_the_custom_logo(); ?>
                </div>

                <div class="col-sm-4 col-xs-12">
                    <div class="contact-details table-align">
                        <div class="table-align-cell v-align-middle">
                        <ul>
                            <?php $best_education_top_header_location = esc_html(best_education_get_option('top_header_location'));
                            if (!empty($best_education_top_header_location)) { ?>
                            <li><?php echo esc_html(best_education_get_option('top_header_location')); ?>
                            </li>
                            <?php } ?>
                            <?php $best_education_top_header_email = esc_html(best_education_get_option('top_header_email'));
                            if (!empty($best_education_top_header_email)) { ?>
                            <li><a href="mailto:<?php echo esc_attr( best_education_get_option('top_header_email') ); ?>"><?php echo esc_attr( antispambot(best_education_get_option('top_header_email'))); ?></a>
                            </li>
                            <?php } ?>
                            <?php $best_education_top_header_telephone = esc_html(best_education_get_option('top_header_telephone'));
                            if (!empty($best_education_top_header_telephone)) { ?>
                                <li><a href="tel:<?php echo preg_replace( '/\D+/', '', esc_attr( best_education_get_option('top_header_telephone') ) ); ?>">
                                        <?php echo esc_html( best_education_get_option('top_header_telephone') ); ?>
                                    </a></li>
                            <?php } ?>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <span class="footer-divider"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="site-copyright">
                        <div class="row">
                            <div class="col-sm-5">
                                <?php
                                $best_education_copyright_text = best_education_get_option('copyright_text');
                                if (!empty ($best_education_copyright_text)) {
                                    echo wp_kses_post($best_education_copyright_text);
                                }
                                ?>
                            </div>
                            <div class="col-sm-2">
                                <a id="scroll-up">
                                    <i class="fa fa-angle-up"></i>
                                </a>
                            </div>

                            <div class="col-sm-5">
                                <div class="pull-right">
                                    <?php printf(esc_html__('Theme: %1$s by %2$s', 'best-education'), 'Best Education', '<a href="https://thememattic.com" target = "_blank" rel="designer">Themematic </a>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<?php
if (1 == best_education_get_option('show_footer_page_section')) {
    $best_education_footer_fix_button_enable = best_education_get_option('show_footer_fix_page_button');
    $best_education_footer_fix_button_text = best_education_get_option('fix_page_button_text');
    $best_education_footer_fix_button_url = best_education_get_option('fix_page_button_link');
    $best_education_footer_fix_page = array();
    $best_education_footer_fix_page[] = esc_attr(best_education_get_option('select_footer_page'));
    $best_education_fix_page_page_excerpt_number = absint(best_education_get_option('number_of_content_home_footer_page'));
    if (!empty($best_education_footer_fix_page)) {
        $best_education_fix_page_page_args = array(
            'orderby' => 'post__in',
            'post_type' => 'page',
            'post__in' => $best_education_footer_fix_page,
        );
    }
    if (!empty($best_education_fix_page_page_args)) {
        $best_education_fix_page_page_query = new WP_Query($best_education_fix_page_page_args);
        while ($best_education_fix_page_page_query->have_posts()): $best_education_fix_page_page_query->the_post();
            if (has_excerpt()) {
                $best_education_fix_page_page_main_content = get_the_excerpt();
            } else {
                $best_education_fix_page_page_main_content = best_education_words_count($best_education_fix_page_page_excerpt_number, get_the_content());
            }
            if (has_post_thumbnail()) {
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                $url = $thumb['0'];
            } else {
                $url = NULL;
            }
            ?>
            <div class="footer-cta primary-bgcolor data-bg bg-fixed" data-background="<?php echo esc_url($url); ?>">
                <div class="container high-index">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="section-header">
                                <h2 class="section-title section-title2"><?php the_title(); ?></h2>
                            </div>
                            <div class="title-divider secondary-bgcolor"></div>
                            <div class="section-content">
                                <?php echo esc_html($best_education_fix_page_page_main_content); ?>
                            </div>

                            <div class="cta-btn-group" role="group" aria-label="buttons">
                                <?php if ($best_education_footer_fix_button_enable == 1) { ?>
                                    <a href="<?php the_permalink(); ?>"
                                       class="btn btn-sm btn-primary tm-shadow"><?php _e('View More', 'best-education'); ?></a>
                                <?php } ?>
                                <?php if (!empty($best_education_footer_fix_button_text)) { ?>
                                    <a href="<?php echo esc_url($best_education_footer_fix_button_url); ?>"
                                       class="btn btn-sm btn-border"><?php echo esc_html($best_education_footer_fix_button_text); ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-overlay"></div>
            </div>
        <?php endwhile;
        wp_reset_postdata();
    }
} ?>

<?php wp_footer(); ?>

</body>
</html>