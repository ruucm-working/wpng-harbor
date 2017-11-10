<?php

if (!defined('ABSPATH')) {
    exit;
}

// Check if get_plugins() function exists. This is required on the front end of the
// site, since it is in a file that is normally only loaded in the admin.
if (!function_exists('get_plugins')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$includes = array(
    'woocommerce-quantity-increment.php'
);

$all_plugins = get_plugins();

foreach ($all_plugins as $path => $info) {
    if (strpos($path, 'woocommerce') !== false) {
        foreach ($includes as $include) {
            if ((strpos($path, $include) !== false) && is_plugin_active($path)) {
                require_once __DIR__ . '/plugins/' . $include;
            }
        }
    }
}