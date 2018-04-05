//tinyMCEPopup.requireLangPack();
	
var CPLWInsertDialog = {
	init : function() {		
	    var shortcode; var strCat; var strWidth; var strHeight; var effects; var display; var strNoOfPosts; var time; var sortBy; var excerptLength; var thumb_w; var thumb_h; var date_format;var strTaxm;var strPostType;var strCatTaxm;
	    jQuery('.btn_add').live("click",function(e){
	    	
			jQuery("#add_CPLWshortcode").validate();

			jQuery.validator.addMethod("NumberValidation", function(value, element) {   
		     	return this.optional(element) || /^[0-9\-\s]+$/i.test(value);
		    }, "Username must contain only numbers, or dashes.");
			
			var form = jQuery("#add_CPLWshortcode");		
			
			if(form.valid())
			{
				update_CPLWshortcode();				
			}
			else
			{
				return false;
			}        
	    });

		function update_CPLWshortcode() {
						
			strTaxm       = ' taxanomy="all" ';
			strWidth      = ' width="500" ';
			strHeight     = ' height="500" ';
			effects       = ' effects="none" ';
			strDisplay    = ' display="title" ' ;	
			strNoOfPosts  = ' posts_to_show="1" ' ;
			time          = ' time="1000" ' ;
			sortBy        = ' order_by="date" ' ;
			sort          = ' order="DESC" ' ;
			thumb_width   = ' thumb_width="150" ';
			excerptLength = ' excerpt_length="10" ' ;
			thumb_height  = ' thumb_height="150" ';
			date_format   = ' date_format="F j, Y" ';
			strCatTaxm	  = ' taxanomy_term="all" ';
			strPostType	  = ' post_type="post" ';
			

			if (jQuery('#postType').val() !='' && jQuery('#postType').val() != '0') {
				strPostType = ' post_type="'+jQuery('#postType').val()+'"';
			}	

			if (jQuery('#taxm').val() !='' && jQuery('#taxm').val() != '0') {
				strTaxm = ' taxanomy="'+jQuery('#taxm').val()+'"';
			}

			if (jQuery('#cat_taxm').val() !='' && jQuery('#cat_taxm').val() != '0') {
				strCatTaxm = ' taxanomy_term="'+jQuery('#cat_taxm').val()+'"';
			}

			if (jQuery('#width').val() !='' && isNaN(jQuery('#height').val()) == false) {	
				strWidth = ' width="'+jQuery('#width').val()+'"';
			}

			if (jQuery('#height').val() !='' && isNaN(jQuery('#height').val()) == false) {	
				strHeight = ' height="'+jQuery('#height').val()+'"';
				
			}
			
			if ((jQuery('#effects').val() != '') ) {
				effects = ' effects="'+jQuery('#effects').val()+'"';
			}
			
				
			if (jQuery('#num').val() !='') {
				strNoOfPosts = ' posts_to_show="'+jQuery('#num').val()+'"';
			}
			
			if (jQuery('select#display option:selected').length > 0) {						
				strDisplay = ' display="'+jQuery('#display').val()+'"';
			}

			if (jQuery('#effects_duration').val() !='') {
				time = ' time="'+jQuery('#effects_duration').val()+'"';
			}

			if (jQuery('#sort_by').val() !='') {
				sortBy = ' order_by="'+jQuery('#sort_by').val()+'"';				
			}

			if (jQuery('#sort_order').val() !='') {
				sort = ' order="'+jQuery('#sort_order').val()+'"';
			}

			if (jQuery('#excerpt_length').val() !='') {
				excerptLength = ' excerpt_length="'+jQuery('#excerpt_length').val()+'"';
			}

			if (jQuery('#thumb_w').val() !='' && isNaN(jQuery('#thumb_w').val()) == false) {
				thumb_width = ' thumb_width="'+jQuery('#thumb_w').val()+'"';
			}

			if (jQuery('#thumb_h').val() !='' && isNaN(jQuery('#thumb_h').val()) == false) {
				thumb_height = ' thumb_height="'+jQuery('#thumb_h').val()+'"';
			}

			if (jQuery('#date_format').val() !='') {
				date_format = ' date_format="'+jQuery('#date_format').val()+'"';
			}

			var newsc = '[cplw '+strPostType+strTaxm+strCatTaxm+strWidth+strHeight+effects+strNoOfPosts+time+sortBy+excerptLength+thumb_width+thumb_height+date_format+strDisplay+sort+']';

			jQuery('#shortcode').val(newsc);
		}		
	},
	insert : function() {		
		// insert the contents from the input into the document		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, jQuery('#shortcode').val());
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(CPLWInsertDialog.init, CPLWInsertDialog);

// function to check height and width is number or not
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}