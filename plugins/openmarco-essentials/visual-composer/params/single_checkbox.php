<?php

use \Essentials\Html\Writer;

WpbakeryShortcodeParams::addField('single_checkbox', function ($settings, $value) {
    $value = $value && $value != 'false';

    $hidden = array(
        'id' => esc_attr($settings['param_name']),
        'name' => esc_attr($settings['param_name']),
        'class' => esc_attr("wpb_vc_param_value {$settings['param_name']} {$settings['type']}"),
        'type' => 'hidden',
        'value' => $value ? 'true' : 'false'
    );

    $checkbox = array(
        'type' => 'checkbox',
        'data-single-checkbox' => esc_attr("#{$settings['param_name']}")
    );

    if ($value) {
        $checkbox['checked'] = 'checked';
    }

    return Writer::init()
        ->input($hidden, null, true)
        ->label('class="vc_checkbox-label"')
        ->input($checkbox, true)
        ->text(" {$settings['label']}");
}, om_get_vc_params_script_url());