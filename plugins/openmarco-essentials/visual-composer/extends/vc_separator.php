<?php

add_action('vc_after_init', function () {
    $param = WPBMap::getParam('vc_separator', 'color');
    $value = array_merge(array(esc_html__('Theme default', 'theme') => 'default_color'), $param['value']);
    om_vc_update_shortcode_param_value('vc_separator', 'color', $value);
    om_vc_update_shortcode_param_setting('vc_separator', 'color', 'std', 'default_color');
});
