<?php

use Essentials\Data\Options;
use Essentials\Html\Base_Shortcode;
use Essentials\Html\Css;

add_action('vc_before_init', function () {
    $global_params = array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Columns mode', 'theme'),
            'description' => esc_html__('Note: This feature is only available once \'Custom Section\' mode has been enabled.', 'theme'),
            'param_name' => 'cols_mode',
            'value' => array(
                esc_html__('Top', 'theme') => 'top',
                esc_html__('Center', 'theme') => 'middle',
                esc_html__('Bottom', 'theme') => 'bottom',
                esc_html__('Equal', 'theme') => 'equal'
            ),
        ),
    );

    $params = array(
        array(
            'group' => 'Section',
            'type' => 'dropdown',
            'heading' => esc_html__('Custom section', 'theme'),
            'param_name' => 'section_enable',
            'description' => esc_html__('Please select \'Disabled\' when using incompatible 3rd party plugins or addons.', 'theme'),
            'value' => array(
                esc_html__('Enabled', 'theme') => 'enable',
                esc_html__('Disabled', 'theme') => 'disable',
            ),
            'std' => 'enable',
            'weight' => 10,
        ),
        array(
            'group' => 'Section',
            'type' => 'dropdown',
            'heading' => esc_html__('Theme defined section content margin', 'theme'),
            'param_name' => 'section_size',
            'value' => array(
                esc_html__('Zero', 'theme') => 'section-zero',
                esc_html__('Half', 'theme') => 'section-half',
                esc_html__('Single', 'theme') => 'section-single',
                esc_html__('Double', 'theme') => 'section-double',
            ),
            'std' => Options::get('content_section_size'),
            'weight' => 10,
            'dependency' => array(
                'element' => 'section_enable',
                'value' => 'enable',
            ),
        ),
        array(
            'group' => 'Section',
            'type' => 'colorpicker',
            'heading' => esc_html__('Section background color', 'theme'),
            'param_name' => 'section_color',
            'weight' => 10,
            'dependency' => array(
                'element' => 'section_enable',
                'value' => 'enable',
            ),
        ),
        array(
            'group' => 'Section',
            'type' => 'attach_image',
            'heading' => esc_html__('Section background image', 'theme'),
            'param_name' => 'section_images',
            'weight' => 10,
            'dependency' => array(
                'element' => 'section_enable',
                'value' => 'enable',
            ),
        ),
        array(
            'group' => 'Section',
            'type' => 'dropdown',
            'heading' => esc_html__('Section background image size', 'theme'),
            'param_name' => 'section_images_size',
            'value' => array(
                esc_html__('Cover', 'theme') => 'cover',
                esc_html__('Contain', 'theme') => 'contain',
                esc_html__('Initial', 'theme') => 'initial',
            ),
            'std' => 'cover',
            'weight' => 10,
            'dependency' => array(
                'element' => 'section_enable',
                'value' => 'enable',
            ),
        ),
        array(
            'group' => 'Section',
            'type' => 'colorpicker',
            'heading' => esc_html__('Section background overlay color', 'theme'),
            'param_name' => 'section_overlay_color',
            'weight' => 10,
            'dependency' => array(
                'element' => 'section_enable',
                'value' => 'enable',
            ),
        ),
        array(
            'group' => 'Section',
            'type' => 'single_checkbox',
            'label' => esc_html__('Floating content when parallax is turned on', 'theme'),
            'param_name' => 'parallax_float_content',
            'value' => 'false',
            'weight' => 0,
            'dependency' => array(
                'element' => 'section_enable',
                'value' => 'enable',
            ),
        ),
        array(
            'group' => 'Section',
            'type' => 'single_checkbox',
            'label' => esc_html__('Hide overflow', 'theme'),
            'param_name' => 'section_inside',
            'value' => 'true',
            'weight' => 0,
            'dependency' => array(
                'element' => 'section_enable',
                'value' => 'enable',
            ),
        ),
        array(
            'group' => 'Section',
            'type' => 'number',
            'heading' => esc_html__('Navigation bar transparency', 'theme'),
            'param_name' => 'navigation_bar_transparency',
            'weight' => 0,
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
            'placeholder' => '1',
            'dependency' => array(
                'element' => 'section_enable',
                'value' => 'enable',
            ),
        )
    );

    vc_add_params('vc_row', $global_params);
    vc_add_params('vc_row', $params);

    vc_add_params('vc_row_inner', $global_params);
});

add_action('vc_after_init', function () {
    om_vc_update_shortcode_param_setting('vc_row', 'parallax_image','dependency' ,array(
        'element' => 'section_enable',
        'value' => 'disable',
    ));

    //om_vc_update_shortcode_param_setting('vc_row', 'parallax', 'group', 'Section');
    //om_vc_update_shortcode_param_setting('vc_row', 'parallax', 'description',  esc_html__('Add parallax to section background for row.', 'theme'));

    //om_vc_update_shortcode_param_setting('vc_row', 'video_bg', 'group', 'Section');
    //om_vc_update_shortcode_param_setting('vc_row', 'video_bg_url', 'group', 'Section');
    //om_vc_update_shortcode_param_setting('vc_row', 'video_bg_parallax', 'group', 'Section');

    om_vc_update_shortcode_param_setting('vc_row', 'columns_placement', 'description',
        'Select columns position within row. Note: This feature is only available once \'Custom Section\' has been disabled, otherwise use \'Columns Mode\' below to achieve the same functionality.');

    //vc_remove_param('vc_row', 'content_placement');

});

add_filter('vc_shortcode_set_template_vc_row', function () {
    return dirname(__DIR__) . '/templates/_vc_row.php';
});

add_filter(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, function ($classes, $tag) {

    if('vc_row' === $tag || 'vc_row_inner' === $tag) {
        $arguments = func_get_args();
        $attributes = isset($arguments[2]) ? $arguments[2] : array();

        if (isset($attributes['cols_mode']) && 'top' !== $attributes['cols_mode']) {

            if ('equal' === $attributes['cols_mode']) {
                $classes .= ' vc-row-match-height';
            } else {
                $classes .= ' vc-content-' . sanitize_html_class(trim($attributes['cols_mode']));
            }
        }
    }

    return $classes;
}, 10, 3);

add_filter('vc_base_build_shortcodes_custom_css', function ($styles) {

    $rows = Base_Shortcode::get_all_shortcodes('vc_row');

    $css = Css::init();

    foreach ($rows as $row) {
        $hash = Base_Shortcode::hash('vc_row', $row['attributes']);
        $class = '.' . $hash;
        $atts = $row['attributes'];

        if (!empty($atts['section_color'])) {
            $css->set("$class.behavior-static .section-background-main", 'background-color', $atts['section_color']);
        }

        if (!empty($atts['section_images'])) {
            $bg_image = wp_get_attachment_image_src($atts['section_images'], 'full');

            $css->set("$class .section-background-image", 'background-image', "url('{$bg_image[0]}')");

            if (!empty($atts['section_images_size'])) {
                $css->set("$class .section-background-image", 'background-size', $atts['section_images_size']);
            }
        }

        if (!empty($atts['section_overlay_color'])) {
            $css->set("$class .section-background-overlay", 'background-color', $atts['section_overlay_color']);
        }
    }

    return $styles . $css->to_string();
});