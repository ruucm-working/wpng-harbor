<?php
/**
 * Login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_user_logged_in() ) {
	return;
}

?>
<form method="post" class="login" <?php if ( $hidden ) echo 'style="display:none;"'; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

	<?php if ( $message ) echo wpautop( wptexturize( $message ) ); ?>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="username"><?php _e( 'Username or email', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="text" class="form-control" name="username" id="username" />
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="form-control" name="password" id="password" />
			</div>
		</div>
	</div>

	<div class="checkbox">
		<label for="rememberme" class="inline">
			<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'woocommerce' ); ?>
		</label>
	</div>

	<?php do_action( 'woocommerce_login_form' ); ?>

	<?php wp_nonce_field( 'woocommerce-login' ); ?>
	<input type="submit" class="btn btn-flat btn-primary" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>" />
	<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>" />

	<a class="btn btn-link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
