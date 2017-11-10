<?php

use Essentials\Data\Options;

class OM_Google_Maps extends Essentials\Html\Base_Shortcode
{
    private static $controls_names = array(
        'zoom_control' => 'zoom',
        'pan_control' => 'pan',
        'map_type_control' => 'type',
        'scale_control' => 'scale',
        'street_view_control' => 'street',
    );

    public function init()
    {
        $this->parameters = array(
            'category' => esc_html__('Colors Creative', 'theme'),
            'name' => esc_html__('Google Maps Lite', 'theme'),
            'description' => esc_html__('Display Google maps', 'theme'),
            'admin_enqueue_js' => '//maps.google.com/maps/api/js?v=3.exp&amp;sensor=false&key=' . Options::get('goodle_maps_api_key', ''),
            'params' => array(
                array(
                    'group' => 'General',
                    'param_name' => 'center',
                    'type' => 'map',
                    'heading' => esc_html__('Marker position', 'theme'),
                    'description' => esc_html__('The name of a place, town, city, or even a country. Can be an exact address too.', 'theme')
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'zoom_level',
                    'type' => 'number',
                    'heading' => esc_html__('Zoom level', 'theme'),
                    'description' => esc_html__('A value from 0 (the world) to 21 (street level).', 'theme'),
                    'min' => '0',
                    'max' => '21',
                    'placeholder' => '15'
                ),
                array(
                    'group' => 'General',
                    'param_name' => 'marker',
                    'type' => 'attach_image',
                    'heading' => esc_html__('Custom marker icon', 'theme'),
                    'description' => esc_html__('Replaces the default map marker with your own image.', 'theme'),
                ),

                array(
                    'group' => 'Advanced',
                    'param_name' => 'background_color',
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Background color', 'theme'),
                ),
                array(
                    'group' => 'Advanced',
                    'param_name' => 'scroll_to_zoom',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Allow scrolling over the map to zoom in or out', 'theme'),
                ),
                array(
                    'group' => 'Advanced',
                    'param_name' => 'draggable',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Allow dragging the map to move it around', 'theme'),
                ),
                array(
                    'group' => 'Advanced',
                    'param_name' => 'styles',
                    'type' => 'textarea_raw_html',
                    'heading' => esc_html__('Raw JSON styles', 'theme'),
                    'description' => sprintf(esc_html__('Copy a predefined JavaScript Style Array (JSON) from %s and paste it here.', 'theme'), '<a href="//snazzymaps.com/">Snazzy Maps</a>'),
                ),

                array(
                    'group' => 'Controls',
                    'param_name' => 'zoom_control',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Zoom control', 'theme'),
                ),
                array(
                    'group' => 'Controls',
                    'param_name' => 'pan_control',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Pan control', 'theme'),
                ),
                array(
                    'group' => 'Controls',
                    'param_name' => 'map_type_control',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Map Type control', 'theme'),
                ),
                array(
                    'group' => 'Controls',
                    'param_name' => 'scale_control',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Scale control', 'theme'),
                ),
                array(
                    'group' => 'Controls',
                    'param_name' => 'street_view_control',
                    'type' => 'single_checkbox',
                    'label' => esc_html__('Street View control', 'theme'),
                ),
            )
        );
    }

    public function get_controls() {
        $controls = array();

        foreach(self::$controls_names as $name => $value) {
            if(isset($this->settings[$name]) && $this->settings[$name] && $this->settings[$name] != 'false') {
                $controls[] = $value;
            }
        }

        return implode(',', $controls);
    }
}