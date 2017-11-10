<?php
if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('WooCommerce_Quantity_Increment')) {

    add_action('wp_enqueue_scripts', function () {
        wp_dequeue_script('wcqi-js');
        wp_dequeue_style('wcqi-css');
    }, 20);
}