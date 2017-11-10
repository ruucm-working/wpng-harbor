<?php

use \Essentials\Html\Writer;

WpbakeryShortcodeParams::addField('map', function ($settings, $value) {
    $value = htmlspecialchars( $value );

    $values = empty($value) ? array('','') : explode('|', $value, 2);

    $attributes = array(
        'name'=> esc_attr($settings['param_name']),
        'class' => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']}"),
        'type'=> 'hidden',
        'value'=> esc_attr($value),
    );

    $text = array(
        'name'=> esc_attr("{$settings['param_name']}-address"),
        'class' => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']} {$settings['type']}"),
        'value'=> esc_attr($values[1]),
        'data-map-address' => esc_attr($values[0])
    );
    
    return Writer::init()
        ->input($attributes, true)
        ->input($text, true);
}, om_get_vc_params_script_url());