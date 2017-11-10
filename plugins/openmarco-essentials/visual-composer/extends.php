<?php

add_action('vc_after_init', function () {
    $excludes = array(
        //'vc_basic_grid', // since 1.4.5.3
        //'vc_btn',
        'vc_cta',
        //'vc_custom_field',
        //'vc_facebook',
        //'vc_flickr',
        'vc_gallery', // grid same as media grid

        'vc_gmaps',
        //'vc_googleplus',
        //'vc_icon',
        'vc_images_carousel',
        //'vc_masonry_grid', // since 1.4.5.3
        //'vc_masonry_media_grid',
        //'vc_media_grid',
        'vc_message',
        'vc_message_old',
        //'vc_pinterest',
        'vc_posts_slider',
        'vc_progress_bar',
        //'vc_separator',
        //'vc_single_image',
        //'vc_teaser_grid',
        //'vc_text_separator',
        'vc_toggle',
        'vc_toggle_old',
        //'vc_tweetmeme',
        //'vc_twitter',
        //'vc_video',
        //'vc_widget_sidebar',

        // Deprecated
        'vc_accordion',
        'vc_accordion_tab',
        'vc_button',
        //'vc_button2', // since 1.4.5.3
        'vc_carousel',
        'vc_cta_button',
        'vc_cta_button2',
        'vc_posts_grid',
        'vc_tab',
        'vc_tabs',
        'vc_tour',
    );

    $excludes = apply_filters('om_theme_vc_excludes', $excludes);

    foreach ($excludes as $tag) {
        vc_remove_element($tag);
    }
});

require_once __DIR__ . '/extends/vc_btn.php';
require_once __DIR__ . '/extends/vc_column.php';
require_once __DIR__ . '/extends/vc_custom_heading.php';
require_once __DIR__ . '/extends/vc_icon.php';
require_once __DIR__ . '/extends/vc_row.php';
require_once __DIR__ . '/extends/vc_separator.php';
require_once __DIR__ . '/extends/vc_single_image.php';


add_action('template_redirect', function () {
    wp_deregister_style('js_composer_front');
    wp_register_style('js_composer_front', get_template_directory_uri() . '/assets/css/visual-composer/css/js_composer.min.css', array(), WPB_VC_VERSION);
}, 20);