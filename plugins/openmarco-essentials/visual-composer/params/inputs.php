<?php

use \Essentials\Html\Writer;

WpbakeryShortcodeParams::addField('inputs', function ($settings, $value) {
    $value = htmlspecialchars($value);

    $values = empty($value) ? array() : explode('|||', $value);

    $count = isset($settings['count']) && is_numeric($settings['count']) ? $settings['count'] : 2;

    $width = 100 / $count;

    $attributes = array(
        'name' => esc_attr($settings['param_name']),
        'class' => esc_attr("wpb_vc_param_value wpb-textinput {$settings['param_name']} {$settings['type']}"),
        'type' => 'hidden',
        'value' => esc_attr($value),
    );

    //return Writer::init()->input($attributes, true);

    $html = Writer::init()
        ->input($attributes, true)
        ->div('class="vc_row"');

    $attributes['type'] = 'text';
    $attributes['data-inputs'] = '';

    if (!empty($settings['placeholder']) && is_string($settings['placeholder'])) {
        $attributes['placeholder'] = esc_attr($settings['placeholder']);
    }

    for ($index = 0; $index < $count; $index++) {

        $attributes['name'] = esc_attr($settings['param_name']) . "[{$index}]";
        $attributes['value'] = isset($values[$index]) ? esc_attr(preg_replace('/[^\d]/', '', $values[$index])) : '';

        if (is_array($settings['placeholder']) && isset($settings['placeholder'][$index])) {
            $attributes['placeholder'] = esc_attr($settings['placeholder'][$index]);
        }

        $html->div(array('class' => 'vc_col-sm-12', 'style' => "width:{$width}%"))
            ->input($attributes, true)
            ->end();
    }

    return $html;
}, om_get_vc_params_script_url());