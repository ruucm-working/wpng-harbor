<?php

/** @var $this OM_Container */

use Essentials\Html\Writer;

$instance = $this->settings;

$classes = array('block', sanitize_html_class($this->hash));
$attributes = array();
$wrapper = array('class' => 'block-wrapper');

if(!empty($instance['content_position'])) {
    $classes[] = 'block-' . sanitize_html_class($instance['content_position']);
}

if(!empty($instance['height_mode'])) {
    if('full' === $instance['height_mode']) {
        $classes[] = 'block-full-height';
    } else if ('viewport' === $instance['height_mode']
        && !empty($instance['height_percent'])) {
        $wrapper['data-vhmin'] = esc_attr($instance['height_percent']);
    }
}

$css = isset($instance['css']) ? $instance['css'] : '';
$classes[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), $this->tag, $instance);

if(!empty($instance['extra_classes'])) {
    $classes[] = esc_attr($instance['extra_classes']);
}

$attributes['class'] = implode(' ', $classes);



$html = Writer::init();

$html->div($attributes);

if(!empty($instance['overlay_color'])) {
    $html->div('class="block-layer block-overlay"', null, true);
}

$html
    ->div($wrapper)
    ->div(array('class' => 'block-content'))
    ->text(wpb_js_remove_wpautop($this->content))
    ->out();