<?php

/** @var $this OM_Device */

use Essentials\Html\Writer;

$instance = $this->settings;

$classes = array('block', sanitize_html_class($this->hash));
$attributes = array();

$css = isset($instance['css']) ? $instance['css'] : '';
$classes[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), $this->tag, $instance);

$attributes['class'] = implode(' ', $classes);

$device = array(
    'class' => 'device ' . (!empty($instance['device_type']) ? str_replace('_', ' ', sanitize_html_class($instance['device_type'])) : 'phone')
);

$html = Writer::init();

$html->div($attributes);

$html
    ->div($device)
    ->div(array('class' => 'device-content'))
    ->text(wpb_js_remove_wpautop($this->content))
    ->out();