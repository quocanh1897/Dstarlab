<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package best-education
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

    <?php if (has_excerpt()) {
    $best_education_search_content = get_the_excerpt();
    } else {
    $best_education_search_content = best_education_words_count(50, get_the_content());
    } ?>
    <div class="entry-summary">
        <?php echo esc_html($best_education_search_content); ?>
    </div><!-- .entry-summary -->
</article><!-- #post-## -->
