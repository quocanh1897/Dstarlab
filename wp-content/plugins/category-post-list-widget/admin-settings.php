<?php 
function get_cplw_settings()
{
	$custom_css = "";
	if(isset($_POST['save_css']) && isset($_POST['custom_css']) )
	{
		$custom_css = $_POST['custom_css'];
		cplw_custom_css($custom_css);
	}

	$custom_css = get_option( 'custom_css');

?>
	<div class="custom-css-page">
		<h1>Custom CSS</h1>
		<form method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<td>
							<textarea cols="80" rows="20" name="custom_css" id="custom-css"><?php echo $custom_css; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" value="Save" class="button button-primary button-large" id="save-css" name="save_css">
						</td>
					</tr>		
				</tbody>			
			</table>
		</form>
	</div>
<?php }

function cplw_custom_css($custom_css)
{
	update_option( 'custom_css', $custom_css );
	$file = plugin_dir_path( __FILE__ ).'/css/cplw-custom-style.css';
    
    // Write the contents back to the file
    file_put_contents($file, $custom_css);
}

