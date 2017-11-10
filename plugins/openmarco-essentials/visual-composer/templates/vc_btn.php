<?php

/**
 * @var $this WPBakeryShortCode_VC_Btn
 * @var $atts
 * @var $style
 * @var $shape
 * @var $color
 * @var $custom_background
 * @var $custom_text
 * @var $size
 * @var $align
 * @var $link
 * @var $title
 * @var $button_block
 * @var $el_class
 * @var $inline_css
 *
 * @var $add_icon
 * @var $i_align
 * @var $i_type
 *
 * ///
 * @var $a_href
 * @var $a_title
 * @var $a_target
 */
$defaults = array(
	'style' => 'classic',
	'shape' => 'rounded',
	'color' => 'grey',
	'custom_background' => '#ededed',
	'custom_text' => '#666',
	'size' => 'md',
	'align' => 'inline',
	'link' => '',
	'title' => __( 'Text on the button', 'js_composer' ),
	'button_block' => '',
	'el_class' => '',
	'add_icon' => '',
	'i_align' => 'left',
	'i_icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-alert',
	'i_type' => 'fontawesome',
	'i_icon_fontawesome' => 'fa fa-adjust',
	'i_icon_openiconic' => 'vc-oi vc-oi-dial',
	'i_icon_typicons' => 'typcn typcn-adjust-brightness',
	'i_icon_entypo' => 'entypo-icon entypo-icon-note',
	'i_icon_linecons' => 'vc_li vc_li-heart',
	'css_animation' => '',
    'outline_custom_color' => '#666',
    'outline_custom_hover_background' => '#666',
    'outline_custom_hover_text' => '#fff',
	'gradient_color_1' => 'turquoise',
	'gradient_color_2' => 'blue',
	'gradient_custom_color_1' => '#dd3333',
	'gradient_custom_color_2' => '#eeee22',
	'gradient_text_color' => '#ffffff'
);
$colors = array(
	'blue' => '#5472d2',
	'turquoise' => '#00c1cf',
	'pink' => '#fe6c61',
	'violet' => '#8d6dc4',
	'peacoc' => '#4cadc9',
	'chino' => '#cec2ab',
	'mulled-wine' => '#50485b',
	'vista-blue' => '#75d69c',
	'orange' => '#f7be68',
	'sky' => '#5aa1e3',
	'green' => '#6dab3c',
	'juicy-pink' => '#f4524d',
	'sandy-brown' => '#f79468',
	'purple' => '#b97ebb',
	'black' => '#2a2a2a',
	'grey' => '#ebebeb',
	'white' => '#ffffff',
);
$inline_css = '';
$icon_wrapper = false;
$icon_html = false;

$atts = shortcode_atts($defaults, $atts);
extract($atts);

$wrapper_classes = array();
$wrapper_attributes = array();
$classes = array('btn');
$attributes = array();

//parse link
$link = ($link === '||') ? '' : $link;
$link = vc_build_link($link);
$use_link = false;
if (strlen($link['url']) > 0) {
    $use_link = true;

    $attributes['href'] = esc_url($link['url']);
    $attributes['title'] = esc_attr($link['title']);
    $attributes['target'] = strlen($link['target']) > 0 ? trim(esc_attr($link['target'])) : '_self';
}

$el_class = $this->getExtraClass($el_class);
$wrapper_classes[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' vc_btn3-container ' . $el_class, $this->settings['base'], $atts);
$wrapper_classes[] = $this->getCSSAnimation($css_animation);
$wrapper_classes[] = 'vc_btn3-' . sanitize_html_class($align);

$classes[] = 'btn-' . sanitize_html_class($size);
$classes[] = 'btn-' . sanitize_html_class($shape);
$classes[] = 'btn-' . str_replace(array('outline-custom', 'outline'), 'ghost', sanitize_html_class($style));

if ( '' == trim( $title ) ) {
    $classes[] = 'btn-vc-icon';
}

if ('true' === $button_block && 'inline' !== $align) {
    $classes[] = 'btn-block';
}

if ('custom' === $style) {
    $attributes['style'] = vc_get_css_color('background-color', $custom_background) . vc_get_css_color('color', $custom_text);
} else if ('outline-custom' === $style) {
    if(!empty($outline_custom_color)) {
        $attributes['data-om-vc-outline-color'] = $outline_custom_color;
    }
    if(!empty($outline_custom_hover_background)) {
        $attributes['data-om-vc-outline-hover-bg'] = $outline_custom_hover_background;
    }
    if(!empty($outline_custom_hover_text)) {
        $attributes['data-om-vc-outline-hover-text'] = $outline_custom_hover_text;
    }
} else if( 'gradient' === $style || 'gradient-custom' === $style ) {

	$gradient_color_1 = $colors[$gradient_color_1];
	$gradient_color_2 = $colors[$gradient_color_2];

	$button_text_color = "#fff";
	if('gradient-custom' === $style ){
		$gradient_color_1 = $gradient_custom_color_1;
		$gradient_color_2 = $gradient_custom_color_2;
		$button_text_color = $gradient_text_color;
	}

	$gradient_css = array();
	$gradient_css[] = 'color: ' . $button_text_color;
	$gradient_css[] = 'border: none';
	$gradient_css[] = 'background-color: ' . $gradient_color_1;
	$gradient_css[] = 'background-image: -webkit-linear-gradient(left, ' . $gradient_color_1 . ' 0%, ' . $gradient_color_2 . ' 50%,' . $gradient_color_1 . ' 100%)';
	$gradient_css[] = 'background-image: linear-gradient(to right, ' . $gradient_color_1 . ' 0%, ' . $gradient_color_2 . ' 50%,' . $gradient_color_1 . ' 100%)';
	$gradient_css[] = '-webkit-transition: all .2s ease-in-out';
	$gradient_css[] = 'transition: all .2s ease-in-out';
	$gradient_css[] = 'background-size: 200% 100%';

	// hover css
	$gradient_css_hover = array();
	$gradient_css_hover[] = 'color: ' . $button_text_color;
	$gradient_css_hover[] = 'background-color: ' . $gradient_color_2;
	$gradient_css_hover[] = 'border: none';
	$gradient_css_hover[] = 'background-position: 100% 0';

	$uid = uniqid();
	echo '<style type="text/css">.vc_btn-gradient-btn-' . $uid . ':hover{' . implode( ';',
			$gradient_css_hover ) . ';' . '}</style>';
	echo '<style type="text/css">.vc_btn-gradient-btn-' . $uid . '{' . implode( ';',
			$gradient_css ) . ';' . '}</style>';
	$classes[] = 'vc_btn-gradient-btn-' . $uid;
	$attributes['data-vc-gradient-1'] = $gradient_color_1;
	$attributes['data-vc-gradient-2'] = $gradient_color_2;
} else {
    $classes[] = 'btn-vc-' . $color;
}

$wrapper_attributes['class'] = esc_attr(trim(implode(' ', array_filter($wrapper_classes))));
$attributes['class'] = esc_attr(trim(implode(' ', array_filter($classes))));

$button_html = $title;

// Icon

if ( 'true' === $add_icon ) {
    vc_icon_element_fonts_enqueue( $i_type );

    if ( isset( ${"i_icon_" . $i_type} ) ) {
        if ( 'pixelicons' === $i_type ) {
            $icon_wrapper = true;
        }
        $iconClass = ${"i_icon_" . $i_type};
    } else {
        $iconClass = 'fa fa-adjust';
    }

    if ( $icon_wrapper ) {
        $icon_html = '<i class="vc_btn3-icon"><span class="vc_btn3-icon-inner ' . esc_attr( $iconClass ) . '"></span></i>';
    } else {
        $icon_html = '<i class="vc_btn3-icon ' . esc_attr( $iconClass ) . '"></i>';
    }


    if ( $i_align === 'left' ) {
        $button_html = $icon_html . ' ' . $button_html;
    } else {
        $button_html .= ' ' . $icon_html;
    }
}

$html = \Essentials\Html\Writer::init();

$html->div($wrapper_attributes);

if ($use_link) {
    $html->a($attributes);
} else {
    $html->button($attributes);
}

$html->text($button_html)->out();