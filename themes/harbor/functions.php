<?php

if (!function_exists('om_php_version_error_notice')) {
	function om_php_version_error_notice() {
		$message = 'It looks like your webhost runs PHP ' . phpversion() . '. However, the theme requires PHP 5.3+. Please, go to the webhost\'s admin panel and activate it.';
		echo "<div class=\"error\"><p>$message</p></div>";
	}
}

if (!function_exists('om_theme_init')) {
	function om_theme_init() {
		require_once __DIR__ . '/lib/plugin-activation.php';
		require_once __DIR__ . '/lib/comments.php';
		require_once __DIR__ . '/lib/contact-form.php';
		
		require_once __DIR__ . '/lib/utils.php';
		require_once __DIR__ . '/lib/theme.php';
		require_once __DIR__ . '/lib/content.php';
		require_once __DIR__ . '/lib/admin.php';
		
		if (om_is_essentials()) {
			require_once __DIR__ . '/lib/styles.php';
			require_once __DIR__ . '/lib/tiny-mce.php';
			require_once __DIR__ . '/lib/widgets.php';
			require_once __DIR__ . '/lib/jetpack.php';
			require_once __DIR__ . '/lib/add-to-any.php';
			
			if (om_is_wc()) {
				require_once __DIR__ . '/lib/woocommerce.php';
			}
			
			if (om_is_ms()) {
				require_once __DIR__ . '/lib/masterslider.php';
			}
			
			if (om_is_wpml()) {
				require_once __DIR__ . '/lib/wpml.php';
			}
			
			openmarco_essentials_init();
		} else {
			require_once __DIR__ . '/lib/fallback.php';
		}
	}
}

if (version_compare(phpversion(), '5.3.0', '<')) {
	if (is_admin()) {
		add_action('admin_notices', 'om_php_version_error_notice');
	} else {
		om_php_version_error_notice();
	}
} else {
	om_theme_init();
}