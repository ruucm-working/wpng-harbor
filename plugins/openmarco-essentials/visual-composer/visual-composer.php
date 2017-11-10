<?php

//vc_disable_frontend();
vc_set_shortcodes_templates_dir(__DIR__ . '/templates');

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/params.php';
require_once __DIR__ . '/extends.php';
require_once __DIR__ . '/shortcodes/shortcodes.php';

add_action('admin_enqueue_scripts', function () {
	$theme = wp_get_theme();
	
	wp_enqueue_style(
		'colors-creative-admin-visual-composer',
		get_template_directory_uri() . '/assets/css/admin/visual-composer.css',
		null,
		$theme->Version);
});

add_action('vc_before_init', function () {
	vc_set_as_theme();
	
	vc_set_default_editor_post_types(array('post', 'page', 'project'));
});

add_action('vc_after_init', function () {
	global $wp_filter;
	
	if(isset($wp_filter['upgrader_pre_download'][10])) {
		$filters = $wp_filter['upgrader_pre_download'][10];
		
		foreach ($filters as $filter) {
			if (isset($filter['function'])
			    && is_array($filter['function'])
			    && $filter['function'][0] instanceof Vc_Updater
			    && $filter['function'][1] === 'preUpgradeFilter'
			) {
				remove_filter('upgrader_pre_download', array($filter['function'][0], $filter['function'][1]));
			}
			
		}
	}
	
});

/**
 * Return search form
 *
 * @return string
 */
function om_vc_wp_get_search_form()
{
	remove_filter('get_search_form', 'om_vc_wp_get_search_form');
	
	ob_start();
	locate_template('parts/search-form.php', true, false);
	return ob_get_clean();
}

/**
 * Update specific shortcode parameter setting
 *
 * @param $shortcode string Shortcode name
 * @param $name string Parameter name
 * @param $setting string Setting name
 * @param $value mixed New setting value
 */
function om_vc_update_shortcode_param_setting($shortcode, $name, $setting, $value)
{
	$param = WPBMap::getParam($shortcode, $name);
	$param[$setting] = $value;
	vc_update_shortcode_param($shortcode, $param);
}

/**
 * Update shortcode parameter value
 *
 * @param $shortcode string Shortcode name
 * @param $name string Parameter name
 * @param $value mixed New value
 */
function om_vc_update_shortcode_param_value($shortcode, $name, $value)
{
	om_vc_update_shortcode_param_setting($shortcode, $name, 'value', $value);
}

/*
 * Update specific shortcode parameter width
 *
 * @param $shortcode string Shortcode name
 * @param $name string Parameter name
 * @param $value mixed New width value
 */
function om_vc_update_shortcode_param_weight($shortcode, $name, $weight)
{
	om_vc_update_shortcode_param_setting($shortcode, $name, 'weight', $weight);
}