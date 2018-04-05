<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class();?>>
    <header class="transparent">
        <!-- Fixed navbar -->
        <nav id="header" class="navbar main-nav fixed-header">
            <div id="header-container" class="container header-wrap navbar-container">
                <div class="navbar-header-logo main-logo"> 
                    <?php 
                    if(has_custom_logo()){
                        the_custom_logo();
                    } 
                   $scroll_logo=wp_get_attachment_url(esc_html(get_theme_mod('scroll_logo','')));
                    if($scroll_logo == ''){
                       $custom_logo_id = get_theme_mod( 'custom_logo' );
                       $scroll_logo = wp_get_attachment_url( $custom_logo_id , 'full' );
                    }
                    if($scroll_logo != ''){ ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link logo-dark" rel="home" itemprop="url">
                        <img class="img-responsive logo-dark" src="<?php echo esc_url($scroll_logo); ?>" alt="<?php esc_attr_e('Logo','impressive-business'); ?>">
                    </a>
                    <?php } ?>
                    <?php if(get_theme_mod('header_text',true)):?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" id='brand' class="custom-logo-link"><span class="site-title"><h4><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h4><small class="site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></small></span></a>   
                    <?php endif; ?>
                </div>
                <div id="mainmenu" classold=="collapse navbar-collapse">
                    <?php
                        $impressive_business_defaults = array(
                            'theme_location'  => 'primary',                            
                            'container'       => 'ul',                            
                            'echo'            => true,
                            'menu_class'      => 'navbar-nav',
                            'depth'           => 0,
                        );                               
                        wp_nav_menu($impressive_business_defaults);
                     ?>
                </div><!-- /.nav-collapse -->
            
            </div><!-- /.container -->
        </nav><!-- /.navbar -->
    </header>