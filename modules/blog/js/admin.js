/* *
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

// Global
var tmp;

// Alias
function get_alias( id, returnId ){
	tmp = returnId;
	var title = strip_tags( document.getElementById(id).value );
	if( title != '' ){
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_name_variable + '=' + nv_module_name + '&get_alias=' + encodeURIComponent( title ), '', 'get_alias_return');
	}
	return false;
}
function get_alias_return(res){
	if(res != ""){
		document.getElementById(tmp).value = res;
	}else{
		document.getElementById(tmp).value = '';
	}
	return false;
}
