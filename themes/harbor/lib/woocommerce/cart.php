<?php

remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals');

add_action('om_wc_cart_totals', 'woocommerce_cart_totals');

add_filter('woocommerce_cross_sells_columns', function () {
    return 4;
});