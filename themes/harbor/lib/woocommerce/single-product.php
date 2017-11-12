<?php

add_filter('woocommerce_product_description_tab_title', 'om_product_tab_title', 10, 2);
add_filter('woocommerce_product_additional_information_tab_title', 'om_product_tab_title', 10, 2);
add_filter('woocommerce_product_reviews_tab_title', 'om_product_tab_title', 10, 2);

function om_product_tab_title($title, $key)
{
    $icons = array(
        'description' => 'ion-ios-pricetags-outline',
        'additional_information' => 'ion-ios-list-outline',
        'reviews' => 'ion-ios-chatboxes-outline'
    );

    if(array_key_exists($key, $icons)) {
        $title = "<span class=\"icon visible-xs {$icons[$key]}\"></span><span class=\"hidden-xs\">$title</span>";
    }

    return $title;
}