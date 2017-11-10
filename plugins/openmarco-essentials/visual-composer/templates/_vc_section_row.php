<?php
use Essentials\Data\Options;
use Essentials\Html\Writer;

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $parallax_float_content
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $content - shortcode content
 * @var $navigation_bar_transparency
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */

$output = $after_output = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);

extract($atts);

wp_enqueue_script('wpb_composer_front_js');

$el_class = $this->getExtraClass($el_class);

$css_classes = array(
    'vc_row',
    //'wpb_row', //deprecated
    'vc_row-fluid',
    $el_class,
    vc_shortcode_custom_css_class($css),
);

$css_class = preg_replace('/\s+/', ' ', apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode(' ', array_filter($css_classes)), $this->settings['base'], $atts));

$background_color_behavior = Options::get('background_color_behavior_current');

if (empty($section_size)) {
    $section_size = Options::get('content_section_size');
}

$container_class = 'container';

if ($this->shortcode === 'vc_row_inner') {
    $container_class .= '-full';
} elseif (!empty($full_width)) {
    if ($full_width === 'stretch_row_content') {
        $container_class .= '-fluid';
    } else if ($full_width === 'stretch_row_content_no_spaces') {
        $container_class .= '-full';
    }
}

$section_inside_class = $section_inside && $section_inside !== 'false' ? 'section-inside' : '';

$hash = \Essentials\Html\Base_Shortcode::hash('vc_row', $this->atts);

// Section attributes
$section_atts = array(
    'class' => esc_attr(trim("section {$section_size} {$section_inside_class} {$hash} behavior-{$background_color_behavior}"))
);

if ($this->shortcode === 'vc_row') {
    $navigation_bar_transparency = is_numeric($navigation_bar_transparency) ? $navigation_bar_transparency : 1;
    $navigation_bar_transparency = max(min($navigation_bar_transparency, 1), 0);
    // Add shifting to non-inner sections
    $section_atts = array_merge($section_atts, ome_get_shifting_attributes($section_color, null, $navigation_bar_transparency));
}

if (isset($el_id) && !empty($el_id)) {
    $section_atts['id'] = esc_attr($el_id);
}

if (!empty($section_color) && $background_color_behavior === 'static') {
    $section_atts['class'] .= ' background-' . (ome_is_dark($section_color) ? 'dark' : 'light');
}

$default_parallax_attributes = array(
    'style' => 'height:150%',
    'data-scroll-animate' => "transform:translate3d(0,$1%,0)",
    'data-scroll-trigger' => ".section",
    'data-0_0' => -33.3,
    'data-100_100' => '0'
);

// use default video if user checked video, but didn't chose url
if (!empty($video_bg) && empty($video_bg_url)) {
    $video_bg_url = 'https://www.youtube.com/watch?v=lMJXxhRFO1k';
}

$has_video_bg = (!empty($video_bg) && !empty($video_bg_url) && vc_extract_youtube_id($video_bg_url));

$parallax_attributes = array('style' => '');

if ($has_video_bg) {
    $parallax = $video_bg_parallax;
}

if ($parallax) {
    $parallax_attributes = $default_parallax_attributes;
}

$html = Writer::init()->div($section_atts);

if (!empty($section_color) && $background_color_behavior === 'static') {
    $html->div(array('class' => 'section-background section-background-main'), null, true);
}

if (!empty($section_images)) {
    $attributes = array_merge($parallax_attributes, array(
        'class' => 'section-background section-background-image',
    ));

    $html->div($attributes, null, true);
}


if ($has_video_bg) {
    wp_enqueue_script('vc_youtube_iframe_api_js');

    $attributes = array(
        'class' => 'section-background section-background-video',
        'data-video-src' => esc_url($video_bg_url),
    );

    if (!empty($video_bg_parallax)) {
        $attributes = array_merge($attributes, $default_parallax_attributes);
    }

    $html->div($attributes, null, true);
}

if (!empty($section_overlay_color)) {
    $attributes = array_merge($parallax_attributes, array(
        'class' => 'section-background section-background-overlay',
    ));

    $html->div($attributes, null, true);
}

$container_atts = array('class' => $container_class);

if ($parallax && $parallax_float_content == 'true') {
    $container_atts = array_merge($container_atts, array(
        'data-scroll-animate' => 'transform:translateY($1%)',
        'data-scroll-trigger' => ".section",
        'data-0_0' => -60,
        'data-50_50' => 0,
        'data-75_75' => 30,
        'data-100_100' => 60,
    ));
}

if (strpos($parallax, 'fade')) {
    if ($parallax_float_content == 'true') {
        $container_atts['data-scroll-animate'] .= ';opacity';
        $container_atts['data-0_0'] .= ';1';
        $container_atts['data-50_50'] .= ';1';
        $container_atts['data-75_75'] .= ';1';
        $container_atts['data-100_100'] .= ';0';
    } else {
        $container_atts = array_merge($container_atts, array(
            'data-scroll-animate' => "opacity",
            'data-scroll-trigger' => ".section",
            'data-100_50' => 1,
            'data-100_80' => '0'
        ));
    }
}

if (!empty($full_height)) {
    $flex_placement = empty($content_placement) ? 'top' : $content_placement;

    $html
        ->div(array('class' => 'section-flex', 'data-vhmin' => '100'))
        ->div(array('class' => 'section-flex-' . sanitize_html_class($flex_placement)));
}

$html
    ->div($container_atts)
    ->div(array('class' => 'section-content'))
    ->div(array('class' => esc_attr($css_class)))
    ->text(wpb_js_remove_wpautop($content))
    ->out();
