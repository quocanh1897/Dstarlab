<?php
/**
* Customization options
**/
function impressive_business_sanitize_select( $input, $setting ) {
  
  $input = sanitize_key( $input );
 
  $choices = $setting->manager->get_control( $setting->id )->choices;
 
  return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

function impressive_business_customize_register( $wp_customize ) {
  $wp_customize->add_setting(
    'impressive_business_theme_color',
    array(
      'default'           => '#1cbac8',
      'capability'        => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
  ) );
  $wp_customize->add_control(
    new WP_Customize_Color_Control( $wp_customize,
      'impressive_business_theme_color',
      array(
        'label'   => esc_html__( 'Theme Color', 'impressive-business' ),
        'section' => 'colors',
      )
  ) );
  $wp_customize->add_setting(
  'secondary_color',
  array(
      'default' => '#383838',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'secondary_color',
    array(
        'label'      => __('Secondary Color', 'impressive-business'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);

/*------Scroll Logo Option---------*/
$wp_customize->add_setting('header_fix', array(
      'default' => false, 
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_text_field',
));
$wp_customize->add_control('header_fix', array(
    'label'   => esc_html__('Display Header Fix?','impressive-business'),
    'section' => 'title_tagline',
    'type'    => 'checkbox',
    'priority' => 20
));
$wp_customize->add_setting('logo_height',array(
    'default' => '',
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'absint',
    )
  );
$wp_customize->add_control('logo_height',array(
    'section' => 'title_tagline',
    'label'      => __('Enter Logo Size', 'impressive-business'),
    'description' => __("Use if you want to increase or decrease logo size (optional) Don't include `px` in the string. e.g. 20 (default: 10px)",'impressive-business'),
    'type'       => 'text',
    'priority'    => 21,
    )
  );
$wp_customize->add_setting(
    'scroll_logo',
    array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'scroll_logo', array(
    'section'     => 'title_tagline',
    'label'       => __( 'Upload Scroll Logo' ,'impressive-business'),
    'description' => __('Logo Size (120 * 60)','impressive-business'),
    'flex_width'  => true,
    'flex_height' => true,
    'width'       => 120,
    'height'      => 50,
    'priority'    => 10,
    'default-image' => '',
) ) );

$wp_customize->get_section('title_tagline')->panel = 'general';
$wp_customize->get_section('header_image')->panel = 'general';
$wp_customize->get_section('title_tagline')->title = __('Header & Logo','impressive-business');

$wp_customize->add_panel(
    'general',
    array(
        'title' => __( 'General', 'impressive-business' ),
        'description' => __('styling options','impressive-business'),
        'priority' => 20, 
    )
  );

  /*--------------start footer-----------------------*/
  $wp_customize->add_panel(
    'footer',
    array(
      'title' => esc_html__( 'Footer','impressive-business' ),
      'description' => esc_html__('layout options', 'impressive-business'), 
      'priority' => 45,
    )
  );
  /* Content Widget Layout */
  $wp_customize->add_section(
    'footer_widget_area',
    array(
      'title' => esc_html__('Footer widget Area','impressive-business'),
      'panel' => 'footer'
    )
  );
  $wp_customize->add_section(
    'footer_copyrights_section',
    array(
      'title' => esc_html__('Footer Copyrights Section','impressive-business'),
      'panel' => 'footer'
    )
  );
  $wp_customize->add_section(
    'footer_socials',
    array(
      'title' => esc_html__('Social Accounts','impressive-business'),
      'description' => __( 'In first input box, you need to add Font Awesome class which you can find <a target="_blank" href="https://fontawesome.bootstrapcheatsheets.com/">here</a> For Example (<b>fa-facebook</b>) and in second input box, you need to add your social media profile URL.<br /> Leave it empty to hide the icon.' , 'impressive-business'),
      'panel' => 'footer'
    )
  );
  $impressive_business_social_icon = array();
  for($i=1;$i <= 5;$i++):
  $impressive_business_social_icon[] =  array( 'slug'=>sprintf('impressive_business_social_icon%d',$i),
   'default' => '',
   'label' => sprintf(esc_html__( 'Social Account %s', 'impressive-business' ),$i),
   'priority' => sprintf('%d',$i) );
  endfor;
  foreach($impressive_business_social_icon as $impressive_business_social_icons){
    $wp_customize->add_setting(
      $impressive_business_social_icons['slug'],
      array(
        'default' => '',
        'capability'     => 'edit_theme_options',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
      )
    );
    $wp_customize->add_control(
      $impressive_business_social_icons['slug'],
      array(
        'type'  => 'text',
        'section' => 'footer_socials',
        'input_attrs' => array( 'placeholder' => esc_attr__('Enter Icon','impressive-business') ),
        'label'      =>   esc_html($impressive_business_social_icons['label']),
        'priority' => $impressive_business_social_icons['priority']
      )
    );
  }
  $impressive_business_social_icon_link = array();
  for($i=1;$i <= 5;$i++):
  $impressive_business_social_icon_link[] =  array( 'slug'=>sprintf('impressive_business_social_icon_link%d',$i),
   'default' => '',
   'label' => sprintf(esc_html__( 'Social Link %s', 'impressive-business' ),$i),
   'priority' => sprintf('%d',$i) );
  endfor;
  foreach($impressive_business_social_icon_link as $impressive_business_social_icons){
    $wp_customize->add_setting(
      $impressive_business_social_icons['slug'],
      array(
        'default' => '',
        'capability'     => 'edit_theme_options',
        'type' => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
      )
    );
    $wp_customize->add_control(
      $impressive_business_social_icons['slug'],
      array(
        'type'  => 'text',
        'section' => 'footer_socials',
        'priority' => $impressive_business_social_icons['priority'],
        'input_attrs' => array( 'placeholder' => esc_html__('Enter URL','impressive-business')),
      )
    );
  }
  //adding setting for footer text area
  $wp_customize->add_setting('footer_copyrights',
    array(
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'wp_kses_post',
    )
  );
  $wp_customize->add_control('footer_copyrights',
    array(
      'label'   => esc_html__('Footer Copy Rights','impressive-business'),
      'section' => 'footer_copyrights_section',
      'type'    => 'textarea',
    )
  );
  /*-------------------footer column hide/show--------------*/
   $wp_customize->add_setting('hide_footer_widget_area', array(
        'default' => true, 
        'capability'     => 'edit_theme_options', 
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hide_footer_widget_area', array(
        'label'   => esc_html__('Display Footer Widget Area','impressive-business'),
        'section' => 'footer_widget_area',
        'type'    => 'checkbox',
        'priority' => 1
    ));
  /*-------------footer column Section----------------------*/
  $wp_customize->add_setting(
    'footer_widget_style',
    array(
        'default' => '3',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'impressive_business_sanitize_select',
        'priority' => 20, 
    )
  );
  $wp_customize->add_control(
    'footer_widget_style',
    array(
        'section' => 'footer_widget_area',                
        'label'   => esc_html__('Select Widget Area','impressive-business'),
        'type'    => 'select',
        'choices'        => array(
            "1"   => esc_html__( "2 column", 'impressive-business' ),
            "2"   => esc_html__( "3 column", 'impressive-business' ),
            "3"   => esc_html__( "4 column", 'impressive-business' )
        ),
    )
  );
  /*---------------------end--------------------------*/ 
}
add_action( 'customize_register', 'impressive_business_customize_register' );
function impressive_business_dynamic_styles() { 
    wp_enqueue_style('impressive-business-style', get_stylesheet_uri(), array());
        $custom_css='';
        if(get_header_image()!=''){
          $custom_css .= "#banner-inner,#error-section{background-image:url(".get_header_image().");}";
      }
      $custom_css .= "*::selection{background: ".esc_attr(get_theme_mod('impressive_business_theme_color','#1cbac8')).";}
       .blog-list-type li .date,.about-us-blog-sidebar h4:before,.subscribe-btn .btn:hover,.our-work-heding-text h1:after,.about-us-blog-sidebar #today,.nav-links ul li span.current,.nav-links ul li a:hover
       ,.main-nav.fixed-header.fixed, #mainmenu ul > li > a:before, #footer-section .calendar_wrap table#wp-calendar tbody td#today, .input-group-btn > .btn:hover{ background-color:".esc_attr(get_theme_mod('impressive_business_theme_color','#1cbac8')).";}
       #mainmenu > ul > li > a:hover, #mainmenu ul ul li:hover > a, #mainmenu ul ul li a:hover, .footer-copy-text a:hover, #footer-section .media-body a:hover, .about-us-blog-sidebar td a, .about-us-blog-sidebar tfoot td a:hover, #footer-section .our-work-heding-text tfoot td a:hover, #footer-section .calendar_wrap table#wp-calendar tbody td a, .breadcrumbs a:hover { color:".esc_attr(get_theme_mod('impressive_business_theme_color','#1cbac8')).";}
       .navbar-form .form-control,.input-group-btn .btn,.subscribe-btn .btn,.tagcloud a:hover,.footer-icon li a:hover,.comment-body,.comment-form textarea, .comment-form input,.comment-form .form-submit input,.about-us-blog-sidebar td, .about-us-blog-sidebar th,.about-us-blog select,.nav-links ul li span.current,.nav-links ul li a:hover, #footer-section select#archives-dropdown--1, #footer-section select#categories-dropdown--1, #footer-section .our-work-heding-text form select, #footer-section .calendar_wrap table#wp-calendar thead th, #footer-section .calendar_wrap table#wp-calendar tbody td, #footer-section .calendar_wrap table#wp-calendar tfoot td
       { border-color: ".esc_attr(get_theme_mod('impressive_business_theme_color','#1cbac8'))."; }
       .blog-text-heding p span,.tagcloud a:hover,.footer-icon li a:hover,.our-work-heding-text li a:hover,.post-cat-list a:hover,.blog-text-heding h1 a:hover,.media-body a:hover,.breadcrumbs .item-current,.blog-pager-section .nav-links a:hover,.reply a:hover{ color:".esc_attr(get_theme_mod('impressive_business_theme_color','#1cbac8')).";}";


       $custom_css .= '
             #footer-section { background-color: '.esc_attr(get_theme_mod('secondary_color','#383838')).'}
       ';

      if(''!=get_theme_mod('logo_height')):
        $custom_css .= '.main-logo a img {  max-height: '.esc_attr(get_theme_mod('logo_height','45')).'px;}';
      else:
        $custom_css .= '.main-logo a img {  max-height: 45px;}';
      endif;
      $custom_css .= "@media (max-width:1024px){
        .fixed-header.fixed #mainmenu > ul > li > a:hover{ color:".esc_attr(get_theme_mod('impressive_business_theme_color','#1cbac8')).";}
       }
       ";
      wp_add_inline_style( 'impressive-business-style', $custom_css );

      
      $header_fix = get_theme_mod('header_fix',0);
      $script_js = '';
      if($header_fix == 1){
        $script_js .= "jQuery(window).scroll(function () {
            if(jQuery(document).scrollTop() > 0)
            {
                jQuery('.navbar').addClass('on-scroll');
                jQuery('.fixed-header').addClass('fixed').css({'position': 'fixed','top': 'auto'});
            }
            else
            {
                jQuery('.navbar').removeClass('on-scroll');
                jQuery('.fixed-header').removeClass('fixed').css({'position': 'absolute','top': 'auto'});        
            }
        });
       ";
     }
     wp_add_inline_script( 'impressive-business-default-js', $script_js );
}