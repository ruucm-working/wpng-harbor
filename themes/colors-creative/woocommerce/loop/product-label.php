<?php
/**
 * Loop Cart Quantity
 *
 * @var $woocommerce WooCommerce
 * @var $product WC_Product
 * @var $woocommerce_loop array
 */

use Essentials\Html\Writer;

global $product;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * @hooked lib/woocommerce - 10
 */
$label = apply_filters('om_wc_loop_label_text', '', $product);

if (!empty($label)) {
    echo apply_filters('om_wc_loop_label', Writer::init()->span('class="product-label"', $label)->to_string(), $product);
}