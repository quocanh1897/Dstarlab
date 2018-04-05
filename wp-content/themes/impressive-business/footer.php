<?php 	$footer_copyrights = esc_html(get_theme_mod('footer_copyrights'));
		$footer_widget_style = esc_html(get_theme_mod('footer_widget_style',3));  ?>
			<section id="footer-section" data-aos="fadeOut" data-aos-offset="200" data-aos-duration="600">
				<div class="container">
					<div class="row">
					<?php  
					$hide_footer_widget_area = esc_html(get_theme_mod('hide_footer_widget_area', 1));
					if($hide_footer_widget_area == 1){
						$footer_widget_style = $footer_widget_style+1;
				        $footer_column_value = floor(12/($footer_widget_style)); ?>
				        <?php $k = 1; ?>
				            <?php for( $i=0; $i<$footer_widget_style; $i++) { ?>
				            <?php if (is_active_sidebar('footer-'.$k)) { ?>
							<div class="col-lg-<?php echo esc_attr($footer_column_value); ?> col-md-<?php echo esc_attr($footer_column_value); ?> col-sm-<?php echo esc_attr($footer_column_value); ?> col-xs-12 footer-responsive">
								<?php	dynamic_sidebar('footer-'.$k); ?>
							</div>
							<?php }
				                $k++;
				            }
				        }     ?>
					</div>
					<div class="footer-copy-text">
						<ul class="footer-icon">
						<?php for($impressive_business_i=1;$impressive_business_i<=10;$impressive_business_i++):
			                if (get_theme_mod('impressive_business_social_icon'.$impressive_business_i) != '' && get_theme_mod('impressive_business_social_icon_link'.$impressive_business_i) != '') {
			                $impressive_business_social_icon_link = get_theme_mod('impressive_business_social_icon_link'.$impressive_business_i);
			                $impressive_business_social_icon = get_theme_mod('impressive_business_social_icon'.$impressive_business_i); ?>
							<li><a href="<?php echo esc_url($impressive_business_social_icon_link); ?>"><i class="fa <?php echo esc_attr($impressive_business_social_icon); ?>"></i></a></li>
						<?php } 
			            endfor; ?>		
						</ul>
						<p><?php if($footer_copyrights != ''){
			                echo wp_kses_post(get_theme_mod('footer_copyrights')); 
			            } ?> <?php esc_html_e('Powered By ','impressive-business'); ?> <a href="<?php echo esc_url('https://voilathemes.com/wordpress-themes/impressive-business/','impressive-business'); ?>" target="_blank"><?php esc_html_e('Impressive Business WordPress Theme.','impressive-business'); ?></a></p>
					</div>
				</div>
			</section>
		<?php wp_footer(); ?>
	</body>
</html>