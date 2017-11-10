<?php

use Essentials\Data\Options;
use Essentials\Html\Writer;

/** @var $this OM_Google_Maps */

$map_key = Options::get('goodle_maps_api_key', '');

$map_url = '//maps.google.com/maps/api/js?v=3&amp;sensor=false&key=' . $map_key;

wp_enqueue_script('google-maps', $map_url, array(), false, true);

$instance = $this->settings;

$defined = array(
	'map-background' => '#2b1c65',
	'map-styles'     => '[{"featureType":"all","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#ff0000"},{"saturation":"-15"},{"lightness":"-14"},{"gamma":"0.75"},{"weight":"0.45"},{"invert_lightness":true}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#6600ff"},{"saturation":-11}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-78},{"hue":"#6600ff"},{"lightness":-47},{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"hue":"#5e00ff"},{"saturation":-79}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":30},{"weight":1.3}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#5e00ff"},{"saturation":-16}]},{"featureType":"transit.line","elementType":"all","stylers":[{"saturation":-72}]},{"featureType":"water","elementType":"all","stylers":[{"saturation":-65},{"hue":"#1900ff"},{"lightness":8}]}]',
);

if (empty($instance['center'])) {
	$place = '[40.689375,-74.044508]';
} else {
	$place = explode('|', $instance['center'], 2);
	$place = '[' . $place[0] . ']';
}

if (empty($instance['marker'])) {
	$marker = get_template_directory_uri() . '/assets/img/marker.png';
} else {
	$marker = ome_get_image($instance['marker']);
	$marker = $marker['full']['url'];
}

$data = array(
	'data-map-place'       => $place,
	'data-map-zoom'        => empty($instance['zoom_level']) ? '15' : $instance['zoom_level'],
	'data-map-bg'          => empty($instance['background_color']) ? $defined['map-background'] : $instance['background_color'],
	'data-map-scrollwheel' => empty($instance['scroll_to_zoom']) ? 'false' : $instance['scroll_to_zoom'],
	'data-map-draggable'   => empty($instance['draggable']) ? 'false' : $instance['draggable'],
	'data-map-styles'      => esc_attr(empty($instance['styles']) ? $defined['map-styles'] : rawurldecode(base64_decode($instance['styles']))),
	'data-map-icon'        => esc_url($marker),
	'data-map-controls'    => $this->get_controls(),
);
?>

<div class="map-place"<?php echo Writer::get_attributes_string($data); ?>></div>