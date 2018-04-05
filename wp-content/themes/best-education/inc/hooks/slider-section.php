<?php
if (!function_exists('best_education_banner_slider_args')) :
    /**
     * Banner Slider Details
     *
     * @since Best Education 1.0.0
     *
     * @return array $qargs Slider details.
     */
    function best_education_banner_slider_args()
    {
        $best_education_banner_slider_number = absint(best_education_get_option('number_of_home_slider'));
        $best_education_banner_slider_from = esc_attr(best_education_get_option('select_slider_from'));
        switch ($best_education_banner_slider_from) {
            case 'from-page':
                $best_education_banner_slider_page_list_array = array();
                for ($i = 1; $i <= $best_education_banner_slider_number; $i++) {
                    $best_education_banner_slider_page_list = best_education_get_option('select_page_for_slider_' . $i);
                    if (!empty($best_education_banner_slider_page_list)) {
                        $best_education_banner_slider_page_list_array[] = absint($best_education_banner_slider_page_list);
                    }
                }
                // Bail if no valid pages are selected.
                if (empty($best_education_banner_slider_page_list_array)) {
                    return;
                }
                /*page query*/
                $qargs = array(
                    'posts_per_page' => esc_attr($best_education_banner_slider_number),
                    'orderby' => 'post__in',
                    'post_type' => 'page',
                    'post__in' => $best_education_banner_slider_page_list_array,
                );
                return $qargs;
                break;

            case 'from-category':
                $best_education_banner_slider_category = esc_attr(best_education_get_option('select_category_for_slider'));
                $qargs = array(
                    'posts_per_page' => esc_attr($best_education_banner_slider_number),
                    'post_type' => 'post',
                    'cat' => $best_education_banner_slider_category,
                );
                return $qargs;
                break;

            default:
                break;
        }
        ?>
        <?php
    }
endif;


if (!function_exists('best_education_banner_slider')) :
    /**
     * Main Slider
     *
     * @since Best Education 1.0.0
     *
     */
    function best_education_banner_slider()
    {
        $best_education_slider_button_text = esc_html(best_education_get_option('button_text_on_slider'));
        $best_education_slider_excerpt_number = absint(best_education_get_option('number_of_content_home_slider'));
        if (1 != best_education_get_option('show_slider_section')) {
            return null;
        }
        $best_education_banner_slider_args = best_education_banner_slider_args();
        $best_education_banner_slider_query = new WP_Query($best_education_banner_slider_args); ?>
        <!-- slider Section -->
        <section class="main-banner">
            <div class="mainbanner-jumbotron">
                <?php
                if ($best_education_banner_slider_query->have_posts()) :
                    while ($best_education_banner_slider_query->have_posts()) : $best_education_banner_slider_query->the_post();
                        if (has_post_thumbnail()) {
                            $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                            $url = $thumb['0'];
                        } else {
                            $url = NULL;
                        }
                        if (has_excerpt()) {
                            $best_education_slider_content = get_the_excerpt();
                        } else {
                            $best_education_slider_content = best_education_words_count($best_education_slider_excerpt_number, get_the_content());
                        }
                        ?>
                        <figure class="slick-item primary-bgcolor">
                            <a href="<?php the_permalink(); ?>" class="bg-image data-bg-slide">
                                <img src="<?php echo esc_url($url); ?>">
                            </a>
                            <figcaption class="slider-figcaption">
                                <div class="table-align">
                                    <div class="table-align-cell v-align-middle">
                                        <div class="slider-wrapper">
                                            <h2 class="slide-title">
                                                <?php the_title(); ?>
                                            </h2>
                                            <div class="slider-content hidden-xs visible">
                                                <?php echo wp_kses_post($best_education_slider_content); ?>
                                            </div>
                                            <?php if (!empty ($best_education_slider_button_text)) { ?>
                                                <a href="<?php the_permalink(); ?>" class="btn btn-lg btn-primary tm-shadow">
                                                    <span><?php echo esc_html($best_education_slider_button_text); ?></span>
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </figcaption>
                            <div class="bg-overlay overlay-dots"></div>
                        </figure>
                        <?php
                        endwhile;
                endif;
                wp_reset_postdata();
                ?>
            </div>
            <?php if (1 == best_education_get_option('show_about_section')) { ?>
                <div class="scroll-down-content">
                    <div class="sd-img-icon">
                        <a href="#about-us" class="smoothscroll">
                            <div class="mouse-icon"><div class="wheel"></div></div>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </section>
        <!-- end slider-section -->
        <?php
    }
endif;
add_action('best_education_action_slider_section', 'best_education_banner_slider', 10);
