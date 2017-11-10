<?php
/**
 * Cart Page
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

wc_print_notices();

do_action('woocommerce_before_cart'); ?>

<div class="row">
    <div class="col-md-7 col-lg-8">

        <form action="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" method="post" class="cart-form">

            <?php do_action('woocommerce_before_cart_table'); ?>

            <table class="table table-cart cart">
                <thead>
                <tr>
                    <th class="product-remove hidden-xs">&nbsp;</th>
                    <th class="product-thumbnail">&nbsp;</th>
                    <th class="product-price"><?php _e( 'Product', 'woocommerce' ); ?> / <?php _e( 'Price', 'woocommerce' ); ?></th>
                    <th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
                    <th class="product-subtotal"><?php _e( 'Total', 'woocommerce' ); ?></th>
                </tr>
                </thead>
                <tbody>

                <?php do_action('woocommerce_before_cart_contents'); ?>

                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : ?>
                    <?php
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                    ?>

                    <?php if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) : ?>
                        <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

                            <td class="product-remove hidden-xs" rowspan="2">
                                <?php
                                echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                    '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s"><span class="ion-ios-close-empty"></span></a>',
                                    esc_url(WC()->cart->get_remove_url($cart_item_key)),
                                    __('Remove this item', 'woocommerce'),
                                    esc_attr($product_id),
                                    esc_attr($_product->get_sku())
                                ), $cart_item_key);
                                ?>
                            </td>

                            <td class="product-thumbnail" rowspan="2">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                if (!$_product->is_visible()) {
                                    echo $thumbnail;
                                } else {
                                    printf('<a href="%s">%s</a>', esc_url($_product->get_permalink($cart_item)), $thumbnail);
                                }
                                ?>
                            </td>

                            <td class="product-name" colspan="3">
                                <?php
                                if (!$_product->is_visible()) {
                                    echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key) . '&nbsp;';
                                } else {
                                    echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s </a>', esc_url($_product->get_permalink($cart_item)), $_product->get_title()), $cart_item, $cart_item_key);
                                }

                                // Meta data
                                echo WC()->cart->get_item_data($cart_item);

                                // Backorder notification
                                if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                    echo '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="product-price">
                                <?php
                                echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                ?>
                            </td>

                            <td class="product-quantity">
                                <?php
                                if ($_product->is_sold_individually()) {
                                    $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                } else {
                                    $product_quantity = woocommerce_quantity_input(array(
                                        'input_name' => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'max_value' => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                        'min_value' => '0'
                                    ), $_product, false);
                                }

                                echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                ?>
                            </td>

                            <td class="product-subtotal">
                                <?php
                                echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                ?>
                            </td>
                        </tr>

                    <?php endif ?>

                <?php endforeach ?>

                <?php do_action('woocommerce_cart_contents'); ?>

                <?php do_action('woocommerce_after_cart_contents'); ?>

                </tbody>
            </table>

            <div class="row">
                <div
                    class="<?php echo (WC()->cart->coupons_enabled()) ? 'col-sm-6 col-sm-push-6 col-md-4 col-md-push-8 col-lg-6 col-lg-push-6' : 'col-sm-12' ?>">
                    <div class="cart-actions text-right">
                        <input type="submit" class="btn btn-flat btn-default" name="update_cart"
                               value="<?php esc_attr_e('Update Cart', 'woocommerce'); ?>"/>

                        <?php do_action('woocommerce_cart_actions'); ?>
                        <?php wp_nonce_field('woocommerce-cart'); ?>
                    </div>
                </div>

                <?php if (WC()->cart->coupons_enabled()) : ?>
                    <div class="col-sm-6 col-sm-pull-6 col-md-8 col-md-pull-4 col-lg-6 col-lg-pull-6">
                        <div class="coupon">
                            <div class="input-group">
                                <label for="coupon_code" class="sr-only"><?php _e('Coupon', 'woocommerce'); ?>:</label>
                                <input type="text" name="coupon_code" class="form-control" id="coupon_code" value=""
                                       placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>"/>
                        <span class="input-group-btn">
                            <input type="submit" class="btn btn-flat btn-default" name="apply_coupon"
                                   value="<?php esc_attr_e('Apply Coupon', 'woocommerce'); ?>"/>
                        </span>
                            </div>

                            <?php do_action('woocommerce_cart_coupon'); ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <?php do_action('woocommerce_after_cart_table'); ?>
        </form>

        <div class="cart-collaterals">

            <?php do_action('woocommerce_cart_collaterals'); ?>

        </div>
    </div>

    <div class="col-md-5 col-lg-4">
        <div class="cart-totals-wrapper">

            <?php do_action('om_wc_cart_totals'); ?>

        </div>
    </div>
</div>


<?php do_action('woocommerce_after_cart'); ?>
