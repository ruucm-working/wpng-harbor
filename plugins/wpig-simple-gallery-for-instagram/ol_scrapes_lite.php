<?php

/*
  Plugin Name: Octolooks Scrapes Lite
  Plugin URI:
  Description: Copy content from websites & RSS feeds into WordPress automatically.
  Version:  1.0.0
  Author: Octolooks
  Author URI: https://codecanyon.net/user/octolooks?ref=Octolooks
  Text Domain: ol-scrapes-lite
  Domain Path: /languages
 */

if (!defined('ABSPATH'))
	exit;

require plugin_dir_path(__FILE__) . "classes/class-ol-scrapes.php";

define("OL_LITE_VERSION", "1.0.0");
define("OL_LITE_PLUGIN_PATH", plugin_dir_path(__FILE__));


add_action('admin_notices', array('OL_Lite_Scrapes', 'show_notice'));

$translates = array();

$OL_Scrapes = new OL_Lite_Scrapes();

register_activation_hook(__FILE__, array('OL_Lite_Scrapes', 'activate_plugin'));
register_deactivation_hook(__FILE__, array('OL_Lite_Scrapes', 'deactivate_plugin'));

$req_result = $OL_Scrapes->requirements_check();
if (!empty($req_result)) {
	set_transient("scrape_msg_req", $req_result);
	add_action('admin_init', array('OL_Lite_Scrapes', 'disable_plugin'));
} else {
	$current_encoding = mb_internal_encoding();
	mb_internal_encoding("UTF-8");
	$OL_Scrapes->add_admin_js_css();
	$OL_Scrapes->add_post_type();
	$OL_Scrapes->header_js_vars();
	$OL_Scrapes->save_post_handler();
	$OL_Scrapes->trash_post_handler();
	$OL_Scrapes->add_ajax_handler();
	$OL_Scrapes->create_cron_schedules();
	$OL_Scrapes->custom_column();
	$OL_Scrapes->remove_publish();
	$OL_Scrapes->register_shutdown();
	$OL_Scrapes->check_warnings();
	$OL_Scrapes->remove_pings();
	$OL_Scrapes->custom_start_stop_action();
	mb_internal_encoding($current_encoding);
}