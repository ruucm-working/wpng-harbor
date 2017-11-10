<?php

class OM_Carousel extends Essentials\Html\Base_Shortcode
{
    public function init()
    {
        $this->parameters = array(
            'category' => esc_html__('Colors Creative', 'theme'),
            'name' => esc_html__('Image carousel Lite', 'theme'),
            'description' => esc_html__('Animated carousel with images', 'theme'),
            'params' => array(
                array(
                    'group' => 'General',
                    'param_name' => 'images',
                    'type' => 'attach_images',
                    'heading' => esc_html__('Images', 'theme'),
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'type',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Type', 'theme'),
                    'value' => array(
                        esc_html__('Standard', 'theme') => 'standard',
                        esc_html__('Creative', 'theme') => 'creative',
                    ),
                    'std' => 'standard'
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'image_size',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Image size', 'theme'),
                    'value' => array_merge(array('Full' => 'full'), ome_get_image_sizes_list()),
                    'std' => 'large',
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'on_click',
                    'type' => 'dropdown',
                    'heading' => esc_html__('On click', 'theme'),
                    'value' => array(
                        esc_html__('Open lightbox', 'theme') => 'lightbox',
                        esc_html__('Do nothing', 'theme') => 'nothing',
                        esc_html__('Open custom link', 'theme') => 'custom',
                    ),
                    'description' => esc_html__('What to do when slide is clicked?', 'theme'),
                    'std' => 'lightbox'
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'links',
                    'type' => 'exploded_textarea',
                    'heading' => esc_html__('Custom links', 'theme'),
                    'description' => esc_html__('Enter links for each slide here. Divide links with linebreaks (Enter)', 'theme'),
                    'dependency' => array(
                        'element' => 'on_click',
                        'value' => 'custom',
                    ),
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'show_pagination',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Show bullet navigation', 'theme'),
                    'value' => true,
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'show_arrows',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Show arrows navigation', 'theme'),
                    'value' => true,
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'enable_autoplay',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Enable autoplay mode', 'theme'),
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'enable_rewind',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Enable rewind navigation', 'theme'),
                ),

                array(
                    'group' => 'Advanced',
                    'param_name' => 'speed',
                    'type' => 'number',
                    'heading' => esc_html__('Speed', 'theme'),
                    'description' => esc_html__('Duration of animation between slide in milliseconds (200 by default)', 'theme')
                ),
                array(
                    'group' => 'Advanced',
                    'param_name' => 'rewind',
                    'type' => 'number',
                    'heading' => esc_html__('Rewind speed', 'theme'),
                    'description' => esc_html__('Duration of rewind animation in milliseconds (500 by default)', 'theme')
                ),
                array(
                    'group' => 'Advanced',
                    'param_name' => 'height',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Height', 'theme'),
                    'value' => array(
                        esc_html__('Default', 'theme') => '',
                        esc_html__('Auto', 'theme') => 'auto',
                        esc_html__('Ratio', 'theme') => 'ratio',
                        esc_html__('Fixed', 'theme') => 'fixed',
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value' => 'standard',
                    ),
                    'std' => '',
                ),
                array(
                    'group' => 'Advanced',
                    'param_name' => 'ratio',
                    'type' => 'textfield',
                    'heading' => esc_html__('Ratio', 'theme'),
                    'description' => esc_html__('Set numeric ratio or like "width/height"', 'theme'),
                    'dependency' => array(
                        'element' => 'height',
                        'value' => 'ratio',
                    ),
                ),
                array(
                    'group' => 'Advanced',
                    'param_name' => 'heights',
                    'type' => 'inputs',
                    'heading' => esc_html__('Heights', 'theme'),
                    'count' => 5,
                    'placeholder' => array('Phone', 'Phablet', 'Tablet', 'Desktop', 'Large'),
                    'description' => esc_html__('If not set, then using left-hand value', 'theme'),
                    'dependency' => array(
                        'element' => 'height',
                        'value' => 'fixed',
                    ),
                ),
                array(
                    'group' => 'Advanced',
                    'param_name' => 'on_hover_stop',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Stop autoplay on mouse hover', 'theme'),
                    'value' => true,
                ),
            )
        );
    }
}