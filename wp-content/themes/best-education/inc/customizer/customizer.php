<?php 

/**
 * best-education Theme Customizer.
 *
 * @package best-education
 */

//customizer core option
require get_template_directory() . '/inc/customizer/core-customizer.php';

//customizer 
require get_template_directory() . '/inc/customizer/customizer-default.php';
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function best_education_customize_register( $wp_customize ) {

	// Load custom customizer functions.
	require get_template_directory() . '/inc/customizer/customizer-function.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	/*theme option panel details*/
	require get_template_directory() . '/inc/customizer/theme-option.php';


	// Register custom section types.
	$wp_customize->register_section_type( 'Best_Education_Customize_Section_Upsell' );

	// Register sections.
	$wp_customize->add_section(
		new Best_Education_Customize_Section_Upsell(
			$wp_customize,
			'theme_upsell',
			array(
				'title'    => esc_html__( 'Best Education Pro', 'best-education' ),
				'pro_text' => esc_html__( 'Upgrade To Pro', 'best-education' ),
				'pro_url'  => 'http://www.thememattic.com/theme/best-education-pro/',
				'priority'  => 1,
			)
		)
	);

}
add_action( 'customize_register', 'best_education_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 1.0.0
 */
function best_education_customize_preview_js() {

	wp_enqueue_script( 'best_education_customizer', get_template_directory_uri() . '/assets/libraries/js/customizer.js', array( 'customize-preview' ), '20130508', true );

}
add_action( 'customize_preview_init', 'best_education_customize_preview_js' );

function best_education_customizer_css() {
	wp_enqueue_script( 'best_education_customize_controls', get_template_directory_uri() . '/assets/libraries/custom/js/customizer-admin.js', array( 'customize-controls' ) );
}
add_action( 'customize_controls_enqueue_scripts', 'best_education_customizer_css',0 );
