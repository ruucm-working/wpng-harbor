<?php

add_action('vc_after_init', function () {
    $param = WPBMap::getParam('vc_btn', 'style');

    $value = $param['value'];
    $first = array_slice($param['value'], 0, 1, TRUE);
    $last = array_slice($param['value'], -1, 1, TRUE);
    unset($value[key($first)]); // modern button removed
    unset($value[key($last)]);

    $value = array_merge($value, array(esc_html__('Link', 'theme') => 'link'), $last);

    om_vc_update_shortcode_param_value('vc_btn', 'style', $value);

    $param = WPBMap::getParam('vc_btn', 'size');
    $value = array_merge($param['value'], array(
            esc_html__('Extra large', 'theme') => 'xl',
        )
    );
    om_vc_update_shortcode_param_value('vc_btn', 'size', $value);
});