<?php

use Essentials\Data\Options;
use Essentials\Html\Css;
use Essentials\Html\Writer;
use Essentials\Utils\Lightbox;

class OM_Gallery extends OM_Base_Gallery
{
    private static $presets = array(
        'fade' => '<div class="caption animate rest-fade"><div class="caption-content"><div class="background"></div></div></div>',
    );

    private $preset;

    public function init()
    {
        $this->parameters = array(
            'category' => esc_html__('Colors Creative', 'theme'),
            'name' => esc_html__('Gallery Lite', 'theme'),
            'description' => esc_html__('Gallery', 'theme'),
            'as_parent' => array('only' => 'om_gallery_image'),
            'content_element' => true,
            'show_settings_on_create' => true,
            'params' => array_merge($this->common_params, array(
                // General

                array(
                    'group' => 'General',
                    'param_name' => 'on_click',
                    'type' => 'dropdown',
                    'heading' => esc_html__('On click', 'theme'),
                    'value' => array(
                        esc_html__('Open lightbox', 'theme') => 'lightbox',
                        esc_html__('Do nothing', 'theme') => 'nothing',
                    ),
                    'description' => esc_html__('What to do when item is clicked?', 'theme'),
                    'weight' => 100,
                    'std' => 'lightbox'
                ),

                // Caption
                array(
                    'group' => 'Caption',
                    'param_name' => 'caption_preset',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Style', 'theme'),
                    'value' => array(
                        esc_html__('None', 'theme') => '',
                        esc_html__('Fade', 'theme') => 'fade',
                    ),
                    'std' => ''
                ),
                array(
                    'group' => 'Caption',
                    'param_name' => 'caption_margin',
                    'type' => 'number',
                    'heading' => esc_html__('Caption margin', 'theme'),
                ),
                array(
                    'group' => 'Caption',
                    'param_name' => 'caption_background_color',
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Caption background color', 'theme'),
                ),
                om_vc_clearfix('Caption'),
                array(
                    'group' => 'Caption',
                    'param_name' => 'caption_background_image',
                    'type' => 'attach_image',
                    'heading' => esc_html__('Caption background image', 'theme'),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
                ),
                array(
                    'group' => 'Caption',
                    'param_name' => 'caption_background_size',
                    'type' => 'number',
                    'heading' => esc_html__('Image width', 'theme'),
                    'min' => '0',
                    'max' => '150',
                    'edit_field_class' => 'vc_col-sm-3 vc_column',
                ),
                array(
                    'group' => 'Caption',
                    'param_name' => 'caption_background_size_unit',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Image width unit', 'theme'),
                    'value' => array(
                        'px' => 'px',
                        '%' => '%',
                    ),
                    'std' => 'px',
                    'edit_field_class' => 'vc_col-sm-3 vc_column',
                ),
                om_vc_clearfix('Caption'),
                array(
                    'group' => 'Caption',
                    'param_name' => 'mobile_caption',
                    'type' => 'dropdown',
                    'heading' => esc_html__('Caption visibility', 'theme'),
                    'value' => array(
                        esc_html__('Visible on tablet and phones', 'theme') => '',
                        esc_html__('Hidden on phones', 'theme') => 'hidden-xs',
                        esc_html__('Hidden on tablets', 'theme') => 'hidden-sm',
                        esc_html__('Hidden on tablets and phones', 'theme') => 'hidden-xs hidden-sm',
                    ),
                    'std' => '',
                ),
            )),
            'js_view' => 'VcColumnView'
        );

        om_gallery_vc_init();
    }

    public function get_styles()
    {
        $shortcodes = $this->get_shortcodes();

        $styles = parent::get_styles();

        foreach ($shortcodes as $shortcode) {
            $css = Css::init();

            $id = '.' . $shortcode['hash'];

            $settings = $shortcode['settings'];

            // Caption

            if (!empty($settings['caption_preset']) && isset($settings['caption_margin']) && $settings['caption_margin'] != '') {
                $value = $settings['caption_margin'] . 'px';

                $css->set("$id .grid-content .caption-content", array(
                    'top' => $value,
                    'bottom' => $value,
                    'left' => $value,
                    'right' => $value,
                ));
            }

            if (!empty($settings['caption_preset']) && !empty($settings['caption_background_color'])) {
                $css->set("$id .grid-content .caption .background", 'background-color', $settings['caption_background_color']);
            }

            if (!empty($settings['caption_background_image'])) {
                $value = ome_get_image($settings['caption_background_image']);
                $value = $value['full']['url'];
                $value = "url('{$value}')";

                $css->set("$id .grid-content .caption .background", array(
                    'background-image' => $value
                ));
            }

            if (!empty($settings['caption_background_image']) && (!empty($settings['caption_background_size']) || '0' == $settings['caption_background_size'])) {
                if ('0' == $settings['caption_background_size']) {
                    $value = 'initial';
                } else {
                    $unit = empty($settings['caption_background_size_unit']) ? 'px' : esc_html($settings['caption_background_size_unit']);
                    $value = esc_html($settings['caption_background_size']) . $unit;
                }

                $css->set("$id .grid-content .caption .background", 'background-size', $value);
            }

            $styles .= $css;
        }

        return $styles;
    }

    public function get_items()
    {
        $content = '[' . trim(preg_replace(array('/^<p>/', '/,<\/p>$/'), '', $this->content), " \t\n\r\0\x0B,") . ']';

        return json_decode($content);
    }

    public function get_caption_preset()
    {
        if (!isset($this->preset)) {
            $this->preset = (!empty($this->settings['caption_preset']) && isset(self::$presets[$this->settings['caption_preset']]))
                ? self::$presets[$this->settings['caption_preset']]
                : '';

            if (!empty($this->settings['mobile_caption'])) {
                $this->preset = preg_replace('/caption /', 'caption ' . $this->settings['mobile_caption'] . ' ', $this->preset);
            }
        }

        return $this->preset;
    }

    public function init_gallery_item($index, $gallery_item)
    {
        $image_id = isset($gallery_item->image) ? $gallery_item->image : null;

        $device = null;
        $device_color = null;

        if ($this->is_devices) {
            $device = isset($gallery_item->device_type) ? $gallery_item->device_type : '';
            $device_color = isset($gallery_item->device_color) ? $gallery_item->device_color : '';
        }

        $this->init_base_gallery_item($index, $image_id, $device, $device_color);

        $this->item['url'] = isset($gallery_item->link) ? $gallery_item->link : '';
        $this->item['image'] = $image_id;

        return $this->item;
    }

    public function render_link_attributes()
    {
        $attributes = array();

        if (!empty($this->item['url'])) {
            $attributes['href'] = esc_url($this->item['url']);
        } else {
            $image = ome_get_image($this->item['image']);

            if (isset($image['full'])) {
                $attributes['data-item'] = $image['full']['url'];
                $attributes['data-size'] = "{$image['full']['width']}x{$image['full']['height']}";
                if(!Options::get('lightbox_title_disable')) {
                    $attributes['data-title'] = Lightbox::get_image_caption($this->item['image']);
                }
            }
        }
        
	    echo Writer::get_attributes_string($attributes);
    }

    public function render_image()
    {
        ome_responsive_image($this->item['image'], $this->get_item_image_sizes(), $this->get_item_image_attributes());
    }

    public function render_caption()
    {
        echo $this->get_caption_preset();
    }

    protected function get_cols($col_counts = null)
    {
        return parent::get_cols(apply_filters('om_gallery_col_counts', $col_counts, $this->settings));
    }
}

function om_gallery_vc_init()
{
    if (class_exists('WPBakeryShortCodesContainer')) {
        class WPBakeryShortCode_OM_Gallery extends WPBakeryShortCodesContainer
        {
        }
    }
}