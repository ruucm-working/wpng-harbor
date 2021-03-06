<?php
/** @var $this WPBakeryShortCode_VC_Icon */
$background_color_custom =
$icon = $color = $size = $align = $el_class = $custom_color = $link = $background_style = $background_color =
$type = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypoicons = $icon_linecons = '';

$defaults = array(
	'type' => 'fontawesome',
	'icon_fontawesome' => 'fa fa-adjust',
	'icon_openiconic' => '',
	'icon_typicons' => '',
	'icon_entypoicons' => '',
	'icon_linecons' => '',
	'icon_entypo' => '',
	'color' => '',
	'custom_color' => '',
	'background_style' => '',
	'background_color' => '',
    '$background_color_custom' => '',
	'size' => 'md',
	'align' => 'center',
	'el_class' => '',
	'link' => '',
	'css_animation' => '',

);
/** @var array $atts - shortcode attributes */
$atts = vc_shortcode_attribute_parse( $defaults, $atts );
extract( $atts );

$class = $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $this->settings['base'], $atts );
$css_class .= $this->getCSSAnimation( $css_animation );
// Enqueue needed icon font.
vc_icon_element_fonts_enqueue( $type );

$url = vc_build_link( $link );
$has_style = false;
if ( strlen( $background_style ) > 0 ) {
	$has_style = true;
	if ( strpos( $background_style, 'outline' ) !== false ) {
		$background_style .= ' vc_icon_element-outline'; // if we use outline style it is border in css
	} else {
		$background_style .= ' vc_icon_element-background';
	}
}
$iconClass = isset( ${"icon_" . $type} ) ? esc_attr( ${"icon_" . $type} ) : 'fa fa-adjust';

$custom_bg = 'custom' === $background_color;

if($custom_bg) {
    $custom_bg_prop = strpos('outline', $background_style) === false ? 'background-color': 'border-color';
}
?>
<div
	class="vc_icon_element vc_icon_element-outer<?php echo esc_attr( $css_class ); ?>  vc_icon_element-align-<?php echo esc_attr( $align ); ?> <?php if ( $has_style ): echo 'vc_icon_element-have-style'; endif; ?>">
	<div
		class="vc_icon_element-inner vc_icon_element-color-<?php echo esc_attr( $color ); ?> <?php if ( $has_style ): echo 'vc_icon_element-have-style-inner'; endif; ?> vc_icon_element-size-<?php echo esc_attr( $size ); ?>  vc_icon_element-style-<?php echo esc_attr( $background_style ); ?> vc_icon_element-background-color-<?php echo esc_attr( $background_color ); ?>"<?php if($custom_bg) : ?> style="<?php echo esc_attr($custom_bg_prop) ?>:<?php echo esc_attr($background_color_custom) ?>"<?php endif; ?>><span
			class="vc_icon_element-icon <?php echo $iconClass; ?>" <?php echo( $color === 'custom' ? 'style="color:' . esc_attr( $custom_color ) . ' !important"' : '' ); ?>></span><?php
		if ( strlen( $link ) > 0 && strlen( $url['url'] ) > 0 ) {
			echo '<a class="vc_icon_element-link" href="' . esc_attr( $url['url'] ) . '" title="' . esc_attr( $url['title'] ) . '" target="' . ( strlen( $url['target'] ) > 0 ? esc_attr( $url['target'] ) : '_self' ) . '"></a>';
		}
		?></div>
</div><?php echo $this->endBlockComment( '.vc_icon_element' ); ?>
