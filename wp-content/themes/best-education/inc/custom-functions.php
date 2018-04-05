<?php
if ( ! function_exists( 'best_education_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 * @since best-education 1.0.0
 */
function best_education_the_custom_logo() {
    if ( function_exists( 'the_custom_logo' ) ) {
        the_custom_logo();
    }
}
endif;


if ( ! function_exists( 'best_education_body_class' ) ) :

	/**
	 * body class.
	 *
	 * @since 1.0.0
	 */
	function best_education_body_class($best_education_body_class) {
		global $post;
		$global_layout = best_education_get_option( 'global_layout' );
		$input = '';
		$home_content_status =	best_education_get_option( 'home_page_content_status' );
		if( 1 != $home_content_status ){
			$input = 'home-content-not-enabled';
		}
		// Check if single.
		if ( $post && is_singular() ) {
			$post_options = get_post_meta( $post->ID, 'best-education-meta-select-layout', true );
			if ( empty( $post_options ) ) {
				$global_layout = esc_attr( best_education_get_option('global_layout') );
			} else{
				$global_layout = esc_attr($post_options);
			}
		}
		if ($global_layout == 'left-sidebar') {
			$best_education_body_class[]= 'left-sidebar ' . esc_attr( $input );
		}
		elseif ($global_layout == 'no-sidebar') {
			$best_education_body_class[]= 'no-sidebar ' . esc_attr( $input );
		}
		else{
			$best_education_body_class[]= 'right-sidebar ' . esc_attr( $input );

		}
		return $best_education_body_class;
	}
endif;

add_action( 'body_class', 'best_education_body_class' );
/**
* Returns word count of the sentences.
*
* @since best-education 1.0.0
*/
if ( ! function_exists( 'best_education_words_count' ) ) :
	function best_education_words_count( $length = 25, $best_education_content = null ) {
		$length = absint( $length );
		$source_content = preg_replace( '`\[[^\]]*\]`', '', $best_education_content );
		$trimmed_content = wp_trim_words( $source_content, $length, '' );
		return $trimmed_content;
	}
endif;


if ( ! function_exists( 'best_education_simple_breadcrumb' ) ) :

	/**
	 * Simple breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function best_education_simple_breadcrumb() {

		if ( ! function_exists( 'breadcrumb_trail' ) ) {

			require_once get_template_directory() . '/assets/libraries/breadcrumbs/breadcrumbs.php';
		}

		$breadcrumb_args = array(
			'container'   => 'div',
			'show_browse' => false,
		);
		breadcrumb_trail( $breadcrumb_args );

	}

endif;


if ( ! function_exists( 'best_education_custom_posts_navigation' ) ) :
	/**
	 * Posts navigation.
	 *
	 * @since 1.0.0
	 */
	function best_education_custom_posts_navigation() {

		$pagination_type = best_education_get_option( 'pagination_type' );

		switch ( $pagination_type ) {

			case 'default':
				the_posts_navigation();
			break;

			case 'numeric':
				the_posts_pagination();
			break;

			default:
			break;
		}

	}
endif;

add_action( 'best_education_action_posts_navigation', 'best_education_custom_posts_navigation' );


if( ! function_exists( 'best_education_excerpt_length' ) && ! is_admin() ) :

    /**
     * Excerpt length
     *
     * @since  best-education 1.0.0
     *
     * @param null
     * @return int
     */
    function best_education_excerpt_length( $length ){
        global $best_education_customizer_all_values;
        $excerpt_length = $best_education_customizer_all_values['excerpt_length_global'];
        if ( empty( $excerpt_length) ) {
            $excerpt_length = $length;
        }
        return absint( $excerpt_length );

    }

endif;
add_filter( 'excerpt_length', 'best_education_excerpt_length', 999 );

/**
 * Recommended plugins
 *
 * @package best education
 */

if ( ! function_exists( 'best_education_recommended_plugins' ) ) :

	/**
	 * Recommend plugins.
	 *
	 * @since 1.0.0
	 */
	function best_education_recommended_plugins() {

		$plugins = array(
            array(
                'name'     => esc_html__( 'Education Connect', 'best-education' ),
                'slug'     => 'education-connect',
                'required' => false,
            ),
            array(
                'name'     => esc_html__( 'Contact Form 7', 'best-education' ),
                'slug'     => 'contact-form-7',
                'required' => false,
            ),
            array(
                'name'     => esc_html__( 'One Click Demo Import', 'best-education' ),
                'slug'     => 'one-click-demo-import',
                'required' => false,
            ),
		);

		tgmpa( $plugins );

	}

endif;

add_action( 'tgmpa_register', 'best_education_recommended_plugins' );

function best_education_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }

    return $title;
}

add_filter( 'get_the_archive_title', 'best_education_archive_title' );