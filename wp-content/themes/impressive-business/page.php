<?php
/**
 * The template for displaying single page
 * @package Impressive Business
 */
get_header(); ?>
<section id="banner-inner">
    <div class="container">
        <div class="our-work-heding-text banner-inner-tetx">
            <h1><?php the_title(); ?></h1>
        </div>
        <?php echo impressive_business_custom_breadcrumbs(); ?>
    </div>
</section>
<section id="banner-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
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
                        <div class="blog-pager-section">
                        <?php 
                        wp_link_pages( );
                        the_post_navigation( array(
                        'prev_text'                 => __( '<i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Story', 'impressive-business' ),
                        'next_text'                 => __( 'Next Story <i class="fa fa-arrow-right" aria-hidden="true"></i>', 'impressive-business' ),
                        'screen_reader_text'        => ' ',
                         ) ); ?>
                        </div>                        
                    </div>
                </div>    
           <?php endwhile;
        endif; ?>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</section>            
<?php get_footer();