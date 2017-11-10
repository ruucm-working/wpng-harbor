<?php

use Essentials\Data\Options;

add_action('admin_head', function(){
    om_admin_colorpicker_meta();
});

add_action('customize_controls_print_scripts', function(){
    om_admin_colorpicker_meta();
} );

add_action('admin_enqueue_scripts', function () {

    wp_enqueue_script('custom_scripts', get_template_directory_uri() . "/assets/js/admin/common.js");

    if(om_is_post_edit()) {
        wp_enqueue_style('chosen-css', get_template_directory_uri() . "/assets/css/admin/chosen.min.css");
        wp_enqueue_script('chosen-jquery', get_template_directory_uri() . "/assets/js/admin/chosen.jquery.min.js");
    }
});

function om_admin_colorpicker_meta() {
    $palette = array(
        '#fff',
        '#333',
        '#eaeaea',
        '#fc7753',
        '#fbf18e',
        '#bdec8e',
        '#b1dceb',
        '#f2efea',
        '#d4be85',
    );

    if(om_is_essentials()) {
        for($index = 0; $index < 9; $index++) {
            $option = 'colorpicker-color-' . ($index+1);

            if(Options::specified($option)) {
                $palette[$index] = Options::get($option);
            }
        }
    }

    $palette = apply_filters('om_admin_colopicker_palette', $palette);

    echo '<meta name="colorpicker" content="', implode(',', $palette), '"/>';
}

function om_is_post_edit(){
    static $is;
    if(null === $is){
        global $pagenow;
        $filtered_action = filter_input(INPUT_GET, 'action' );
        $is = ($pagenow === 'post-new.php' || ($pagenow === 'post.php' && ( 0 === strcmp( $filtered_action, 'edit' ) ) ) );
    }
    return $is;
}