<?php

require_once __DIR__ . '/om_base_gallery/om_base_gallery.php';

require_once __DIR__ . '/om_carousel/om_carousel.php';
require_once __DIR__ . '/om_container/om_container.php';
require_once __DIR__ . '/om_device/om_device.php';
require_once __DIR__ . '/om_gallery/om_gallery.php';
require_once __DIR__ . '/om_gallery_image/om_gallery_image.php';
require_once __DIR__ . '/om_google_maps/om_google_maps.php';
require_once __DIR__ . '/om_portfolio/om_portfolio.php';
require_once __DIR__ . '/om_simple_gallery/om_simple_gallery.php';

$theme_elements = array(
    'OM_Carousel',
    'OM_Container',
    'OM_Device',
    'OM_Gallery',
    'OM_Gallery_Image',
    'OM_Google_Maps',
    'OM_Portfolio',
    'OM_Simple_Gallery'
);

$theme_elements = apply_filters('om_theme_vc_elements', $theme_elements);

foreach($theme_elements as $theme_element) {
    new $theme_element();
}
