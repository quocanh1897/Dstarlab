<?php
/**
 * Implement theme metabox.
 *
 * @package best-education
 */

if ( ! function_exists( 'best_education_add_theme_post_type_meta_box' ) ) :

    /**
     * Add the Meta Box
     *
     * @since 1.0.0
     */
    function best_education_add_theme_post_type_meta_box() {

        $apply_metabox_post_types = array( 'testimonials', 'teams', 'courses', 'events' );

        foreach ( $apply_metabox_post_types as $key => $type ) {
            add_meta_box(
                'best-education-theme-settings',
                esc_html__( 'Single Post Settings', 'best-education' ),
                'best_education_render_theme_post_type_settings_metabox',
                $type
            );
        }

    }

endif;

add_action( 'add_meta_boxes', 'best_education_add_theme_post_type_meta_box' );

if ( ! function_exists( 'best_education_render_theme_post_type_settings_metabox' ) ) :

    /**
     * Render theme settings meta box.
     *
     * @since 1.0.0
     */
    function best_education_render_theme_post_type_settings_metabox( $post, $metabox ) {

        $post_id = $post->ID;
        $best_education_post_meta_value = get_post_meta($post_id);

        // Meta box nonce for verification.
        wp_nonce_field( basename( __FILE__ ), 'best_education_meta_box_nonce' );
        // Fetch Options list.
        $page_layout = get_post_meta($post_id,'best-education-meta-select-layout',true);
        $page_image_layout = get_post_meta($post_id,'best-education-meta-image-layout',true);
        ?>
        <div id="best-education-settings-metabox-container" class="best-education-settings-metabox-container">
            <div id="best-education-settings-metabox-tab-layout">
                <h4><?php echo __( 'Layout Settings', 'best-education' ); ?></h4>

                <div class="best-education-row-content">
                    <!-- Checkbox Field-->
                    <p>
                    <div class="best-education-row-content">
                        <label for="best-education-meta-checkbox">
                            <input type="checkbox" name="best-education-meta-checkbox" id="best-education-meta-checkbox"
                                   value="yes" <?php if (isset($best_education_post_meta_value['best-education-meta-checkbox'])) {
                                checked($best_education_post_meta_value['best-education-meta-checkbox'][0], 'yes');
                            }
                            ?>/>
                            <?php _e('Check To Use Featured Image As Banner Image', 'best-education') ?>
                        </label>
                    </div>
                    </p>
                </div><!-- .best-education-row-content -->
            </div><!-- #best-education-settings-metabox-tab-layout -->
        </div><!-- #best-education-settings-metabox-container -->

        <?php
    }

endif;



if ( ! function_exists( 'best_education_save_post_settings_meta' ) ) :

    /**
     * Save theme settings meta box value.
     *
     * @since 1.0.0
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post Post object.
     */
    function best_education_save_post_settings_meta( $post_id, $post ) {

        // Verify nonce.
        if ( ! isset( $_POST['best_education_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['best_education_meta_box_nonce'], basename( __FILE__ ) ) ) {
            return; }

        // Bail if auto save or revision.
        if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
            return;
        }

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
        if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
            return;
        }

        // Check permission.
        if ( 'page' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return; }
        } else if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        $best_education_meta_checkbox = isset($_POST['best-education-meta-checkbox']) ? esc_attr($_POST['best-education-meta-checkbox']) : '';
        update_post_meta($post_id, 'best-education-meta-checkbox', sanitize_text_field($best_education_meta_checkbox));
    }

endif;

add_action( 'save_post', 'best_education_save_post_settings_meta', 10, 2 );