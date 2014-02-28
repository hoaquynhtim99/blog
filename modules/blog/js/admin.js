/* *
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

// Global
var tmp, nv_timer;

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

// Thao tac tra ve
function nv_delete_result( res ){
	if( res == 'OK' ){
		window.location.href = window.location.href;
	}else{
		alert( nv_is_del_confirm[2] );
	}
	return false;
}
function nv_change_status_result( res ){
	if( res != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
		window.location.href = window.location.href;
	}
	return;
}
function nv_change_status_list_result( res ){
	if( res != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
	}
	window.location.href = window.location.href;
	return;
}
function nv_chang_weight_result( res ){
	if ( res != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
	}
	clearTimeout( nv_timer );
	window.location.href = window.location.href;
	return;
}

// Thao tac voi email dang ky nhan tin
function nv_newsletters_action(oForm, nv_message_no_check, key) {
	var fa = oForm['idcheck[]'];
	var listid = [];
	
	if (fa.length){
		for ( var i = 0; i < fa.length; i++){
			if (fa[i].checked){
				listid.push(fa[i].value);
			}
		}
	}else{
		if(fa.checked){
			listid.push(fa.value);
		}
	}
	
	if (listid != ''){
		if (key == 1){
			if ( confirm(nv_is_del_confirm[0]) ){
				nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&del=1&listid=' + listid, '', 'nv_delete_result');
			}
		}else if (key == 2){
			nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&changestatus=1&status=1&listid=' + listid, '', 'nv_change_status_list_result');
		}else if (key == 3){
			nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&changestatus=1&status=2&listid=' + listid, '', 'nv_change_status_list_result');
		}
	}else{
		alert(nv_message_no_check);
	}
}
function nv_delete_newsletters( id ){
	if ( confirm( nv_is_del_confirm[0] ) ){
		nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&del=1&id=' + id, '', 'nv_delete_result' );
	}
	return false;
}

// Thao tac voi danh muc tin
function nv_change_cat_status( id ){
	var nv_timer = nv_settimeout_disable( 'change_status' + id, 4000 );
	nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&changestatus=1&id=' + id, '', 'nv_change_status_result' );
	return;
}
function nv_change_cat_weight( id ){
	var nv_timer = nv_settimeout_disable( 'weight' + id, 5000 );
	var newpos = document.getElementById( 'weight' + id ).options[document.getElementById( 'weight' + id ).selectedIndex].value;
	nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&changeweight=1&id=' + id + '&new=' + newpos + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_weight_result' );
	return;
}
function nv_delete_cat( id ){
	if ( confirm( nv_is_del_confirm[0] ) ){
		nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&del=1&id=' + id, '', 'nv_delete_result' );
	}
	return false;
}

// Thao tac voi tags
function nv_tags_action(oForm, nv_message_no_check, key) {
	var fa = oForm['idcheck[]'];
	var listid = [];
	
	if (fa.length){
		for ( var i = 0; i < fa.length; i++){
			if (fa[i].checked){
				listid.push(fa[i].value);
			}
		}
	}else{
		if(fa.checked){
			listid.push(fa.value);
		}
	}
	
	if (listid != ''){
		if (key == 1){
			if ( confirm(nv_is_del_confirm[0]) ){
				nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&del=1&listid=' + listid, '', 'nv_delete_result');
			}
		}
	}else{
		alert(nv_message_no_check);
	}
}
function nv_delete_tags( id ){
	if ( confirm( nv_is_del_confirm[0] ) ){
		nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&del=1&id=' + id, '', 'nv_delete_result' );
	}
	return false;
}