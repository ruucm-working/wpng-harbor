<?php

require_once __DIR__ . '/woocommerce/cart.php';
require_once __DIR__ . '/woocommerce/forms.php';
require_once __DIR__ . '/woocommerce/navigation.php';
require_once __DIR__ . '/woocommerce/single-product.php';
require_once __DIR__ . '/woocommerce/review.php';
require_once __DIR__ . '/woocommerce/plugins.php';

use Essentials\Data\Options;
use Essentials\Html\Writer;

add_action('woocommerce_init', function () {

    // Shop Loop
    // =========

    // Remove WC default title
    add_filter('woocommerce_show_page_title', '__return_false');

    add_filter('loop_shop_per_page', function ($products_per_page) {
        $count = Options::get('wc_products_per_page');

        return is_numeric($count) ? $count : $products_per_page;
    });

    add_filter('loop_shop_columns_cat', function ($number) {
        $value = Options::get('wc_loop_columns_cat');
        return !empty($value) ? $value : $number;
    });

    add_filter('woocommerce_subcategory_count_html', function ($html, $category) {
        return ' <mark class="count"> ' . $category->count . ' </mark>';
    }, 10, 2);

    add_filter('loop_shop_columns', function ($number) {
        $value = Options::get('wc_loop_columns');
        return !empty($value) ? $value : $number;
    });

    add_filter('om_wc_loop_label_text', function ($label, $product) {
        /**
         * @var $product WC_Product
         */

        if (!Options::get('wc_loop_badges_enabled')) {
            return null;
        }

        if (!$product->is_purchasable() && !$product->is_type('grouped') && !$product->is_type('external')) {
            $label = esc_html__('Not for sale', 'colors-creative');
        } else if (!$product->is_in_stock()) {
            $label = esc_html__('Out of stock', 'colors-creative');
        } else if ($product->is_featured()) {
            $label = esc_html__('Featured', 'colors-creative');
        }

        return $label;
    }, 10, 2);

    add_filter('woocommerce_sale_flash', function ($html, $post, $product) {
        return Options::get('wc_loop_badges_enabled') ? $html : '';
    }, 10, 3);

    add_filter('woocommerce_loop_add_to_cart_link', function ($link, $product) {
        /**
         * @var WC_Product $product
         */

        return om_loop_add_to_cart_link($product);
    }, 10, 2);

    add_filter('woocommerce_product_get_rating_html', function ($rating_html) {
        return '<div class="star-rating-loop">' . $rating_html . '</div>';
    });

    // Actions

    add_action('woocommerce_before_shop_loop', function () {
        wc_get_template_part('wrapper', 'start');
    }, 10);

    add_action('woocommerce_after_shop_loop', function () {
        wc_get_template_part('wrapper', 'end');
    }, 100);

    add_action('woocommerce_before_shop_loop', function () {
        echo '<div class="row"><div class="col-sm-8 col-lg-9">';
    }, 15);

    add_action('woocommerce_before_shop_loop', function () {
        echo '</div><div class="col-sm-4 col-lg-3">';
    }, 25);

    add_action('woocommerce_before_shop_loop', function () {
        echo '</div></div>';
    }, 35);

    add_action('woocommerce_before_shop_loop', function () {
        wc_get_template_part('loop/loop-wrapper', 'start');
    }, 100);

    add_action('woocommerce_after_shop_loop', function () {
        wc_get_template_part('loop/loop-wrapper', 'end');
    }, 15);

    add_action('woocommerce_before_shop_loop_item', function () {
        wc_get_template_part('loop/product', 'quantity');
    });

    add_action('woocommerce_before_shop_loop_item_title', function () {
        wc_get_template_part('loop/product', 'label');
    });

    // Product
    // =======

    add_action('woocommerce_before_single_product', function () {
        wc_get_template_part('wrapper', 'start');
    }, 5);

    add_action('woocommerce_after_single_product', function () {
        wc_get_template_part('wrapper', 'end');
    }, 100);

    add_filter('woocommerce_product_gallery_attachment_ids', function ($attachment_ids, $product) {
        $thumbnail_id = get_post_thumbnail_id($product->id);

        if ($thumbnail_id) {
            $attachment_ids = array_merge(array($thumbnail_id), $attachment_ids);
        }

        return $attachment_ids;
    }, 10, 2);

    add_filter('woocommerce_available_variation', function ($params, $product, $variation) {
        /**
         * @var array $params
         * @var WC_Product $product
         * @var WC_Product|int $variation Variation product object or ID
         */

        if (!empty($params['image_link']) && has_post_thumbnail($variation->get_variation_id())) {
            $attachment_id = get_post_thumbnail_id($variation->get_variation_id());
            $attachment = wp_get_attachment_image_src($attachment_id, 'full');

            if ($attachment) {
                $params['image_link_width'] = $attachment[1];
                $params['image_link_height'] = $attachment[2];
            }
        }

        return $params;
    }, 10, 3);

    add_action('om_wc_add_to_cart_button', function () {
        global $product;

        om_wc_product_add_to_cart_button($product)->out();
    });

    // Checkout
    // ========

    add_filter('woocommerce_checkout_fields', 'om_wc_filter_fields_groups');
    add_filter('woocommerce_order_button_html', 'om_wc_filter_button_html');

    // My account
    // ==========

    add_filter('woocommerce_address_to_edit', 'om_wc_filter_fields');
});


add_action('om_wc_before_quantity_input', function () {
    echo '<span class="input-group-btn"><a class="btn btn-flat btn-default" data-toggle="quantity" data-value="-1">&minus;</a></span>';
});

add_action('om_wc_after_quantity_input', function () {
    echo '<span class="input-group-btn"><a class="btn btn-flat btn-default" data-toggle="quantity" data-value="1">+</a></span>';
});

/**
 * @param $columns int Number of columns in the shop row
 * @return array Column size rates in the shop row
 */
function om_get_shop_col_rates($columns)
{
    switch ($columns) {
        case 2 :
            $rates = array(12, 6, 6, 6);
            break;
        case 3 :
            $rates = array(12, 6, 4, 4);
            break;
        case 4 :
            $rates = array(12, 6, 4, 3);
            break;
        case 6 :
            $rates = array(6, 4, 3, 2);
            break;
        default:
            $rates = array(12, 12, 12, 12);
    }

    return $rates;
}

/**
 * @param $col_rates array Column size rates in the shop row
 * @return string Column classes in the shop row
 */
function om_get_shop_col_classes($col_rates)
{
    $breakpoints = array('xs', 'sm', 'md', 'lg');
    $classes = array();

    foreach ($col_rates as $index => $rate) {
        if (0 === $index || $rate !== $col_rates[$index - 1]) {
            $classes[] = 'col-' . $breakpoints[$index] . '-' . $rate;
        }
    }

    return implode(' ', $classes);
}

function om_shop_clearfix($col_rates, $loop)
{
    if ($loop === 0) {
        return;
    }

    $breakpoints = array('xs', 'sm', 'md', 'lg');
    $clearfix = array();

    foreach ($col_rates as $index => $rate) {
        if ($rate < 12 && 0 === $loop % (12 / $rate)) {
            $clearfix[] = 'visible-' . $breakpoints[$index];
        }
    }

    if (count($clearfix)) {
        echo '<div class="clearfix ' . implode(' ', $clearfix) . '"></div>';
    }
}

/**
 * Add to Cart Link Params
 *
 * @param WC_Product $product
 * @return array
 */
function om_get_add_to_cart_link_params($product)
{
    $classes = array('btn', 'btn-flat', 'btn-add-to-cart', 'product_type_' . sanitize_html_class($product->product_type));

    if ($product->is_purchasable() && $product->is_in_stock()) {
        $classes[] = 'add_to_cart_button';
    }

    if ($product->supports('ajax_add_to_cart')) {
        $classes[] = 'ajax_add_to_cart';
    }

    if (!$product->is_in_stock()) {
        $icon_default = 'ion-sad-outline';
    } else if ($product->is_type('grouped')) {
        $icon_default = 'ion-ios-photos-outline';
    } else if ($product->is_type('external')) {
        $icon_default = 'ion-ios-arrow-thin-right';
    } else if (!$product->is_purchasable()) {
        $icon_default = 'ion-ios-information-outline';
    } else if ($product->is_type('variable')) {
        $icon_default = 'ion-ios-checkmark-outline';
    } else {
        $icon_default = 'ion-ios-plus-empty loading-spin';
        $icon_added = 'ion-ios-checkmark-empty';
    }


    return array(
        'attributes' => array(
            'href' => esc_url($product->add_to_cart_url()),
            'rel' => 'nofollow',
            'data-product_id' => esc_attr($product->id),
            'product_sku' => esc_attr($product->get_sku()),
            'data-quantity' => esc_attr(isset($quantity) ? $quantity : 1),
            'class' => implode(' ', $classes),
        ),
        'icon' => $icon_default,
        'icon_secondary' => isset($icon_added) ? $icon_added : null
    );
}

/**
 * Generate Add to Cart Link
 *
 * @param $product WC_Product
 *
 * @return string
 */
function om_loop_add_to_cart_link($product)
{
    $params = om_get_add_to_cart_link_params($product);

    $params['attributes']['class'] .= $product->is_in_stock() ? ' btn-primary' : ' btn-default';

    $html = Writer::init()
        ->a($params['attributes'])
        ->span('class="action-tooltip"', esc_html($product->add_to_cart_text()), true);


    $html->span(array('class' => 'default ' . apply_filters('om_wc_loop_add_to_cart_icon', $params['icon'], $product)), '', true);

    if (!empty($params['icon_secondary'])) {
        $html->span(array('class' => 'success ' . apply_filters('om_wc_loop_add_to_cart_icon_added', $params['icon_secondary'], $product)), '', true);
    }

    return $html->to_string();
}

/**
 * Generate Add to Cart Link
 *
 * @param $product WC_Product
 *
 * @return string
 */
function om_the_single_add_to_cart_link($product)
{
    $params = om_get_add_to_cart_link_params($product);

    $params['attributes']['class'] .= ' btn-default';

    Writer::init()->a($params['attributes'], esc_html($product->add_to_cart_text()), true)->out();
}

/**
 * Output the add to cart button for variations (WooCommerce override).
 */
function woocommerce_single_variation_add_to_cart_button()
{
    global $product;

    Writer::init()
        ->div('variations_button"')
        ->text(woocommerce_quantity_input(array('input_value' => isset($_POST['quantity']) ? wc_stock_amount($_POST['quantity']) : 1), $product, false))
        ->html(om_wc_product_add_to_cart_button($product))
        ->input(array(
            'type' => 'hidden',
            'name' => 'add-to-cart',
            'value' => absint($product->id)
        ), true)
        ->input(array(
            'type' => 'hidden',
            'name' => 'product_id',
            'value' => absint($product->id)
        ), true)
        ->input(array(
            'type' => 'hidden',
            'name' => 'variation_id',
            'class' => 'variation_id',
            'value' => ''
        ), true)
        ->out();
}

/**
 * @param $product WC_Product
 * @return Writer Html object with add to cart button
 */
function om_wc_product_add_to_cart_button($product)
{
    return Writer::init()
        ->div('class="form-group"')
        ->button(array(
            'type' => 'submit',
            'class' => 'single_add_to_cart_button btn btn-flat btn-primary'
        ), $product->single_add_to_cart_text(), true);
}

function om_wc_get_rating_select_field()
{
    return '<select name="rating" id="rating">
                <option value="">' . __('Rate&hellip;', 'woocommerce') . '</option>
                <option value="5">' . __('Perfect', 'woocommerce') . '</option>
                <option value="4">' . __('Good', 'woocommerce') . '</option>
                <option value="3">' . __('Average', 'woocommerce') . '</option>
                <option value="2">' . __('Not that bad', 'woocommerce') . '</option>
                <option value="1">' . __('Very Poor', 'woocommerce') . '</option>
            </select>';
}