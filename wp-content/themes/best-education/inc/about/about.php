<?php
/**
 * About setup
 *
 * @package Best Education
 */

if ( ! function_exists( 'best_education_about_setup' ) ) :

	/**
	 * About setup.
	 *
	 * @since 1.0.0
	 */
	function best_education_about_setup() {

		$config = array(

			// Welcome content.
			'welcome_content' => sprintf( esc_html__( ' First off, We’d like to extend a warm welcome and thank you for choosing %1$s. %1$s is now installed and ready to use. We want to make sure you have the best experience using the theme and that is why we gathered here all the necessary information for you. We would like to suggest you to use all our recommend plugins especially Education Connect to get full advantage of this theme. Again, Thanks for using our theme!', 'best-education' ), 'Best Education' ),

			// Tabs.
			'tabs' => array(
				'getting-started' => esc_html__( 'Getting Started', 'best-education' ),
				'support'         => esc_html__( 'Support', 'best-education' ),
				'useful-plugins'  => esc_html__( 'Useful Plugins', 'best-education' ),
				'demo-content'    => esc_html__( 'Demo Content', 'best-education' ),
				),

			// Quick links.
			'quick_links' => array(
				'theme_url' => array(
					'text' => esc_html__( 'Theme Details', 'best-education' ),
					'url'  => 'https://thememattic.com/theme/best-education/',
					),
				'demo_url' => array(
					'text' => esc_html__( 'View Demo', 'best-education' ),
					'url'  => 'https://thememattic.com/theme-demos/?demo=best-education',
					),
				'documentation_url' => array(
					'text'   => esc_html__( 'View Documentation', 'best-education' ),
					'url'    => 'https://docs.thememattic.com/themes/best-education/',
					'button' => 'primary',
					),
				),

			// Getting started.
			'getting_started' => array(
				'one' => array(
					'title'       => esc_html__( 'Theme Documentation', 'best-education' ),
					'icon'        => 'dashicons dashicons-format-aside',
					'description' => esc_html__( 'Please check our full documentation for detailed information on how to setup and customize the theme.', 'best-education' ),
					'button_text' => esc_html__( 'View Documentation', 'best-education' ),
					'button_url'  => 'https://docs.thememattic.com/themes/best-education/',
					'button_type' => 'primary',
					'is_new_tab'  => true,
					),
				'two' => array(
					'title'       => esc_html__( 'Static Front Page', 'best-education' ),
					'icon'        => 'dashicons dashicons-admin-generic',
					'description' => esc_html__( 'To achieve custom home page other than blog listing, you need to create and set static front page.', 'best-education' ),
					'button_text' => esc_html__( 'Static Front Page', 'best-education' ),
					'button_url'  => admin_url( 'customize.php?autofocus[section]=static_front_page' ),
					'button_type' => 'primary',
					),
				'three' => array(
					'title'       => esc_html__( 'Theme Options', 'best-education' ),
					'icon'        => 'dashicons dashicons-admin-customizer',
					'description' => esc_html__( 'Theme uses Customizer API for theme options. Using the Customizer you can easily customize different aspects of the theme.', 'best-education' ),
					'button_text' => esc_html__( 'Customize', 'best-education' ),
					'button_url'  => wp_customize_url(),
					'button_type' => 'primary',
					),
				'four' => array(
					'title'       => esc_html__( 'Widget Ready', 'best-education' ),
					'icon'        => 'dashicons dashicons-admin-settings',
					'description' => esc_html__( 'Best Education is widget based Theme. Best Education has some pre designed custom additional layout.', 'best-education' ),
					'button_text' => esc_html__( 'View Widgets', 'best-education' ),
					'button_url'  => admin_url( 'widgets.php' ),
					'button_type' => 'secondary',
					),
				'five' => array(
					'title'       => esc_html__( 'Demo Content', 'best-education' ),
					'icon'        => 'dashicons dashicons-layout',
					'description' => sprintf( esc_html__( '%1$s plugin should be installed and activated. After plugin is activated, visit Import Demo Data menu under Appearance.', 'best-education' ), esc_html__( 'One Click Demo Import', 'best-education' ) ),
					'button_text' => esc_html__( 'Demo Content', 'best-education' ),
					'button_url'  => admin_url( 'themes.php?page=best-education-about&tab=demo-content' ),
					'button_type' => 'secondary',
					),
				'six' => array(
					'title'       => esc_html__( 'Theme Preview', 'best-education' ),
					'icon'        => 'dashicons dashicons-welcome-view-site',
					'description' => esc_html__( 'You can check out the theme demos for reference to find out what you can achieve using the theme and how it can be customized.', 'best-education' ),
					'button_text' => esc_html__( 'View Demo', 'best-education' ),
					'button_url'  => 'https://thememattic.com/theme-demos/?demo=best-education',
					'button_type' => 'secondary',
					'is_new_tab'  => true,
					),
				),

			// Support.
			'support' => array(
				'one' => array(
					'title'       => esc_html__( 'Contact Support', 'best-education' ),
					'icon'        => 'dashicons dashicons-sos',
					'description' => esc_html__( 'Got theme support question or found bug or got some feedbacks? Best place to ask your query is the dedicated Support forum for the theme.', 'best-education' ),
					'button_text' => esc_html__( 'Contact Support', 'best-education' ),
					'button_url'  => 'https://wordpress.org/support/theme/best-education/',
					'button_type' => 'secondary',
					'is_new_tab'  => true,
					),
				'two' => array(
					'title'       => esc_html__( 'Theme Documentation', 'best-education' ),
					'icon'        => 'dashicons dashicons-format-aside',
					'description' => esc_html__( 'Please check our full documentation for detailed information on how to setup and customize the theme.', 'best-education' ),
					'button_text' => esc_html__( 'View Documentation', 'best-education' ),
					'button_url'  => 'https://docs.thememattic.com/themes/best-education/',
					'button_type' => 'secondary',
					'is_new_tab'  => true,
					),
				'three' => array(
					'title'       => esc_html__( 'Child Theme', 'best-education' ),
					'icon'        => 'dashicons dashicons-admin-tools',
					'description' => esc_html__( 'For advanced theme customization, it is recommended to use child theme rather than modifying the theme file itself.', 'best-education' ),
					'button_text' => esc_html__( 'Learn More', 'best-education' ),
					'button_url'  => 'https://developer.wordpress.org/themes/advanced-topics/child-themes/',
					'button_type' => 'secondary',
					'is_new_tab'  => true,
					),
				),

			// Useful plugins.
			'useful_plugins' => array(
				'description' => esc_html__( 'Theme supports some helpful WordPress plugins to enhance your site. But, please enable only those plugins which you need in your site. For example, enable Contact Form 7 only if you are using it.', 'best-education' ),
				),

			// Demo content.
			'demo_content' => array(
				'description' => sprintf( esc_html__( 'To import demo content for this theme, %1$s plugin is needed. Please make sure plugin is installed and activated. After plugin is activated, you will see Import Demo Data menu under Appearance.', 'best-education' ), '<a href="https://wordpress.org/plugins/one-click-demo-import/" target="_blank">' . esc_html__( 'One Click Demo Import', 'best-education' ) . '</a>' ),
				),

			// Upgrade to pro.
			'upgrade_to_pro' => array(
				'description' => esc_html__( 'You can upgrade to pro version of the theme for more exciting features.', 'best-education' ),
				'button_text' => esc_html__( 'Upgrade to Pro','best-education' ),
				'button_url'  => 'https://thememattic.com/theme/best-education/',
				'button_type' => 'primary',
				'is_new_tab'  => true,
				),
			);

		Best_Education_About::init( $config );
	}

endif;

add_action( 'after_setup_theme', 'best_education_about_setup' );
