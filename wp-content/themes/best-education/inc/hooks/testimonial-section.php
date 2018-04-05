<?php
if (!function_exists('best_education_testimonial_args')) :
    /**
     * Testimonial Details
     *
     * @since Best Education 1.0.0
     *
     * @return array $qargs testimonial details.
     */
    function best_education_testimonial_args()
    {
        $best_education_testimonial_number = absint(best_education_get_option('number_of_home_testimonial'));
        $best_education_testimonial_from = esc_attr(best_education_get_option('select_testimonial_from'));
        switch ($best_education_testimonial_from) {
            case 'from-page':
                $best_education_testimonial_page_list_array = array();
                for ($i = 1; $i <= $best_education_testimonial_number; $i++) {
                    $best_education_testimonial_page_list = best_education_get_option('select_page_for_testimonial_' . $i);
                    if (!empty($best_education_testimonial_page_list)) {
                        $best_education_testimonial_page_list_array[] = absint($best_education_testimonial_page_list);
                    }
                }
                // Bail if no valid pages are selected.
                if (empty($best_education_testimonial_page_list_array)) {
                    return;
                }
                /*page query*/
                $qargs = array(
                    'posts_per_page' => esc_attr($best_education_testimonial_number),
                    'orderby' => 'post__in',
                    'post_type' => 'page',
                    'post__in' => $best_education_testimonial_page_list_array,
                );
                return $qargs;
                break;

            case 'from-category':
                if (class_exists('Education_Connect') && post_type_exists('teams' )) {
                    $best_education_testimonial_category = esc_attr(best_education_get_option('select_category_for_testimonial'));

                    $qargs = array(
                        'post_type' => 'testimonials',
                        'posts_per_page' => absint($best_education_testimonial_number),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'testimonials-category',
                                'field' => 'id',
                                'terms' => absint($best_education_testimonial_category),
                            )
                        ));
                } else {
                    $best_education_testimonial_category = esc_attr(best_education_get_option('select_category_for_testimonial'));

                    $qargs = array(
                        'posts_per_page' =>  absint($best_education_testimonial_number),
                        'post_type' => 'post',
                        'cat' => absint($best_education_testimonial_category),
                    );
                }
                return $qargs;
                break;

            default:
                break;
        }
        ?>
        <?php
    }
endif;


if (!function_exists('best_education_testimonial')) :
    /**
     * Testimonial
     *
     * @since best-education 1.0.0
     *
     */
    function best_education_testimonial()
    {
        $best_education_testimonial_excerpt_number = absint(best_education_get_option('number_of_content_home_testimonial'));
        if (1 != best_education_get_option('show_testimonial_section')) {
            return null;
        }
        $best_education_testimonial_args = best_education_testimonial_args();
        $best_education_testimonial_query = new WP_Query($best_education_testimonial_args); ?>
        <section class="section-block section-block-2 data-bg bg-fixed primary-bgcolor testmonial-section" data-stellar-background-ratio="0.5" data-background="<?php echo esc_url(best_education_get_option('testimonial_section_background_image')); ?>">
            <div class="testmonial-slides high-index">
                <?php
                if ($best_education_testimonial_query->have_posts()) :
                    while ($best_education_testimonial_query->have_posts()) : $best_education_testimonial_query->the_post();
                        if (has_post_thumbnail()) {
                            $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium');
                            $url = $thumb['0'];
                        } else {
                            $url = '';
                        }
                        if (has_excerpt()) {
                            $best_education_testimonial_content = get_the_excerpt();
                        } else {
                            $best_education_testimonial_content = best_education_words_count($best_education_testimonial_excerpt_number, get_the_content());
                        }
                        ?>
                        <div class="testmonial-item">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <div class="avatar-wrapper">
                                            <a href="<?php the_permalink(); ?>">
                                                <img src="<?php echo esc_url($url); ?>">
                                            </a>
                                            <div class="service-logo-border"></div>
                                        </div>
                                        <h3 class="testmonial-user">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                        <div class="section-content-wrapper">
                                            <div class="section-content testmonial-section-content">
                                                <?php echo wp_kses_post($best_education_testimonial_content); ?>
                                            </div>
                                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary tm-shadow"><?php _e("Learn More","best-education"); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>
            <div class="bg-overlay primary-bgcolor"></div>
        </section>
        <!-- End Testmonial -->
        <?php
    }
endif;
add_action('best_education_action_front_page', 'best_education_testimonial', 50);