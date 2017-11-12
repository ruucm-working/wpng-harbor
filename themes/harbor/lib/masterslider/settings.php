<?php

add_filter('masterslider_disable_auto_update', '__return_true');

add_filter('masterslider_skins', function ($skins) {
    $skins[] = array('class' => 'ms-skin-colors-creative', 'label' => 'Colors Creative Theme');

    return $skins;
});

add_filter('masterslider_panel_default_setting', function ($default_options) {
    // set theme skin as default
    $default_options['skin'] = 'ms-skin-colors-creative';

    return $default_options;
});

add_filter('msp_woocommerce_single_product_slider_params', function ($slider_params, $post) {
    // set theme skin
    //$slider_params['skin'] = 'ms-skin-colors-creative';

    return $slider_params;
}, 10, 2);