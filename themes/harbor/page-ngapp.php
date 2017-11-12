<?php 
/* Template Name: Ng APP */
if (is_user_logged_in()) {
	require get_template_directory()."/dist/index.html"; 
}
else {
	auth_redirect();
}

?>
