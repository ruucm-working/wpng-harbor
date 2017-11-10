<?php

use Essentials\Data\Options;
use Essentials\Html\Css;

class OM_Portfolio extends OM_Base_Gallery {
	
	public function init() {
		$this->parameters = array(
			'category'    => esc_html__('Colors Creative', 'theme'),
			'name'        => esc_html__('Portfolio Lite', 'theme'),
			'description' => esc_html__('Display portfolio', 'theme'),
			'params'      => array_merge($this->common_params, array(
					// Data
					
					array(
						'group'      => 'Data',
						'param_name' => 'number',
						'type'       => 'number',
						'heading'    => esc_html__('Number', 'theme'),
						'value'      => '9',
						'min'        => '1',
						'max'        => '100',
						'weight'     => '100'
					),
					array(
						'group'      => 'Data',
						'param_name' => 'filter_type',
						'type'       => 'dropdown',
						'heading'    => esc_html__('Data filtering', 'theme'),
						'value'      => array(
							esc_html__('None', 'theme')                   => '',
							esc_html__('By categories and tags', 'theme') => 'taxonomies',
							esc_html__('By projects', 'theme')            => 'projects',
						),
						'std'        => ''
					),
					array(
						'group'       => 'Data',
						'param_name'  => 'filter_categories',
						'type'        => 'chosen',
						'heading'     => esc_html__('Categories', 'theme'),
						'placeholder' => esc_html__('Select categories', 'theme'),
						'taxonomies'  => 'project_category',
						'dependency'  => array(
							'element' => 'filter_type',
							'value'   => 'taxonomies'
						),
					),
					array(
						'group'       => 'Data',
						'param_name'  => 'filter_tags',
						'type'        => 'chosen',
						'heading'     => esc_html__('Tags', 'theme'),
						'placeholder' => esc_html__('Select tags', 'theme'),
						'taxonomies'  => 'project_tag',
						'dependency'  => array(
							'element' => 'filter_type',
							'value'   => 'taxonomies'
						),
					),
					array(
						'group'       => 'Data',
						'param_name'  => 'filter_projects',
						'type'        => 'chosen',
						'heading'     => esc_html__('Projects', 'theme'),
						'placeholder' => esc_html__('Select projects', 'theme'),
						'post_types'  => 'project',
						'dependency'  => array(
							'element' => 'filter_type',
							'value'   => 'projects'
						),
					),
					array(
						'group'      => 'Data',
						'param_name' => 'order_by',
						'type'       => 'dropdown',
						'heading'    => esc_html__('Order by', 'theme'),
						'value'      => array(
							esc_html__('Date', 'theme')              => 'date',
							esc_html__('Modification date', 'theme') => 'modified',
							esc_html__('Order attribute', 'theme')   => 'menu_order',
							esc_html__('Title', 'theme')             => 'title',
							esc_html__('Random', 'theme')            => 'rand',
						),
						'std'        => 'date'
					),
					array(
						'group'      => 'Data',
						'param_name' => 'order',
						'type'       => 'dropdown',
						'heading'    => esc_html__('Order', 'theme'),
						'value'      => array(
							esc_html__('Ascending', 'theme')  => 'ASC',
							esc_html__('Descending', 'theme') => 'DESC',
						),
						'std'        => 'ASC'
					),
					array(
						'group'      => 'Data',
						'param_name' => 'category_filter',
						'type'       => 'single_checkbox',
						'label'      => esc_html__('Show filter by categories', 'theme'),
					),
					
					// Caption
					
					array(
						'group'      => 'Caption',
						'param_name' => 'caption_preset',
						'type'       => 'dropdown',
						'heading'    => esc_html__('Style', 'theme'),
						'value'      => array(
							esc_html__('Fade', 'theme')             => 'fade',
							esc_html__('Fade 2', 'theme')           => 'fade-2',
							esc_html__('Fade 3', 'theme')           => 'fade-3',
							esc_html__('Slide from right', 'theme') => 'slide-from-right',
							esc_html__('Static', 'theme')           => 'static',
						),
						'dependency' => array(
							'element' => 'layout',
							'value'   => array('large_first', 'masonry', 'equal', 'auto', 'devices', 'devices_masonry')
						),
						'std'        => 'fade'
					),
					array(
						'group'      => 'Caption',
						'param_name' => 'caption_margin',
						'type'       => 'number',
						'heading'    => esc_html__('Caption margin', 'theme'),
					),
					array(
						'group'      => 'Caption',
						'param_name' => 'caption_background_color',
						'type'       => 'colorpicker',
						'heading'    => esc_html__('Caption background color', 'theme'),
					),
					array(
						'group'      => 'Caption',
						'param_name' => 'caption_background_image',
						'type'       => 'attach_image',
						'heading'    => esc_html__('Caption background image', 'theme'),
					),
					array(
						'group'      => 'Caption',
						'param_name' => 'title_show',
						'type'       => 'single_checkbox',
						'label'      => esc_html__('Yes, please', 'theme'),
						'heading'    => esc_html__('Show title', 'theme'),
						'value'      => true,
					),
					array(
						'group'       => 'Caption',
						'param_name'  => 'title_size',
						'type'        => 'inputs',
						'heading'     => esc_html__('Title font size', 'theme'),
						'placeholder' => array(
							esc_html__('Desktop', 'theme'),
							esc_html__('Large desktop', 'theme')
						)
					),
					array(
						'group'            => 'Caption',
						'param_name'       => 'title_color',
						'type'             => 'colorpicker',
						'heading'          => esc_html__('Title color', 'theme'),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'group'            => 'Caption',
						'param_name'       => 'title_hover_color',
						'type'             => 'colorpicker',
						'heading'          => esc_html__('Title hover color', 'theme'),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'group'      => 'Caption',
						'param_name' => 'additional_info',
						'type'       => 'dropdown',
						'heading'    => esc_html__('Additional info', 'theme'),
						'value'      => array(
							esc_html__('None', 'theme')        => 'none',
							esc_html__('Categories', 'theme')  => 'categories',
							esc_html__('Description', 'theme') => 'description',
						),
						'std'        => 'none'
					),
					array(
						'group'       => 'Caption',
						'param_name'  => 'additional_size',
						'type'        => 'inputs',
						'heading'     => esc_html__('Additional font size', 'theme'),
						'placeholder' => array(
							esc_html__('Desktop', 'theme'),
							esc_html__('Large desktop', 'theme')
						),
					),
					array(
						'group'            => 'Caption',
						'param_name'       => 'additional_color',
						'type'             => 'colorpicker',
						'heading'          => esc_html__('Additional color', 'theme'),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'group'            => 'Caption',
						'param_name'       => 'additional_hover_color',
						'type'             => 'colorpicker',
						'heading'          => esc_html__('Additional hover color', 'theme'),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'group'      => 'Caption',
						'param_name' => 'smallest_device',
						'type'       => 'dropdown',
						'heading'    => esc_html__('Smallest device', 'theme'),
						'value'      => array(
							esc_html__('Watch', 'theme')   => 'watch',
							esc_html__('Phone', 'theme')   => 'phone',
							esc_html__('Tablet', 'theme')  => 'tablet',
							esc_html__('Desktop', 'theme') => 'desktop',
						),
						'std'        => 'watch',
						'dependency' => array(
							'element' => 'layout',
							'value'   => array('devices', 'devices_masonry')
						),
					),
					array(
						'group'            => 'Caption',
						'param_name'       => 'border_on',
						'type'             => 'dropdown',
						'heading'          => esc_html__('Use colorful border', 'theme'),
						'value'            => array(
							esc_html__('No', 'theme')                   => 'no',
							esc_html__('Define one color for all items', 'theme')                  => 'yes',
							esc_html__('Use item background color', 'theme') => 'use',
						),
						'std'              => 'no',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'group'            => 'Caption',
						'param_name'       => 'border',
						'type'             => 'number',
						'heading'          => esc_html__('Width, px', 'theme'),
						'min'              => 0,
						'placeholder'      => 0,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'border_on',
							'value'   => array('yes', 'use'),
						),
					),
					array(
						'group'      => 'Caption',
						'param_name' => 'border_color',
						'type'       => 'colorpicker',
						'heading'    => esc_html__('Border color', 'theme'),
						'dependency' => array(
							'element' => 'border_on',
							'value'   => 'yes',
						),
					),
					array(
						'group'      => 'Caption',
						'param_name' => 'hide_link_title',
						'type'       => 'single_checkbox',
						'label'      => esc_html__('Yes, please', 'theme'),
						'heading'    => esc_html__('Hide tooltip', 'theme'),
						'value'      => false,
					),
					
					// Mobile
					
					array(
						'group'       => 'Mobile',
						'param_name'  => 'mobile_gutter',
						'type'        => 'inputs',
						'heading'     => esc_html__('Space between items', 'theme'),
						'placeholder' => array(
							esc_html__('Phone', 'theme'),
							esc_html__('Tablet', 'theme'),
						)
					),
					array(
						'group'      => 'Mobile',
						'param_name' => 'mobile_caption',
						'type'       => 'dropdown',
						'heading'    => esc_html__('Caption visibility', 'theme'),
						'value'      => array(
							esc_html__('Visible on tablet and phones', 'theme') => '',
							esc_html__('Hidden on phones', 'theme')             => 'hidden-xs',
							esc_html__('Hidden on tablets', 'theme')            => 'hidden-sm',
							esc_html__('Hidden on tablets and phones', 'theme') => 'hidden-xs hidden-sm',
						),
						'std'        => '',
					),
					array(
						'group'       => 'Mobile',
						'param_name'  => 'mobile_caption_opacity',
						'type'        => 'number',
						'heading'     => esc_html__('Caption background opacity', 'theme'),
						'step'        => 0.1,
						'min'         => 0,
						'max'         => 1,
						'placeholder' => '1',
					),
					array(
						'group'      => 'Mobile',
						'param_name' => 'mobile_title',
						'type'       => 'dropdown',
						'heading'    => esc_html__('Title visibility', 'theme'),
						'value'      => array(
							esc_html__('Visible on tablet and phones', 'theme') => '',
							esc_html__('Hidden on phones', 'theme')             => 'hidden-xs',
							esc_html__('Hidden on tablets', 'theme')            => 'hidden-sm',
							esc_html__('Hidden on tablets and phones', 'theme') => 'hidden-xs hidden-sm',
						),
						'std'        => '',
					),
					array(
						'group'       => 'Mobile',
						'param_name'  => 'mobile_title_size',
						'type'        => 'inputs',
						'heading'     => esc_html__('Title font size', 'theme'),
						'placeholder' => array(
							esc_html__('Phone', 'theme'),
							esc_html__('Tablet', 'theme'),
						)
					),
					array(
						'group'            => 'Mobile',
						'param_name'       => 'mobile_title_color',
						'type'             => 'colorpicker',
						'heading'          => esc_html__('Title color', 'theme'),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'group'            => 'Mobile',
						'param_name'       => 'mobile_title_hover_color',
						'type'             => 'colorpicker',
						'heading'          => esc_html__('Title hover color', 'theme'),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'group'      => 'Mobile',
						'param_name' => 'mobile_additional',
						'type'       => 'dropdown',
						'heading'    => esc_html__('Additional info visibility', 'theme'),
						'value'      => array(
							esc_html__('Visible on tablet and phones', 'theme') => '',
							esc_html__('Hidden on phones', 'theme')             => 'hidden-xs',
							esc_html__('Hidden on tablets', 'theme')            => 'hidden-sm',
							esc_html__('Hidden on tablets and phones', 'theme') => 'hidden-xs hidden-sm',
						),
						'std'        => '',
					),
					array(
						'group'       => 'Mobile',
						'param_name'  => 'mobile_additional_size',
						'type'        => 'inputs',
						'heading'     => esc_html__('Additional font size', 'theme'),
						'placeholder' => array(
							esc_html__('Phone', 'theme'),
							esc_html__('Tablet', 'theme'),
						)
					),
					array(
						'group'            => 'Mobile',
						'param_name'       => 'mobile_additional_color',
						'type'             => 'colorpicker',
						'heading'          => esc_html__('Additional color', 'theme'),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'group'            => 'Mobile',
						'param_name'       => 'mobile_additional_hover_color',
						'type'             => 'colorpicker',
						'heading'          => esc_html__('Additional hover color', 'theme'),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
				)
			)
		);
		
		foreach ($this->parameters['params'] as &$param) {
			if ($param['param_name'] === 'layout') {
				$param['value'][esc_html__('Masonry text on top', 'theme')]    = 'top';
				$param['value'][esc_html__('Masonry text on bottom', 'theme')] = 'bottom';
			}
		}
	}
	
	public function get_styles() {
		$styles = parent::get_styles();
		
		$shortcodes = $this->get_shortcodes();
		
		foreach ($shortcodes as $shortcode) {
			$css = Css::init();
			
			$id = '.' . $shortcode['hash'];
			
			$settings = $shortcode['settings'];
			
			// Composite settings
			
			if (!empty($settings['title_size'])) {
				$title_size = explode('|||', $settings['title_size']);
			}
			
			if (!empty($settings['additional_size'])) {
				$additional_size = explode('|||', $settings['additional_size']);
			}
			
			if (!empty($settings['mobile_gutter'])) {
				$mobile_gutter = explode('|||', $settings['mobile_gutter']);
			}
			
			if (!empty($settings['mobile_title_size'])) {
				$mobile_title_size = explode('|||', $settings['mobile_title_size']);
			}
			
			if (!empty($settings['mobile_additional_size'])) {
				$mobile_additional_size = explode('|||', $settings['mobile_additional_size']);
			}
			
			// Caption
			
			if (isset($settings['caption_margin']) && $settings['caption_margin'] != '') {
				$value = $settings['caption_margin'] . 'px';
				
				$css->set("$id .grid-content .caption-content", array(
					'top'    => $value,
					'bottom' => $value,
					'left'   => $value,
					'right'  => $value,
				));
			}
			
			if (!empty($settings['caption_background_color'])) {
				$css->set(array(
					"$id .grid-content .caption .background",
					"$id .grid-content .caption-static .background"
				), 'background-color', $settings['caption_background_color']);
			}
			
			if (!empty($settings['caption_background_image'])) {
				$value = ome_get_image($settings['caption_background_image']);
				$value = $value['full']['url'];
				$value = "url('{$value}')";
				
				$css->set("$id .grid-content .caption .background", 'background-image', $value);
			}
			
			if (isset($title_size[0])) {
				$value = self::get_font_size($title_size[0]);
				
				$css->set("$id .grid-content .title", 'font-size', $value);
			}
			
			if (!empty($settings['title_color'])) {
				$css->set("$id .grid-content .title", 'color', $settings['title_color']);
			}
			if (!empty($settings['title_hover_color'])) {
				$css->set(array(
					"$id .grid-content:hover .title",
					"$id .grid-content:focus .title"
				), 'color', $settings['title_hover_color']);
			}
			
			if (isset($additional_size[0])) {
				$value = self::get_font_size($additional_size[0]);
				
				$css->set("$id .grid-content .categories", 'font-size', $value)
				    ->set("$id .grid-content .description", 'font-size', $value);
			}
			
			if (!empty($settings['additional_color'])) {
				$css->set("$id .grid-content .categories", 'color', $settings['additional_color'])
				    ->set("$id .grid-content .description", 'color', $settings['additional_color']);
			}
			if (!empty($settings['additional_hover_color'])) {
				$css->set(array(
					"$id .grid-content:hover .categories",
					"$id .grid-content:focus .categories"
				), 'color', $settings['additional_hover_color'])
				    ->set(array(
					    "$id .grid-content:hover .description",
					    "$id .grid-content:focus .description"
				    ), 'color', $settings['additional_hover_color']);
			}
			
			if (in_array($settings['layout'], array('devices', 'devices_masonry'))
			    && !empty($settings['smallest_device'])
			    && $settings['smallest_device'] !== 'watch'
			) {
				$format    = "$id .device.%1\$s .title,$id .device.%1\$s .categories,$id .device.%1\$s .description";
				$selectors = array(sprintf($format, 'watch'));
				
				if ($settings['smallest_device'] !== 'phone') {
					$selectors[] = sprintf($format, 'phone');
					
					if ($settings['smallest_device'] !== 'tablet') {
						$selectors[] = sprintf($format, 'tablet');
					}
				}
				
				$css->set(join(',', $selectors), 'display', 'none');
			}
			
			$styles .= $css;
			
			// Large desktop
			
			$css->clear();
			
			if (isset($title_size[1])) {
				$value = self::get_font_size($title_size[1]);
				
				$css->set("$id .grid-content .title", 'font-size', $value);
			}
			
			if (isset($additional_size[1])) {
				$value = self::get_font_size($additional_size[1]);
				
				$css->set("$id .grid-content .categories", 'font-size', $value)
				    ->set("$id .grid-content .description", 'font-size', $value);
			}
			
			$large_css = $css->to_string();
			
			if (!empty($large_css)) {
				$styles .= "@media(min-width:1320px){{$large_css}}";
			}
			
			// Phone
			
			$css->clear();
			
			if (isset($mobile_gutter) && is_array($mobile_gutter) && is_string($mobile_gutter[0]) && strlen($mobile_gutter[0])) {
				$value = round((int)($mobile_gutter[0]) / 2) . 'px';
				
				$css
					->set("$id .grid", 'margin', '-' . $value)
					->set("$id .grid .grid-cell", 'margin', $value)
					->set("$id .grid .grid-cell-full", array(
						'margin' => '0',
						'top'    => $value,
						'bottom' => $value,
						'left'   => $value,
						'right'  => $value,
					));
			}
			
			if (!empty($settings['mobile_caption_opacity'])) {
				$css->set("$id .grid-content .caption .background", 'opacity', $settings['mobile_caption_opacity']);
			}
			
			if (isset($mobile_title_size[0])) {
				$value = self::get_font_size($mobile_title_size[0]);
				
				$css->set("$id .grid-content .title", 'font-size', $value);
			}
			
			if (!empty($settings['mobile_title_color'])) {
				$css->set("$id .grid-content .title", 'color', $settings['mobile_title_color']);
			}
			if (!empty($settings['mobile_title_hover_color'])) {
				$css->set(array(
					"$id .grid-content:hover .title",
					"$id .grid-content:focus .title"
				), 'color', $settings['mobile_title_hover_color']);
			}
			
			if (isset($mobile_additional_size[0])) {
				$value = self::get_font_size($mobile_additional_size[0]);
				
				$css->set("$id .grid-content .categories", 'font-size', $value)
				    ->set("$id .grid-content .description", 'font-size', $value);
			}
			
			if (!empty($settings['mobile_additional_color'])) {
				$css->set("$id .grid-content .categories", 'color', $settings['mobile_additional_color'])
				    ->set("$id .grid-content .description", 'color', $settings['mobile_additional_color']);
			}
			if (!empty($settings['mobile_additional_hover_color'])) {
				$css->set(array(
					"$id .grid-content:hover .categories",
					"$id .grid-content:focus .categories"
				), 'color', $settings['mobile_additional_hover_color'])
				    ->set(array(
					    "$id .grid-content:hover .description",
					    "$id .grid-content:focus .description"
				    ), 'color', $settings['mobile_additional_hover_color']);
			}
			
			$mobile_css = $css->to_string();
			
			if (!empty($mobile_css)) {
				$styles .= "@media(max-width:767px){{$mobile_css}}";
			}
			
			// Tablet
			
			$css->clear();
			
			if (isset($mobile_gutter) && is_array($mobile_gutter) && is_string($mobile_gutter[1]) && strlen($mobile_gutter[1])) {
				$value = round((int)($mobile_gutter[1]) / 2) . 'px';
				
				$css
					->set("$id .grid", 'margin', '-' . $value)
					->set("$id .grid .grid-cell", 'margin', $value)
					->set("$id .grid .grid-cell-full", array(
						'margin' => '0',
						'top'    => $value,
						'bottom' => $value,
						'left'   => $value,
						'right'  => $value,
					));
			}
			
			if (isset($settings['mobile_caption_opacity']) && is_numeric($settings['mobile_caption_opacity'])) {
				$css->set("$id .grid-content .caption .background", 'opacity', $settings['mobile_caption_opacity']);
			}
			
			if (isset($mobile_title_size[1])) {
				$value = self::get_font_size($mobile_title_size[1]);
				
				$css->set("$id .grid-content .title", 'font-size', $value);
			}
			
			if (!empty($settings['mobile_title_color'])) {
				$css->set("$id .grid-content .title", 'color', $settings['mobile_title_color']);
			}
			
			if (isset($mobile_additional_size[1])) {
				$value = self::get_font_size($mobile_additional_size[1]);
				
				$css->set("$id .grid-content .categories", 'font-size', $value)
				    ->set("$id .grid-content .description", 'font-size', $value);
			}
			
			if (!empty($settings['mobile_additional_color'])) {
				$css->set("$id .grid-content .categories", 'color', $settings['mobile_additional_color'])
				    ->set("$id .grid-content .description", 'color', $settings['mobile_additional_color']);
			}
			
			$mobile_css = $css->to_string();
			
			if (!empty($mobile_css)) {
				$styles .= "@media (min-width:768px) and (max-width:991px){{$mobile_css}}";
			}
		}
		
		return $styles;
	}
	
	public function get_tax_query() {
		if ($this->settings['filter_type'] !== 'taxonomies') {
			return '';
		}
		
		if (empty($this->settings['filter_categories']) && empty($this->settings['filter_tags'])) {
			return '';
		}
		
		$tax_query = array('relation' => 'AND');
		
		if (!empty($this->settings['filter_categories'])) {
			$tax_query[] = array(
				'taxonomy' => 'project_category',
				'field'    => 'term_id',
				'terms'    => self::ids_string_as_array($this->settings['filter_categories']),
			);
		}
		
		if (!empty($this->settings['filter_tags'])) {
			$tax_query[] = array(
				'taxonomy' => 'project_tag',
				'field'    => 'term_id',
				'terms'    => self::ids_string_as_array($this->settings['filter_tags']),
			);
		}
		
		return $tax_query;
	}
	
	public function get_projects() {
		return get_posts(array(
			'posts_per_page'   => !empty($this->settings['number']) ? $this->settings['number'] : 9,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => $this->settings['order_by'],
			'order'            => $this->settings['order'],
			'post_type'        => 'project',
			'include'          => $this->settings['filter_type'] === 'projects' ? $this->settings['filter_projects'] : '',
			'tax_query'        => $this->get_tax_query(),
			'suppress_filters' => false
		));
	}
	
	public function get_filter_categories($projects) {
		$args = array(
			'hierarchical' => false,
		);
		
		if ($this->settings['filter_type'] === 'taxonomies' && !empty($this->settings['filter_categories'])) {
			$args['include'] = self::ids_string_as_array($this->settings['filter_categories']);
		} else {
			$ids = array();
			
			foreach ($projects as $project) {
				$ids = array_merge($ids, wp_get_post_terms($project->ID, 'project_category', array("fields" => "ids")));
			}
			
			$args['include'] = array_unique($ids);
		}
		
		return get_terms('project_category', $args);
	}
	
	public function get_animation_classes($hover_name, $transform_name) {
		if (empty($this->settings['on_hover'])) {
			return '';
		}
		
		$classes = array();
		
		if ($this->settings[$hover_name] == 'true') {
			$classes[] = 'opacity';
		}
		
		$classes[] = $this->settings[$transform_name];
		
		return trim(join(' ', $classes));
	}
	
	public function init_portfolio_item($index, $project) {
		$image_id = get_post_thumbnail_id($project->ID);
		
		$device       = null;
		$device_color = null;
		
		if ($this->is_devices) {
			$device       = Options::get('device_type', $project->ID);
			$device_color = Options::get('device_color', $project->ID);
		}
		
		$this->init_base_gallery_item($index, $image_id, $device, $device_color);
		
		$categories           = get_the_terms($project, 'project_category');
		$this->item['filter'] = empty($categories) ? '' : from($categories)->toString(',', '$v->slug');
		
		$this->item['url'] = Options::specified('project_custom_link', $project->ID)
			? esc_url(Options::get('project_custom_link', $project->ID))
			: get_permalink($project->ID);
		
		return $this->item;
	}
	
	public function render_image($project) {
		ome_responsive_thumbnail($project->ID, $this->get_item_image_sizes(), $this->get_item_image_attributes());
	}
	
	public function render_caption($project) {
		if (in_array($this->settings['layout'], array('bottom', 'top'))) {
			$this->settings['caption_preset'] = 'static';
		}
		$caption_preset = empty($this->settings['caption_preset']) ? 'fade' : $this->settings['caption_preset'];
		
		$caption_class = in_array($this->settings['layout'], array('bottom', 'top')) ? 'caption-static' : 'caption';
		$caption_class .= ' ' . $this->settings['mobile_caption'];
		
		$caption_title_class      = ' ' . $this->settings['mobile_title'];
		$caption_additional_class = ' ' . $this->settings['mobile_additional'];
		
		$preset_title       = esc_html($project->post_title);
		$preset_categories  = esc_html(ome_get_categories_names($project));
		$preset_description = esc_html($project->post_excerpt);
		
		require __DIR__ . '/captions/' . $caption_preset . '.php';
	}
	
	protected function get_cols($col_counts = null) {
		return parent::get_cols(apply_filters('om_portfolio_col_counts', $col_counts, $this->settings));
	}
	
	public static function get_project_background_styles($project, $settings) {
		$background_style = array();
		
		$project_color = Options::get('project_color', $project->ID);
		$project_image = Options::get_image('project_secondary_image', 'full', $project->ID);
		
		if (!empty($project_color)) {
			$background_style[] = 'background-color:' . $project_color;
		}
		
		if (!empty($project_image) && !in_array($settings['layout'], array('bottom', 'top'))) {
			$background_style[] .= 'background-image:url(' . esc_url($project_image) . ')';
		}
		
		return implode(';', $background_style);
	}
	
	public static function get_project_title_styles($project) {
		
		$project_color = Options::get('project_title_color', $project->ID);
		
		if (!empty($project_color)) {
			return 'color:' . $project_color . ';';
		}
		
		return '';
	}
	
	public static function get_project_additional_styles($project) {
		
		$project_color = Options::get('project_additional_color', $project->ID);
		
		if (!empty($project_color)) {
			return 'color:' . $project_color . ';';
		}
		
		return '';
	}
	
	public static function get_font_size($value) {
		$value = trim(esc_html($value));
		
		if (is_numeric($value)) {
			$value .= 'px';
		}
		
		return $value;
	}
	
	public static function ids_string_as_array($string) {
		if (!is_string($string) || !strlen($string)) {
			return array();
		}
		
		$values = explode(',', $string);
		
		foreach ($values as &$value) {
			$value = (int)(trim($value));
		}
		
		return $values;
	}
}