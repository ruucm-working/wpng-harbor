<?php 
/* Template Name: Ng Admin */
if (current_user_can('administrator')) {
	$current_user = wp_get_current_user();
	echo '<div id="wp-username" style="visibility: hidden;height: 50px;margin-top: -50px;">' . $current_user->display_name. '</div>';
	if (current_user_can('administrator')) {
		echo '<div id="wp-admin" style="visibility: hidden;height: 50px;margin-top: -50px;">is admin</div>';
	}
	echo '<div id="wp-route" style="visibility: hidden;height: 50px;margin-top: -50px;">app/admin</div>';

	require get_template_directory()."/dist/index.html"; 
}
else {
	auth_redirect();
}

?>
