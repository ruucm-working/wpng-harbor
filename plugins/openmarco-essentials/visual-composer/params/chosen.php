<?php

use \Essentials\Html\Writer;

WpbakeryShortcodeParams::addField('chosen', function ($settings, $value) {
    if(is_string($value)) {
        $value = explode(',', $value);
    }

    if(!is_array($value)) {
        $value = array();
    }

    foreach($value as &$item) {
        $item = trim($item);
    }

    $attributes = array(
        'name' => esc_attr($settings['param_name']),
        'class' => esc_attr("wpb_vc_param_value wpb-select {$settings['param_name']} {$settings['type']}"),
        'data-chosen' => '100%'
    );

    if (!(isset($settings['single']) && $settings['single'])) {
        $attributes['multiple'] = 'multiple';
    }

    if (isset($settings['placeholder'])) {
        $attributes['data-placeholder'] = esc_attr($settings['placeholder']);
    }

    $html = Writer::init()->select($attributes);

    if (isset($settings['taxonomies'])) {
        $settings['options'] = get_terms($settings['taxonomies'], array(
            'fields' => 'id=>name',
            'hierarchical' => false,
            'hide_empty' => true
        ));
    }

    if (isset($settings['post_types'])) {
        $posts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => $settings['post_types'],
            'suppress_filters' => false
        ));

        $settings['options'] = from($posts)->toDictionary('$v->ID', '$v->post_title')->toArray();
    }

    if (isset($settings['options']) && is_array($settings['options'])) {
        foreach ($settings['options'] as $id => $name) {
            $attributes = array(
                'value'=> esc_attr($id)
            );

            if (in_array($id ,$value)) {
                $attributes['selected'] = 'selected';
            }

            $html->option($attributes, $name, true);
        }
    }

    return $html;
}, om_get_vc_params_script_url());