<?php 
/**
 * The template for displaying sidebar
 * @package Impressive Business
 */  
if (is_active_sidebar('sidebar-1')) { ?>
	<?php dynamic_sidebar('sidebar-1'); ?>
<?php } ?>