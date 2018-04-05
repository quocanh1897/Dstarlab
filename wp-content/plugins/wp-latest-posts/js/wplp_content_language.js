/** WPLP back-end jQuery script v.0.1 **/

(function($){
    $( document ).ready(function() {
        // Storage source type
        // Using to get content language
        selected_type = $('input[name=wplp_source_type]:checked').val();
        $('#selected_source_type').val(selected_type);

        $("input[name=wplp_source_type]").click(function(){
            var selected_type = $(this).val();
            $('#selected_source_type').val(selected_type);
        });
        // Storage blog for category list type content
        // Using to get content language
        $("select[name=wplp_mutilsite_cat_list]").change(function(){
            var selected_multisite_cat_list_post_type = $(this).val();
            $('#selected_multisite_cat_list_post_type').val(selected_multisite_cat_list_post_type);
        });
        // Storage blog for post type content
        // Using to get content language
        $("select[name=wplp_mutilsite_cat]").change(function(){
            var selected_multisite_post_type = $(this).val();
            $('#selected_multisite_post_type').val(selected_multisite_post_type);
        });
        // Storage blog for page type content
        // Using to get content language
        $("select[name=wplp_mutilsite_page]").change(function(){
            var selected_multisite_page_type = $(this).val();
            $('#selected_multisite_page_type').val(selected_multisite_page_type);
        });
        // Storage blog for tags type content
        // Using to get content language
        $("select[name=wplp_mutilsite_tag]").change(function(){
            var selected_multisite_tags_type = $(this).val();
            $('#selected_multisite_tags_type').val(selected_multisite_tags_type);
        });
        // Storage blog for custom post type content
        // Using to get content language

        $("select[name=wplp_content_language]").change(function(){
            var custom_posttype_language = $(this).val();
            $('#custom_posttype_language').val(custom_posttype_language);
            $('#selected_content_language').val(custom_posttype_language);
        });





        $("#content_language").on('change',function(){
            var current_page =  $('#selected_source_type').val();
            var blog_catlist = $('#selected_multisite_cat_list_post_type').val();
            var blog_post = $('#selected_multisite_post_type').val();
            var blog_page = $('#selected_multisite_page_type').val();
            var blog_tags = $('#selected_multisite_tags_type').val();
            var language = $(this).val();
            loading = '<div style="content-language-loading"><img src="' + content_language_param.plugin_dir + '/css/images/loading.gif"</div>';
            if(current_page == 'src_category'){
                $('ul.post_field').html(loading);
            }else if(current_page == 'src_page'){
                $('ul.page_field').html(loading);
            }else if(current_page == 'src_tags'){
                $('ul.tag_field').html(loading);
            }else if(current_page == 'src_category_list') {
                $('ul.catlist_field').html(loading);
            }
            $.ajax({
                url : ajaxurl,
                dataType : 'json',
                method : 'POST',
                data : {
                    action : 'change_source_type_by_language',
                    language : language,
                    page : current_page,
                    blog_post : blog_post,
                    blog_page : blog_page,
                    blog_tags : blog_tags,
                    blog_catlist : blog_catlist,
                    security : _token_name.check_change_content_language
                },success : function(res){
                    if(res.type == 'src_category'){
                        $('ul.post_field').not(".first_post_field").html(res.output);
                    }else if(res.type == 'src_page'){
                        $('ul.page_field').html(res.output);
                    }else if(res.type == 'src_tags'){
                        $('ul.tag_field').html(res.output);
                    }else if(res.type == 'src_category_list'){
                        $('ul.catlist_field').html(res.output);
                    }
                }
            });
        });

    });
})( jQuery );
