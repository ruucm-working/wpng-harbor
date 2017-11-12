<?php
/**
 * Order Customer Details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="row">
	<div class="col-lg-6">
		<header><h2><?php _e( 'Customer Details', 'woocommerce' ); ?></h2></header>

		<table class="table">
			<?php if ( $order->customer_note ) : ?>
				<tr>
					<th><?php _e( 'Note:', 'woocommerce' ); ?></th>
					<td><?php echo wptexturize( $order->customer_note ); ?></td>
				</tr>
			<?php endif; ?>

			<?php if ( $order->billing_email ) : ?>
				<tr>
					<th><?php _e( 'Email:', 'woocommerce' ); ?></th>
					<td><?php echo esc_html( $order->billing_email ); ?></td>
				</tr>
			<?php endif; ?>

			<?php if ( $order->billing_phone ) : ?>
				<tr>
					<th><?php _e( 'Telephone:', 'woocommerce' ); ?></th>
					<td><?php echo esc_html( $order->billing_phone ); ?></td>
				</tr>
			<?php endif; ?>

			<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
		</table>
	</div>
	<div class="<?php echo (!wc_ship_to_billing_address_only() && $order->needs_shipping_address()) ? 'col-sm-6 col-lg-3' : 'col-lg-6' ?>">
        <header class="title">
            <h3><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>
        </header>
        <address>
            <?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
        </address>
	</div>
    <?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>
        <div class="col-sm-6 col-lg-3">
            <div class="col-2">
                <header class="title">
                    <h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>
                </header>
                <address>
                    <?php echo ( $address = $order->get_formatted_shipping_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
                </address>
            </div>
        </div>
    <?php endif; ?>
</div>
