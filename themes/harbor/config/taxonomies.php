<?php return array(
	array(
		'name'       => 'project_category',
		'singular'   => 'Category',
		'plural'     => 'Categories',
		'post_types' => array('project'),
		'args'       => array(
			'hierarchical' => true
		)
	),
	array(
		'name'       => 'project_tag',
		'singular'   => 'Tag',
		'plural'     => 'Tags',
		'post_types' => array('project')
	)
);
