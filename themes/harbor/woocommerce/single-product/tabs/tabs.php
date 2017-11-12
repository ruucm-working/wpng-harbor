<?php
/**
 * Single Product tabs
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters('woocommerce_product_tabs', array());

if (!empty($tabs)) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper" role="tabpanel">
		<ul class="nav nav-tabs" role="tablist">
			<?php foreach ($tabs as $key => $tab) : ?>
				<li class="<?php echo esc_attr($key); ?>_tab<?php if ($tab === reset($tabs)): echo ' active'; endif ?>"
				    role="presentation">
					<a href="#tab-<?php echo esc_attr($key); ?>" data-toggle="tab"
					   role="tab"><?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php foreach ($tabs as $key => $tab) : ?>
			<div class="panel entry-content<?php if ($tab === reset($tabs)): echo ' active'; endif ?>"
			     id="tab-<?php echo esc_attr($key); ?>" role="tabpanel">
				<?php call_user_func($tab['callback'], $key, $tab); ?>
			</div>
		<?php endforeach; ?>
	</div>

<?php endif; ?>
