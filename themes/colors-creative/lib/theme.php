<?php

use Essentials\Data\Options;

function om_get_theme_defined() {
	static $defined;
	
	if (!$defined) {
		$theme = 'default';
		
		$defined = array(
			'background'     => '#eaeaea',
			'map-background' => '#2b1c65',
			'map-styles'     => '[{"featureType":"all","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#ff0000"},{"saturation":"-15"},{"lightness":"-14"},{"gamma":"0.75"},{"weight":"0.45"},{"invert_lightness":true}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#6600ff"},{"saturation":-11}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-78},{"hue":"#6600ff"},{"lightness":-47},{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"hue":"#5e00ff"},{"saturation":-79}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":30},{"weight":1.3}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#5e00ff"},{"saturation":-16}]},{"featureType":"transit.line","elementType":"all","stylers":[{"saturation":-72}]},{"featureType":"water","elementType":"all","stylers":[{"saturation":-65},{"hue":"#1900ff"},{"lightness":8}]}]',
		);
		
		switch ($theme) {
			case 'blue-iris':
				$defined = array(
					'background'     => '#402f7e',
					'map-background' => '#2b1c65',
					'map-styles'     => '[{"featureType":"all","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#ff0000"},{"saturation":"-15"},{"lightness":"-14"},{"gamma":"0.75"},{"weight":"0.45"},{"invert_lightness":true}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#6600ff"},{"saturation":-11}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-78},{"hue":"#6600ff"},{"lightness":-47},{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"hue":"#5e00ff"},{"saturation":-79}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":30},{"weight":1.3}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#5e00ff"},{"saturation":-16}]},{"featureType":"transit.line","elementType":"all","stylers":[{"saturation":-72}]},{"featureType":"water","elementType":"all","stylers":[{"saturation":-65},{"hue":"#1900ff"},{"lightness":8}]}]',
				);
				break;
			case 'theme-black':
				$defined = array_merge($defined, array(
					'background'     => '#000',
					'map-background' => 'transparent',
					'map-styles'     => '[{"featureType":"all","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"all","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"all","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#333739"},{"weight":0.8}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#2ecc71"}]},{"featureType":"landscape.natural","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"color":"#2ecc71"},{"lightness":-7}]},{"featureType":"poi.park","elementType":"all","stylers":[{"color":"#2ecc71"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#333739"},{"weight":0.3},{"lightness":10}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#2ecc71"},{"lightness":-28}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#2ecc71"},{"visibility":"on"},{"lightness":-15}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#2ecc71"},{"lightness":-18}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#2ecc71"},{"lightness":-34}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#333739"}]}]',
				));
				break;
			case 'theme-mona-lisa':
				$defined = array_merge($defined, array(
					'background'     => '#efe9ea',
					'map-background' => 'transparent',
					'map-styles'     => '[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#ff6a6a"},{"lightness":"0"}]},{"featureType":"road.highway","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ff6a6a"},{"lightness":"75"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"lightness":"75"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit.station.bus","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit.station.rail","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit.station.rail","elementType":"labels.icon","stylers":[{"weight":"0.01"},{"hue":"#ff0028"},{"lightness":"0"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#80e4d8"},{"lightness":"25"},{"saturation":"-23"}]}]',
				));
				break;
		}
	}
	
	return $defined;
}

add_action('after_setup_theme', function () {
	add_image_size('small-width', 512);
	add_image_size('medium-width', 768);
	add_image_size('large-width', 1024);
	add_image_size('extra-large-width', 1412);
	
	load_theme_textdomain('colors-creative', get_template_directory() . '/lang');
});

add_filter('upload_mimes', function ($existing_mimes = array()) {
	$existing_mimes['svg'] = 'image/svg+xml';
	
	return $existing_mimes;
});

add_action('wp_head', function () {
	if (!om_is_essentials()) {
		return;
	}
	
	// Is mobile
	
	echo '<meta name="ismobile" content="', wp_is_mobile() ? 'true' : 'false', '"/>';
	
	// Is FastClick
	
	echo '<meta name="isfastclick" content="', (Options::specified('fast_click') && (bool)Options::get('fast_click')) ? 'true' : 'false', '"/>';
	
	// Page scroll
	
	$page_scroll = apply_filters('om_theme_page_scroll', Options::get('page_scroll'));
	
	if (!empty($page_scroll) && 'none' !== $page_scroll) {
		echo '<meta name="pagescroll" content="', esc_attr($page_scroll), '"/>';
	}
	
	// Lightbox
	
	$lightbox = array(
		'share' => array()
	);
	
	if (Options::specified('lightbox_fullscreen')) {
		$lightbox['fullscreen'] = (bool)Options::get('lightbox_fullscreen');
	}
	
	if (Options::specified('lightbox_zoom')) {
		$lightbox['zoom'] = (bool)Options::get('lightbox_zoom');
	}
	
	if (Options::specified('lightbox_share_download')) {
		$lightbox['share']['download'] = esc_html__('Download image', 'colors-creative');
	}
	if (Options::specified('lightbox_share_facebook')) {
		$lightbox['share']['facebook'] = esc_html__('Share on Facebook', 'colors-creative');
	}
	if (Options::specified('lightbox_share_twitter')) {
		$lightbox['share']['twitter'] = esc_html__('Tweet', 'colors-creative');
	}
	if (Options::specified('lightbox_share_pinterest')) {
		$lightbox['share']['pinterest'] = esc_html__('Pin it', 'colors-creative');
	}
	if (Options::specified('lightbox_share_tumblr')) {
		$lightbox['share']['tumblr'] = esc_html__('Share on Tumblr', 'colors-creative');
	}
	if (Options::specified('lightbox_share_google_plus')) {
		$lightbox['share']['google-plus'] = esc_html__('Share on Google+', 'colors-creative');
	}
	if (Options::specified('lightbox_share_vk')) {
		$lightbox['share']['vk'] = esc_html__('Share on VK', 'colors-creative');
	}
	if (Options::specified('lightbox_share_reddit')) {
		$lightbox['share']['reddit'] = esc_html__('Reddit this', 'colors-creative');
	}
	
	if (count($lightbox['share']) === 0) {
		unset ($lightbox['share']);
	}
	
	$lightbox = apply_filters('om_theme_lightbox_settings', $lightbox);
	
	if (count($lightbox) !== 0) {
		echo '<meta name="photoswipe" content="', esc_attr(json_encode($lightbox)), '"/>';
	}
});

add_filter('om_custom_title', 'om_custom_title');
add_filter('om_custom_subtitle', 'om_custom_title');

add_filter('tf_enqueue_google_webfont_colors-creative', function ($fontUrl) {
	return preg_replace('/^http:/', '', $fontUrl);
});

add_filter('tf_google_font_subsets_colors-creative', function ($subsets) {
	static $theme_subsets;
	
	if (!$theme_subsets) {
		$theme_subsets = $subsets;
		
		$extra = array('greek', 'greek-ext', 'vietnamese', 'cyrillic', 'cyrillic-ext');
		
		foreach ($extra as $subset) {
			if (Options::get('gfont_subset_' . $subset)) {
				$theme_subsets[] = $subset;
			}
		}
	}
	
	return $theme_subsets;
});

add_action('wp_footer', function () {
	if (om_is_essentials() && Options::specified('footer_javascript')) {
		$custom_js = apply_filters('om_theme_custom_scripts', Options::get('footer_javascript'));
		
		if (!empty($custom_js)) {
			echo '<script>', $custom_js, '</script>';
		}
	}
});

add_filter('ome_menu_items', function ($items) {
	global $wp;
	$current_url = home_url($wp->request);
	
	foreach ($items as &$item) {
		$url = preg_replace('/^\/+/', '', str_replace($current_url, '', $item->url));
		if (!empty($url) && $url[0] === '#') {
			$item->url     = $url;
			$item->current = false;
		}
	}
	
	return $items;
});