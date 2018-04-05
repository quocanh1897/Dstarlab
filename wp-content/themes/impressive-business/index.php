<?php
/**
 * The template for displaying blog
 * @package Impressive Business
 */
get_header(); ?>
<section id="banner-inner">
    <div class="container">
        <div class="our-work-heding-text banner-inner-tetx">
        <?php $impressive_business_blog_page = esc_html(get_option('page_for_posts')); 
            if(!empty($impressive_business_blog_page)){ 
                echo '<h1>'.esc_html(get_the_title(get_option( 'page_for_posts' ))).'</h1>'; 
            } else{ ?> 
              <h1><?php esc_html_e( "Blog", 'impressive-business' ); ?></h1>
           <?php }  ?>
        </div>
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
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'full', array('class' => 'img-responsive') ); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="blog-text-heding">
                        <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>                        
                        <p><?php esc_html_e('By','impressive-business'); ?> <span><?php echo esc_html(ucfirst(get_the_author())); ?></span></p>
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
                <?php endwhile;?>
                <?php the_posts_pagination( array(
                    'type'  => 'list',                    
                    'prev_text'          => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                    'next_text'          => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
                ) );?>
           <?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <?php get_sidebar(); ?>
            </div>    
        </div>
    </div>
</section>
<?php get_footer();