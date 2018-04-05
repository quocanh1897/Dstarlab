<?php
/**
 * The template for displaying archive pages like categories, tags, authors, months
 * @package Impressive Business
 */
 get_header(); ?>
 <section id="banner-inner">
    <div class="container">
        <div class="our-work-heding-text banner-inner-tetx">
            <h1><?php the_archive_title(); ?></h1>
        </div>
		<?php echo esc_html(impressive_business_custom_breadcrumbs()); ?>
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
                    <div class="blog-text-heding">
                        <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>                        
                        <p><?php esc_html_e('By','impressive-business'); ?> <span><?php echo esc_attr(ucfirst(get_the_author())); ?></span></p>
                    </div>
                    <div class="blog-list">
                        <ul class="blog-list-type">
                            <?php $impressive_business_comments = wp_count_comments(get_the_ID());     
                    $impressive_business_total_comments = $impressive_business_comments->total_comments;
                            if($impressive_business_total_comments != ''){ ?>
                            <li><i class="fa fa-comments-o"></i><?php echo esc_html($impressive_business_total_comments); ?></li>
                            <?php } $categories = get_the_category();
                            if($categories != ''){ ?>
                            <li><i class="fa fa-bookmark-o"></i><?php foreach( $categories as $category ) { echo esc_html( $category->name ).' , '; } ?> </li>
                            <?php } 
                             the_tags( '<li>Tags: ', ', ', '</li>' ); ?>
                            <li><span class="date"><?php echo esc_html(get_the_date( 'd M')); ?></span></li>
                        </ul>
                        <?php the_excerpt(); ?>
                    </div>
                </div>
                <?php endwhile;
                the_posts_pagination( array(
                    'type'  => 'list',
                    'screen_reader_text' => ' ',
                    'prev_text'          => esc_html__( 'Previous', 'impressive-business' ),
                    'next_text'          => esc_html__('Next','impressive-business'),
                ) );
            endif; ?>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <?php get_sidebar(); ?>
            </div>    
        </div>
    </div>
</section>
<?php get_footer();