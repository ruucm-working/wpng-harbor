<?php

add_filter('ome_menu_items', function ($items) {
    if (om_is_wc_nav_cart()) {
        $page = get_post(wc_get_page_id('cart'));

        if($page !== null) {
            $arguments = array(
                'menu_level' => 0,
                'current' => is_cart(),
                'current_item_ancestor' => false,
                'url' => get_permalink($page),
                'target' => null,
                'attr_title' => '',
                'classes' => array(),
                'title' => $page->post_title,
                'li_classes' => array('visible-xs')
            );

            $items[] = new WP_Post((object)$arguments);
        }
    }

    return $items;
}, 10, 3);

function om_get_navigation_shop_icon_class() {
    return apply_filters('om_navigation_shop_icon_class', 'icon ion-bag');
}