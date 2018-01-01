<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">

        <div role="tabpanel">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-login" aria-controls="login" role="tab" data-toggle="tab"><?php esc_html_e( 'Login', 'woocommerce' ); ?></a></li>
                <?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
                <li role="presentation"><a href="#tab-register" aria-controls="register" role="tab" data-toggle="tab"><?php esc_html_e( 'Register', 'woocommerce' ); ?></a></li>
                <?php endif; ?>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="tab-login">
                    <h2 class="sr-only"><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

                    <form method="post" class="login">

                        <?php do_action( 'woocommerce_login_form_start' ); ?>

                        <div class="form-group">
                            <label for="username"><?php _e( 'Username or email address', 'woocommerce' ); ?> <span class="required">*</span></label>
                            <input type="text" class="form-control" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                            <input class="form-control" type="password" name="password" id="password" />
                        </div>

                        <?php do_action( 'woocommerce_login_form' ); ?>

                        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

                        <div class="checkbox">
                            <label for="rememberme">
                                <input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'woocommerce' ); ?>
                            </label>
                        </div>

                        <input type="submit" class="btn btn-flat btn-primary" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>" />

                        <a class="btn btn-link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>

                        <?php do_action( 'woocommerce_login_form_end' ); ?>

                    </form>
                </div>
                <?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
                <div role="tabpanel" class="tab-pane fade" id="tab-register">
                    <h2 class="sr-only"><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

                    <form method="post">

                        <?php do_action( 'woocommerce_register_form_start' ); ?>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                            <div class="form-group">
                                <label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
                                <input type="text" class="form-control" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                            </div>

                        <?php endif; ?>

                        <div class="form-group">
                            <label for="reg_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
                            <input type="email" class="form-control" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
                        </div>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                            <div class="form-group">
                                <label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                                <input type="password" class="form-control" name="password" id="reg_password" />
                            </div>

                        <?php endif; ?>

                        <!-- Spam Trap -->
                        <div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

                        <?php do_action( 'woocommerce_register_form' ); ?>
                        <?php do_action( 'register_form' ); ?>

                        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                        <input type="submit" class="btn btn-flat btn-primary" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>" />

                        <?php do_action( 'woocommerce_register_form_end' ); ?>

                    </form>
                </div>
                <?php endif; ?>
            </div>

        </div>

    </div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
