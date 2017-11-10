<?php

use Essentials\Data\Options;
use Essentials\Html\Writer;
use Essentials\Utils\Lightbox;

add_action('vc_before_init', function () {
    vc_add_params('vc_single_image', array(
        array(
            'group' => 'Advanced',
            'param_name' => 'hover_image',
            'type' => 'attach_image',
            'heading' => esc_html__('Hover image', 'theme'),
            'description' => esc_html__('Image shown on mouseover', 'theme'),
        ),
        array(
            'group' => 'Advanced',
            'type' => 'dropdown',
            'heading' => esc_html__('Lightbox group', 'theme'),
            'param_name' => 'lightbox_group',
            'value' => array(
                esc_html__('Group 1', 'theme') => 'group-1',
                esc_html__('Group 2', 'theme') => 'group-2',
                esc_html__('Group 3', 'theme') => 'group-3',
                esc_html__('Group 4', 'theme') => 'group-4',
                esc_html__('Group 5', 'theme') => 'group-5',
                esc_html__('Group 6', 'theme') => 'group-6',
                esc_html__('Group 7', 'theme') => 'group-7',
                esc_html__('Group 8', 'theme') => 'group-8',
                esc_html__('Group 9', 'theme') => 'group-9',
                esc_html__('Group 10', 'theme') => 'group-10',
                esc_html__('Group 11', 'theme') => 'group-11',
                esc_html__('Group 12', 'theme') => 'group-12',
            ),
            'dependency' => array(
                'element' => 'onclick',
                'value' => 'link_image',
            ),
        ),
    ));
});

add_action('vc_after_init', function () {
    $onclick_param = WPBMap::getParam('vc_single_image', 'onclick');

    if(isset($onclick_param['value']) && is_array($onclick_param['value'])) {

        $onclick_options = array();

        foreach ($onclick_param['value'] as $title => $value) {
            if ($value === 'link_image') {
                $title = __('Open lightbox', 'theme');
            }

            $onclick_options[$title] = $value;
        }

        om_vc_update_shortcode_param_value('vc_single_image', 'onclick', $onclick_options);
    }
});

add_filter('vc_shortcode_output', function ($output, $element, $attributes) {

    if ($element instanceof WPBakeryShortCode_VC_Single_image) {
        if (!empty($attributes['onclick']) && 'link_image' === $attributes['onclick']
            && !empty($attributes['image'])
        ) {

            if(empty($attributes['lightbox_group'])) {
                $attributes['lightbox_group'] = 'group-1';
            }

            $image_info = wp_get_attachment_image_src($attributes['image'], 'full');

            if (is_array($image_info)) {
                $data = array(
                    'data-photoswipe-group' => $attributes['lightbox_group'],
                    'data-item' => esc_url($image_info[0]),
                    'data-size' => $image_info[1] . 'x' . $image_info[2]
                );

                if(!Options::get('lightbox_title_disable')) {
                    $caption = Lightbox::get_image_caption($attributes['image']);

                    if (!empty($caption)) {
                        $data['data-title'] = $caption;
                    }
                }

                $output = str_replace(array('<a rel="prettyPhoto',' class="prettyphoto"', 'prettyphoto'), array('<a' . Writer::get_attributes_string($data) . ' rel="prettyPhoto','', ''), $output);
                $output = preg_replace('/<a data-rel="prettyPhoto\[.+\]"/','<a' . Writer::get_attributes_string($data),$output);
            }
        }

        if (!empty($attributes['hover_image'])) {
            $image_info = wp_get_attachment_image_src($attributes['hover_image'], empty($attributes['img_size']) ? 'thumbnail' : $attributes['img_size']);

            if (is_array($image_info)) {
                $output = preg_replace('/<img/',
                    '<div class="vc_single_image_hover" style="background-image:url(\'' . $image_info[0] . '\')"></div><img',
                    $output);
            }
        }
    }

    return $output;
}, 10, 3);