<?php
/**
 * The main template file
 *
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Beus WP-Angular4
 * @since 1.0
 * @version 1.0
 */

if (is_user_logged_in())
	require get_template_directory()."/dist/index.html"; 
else {
	// echo 'Please Login';
	auth_redirect();
}

?>