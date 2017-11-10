<?php return array(
	array(
		'name' => 'project',
		'args' => array(
			'menu_icon'   => 'dashicons-portfolio',
			'supports'    => array(
				'title',
				'editor',
				'thumbnail',
				'revisions',
				'excerpt',
				'page-attributes',
				'comments',
				'revisions'
			),
			'has_archive' => false,
			'rewrite'     => array(
				'slug' => 'projects'
			)
		)
	)
);
