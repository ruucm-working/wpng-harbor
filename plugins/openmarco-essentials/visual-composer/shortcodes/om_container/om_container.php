<?php

use Essentials\Html\Css;

class OM_Container extends Essentials\Html\Base_Shortcode
{
    public function init()
    {
        $this->parameters = array(
            'category' => esc_html__('Colors Creative', 'theme'),
            'name' => esc_html__('Container', 'theme'),
            'description' => esc_html__('Container', 'theme'),
            'as_parent' => array('except' => 'vc_row,om_container'),
            'content_element' => true,
            'show_settings_on_create' => false,
            'params' => array(
                array(
                    'group' => 'General',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Height', 'theme'),
                    'param_name' => 'height_mode',
                    'value' => array(
                        esc_html__('Initial', 'theme') => 'initial',
                        esc_html__('100% of container', 'theme') => 'full',
                        esc_html__('Percent of viewport', 'theme') => 'viewport'
                    ),
                    'std' => 'initial',
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'height_percent',
                    'type' => 'number',
                    'heading' => esc_html__('Height percent', 'theme'),
                    'min'=> '1',
                    'max'=> '100',
                    'step'=> '1',
                    'dependency' => array(
                        'element' => 'height_mode',
                        'value' => 'viewport',
                    ),
                ),
                array(
                    'group' => 'General',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Content position', 'theme'),
                    'param_name' => 'content_position',
                    'value' => array(
                        esc_html__('Top', 'theme') => 'top',
                        esc_html__('Center', 'theme') => 'middle',
                        esc_html__('Bottom', 'theme') => 'bottom',
                        esc_html__('Full height', 'theme') => 'full'
                    ),
                    'std' => 'top',
                ),
                array(
                    'group' => 'General',
                    'type' => 'textfield',
                    'heading' => esc_html__('Additional classes', 'theme'),
                    'param_name' => 'extra_classes',
                    'description' => esc_html__('List of classes separated by spaces', 'theme'),
                ),
                array(
                    'group' => 'Design',
                    'type' => 'css_editor',
                    'heading' => esc_html__( 'Css', 'theme' ),
                    'param_name' => 'css',
                ),
                array(
                    'group' => 'Design',
                    'param_name' => 'overlay_color',
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Background overlay color', 'theme'),
                ),
            ),
            'js_view' => 'VcColumnView'
        );

        om_container_vc_init();
    }

    public function get_styles()
    {
        $shortcodes = $this->get_shortcodes();

        $styles = '';

        foreach ($shortcodes as $shortcode) {
            $css = Css::init();

            $id = '.' . $shortcode['hash'];

            $settings = $shortcode['settings'];

            if (!empty($settings['overlay_color'])) {
                $css->set("$id > .block-overlay", 'background-color', $settings['overlay_color']);
            }

            $styles .= $css;
        }

        return $styles;
    }
}

function om_container_vc_init()
{
    if (class_exists('WPBakeryShortCodesContainer')) {
        class WPBakeryShortCode_OM_Container extends WPBakeryShortCodesContainer
        {
        }
    }
}