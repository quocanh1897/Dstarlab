<?php
/**
 * The template for displaying search form
 * @package Impressive Business
 */
?>
<form method="get" class="navbar-form" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group add-on">
		<input type="text" id="srch-term" name="s" placeholder="<?php esc_attr_e('Search','impressive-business'); ?>" class="form-control" required="">
		<div class="input-group-btn">
			<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
		</div>
	</div>
</form>
