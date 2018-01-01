<?php
/**
 * Proceed to checkout button
 *
 * Contains the markup for the proceed to checkout button on the cart
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<a href="<?php echo esc_url( WC()->cart->get_checkout_url() ) ?>" class="btn btn-flat btn-primary">
	<?php esc_html_e( 'Proceed to Checkout', 'woocommerce' ) ?>
</a>
