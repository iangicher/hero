<?php

/**
* Members Listing Template Plugin
*
* Lists all the members of the site
*
* @param string $var Variable name for each member's data
* @param string $[any_custom_field_name]
* @param string $username
* @param string $name
* @param string $email
* @param string $id
*/

function smarty_block_members ($params, $tagdata, &$smarty, &$repeat){
	if (!isset($params['var'])) {
		$smarty->trigger_error('You must specify a "var" parameter for template {members} calls.  This parameter specifies the variable name for the returned array.');
	}
	else {
		$filters = array();
		
		if (isset($params['id'])) {
			$filters['id'] = $params['id'];
		}
		
		if (isset($params['username'])) {
			$filters['username'] = $params['username'];
		}
		
		if (isset($params['name'])) {
			$filters['name'] = $params['name'];
		}
		
		if (isset($params['email'])) {
			$filters['email'] = $params['email'];
		}
		
		// custom field params
		$fields = $smarty->CI->user_model->get_custom_fields();
		
		foreach ($fields as $field) {
			if (isset($params[$field['name']])) {
				$filters[$field['name']] = $params[$field['name']];
			}
		}
		
		// initialize block loop
		$data_name = $smarty->loop_data_key($filters);
		
		if ($smarty->loop_data($data_name) === FALSE) {
			// make content request
			$smarty->CI->load->model('users/user_model');
			$users = $smarty->CI->user_model->get_users($filters);
		}
		else {
			$users = FALSE;
		}
		
		$smarty->block_loop($data_name, $users, (string)$params['var'], $repeat);
			
		echo $tagdata;
	}
}