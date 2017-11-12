<?php return array(
	'content_width' => 1140,
	'support'       => array(
		'post-thumbnails' => true,
		'woocommerce'     => true
	),
	'menus'         => array(
		'primary' => 'Primary navigation'
	),
	'sidebars'      => array(
		
		'posts_left'       => array(
			'name'        => 'Posts Page Left Sidebar',
			'description' => 'Widget area will be shown left at the posts page\'s'
		),
		'posts_right'      => array(
			'name'        => 'Posts Page Right Sidebar',
			'description' => 'Widget area will be shown right at the posts page\'s'
		),
		'primary_footer'   => array(
			'name'        => 'Primary Footer',
			'description' => 'Primary widget area will be shown at the page\'s bottom',
			'columns'     => 4,
			'field'       => 'sidebar_primary_footer_columns'
		),
		'secondary_footer' => array(
			'name'        => 'Secondary Footer',
			'description' => 'Secondary widget area will be shown at the page\'s bottom',
			'columns'     => 4,
			'field'       => 'sidebar_secondary_footer_columns'
		),
		'woocommerce_shop' => array(
			'name'        => 'Woocommerce shop',
			'description' => 'Shop widget area will be shown at the page\'s side',
			'dependency'  => array(
				'type'  => 'class_exists',
				'value' => 'WooCommerce'
			)
		)
	)
);
