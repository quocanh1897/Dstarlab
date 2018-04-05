<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package best-education
 */

?>
<?php
    $post_image_class = '';
    if (has_post_thumbnail()){
        $post_image_class='';
    }else{
        $post_image_class='post-no-image';
    }
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($post_image_class); ?>>
    <?php if (!is_single()) { ?>
        <?php $archive_layout = best_education_get_option('archive_layout'); ?>
        <?php $archive_layout_image = best_education_get_option('archive_layout_image'); ?>
        <?php if (has_post_thumbnail()) :
            if ('left' == $archive_layout_image) {
                echo "<div class='tm-image-archive image-left'>";
                the_post_thumbnail('full');
            } elseif ('right' == $archive_layout_image) {
                echo "<div class='tm-image-archive image-right'>";
                the_post_thumbnail('full');
            } elseif ('full' == $archive_layout_image) {
                echo "<div class='tm-image-archive image-full tm-hover primary-bgcolor tm-hover-enable'>";
                the_post_thumbnail('full');
            } else {
                echo "<div>";
            }
            echo "</div>";/*div end*/

        endif; ?>
        <div class="tm-archive-details">
            <h2 class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <?php $archive_layout = best_education_get_option('archive_layout'); ?>
            <?php $archive_layout_image = best_education_get_option('archive_layout_image'); ?>
            <?php if ('full' == $archive_layout_image) {
                $full_width_content = 'archive-image-full';
            } else {
                $full_width_content = 'tm-archive-lr';
            }
            ?>
            <div class="entry-content tm-entry-content <?php echo esc_attr($full_width_content); ?>">

                <?php if ('full' == $archive_layout) : ?>
                    <?php
                    the_content(sprintf(
                    /* translators: %s: Name of current post. */
                        wp_kses(__('Continue reading %s <span class="meta-nav">&rarr;</span>', 'best-education'), array('span' => array('class' => array()))),
                        the_title('<span class="screen-reader-text">"', '"</span>', false)
                    ));
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'best-education'),
                        'after' => '</div>',
                    ));
                    ?>
                <?php else : ?>
                    <?php the_excerpt(); ?>
                <?php endif ?>
            </div><!-- .entry-content -->
        </div>
    <?php } else { ?>

        <div class="entry-content">
            <?php
            $image_values = get_post_meta($post->ID, 'best-education-meta-image-layout', true);
            if (empty($image_values)) {
                $values = esc_attr(best_education_get_option('single_post_image_layout'));
            } else {
                $values = esc_attr($image_values);
            }
            if ('no-image' != $values) {
                if ('left' == $values) {
                    echo "<div class='image-left'>";
                    the_post_thumbnail('medium');
                } elseif ('right' == $values) {
                    echo "<div class='image-right'>";
                    the_post_thumbnail('medium');
                } else {
                    echo "<div class='image-full'>";
                    the_post_thumbnail('full');
                }
                echo "</div>";/*div end */
            }
            ?>
            <?php the_content(); ?>
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'best-education'),
                'after' => '</div>',
            ));
            ?>
        </div><!-- .entry-content -->

    <?php } ?>
    <?php if (is_single()) { ?>
    <footer class="entry-footer">
        <div class="tm-footer-tags">
            <?php best_education_entry_tags(); ?>
        </div>
        <div class="tm-footer-categories">
            <?php best_education_entry_footer(); ?>
        </div>
    </footer><!-- .entry-footer -->
    <?php } ?>
</article><!-- #post-## -->
