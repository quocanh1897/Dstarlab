<?php
/**
 * The template for displaying 404 pages (not found)
 * @package Impressive Business
 */
get_header(); ?>
<section id="error-section">
	<div class="error-page-section">
		<div class="container">
			<div class="error-text">
				<h1><?php esc_html_e('404','impressive-business'); ?></h1>
				<h4><?php esc_html_e( "Oops! That page can't be found.", 'impressive-business' ); ?></h4>
				<p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'impressive-business'); ?></p>
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</section>
<?php get_footer();