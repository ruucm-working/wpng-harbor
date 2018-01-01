<?php

global $content_width;
$content_width = 1140;

add_theme_support('title-tag');
add_theme_support('automatic-feed-links');
add_theme_support('post-thumbnails');
add_theme_support('html5' ,array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));

register_nav_menu('primary', 'Primary navigation');

add_action('widgets_init', function(){
    $sidebar_defaults = array(
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
    );

    $sidebars = array(
        'primary_footer_1' => esc_html__('Primary Footer 1', 'colors-creative'),
        'primary_footer_2' => esc_html__('Primary Footer 2', 'colors-creative'),
        'primary_footer_3' => esc_html__('Primary Footer 3', 'colors-creative'),
        'secondary_footer_1' => esc_html__('Secondary Footer 1', 'colors-creative'),
    );

    foreach ($sidebars as $id => $name) {
        register_sidebar(array_merge($sidebar_defaults, array('id' => $id, 'name' => $name)));
    }
});


if (is_singular()) wp_enqueue_script('comment-reply');

add_filter('template_include', function ($template){
    global $fallback;
    $fallback = basename($template);
    locate_template('shared/fallback.php', true);
}, 99);

add_filter('wp_enqueue_scripts', function ()
{
    $config = require get_template_directory() . '/config/assets.php';

    $theme = wp_get_theme();

    $version = $theme->Version;

    foreach ((array)$config['css'] as $style) {
        wp_enqueue_style(
        	$style['handle'],
	        get_template_directory_uri() . $style['src'],
	        array_key_exists('deps', $style) ? $style['deps'] : null,
	        $version);
    }

    foreach ((array)$config['js'] as $script) {
        wp_enqueue_script(
            $script['handle'],
            get_template_directory_uri() . $script['src'],
	        array_key_exists('deps', $script) ? $script['deps'] : null,
            $version,
	        array_key_exists('footer', $script) ? $script['footer'] : true);
    }
});