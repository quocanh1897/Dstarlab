<?php
if (!function_exists('best_education_team_args')) :
    /**
     * Team Details
     *
     * @since Best Education 1.0.0
     *
     * @return array $qargs team details.
     */
    function best_education_team_args()
    {
        $best_education_team_number = absint(best_education_get_option('number_of_home_team'));
        $best_education_team_from = esc_attr(best_education_get_option('select_team_from'));
        switch ($best_education_team_from) {
            case 'from-page':
                $best_education_team_page_list_array = array();
                for ($i = 1; $i <= $best_education_team_number; $i++) {
                    $best_education_team_page_list = best_education_get_option('select_page_for_team_' . $i);
                    if (!empty($best_education_team_page_list)) {
                        $best_education_team_page_list_array[] =  absint($best_education_team_page_list);
                    }
                }
                // Bail if no valid pages are selected.
                if (empty($best_education_team_page_list_array)) {
                    return;
                }
                /*page query*/
                $qargs = array(
                    'posts_per_page' => esc_attr($best_education_team_number),
                    'orderby' => 'post__in',
                    'post_type' => 'page',
                    'post__in' => $best_education_team_page_list_array,
                );
                return $qargs;
                break;

            case 'from-category':
                if (class_exists('Education_Connect') && post_type_exists('teams' )) {
                    $best_education_team_category = esc_attr(best_education_get_option('select_category_for_team'));
                    $qargs = array(
                        'post_type' => 'teams',
                        'posts_per_page' => esc_attr($best_education_team_number),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'team-category',
                                'field' => 'id',
                                'terms' => absint($best_education_team_category),
                            )
                        ));
                } else {
                    $best_education_team_category = esc_attr(best_education_get_option('select_category_for_team'));
                    $qargs = array(
                        'posts_per_page' => esc_attr($best_education_team_number),
                        'post_type' => 'post',
                        'cat' => $best_education_team_category,
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


if (!function_exists('best_education_team')) :
    /**
     * Team
     *
     * @since Best Education 1.0.0
     *
     */
    function best_education_team()
    {
        $best_education_team_excerpt_number = absint(best_education_get_option('number_of_content_home_team'));
        if (1 != best_education_get_option('show_team_section')) {
            return null;
        }
        $best_education_team_args = best_education_team_args();
        $best_education_team_query = new WP_Query($best_education_team_args); ?>
        <section class="section-block section-block-1 team-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="block-heading">
                            <div class="section-header">
                                <h2 class="section-title section-title2">
                                    <span><?php echo esc_html(best_education_get_option('title_team_section')); ?></span>
                                </h2>
                            </div>
                            <div class="title-divider secondary-bgcolor"></div>
                            <div class="section-content">
                                <?php echo esc_html(best_education_get_option('sub_title_team_section')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="tm-team">
                            <?php if ($best_education_team_query->have_posts()) :
                                while ($best_education_team_query->have_posts()) : $best_education_team_query->the_post();
                                    if (has_post_thumbnail()) {
                                        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');
                                        $url = $thumb['0'];
                                    } else {
                                        $url = '';
                                    }
                                    if (has_excerpt()) {
                                        $best_education_team_content = get_the_excerpt();
                                    } else {
                                        $best_education_team_content = best_education_words_count($best_education_team_excerpt_number, get_the_content());
                                    }
                                    ?>
                                    <div class="team-item">
                                        <div class="photo data-bg" data-background="<?php echo esc_url($url); ?>">
                                            <div class="overlay">
                                                <p><?php echo wp_kses_post($best_education_team_content); ?></p>
                                            </div>
                                            <div class="move">
                                                <h4 class="section-block-title">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h4>
                                                <?php if (class_exists('Education_Connect') && post_type_exists('teams' )) { ?>
                                                    <div class="tm-designation"><?php education_connect_get_terms('team-designation'); ?></div>
                                                    <div class="tm-social"><?php education_connect_team_social(); ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata();
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Team -->
        <?php
    }
endif;
add_action('best_education_action_front_page', 'best_education_team', 60);