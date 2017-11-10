<?php

add_action('vc_after_init', function () {
    $global = \Essentials\Data\Options::get('font_headings');

    if($global['font-type'] !== 'google') {
        return;
    }

    $family = $global['font-family'];
    $weight = $global['font-weight'];
    $type = $weight > 400 ? 'bold regular' : ($weight < 400 ? 'light regular' : 'regular');

    $family = rawurlencode($family);
    $style = rawurlencode("{$weight} {$type}:{$weight}:normal");

    $value = "font_family:{$family}|font_style:{$style}";

    om_vc_update_shortcode_param_value('vc_custom_heading', 'google_fonts', $value);
});
