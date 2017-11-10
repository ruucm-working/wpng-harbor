<?php

add_action('vc_before_init', function () {
	vc_add_params('vc_icon', array(
		array(
			'type'        => 'iconpicker',
			'heading'     => esc_html__('Icon', 'js_composer'),
			'param_name'  => 'icon_ionicons',
			'value'       => 'ion-alert',
			'settings'    => array(
				'emptyIcon'    => false,
				'type'         => 'ionicons',
				'iconsPerPage' => 4000,
			),
			'dependency'  => array(
				'element' => 'type',
				'value'   => 'ionicons',
			),
			'description' => esc_html__('Select icon from library.', 'theme'),
			'weight'      => 90,
		),
		array(
			'type'        => 'iconpicker',
			'heading'     => esc_html__('Icon', 'js_composer'),
			'param_name'  => 'icon_et_line',
			'value'       => 'et-icon-mobile',
			'settings'    => array(
				'emptyIcon'    => false,
				'type'         => 'et_line',
				'iconsPerPage' => 4000,
			),
			'dependency'  => array(
				'element' => 'type',
				'value'   => 'et_line',
			),
			'description' => esc_html__('Select icon from library.', 'theme'),
			'weight'      => 90,
		),
		array(
			'param_name'  => 'background_color_custom',
			'type'        => 'colorpicker',
			'heading'     => esc_html__('Custom background color', 'theme'),
			'dependency'  => array(
				'element' => 'background_color',
				'value'   => 'custom',
			),
			'description' => esc_html__('Select custom background color.', 'theme'),
			'weight'      => 60
		)
	));
});

add_action('vc_after_init', function () {
	$param = WPBMap::getParam('vc_icon', 'type');
	$param['value'][esc_html__('Ionicons', 'theme')] = 'ionicons';
	$param['value'][esc_html__('Elegant Themes 100 Line Icons', 'theme')] = 'et_line';
	om_vc_update_shortcode_param_value('vc_icon', 'type', $param['value']);
	om_vc_update_shortcode_param_weight('vc_icon', 'type', 100);

	om_vc_update_shortcode_param_weight('vc_icon', 'color', 80);
	om_vc_update_shortcode_param_weight('vc_icon', 'custom_color', 80);
	om_vc_update_shortcode_param_weight('vc_icon', 'background_style', 80);

	$param = WPBMap::getParam('vc_icon', 'background_color');
	$param['value'][esc_html__('Custom', 'theme')] = 'custom';
	om_vc_update_shortcode_param_value('vc_icon', 'background_color', $param['value']);
	om_vc_update_shortcode_param_weight('vc_icon', 'background_color', 70);

});
/*vc_enqueue_font_icon_element*/
add_action('admin_enqueue_scripts', function () {
	wp_enqueue_style('ionicons', get_template_directory_uri() . '/assets/css/ionicons.min.css');
	wp_enqueue_style('et-line', get_template_directory_uri() . '/assets/css/et-line.css');
});