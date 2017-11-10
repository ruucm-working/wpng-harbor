<?php return array(
	array(
		'name'      => 'Page Settings',
		'priority'  => 'high',
		'post_type' => array(
			'post',
			'page',
			'project',
			'product'
		),
		'options'   => array(
			array(
				'name'    => 'Background color behavior',
				'id'      => 'background_color_behavior_current',
				'type'    => 'select',
				'options' => array(
					'default'  => 'Default',
					'static'   => 'Static',
					'shifting' => 'Shifting'
				),
				'default' => 'default'
			),
			array(
				'name' => 'Hide navigation bar',
				'desc' => 'Yes, please',
				'id'   => 'hide_navigation_bar',
				'type' => 'checkbox'
			),
			array(
				'name'    => 'Navigation bar overlay',
				'id'      => 'navigation_overlay_current',
				'type'    => 'select',
				'options' => array(
					'default' => 'Default',
					'true'    => 'True',
					'false'   => 'False'
				),
				'default' => 'default'
			),
			array(
				'name'    => 'Navigation bar inherit section color',
				'id'      => 'navigation_header_overlay_by_section_current',
				'type'    => 'select',
				'options' => array(
					'default' => 'Default',
					'true'    => 'True',
					'false'   => 'False'
				),
				'default' => 'default'
			),
			array(
				'name'    => 'Opened navigation background inherit section color',
				'id'      => 'navigation_nav_by_section_current',
				'type'    => 'select',
				'options' => array(
					'default' => 'Default',
					'true'    => 'True',
					'false'   => 'False'
				),
				'default' => 'default'
			),
			array(
				'name'  => 'Page text color',
				'id'    => 'text_color_current',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name' => 'Page background color',
				'id'   => 'body_background_color_current',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Hide footer',
				'desc' => 'Yes, please',
				'id'   => 'hide_footer',
				'type' => 'checkbox'
			)
		)
	),
	array(
		'name'      => 'Page Header Settings',
		'priority'  => 'high',
		'post_type' => array(
			'post',
			'page',
			'project',
			'product'
		),
		'options'   => array(
			array(
				'name'    => 'Display',
				'id'      => 'header_display_current',
				'type'    => 'select',
				'options' => array(
					'default'   => 'Default',
					'visible'   => 'Visible',
					'invisible' => 'Invisible',
					'none'      => 'No header'
				),
				'default' => 'default'
			),
			array(
				'name'    => 'Type',
				'id'      => 'header_type',
				'type'    => 'select',
				'options' => array(
					'standard'       => 'Standard',
					'standard-fluid' => 'Standard fluid',
					'alternate'      => 'Alternate'
				),
				'default' => 'standard'
			),
			array(
				'name' => 'Header height',
				'id'   => 'header_height',
				'type' => 'number',
				'min'  => 0,
				'max'  => 100,
				'unit' => '%',
				'desc' => 'Note: applicable only to the standard header'
			),
			array(
				'name' => 'Background main color',
				'id'   => 'header_background_color',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Background additional color 1',
				'id'   => 'header_background_color_2',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Background additional color 2',
				'id'   => 'header_background_color_3',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Background additional color 3',
				'id'   => 'header_background_color_4',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Background additional color 4',
				'id'   => 'header_background_color_5',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Background additional color 5',
				'id'   => 'header_background_color_6',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Background additional color 6',
				'id'   => 'header_background_color_7',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Background additional color 7',
				'id'   => 'header_background_color_8',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Background additional color 8',
				'id'   => 'header_background_color_9',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Background additional color 9',
				'id'   => 'header_background_color_10',
				'type' => 'color-advanced'
			),
			array(
				'name'    => 'Content horizontal alignment',
				'id'      => 'header_content_h_alignment',
				'type'    => 'select',
				'options' => array(
					'left'   => 'Left',
					'center' => 'Center',
					'right'  => 'Right'
				),
				'default' => 'center'
			),
			array(
				'name'    => 'Content vertical alignment',
				'id'      => 'header_content_v_alignment',
				'type'    => 'select',
				'options' => array(
					'top'    => 'Top',
					'middle' => 'Middle',
					'bottom' => 'Bottom'
				),
				'default' => 'middle'
			),
			array(
				'name' => 'Hide title and subtitle',
				'desc' => 'Yes, please',
				'id'   => 'header_title_hide',
				'type' => 'checkbox'
			),
			array(
				'name'   => 'Title overwrite',
				'id'     => 'header_title',
				'type'   => 'code',
				'lang'   => 'html',
				'height' => 36
			),
			array(
				'name'  => 'Title color',
				'id'    => 'header_title_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'   => 'Subtitle',
				'id'     => 'header_subtitle',
				'type'   => 'code',
				'lang'   => 'html',
				'height' => 36
			),
			array(
				'name'  => 'Subtitle color',
				'id'    => 'header_subtitle_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name' => 'Description',
				'id'   => 'header_text',
				'type' => 'editor',
				'rows' => 3
			),
			array(
				'name'  => 'Description color',
				'id'    => 'header_text_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'    => 'Use featured image as background',
				'id'      => 'header_use_featured_image_current',
				'type'    => 'select',
				'options' => array(
					'default' => 'Default',
					'true'    => 'Yes',
					'false'   => 'No'
				),
				'default' => 'default'
			),
			array(
				'name' => 'Image',
				'id'   => 'header_image',
				'type' => 'upload'
			),
			array(
				'name'    => 'Image size',
				'id'      => 'header_image_size',
				'type'    => 'select',
				'options' => array(
					'cover'   => 'Cover',
					'contain' => 'Contain',
					'initial' => 'Initial'
				),
				'default' => 'cover'
			),
			array(
				'name'  => 'Image overlay',
				'id'    => 'header_overlay_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'    => 'Parallax',
				'desc'    => 'Yes, please',
				'id'      => 'header_parallax',
				'type'    => 'checkbox',
				'default' => true
			),
			array(
				'name' => 'Show overflow',
				'desc' => 'Yes, please',
				'id'   => 'header_section_show_overflow',
				'type' => 'checkbox'
			),
			array(
				'name'    => 'Navigation bar transparency',
				'id'      => 'header_navigation_bar_transparency',
				'type'    => 'number',
				'min'     => 0,
				'max'     => 1,
				'step'    => 0.1,
				'default' => 1
			)
		)
	),
	array(
		'name'      => 'Footer Settings',
		'priority'  => 'high',
		'post_type' => array(
			'post',
			'page',
			'project',
			'product'
		),
		'options'   => array(
			array(
				'name' => 'Primary footer background',
				'id'   => 'footer_primary_background_color_current',
				'type' => 'color'
			),
			array(
				'name' => 'Primary footer text',
				'id'   => 'footer_primary_text_color_current',
				'type' => 'color'
			),
			array(
				'name' => 'Primary footer links',
				'id'   => 'footer_primary_link_color_current',
				'type' => 'color'
			),
			array(
				'name' => 'Primary footer hover links',
				'id'   => 'footer_primary_link_color_hover_current',
				'type' => 'color'
			),
			array(
				'name' => 'Secondary footer background',
				'id'   => 'footer_secondary_background_color_current',
				'type' => 'color'
			),
			array(
				'name' => 'Secondary footer text',
				'id'   => 'footer_secondary_text_color_current',
				'type' => 'color'
			),
			array(
				'name' => 'Secondary footer links',
				'id'   => 'footer_secondary_link_color_current',
				'type' => 'color'
			),
			array(
				'name' => 'Secondary footer hover links',
				'id'   => 'footer_secondary_link_color_hover_current',
				'type' => 'color'
			)
		)
	),
	array(
		'name'      => 'Posts page colors',
		'context'   => 'side',
		'priority'  => 'low',
		'post_type' => array(
			'post'
		),
		'options'   => array(
			array(
				'name'  => 'Item background',
				'id'    => 'index_item_current_background_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'  => 'Item title',
				'id'    => 'index_item_current_title_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'  => 'Item meta',
				'id'    => 'index_item_current_meta_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'  => 'Item hover background',
				'id'    => 'index_item_current_hover_background_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'  => 'Item hover title',
				'id'    => 'index_item_current_hover_title_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'  => 'Item hover meta',
				'id'    => 'index_item_current_hover_meta_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'  => 'Item image overlay color',
				'id'    => 'index_item_current_overlay_color',
				'type'  => 'color-advanced',
				'alpha' => true
			)
		)
	),
	array(
		'name'      => 'Project Grid Settings',
		'context'   => 'side',
		'priority'  => 'low',
		'post_type' => array(
			'project'
		),
		'options'   => array(
			array(
				'name'  => 'Portfolio color',
				'id'    => 'project_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'  => 'Portfolio title color',
				'id'    => 'project_title_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name'  => 'Portfolio additional info color',
				'id'    => 'project_additional_color',
				'type'  => 'color-advanced',
				'alpha' => true
			),
			array(
				'name' => 'Portfolio secondary image',
				'id'   => 'project_secondary_image',
				'type' => 'upload'
			),
			array(
				'name'    => 'Device type',
				'id'      => 'device_type',
				'type'    => 'select',
				'options' => array(
					'empty'            => 'None',
					'phone'            => 'Phone',
					'tablet'           => 'Tablet',
					'tablet landscape' => 'Landscape tablet',
					'browser'          => 'Desktop browser',
					'watch'            => 'Watch'
				),
				'default' => 'empty'
			),
			array(
				'name' => 'Device color',
				'id'   => 'device_color',
				'type' => 'color-advanced'
			),
			array(
				'name' => 'Custom link',
				'id'   => 'project_custom_link',
				'type' => 'text'
			)
		)
	),
	array(
		'name'      => 'Contact Form 7 Settings',
		'post_type' => array(
			'wpcf7_contact_form'
		),
		'options'   => array(
			array(
				'name'    => '3 in row',
				'id'      => '3_in_row',
				'type'    => 'checkbox',
				'default' => true
			)
		)
	)
);
