/* *
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */
 
// Comment click
$(document).ready(function(){
	$('a[href^="#"]').click(function(e){
		e.preventDefault();
	});	
});

var BL = {
	siteroot: nv_siteroot,
	sitelang: nv_sitelang,
	name_variable: nv_name_variable,
	module_name: nv_module_name,
	op_variable: nv_fc_variable,
	callApi: function(q){
		$.ajax({
			type: 'POST',
			url: BL.siteroot + 'index.php',
			data: BL.lang_variable + '=' + BL.sitelang + '&' + BL.name_variable + '=' + BL.module_name + '&' + BL.op_variable + '=api&' + q,
			success: function(e){}
		});
	},
	addCommentOnly: function(blog_id){
		BL.callApi('addCommentOnly=1&id=' + blog_id)
	},
	delCommentOnly: function(blog_id){
		BL.callApi('delCommentOnly=1&id=' + blog_id);
	},
};