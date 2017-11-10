<?php

class OM_Gallery_Image extends \Essentials\Html\Base_Shortcode
{
    public function init()
    {
        $this->parameters = array(
            'category' => esc_html__('Colors Creative', 'theme'),
            'name' => esc_html__('Gallery image Lite', 'theme'),
            'as_child' => array('only' => 'om_gallery'),
            'custom_markup' => '<h4 class="wpb_element_title">Gallery image <img width="150" height="150" src="'. get_template_directory_uri() . '/assets/img/visual-composer/image.svg" class="attachment-thumbnail vc_element-icon" data-name="image" alt="" title="" style="display: none;" /><span class="no_image_image vc_element-icon icon-wpb-single-image"></span><a href="#" class="column_edit_trigger">Add image</a></h4>',
            'params' => array(
                array(
                    //'group' => 'General',
                    'param_name' => 'image',
                    'type' => 'attach_image',
                    'heading' => esc_html__('Image', 'theme'),
                ),
                array(
                    //'group' => 'General',
                    'param_name' => 'link',
                    'type' => 'textfield',
                    'heading' => esc_html__('Link', 'theme'),
                ),
                array(
                    //'group' => 'General',
                    'param_name' => 'device_type',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Device type', 'theme'),
                    'description' => esc_html__('Only works with Devices gallery layouts', 'theme'),
                    'value' => array(
                        esc_html__('None', 'theme') => 'empty',
                        esc_html__('Phone', 'theme') => 'phone',
                        esc_html__('Tablet', 'theme') => 'tablet',
                        esc_html__('Landscape tablet', 'theme') => 'tablet_landscape',
                        esc_html__('Desktop browser', 'theme') => 'browser',
                        esc_html__('Watch', 'theme') => 'watch',
                    ),
                    'std' => 'empty'
                ),
                array(
                    //'group' => 'General',
                    'param_name' => 'device_color',
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Device color', 'theme'),
                    'dependency' => array(
                        'element' => 'device_type',
                        'value' => array('phone', 'tablet', 'tablet_landscape', 'browser', 'watch')
                    ),
                ),
            )
        );
    }
}
