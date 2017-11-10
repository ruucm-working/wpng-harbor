<?php

use \Essentials\Html\Writer;

WpbakeryShortcodeParams::addField('number', function ($settings, $value) {
    $value = htmlspecialchars( $value );

    $attributes = array(
        'name'=> esc_attr($settings['param_name']),
        'class' => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']} {$settings['type']}"),
        'type'=> 'number',
        'value'=> esc_attr($value),
    );

    if(isset($settings['min'])) {
        $attributes['min'] = esc_attr($settings['min']);
    }

    if(isset($settings['max'])) {
        $attributes['max'] = esc_attr($settings['max']);
    }

    if(!empty($settings['step'])) {
        $attributes['step'] = esc_attr($settings['step']);
    }

    if(isset($settings['placeholder'])) {
        $attributes['placeholder'] = esc_attr($settings['placeholder']);
    }

    return Writer::init()->input($attributes, true);
}, om_get_vc_params_script_url());