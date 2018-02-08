<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}
require plugin_dir_path(__FILE__) . "classes/class-ol-scrapes.php";
$OL_Scrapes = new OL_Lite_Scrapes();

$OL_Scrapes->clear_all_schedules();
$OL_Scrapes->clear_all_tasks();
$OL_Scrapes->clear_all_values();
