<?php

/**
 *	Writing Debuggin log function
 */
function logw ($str) {
	error_log("$str\n", 3, plugin_dir_path( __FILE__ ) . "wordpress.log");
}
function logw_a ($arr) {
	$str = print_r($arr, true);
	error_log("$str\n", 3, plugin_dir_path( __FILE__ ) . "wordpress.log");
}
function logat ($str) {
	$timezone  = 9; //(GMT +9:00) EST (U.S. & Canada) 
	$d = gmdate("Y/m/j H:i:s", time() + 3600*($timezone+date("I")));
	error_log("[$d] $str\n", 3, "/srv/log/wordpress.log");
}
