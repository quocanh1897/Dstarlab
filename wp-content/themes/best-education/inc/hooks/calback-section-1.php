<?php
if (!function_exists('best_education_callback_section')) :
    /**
     * Tab callback Details
     *
     * @since Best Education 1.0.0
     *
     */
    function best_education_callback_section()
    {
        $best_education_callback_button_enable = best_education_get_option('show_page_link_button');
        $best_education_callback_button_text = best_education_get_option('callback_button_text');
        $best_education_callback_button_url = best_education_get_option('callback_button_link');
        $best_education_callback_excerpt_number = absint(best_education_get_option('number_of_content_home_callback'));
        $best_education_callback_page = array();
        $best_education_callback_page[] = esc_attr(best_education_get_option('select_callback_page'));
        if (1 != best_education_get_option('show_our_callback_section')) {
            return null;
        }
        if (!empty($best_education_callback_page)) {
            $best_education_callback_page_args = array(
                'post_type' => 'page',
                'post__in' => $best_education_callback_page,
                'orderby' => 'post__in'
            );
        }
        if (!empty($best_education_callback_page_args)) {
            $best_education_callback_page_query = new WP_Query($best_education_callback_page_args);
            while ($best_education_callback_page_query->have_posts()): $best_education_callback_page_query->the_post();
                if (has_excerpt()) {
                    $best_education_callback_main_content = get_the_excerpt();
                } else {
                    $best_education_callback_main_content = best_education_words_count($best_education_callback_excerpt_number, get_the_content());
                }
                if (has_post_thumbnail()) {
                    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                    $url = $thumb['0'];
                } else {
                    $url = NULL;
                }
                ?>
                <!--CTA Starts-->
                <section class="section-block section-block-1 grid-cta-section">
                    <div class="container">
                        <div class="row align-items-center no-gutters grid-cta-bg ">
                            <div class="col-md-6">
                                <div class="section-content">
                                    <div class="section-header">
                                        <h2 class="section-title section-title1">
                                            <span>
                                               <?php the_title(); ?>
                                            </span>
                                        </h2>
                                    </div>
                                    <div class="title-divider secondary-bgcolor"></div>
                                    <div class="cta-description small-font clearfix">
                                        <?php echo esc_html($best_education_callback_main_content); ?>
                                    </div>
                                    <div class="cta-btn-group" role="group" aria-label="buttons">
                                        <?php if ($best_education_callback_button_enable == 1) { ?>
                                            <a href="<?php the_permalink(); ?>"
                                               class="btn btn-sm btn-primary tm-shadow"><?php _e('Learn More', 'best-education'); ?></a>
                                        <?php } ?>
                                        <?php if (!empty($best_education_callback_button_text)) { ?>
                                            <a href="<?php echo esc_url($best_education_callback_button_url); ?>"
                                               class="btn btn-sm btn-border"><?php echo esc_html($best_education_callback_button_text); ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="grid-cta-img">
                                    <img src="<?php echo esc_url($url); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--CTA Ends-->
            <?php endwhile;
            wp_reset_postdata();
        } ?>
        <?php
    }
endif;
add_action('best_education_action_front_page', 'best_education_callback_section', 40);