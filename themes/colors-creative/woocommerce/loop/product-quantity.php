<?php
/**
 * Loop Cart Quantity
 *
 * @var $product WC_Product
 * @var $woocommerce_loop array
 */

global $product, $woocommerce_loop;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Store loop items quantities
if (empty($woocommerce_loop['item_quantities'])) {
    $woocommerce_loop['item_quantities'] = WC()->cart->get_cart_item_quantities();
}

$quantity = isset($woocommerce_loop['item_quantities'][$product->id]) ? $woocommerce_loop['item_quantities'][$product->id] : 0;

$quantity_html = '<a href="' . esc_url(WC()->cart->get_cart_url()) . '" class="product-label right" data-product="' . esc_attr($product->id) . '" data-product-quantity="' . esc_attr($quantity) . '">' . ($quantity ? esc_html($quantity) : '') . '</a>';

echo apply_filters('om_wc_loop_quantity', $quantity_html, $product);