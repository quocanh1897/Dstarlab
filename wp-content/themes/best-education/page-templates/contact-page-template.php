<?php
/**
 * Template Name: Contact Form
 *
 * @subpackage Best_Education
 * @since Best Education 1.0.0
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="content-box">
                    <?php the_content(); ?>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
            <?php
            $best_education_contact_form = wp_kses_post(best_education_get_option('contact_form_shortcodes'));
            if (!empty($best_education_contact_form)) { ?>
                <div class="contact-form">
                    <?php echo do_shortcode($best_education_contact_form); ?>
                </div>
            <?php }
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
<?php
get_sidebar();
get_footer();
