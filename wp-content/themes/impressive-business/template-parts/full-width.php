<?php
/**
 * Template Name: Full With
 * @package Impressive Business
 */
get_header(); ?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php if ( have_posts() ) :
        	while ( have_posts() ) : the_post(); ?>
                <div class="blog-post">
                    <?php if(has_post_thumbnail()): ?>
                    <div class="blog-post-img">    
                        <?php the_post_thumbnail( 'full', array('class' => 'img-responsive') ); ?>
                    </div>
                    <?php endif; ?>
                    <div class="blog-list">
                        <?php the_content(); ?>
                    </div>
                </div>    
           <?php endwhile;
        endif; ?>
            </div>
        </div>
    </div>
</section>            
<?php get_footer();