<?php

add_action('vc_before_init', function () {
    $params = array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Texts colors', 'theme'),
            'param_name' => 'background_lightness',
            'value' => array(
                esc_html__('Default', 'theme') => 'default',
                esc_html__('Light', 'theme') => 'dark',
                esc_html__('Dark', 'theme') => 'light',
            )
        ),
    );

    vc_add_params('vc_column', $params);
    vc_add_params('vc_column_inner', $params);
});

add_filter(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, function ($classes, $tag) {

    if ('vc_column' === $tag || 'vc_column_inner' === $tag) {
        $arguments = func_get_args();
        $attributes = isset($arguments[2]) ? $arguments[2] : array();

        if (!empty($attributes['background_lightness']) && 'default' !== $attributes['background_lightness']) {
            $classes .= ' background-' . sanitize_html_class(trim($attributes['background_lightness']));
        }
    }

    return $classes;
}, 10, 3);