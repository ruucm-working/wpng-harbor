<?php return array(
	'css' => array(
		array(
			'handle'  => 'bootstrap',
			'src'     => '/assets/css/bootstrap.min.css',
			'version' => true
		),
		array(
			'handle'  => 'bootstrap-extends',
			'src'     => '/assets/css/bootstrap-extends.min.css',
			'deps'    => array('bootstrap'),
			'version' => true
		),
		array(
			'handle'  => 'theme',
			'src'     => '/assets/css/theme.min.css',
			'deps'    => array('bootstrap', 'bootstrap-extends'),
			'version' => true
		),
		array(
			'handle'     => 'theme-visual-composer',
			'src'        => '/assets/css/visual-composer.min.css',
			'deps'       => array('bootstrap', 'bootstrap-extends', 'js_composer_front'),
			'version'    => true,
			'dependency' => array(
				'type'  => 'om_is_vc_page',
				'value' => null
			)
		),
		array(
			'handle'     => 'theme-woocommerce',
			'src'        => '/assets/css/woocommerce.min.css',
			'deps'       => array(
				'bootstrap',
				'bootstrap-extends',
				'woocommerce-general',
				'woocommerce-layout',
				'woocommerce-smallscreen'
			),
			'version'    => true,
			'dependency' => array(
				'type'  => 'class_exists',
				'value' => 'WooCommerce'
			)
		),
		array(
			'handle'     => 'theme-masterslider',
			'src'        => '/assets/css/masterslider.min.css',
			'deps'       => array('ms-main'),
			'version'    => true,
			'dependency' => array(
				'type'  => 'defined',
				'value' => 'MSWP_AVERTA_VERSION'
			)
		),
		array(
			'handle'  => 'ionicons',
			'src'     => '/assets/css/ionicons.min.css',
			'version' => true
		),
		array(
			'handle'  => 'et-line',
			'src'     => '/assets/css/et-line.css',
			'version' => true
		),
		array(
			'handle'  => 'photoswipe',
			'src'     => '/assets/css/photoswipe/photoswipe.css',
			'version' => true
		)
	),
	'js'  => array(
		array(
			'handle' => 'modernizr',
			'src'    => '/assets/js/vendor/modernizr.min.js',
			'cdn'    => '//cdn.jsdelivr.net/modernizr/2/modernizr.min.js',
			'test'   => 'window.Modernizr',
			'footer' => false
		),
		array(
			'handle'  => 'scripts',
			'src'     => '/assets/js/scripts.js',
			'deps'    => array('jquery'),
			'version' => true
		)
	)
);
