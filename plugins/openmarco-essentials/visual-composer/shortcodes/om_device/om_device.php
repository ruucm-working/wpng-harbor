<?php

use Essentials\Html\Css;

class OM_Device extends Essentials\Html\Base_Shortcode
{
    public function init()
    {
        $this->parameters = array(
            'category' => esc_html__('Colors Creative', 'theme'),
            'name' => esc_html__('Device Lite', 'theme'),
            'description' => esc_html__('Container', 'theme'),
            'as_parent' => array('except' => 'vc_row,om_container'),
            'content_element' => true,
            'show_settings_on_create' => true,
            'params' => array(
                array(
                    'group' => 'General',
                    'param_name' => 'device_type',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Device type', 'theme'),
                    'value' => array(
                        esc_html__('Phone', 'theme') => 'phone',
                        esc_html__('Tablet', 'theme') => 'tablet',
                        esc_html__('Landscape tablet', 'theme') => 'tablet_landscape',
                        esc_html__('Desktop browser', 'theme') => 'browser',
                        esc_html__('Watch', 'theme') => 'watch',
                    ),
                    'std' => 'phone',
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'device_color',
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Device color', 'theme'),
                    'dependency' => array(
                        'element' => 'device_type',
                        'value' => array('phone', 'tablet', 'tablet_landscape', 'browser', 'watch')
                    ),
                ),
                array(
                    'group' => 'Design',
                    'type' => 'css_editor',
                    'heading' => esc_html__( 'Css', 'theme' ),
                    'param_name' => 'css',
                ),
            ),
            'js_view' => 'VcColumnView'
        );

        om_device_vc_init();
    }

    public function get_styles()
    {
        $shortcodes = $this->get_shortcodes();

        $styles = '';

        foreach ($shortcodes as $shortcode) {
            $css = Css::init();

            $id = '.' . $shortcode['hash'];

            $settings = $shortcode['settings'];

            if (!empty($settings['device_color'])) {
                $css->set("$id .device", 'background-color', $settings['device_color']);
            }

            $styles .= $css;
        }

        return $styles;
    }
}

function om_device_vc_init()
{
    if (class_exists('WPBakeryShortCodesContainer')) {
        class WPBakeryShortCode_OM_Device extends WPBakeryShortCodesContainer
        {
        }
    }
}