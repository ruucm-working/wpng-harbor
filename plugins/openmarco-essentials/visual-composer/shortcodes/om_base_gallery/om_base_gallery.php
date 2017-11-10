<?php

use Essentials\Data\Options;
use Essentials\Html\Css;
use Essentials\Html\Writer;

class OM_Base_Gallery extends \Essentials\Html\Base_Shortcode {
	
	protected $common_params;
	
	private $cols;
	
	private $rows;
	
	protected $is_devices;
	
	protected $item;
	
	private $item_image_attributes;
	
	private static $sizes = array('-xs-', '-sm-', '-md-', '-lg-');
	
	private static $cols_defaults = array(
		'1'  => array(12, 12, 12, 12),
		'2'  => array(12, 6, 6, 6),
		'3'  => array(12, 6, 4, 4),
		'4'  => array(12, 4, 3, 3),
		'6'  => array(12, 3, 2, 2),
		'12' => array(6, 2, 1, 1),
	);
	
	private static $aspect_defaults = array(
		'low'        => array(
			'1'  => 1,
			'2'  => 1,
			'3'  => 1,
			'4'  => 2,
			'6'  => 3,
			'12' => 6,
		),
		'horizontal' => array(
			'1'  => 1,
			'2'  => 1,
			'3'  => 2,
			'4'  => 3,
			'6'  => 4,
			'12' => 9,
		),
		'square'     => array(
			'1'  => 1,
			'2'  => 2,
			'3'  => 3,
			'4'  => 4,
			'6'  => 6,
			'12' => 12,
		),
		'vertical'   => array(
			'1'  => 1,
			'2'  => 3,
			'3'  => 4,
			'4'  => 5,
			'6'  => 8,
			'12' => 15,
		),
		'high'       => array(
			'1'  => 2,
			'2'  => 4,
			'3'  => 5,
			'4'  => 7,
			'6'  => 10,
			'12' => 18,
		),
	);
	
	public function construct() {
		$this->common_params = array(
			// General
			
			array(
				'group'      => 'General',
				'param_name' => 'id',
				'type'       => 'textfield',
				'heading'    => esc_html__('ID', 'theme'),
			),
			
			// Layout
			
			array(
				'group'      => 'Layout',
				'param_name' => 'cols',
				'type'       => 'dropdown',
				'heading'    => esc_html__('Columns count', 'theme'),
				'value'      => array(
					'1'  => '1',
					'2'  => '2',
					'3'  => '3',
					'4'  => '4',
					'6'  => '6',
					'12' => '12',
				),
				'std'        => '4',
			),
			array(
				'group'      => 'Layout',
				'param_name' => 'layout',
				'type'       => 'dropdown',
				'heading'    => esc_html__('Layout', 'theme'),
				'value'      => array(
					esc_html__('Large first', 'theme')      => 'large_first',
					esc_html__('Masonry', 'theme')          => 'masonry',
					esc_html__('Equal', 'theme')            => 'equal',
					esc_html__('Auto adjust size', 'theme') => 'auto',
					esc_html__('Devices', 'theme')          => 'devices',
					esc_html__('Devices masonry', 'theme')  => 'devices_masonry',
				),
				'std'        => 'large_first',
			),
			array(
				'group'      => 'Layout',
				'param_name' => 'base_cell',
				'type'       => 'dropdown',
				'heading'    => esc_html__('Base cell aspect', 'theme'),
				'value'      => array(
					esc_html__('Horizontal low', 'theme')    => 'low',
					esc_html__('Horizontal medium', 'theme') => 'horizontal',
					esc_html__('Square', 'theme')            => 'square',
					esc_html__('Vertical medium', 'theme')   => 'vertical',
					esc_html__('Vertical high', 'theme')     => 'high',
				),
				'std'        => 'square',
				'dependency' => array(
					'element' => 'layout',
					'value'   => array('large_first', 'equal', 'auto')
				)
			),
			array(
				'group'       => 'Layout',
				'param_name'  => 'gutter',
				'type'        => 'number',
				'heading'     => esc_html__('Space between items', 'theme'),
				'min'         => 0,
				'max'         => 100,
				'step'        => 2,
				'placeholder' => 30,
			),
			array(
				'group'      => 'Layout',
				'param_name' => 'background_color',
				'type'       => 'colorpicker',
				'heading'    => esc_html__('Item background color', 'theme'),
			),
			array(
				'group'      => 'Layout',
				'param_name' => 'scroll_effect',
				'type'       => 'dropdown',
				'heading'    => esc_html__('Scroll effects', 'theme'),
				'value'      => array(
					esc_html__('None', 'theme')                => 'none',
					esc_html__('Fast Parallax', 'theme')       => 'type1',
					esc_html__('Slow Parallax', 'theme')       => 'type6',
					esc_html__('Super Slow Parallax', 'theme') => 'type5',
					esc_html__('Type 2', 'theme')              => 'type2',
					esc_html__('Type 3', 'theme')              => 'type3',
					esc_html__('Type 4', 'theme')              => 'type4',
				),
				'std'        => 'none',
			),
		);
		
		parent::construct();
	}
	
	public function init() {
	}
	
	public function get_styles() {
		// cols layout base_cell gutter gutter background_color scroll_effect
		
		$styles = '';
		
		$shortcodes = $this->get_shortcodes();
		
		foreach ($shortcodes as $shortcode) {
			$css = Css::init();
			
			$id = '.' . $shortcode['hash'];
			
			$settings = $shortcode['settings'];
			
			// Layout
			
			if (isset($settings['gutter']) && $settings['gutter'] != '') {
				$value = round((int)($settings['gutter']) / 2) . 'px';
				
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
			
			if (!empty($settings['background_color'])) {
				$css->set("$id .grid-content", 'background-color', $settings['background_color']);
			}
			
			if (isset($settings['layout']) && $settings['layout'] === 'devices_masonry') {
				$gutter = empty($settings['gutter']) ? 30 : (int)($settings['gutter']);
				$half   = $gutter / 2;
				
				$css->set("$id .grid .device.tablet", 'margin', "0 {$half}px")
				    ->set("$id .grid .device.tablet.landscape", 'margin', "0 {$gutter}px");
			}
			
			$styles .= $css;
		}
		
		return $styles;
	}
	
	public function init_gallery() {
		// Rates
		$this->cols = $this->get_cols();
		$this->rows = array();
		
		if (in_array($this->settings['layout'], array('large_first', 'equal', 'auto'))) {
			$aspect = self::$aspect_defaults[$this->settings['base_cell']];
			
			foreach ($this->cols as $col) {
				$this->rows[] = $aspect[$col];
			}
		}
		
		if ($this->settings['layout'] === 'devices') {
			$this->rows = $this->cols;
		}
		
		// Devices
		
		$this->is_devices = in_array($this->settings['layout'], array('devices', 'devices_masonry'));
	}
	
	protected function init_base_gallery_item($index, $image_id, $device, $device_color) {
		$item_rates = $this->get_item_rates($index, $image_id, $device);
		
		$this->item = array(
			'cols'         => $item_rates['cols'],
			'rows'         => $item_rates['rows'],
			'device'       => $device,
			'device_color' => $device_color,
		);
	}
	
	public function the_sizer_classes() {
		$this->render_classes('col', $this->cols);
	}
	
	public function the_item_classes() {
		$this->render_classes('col', $this->item['cols']);
		$this->render_classes('row', $this->item['rows']);
	}
	
	public function the_item_cell_classes() {
		if (!empty($this->item['rows'])) {
			echo ' grid-cell-full';
		}
	}
	
	public function the_scroll_effect_attributes($index) {
		switch ($this->settings['scroll_effect']) {
			case 'type1':
				$shift = ($index % 2) ? 10 : 40;
				
				$attributes = array(
					'data-scroll-animate' => 'transform:translate3d(0,$1%,0)',
					'data-0_0'            => $shift,
					'data-100_100'        => -$shift,
				);
				break;
			
			case 'type5':
				$shift = ($index % 2) ? 5 : 20;
				
				$attributes = array(
					'data-scroll-animate' => 'transform:translate3d(0,$1%,0)',
					'data-0_0'            => $shift,
					'data-100_100'        => -$shift,
				);
				break;
			
			case 'type6':
				$shift = ($index % 2) ? 7 : 27;
				
				$attributes = array(
					'data-scroll-animate' => 'transform:translate3d(0,$1%,0)',
					'data-0_0'            => $shift,
					'data-100_100'        => -$shift,
				);
				break;
			
			case 'type2':
				$attributes = array(
					'data-scroll-animate' => 'transform:translate3d(0,$1%,0)',
					'data-0_0'            => 30,
					'data-25_25'          => 0,
					'data-75_75'          => 0,
					'data-100_100'        => -30,
				);
				break;
			
			case 'type3':
				$dy      = rand(-10, 10);
				$shift   = rand(0, 40);
				$shift_1 = $shift * 0.8;
				$shift_2 = $shift * 0.6;
				$shift_3 = $shift * 0.3;
				
				$attributes = array(
					'data-scroll-animate' => 'transform:translate3d($2%,$1%,0)',
					'data-0_0'            => ($shift + $dy) . ',' . rand(-20, 20),
					'data-10_10'          => ($shift_1 + $dy) . ',' . rand(-20, 20),
					'data-20_20'          => ($shift_2 + $dy) . ',' . rand(-20, 20),
					'data-30_30'          => ($shift_3 + $dy) . ',' . rand(-20, 20),
					'data-40_40'          => $dy . ',' . rand(-20, 20),
					'data-50_50'          => $dy . ',' . rand(-20, 20),
					'data-60_60'          => $dy . ',' . rand(-20, 20),
					'data-70_70'          => (-$shift_3 + $dy) . ',' . rand(-20, 20),
					'data-80_80'          => (-$shift_2 + $dy) . ',' . rand(-20, 20),
					'data-90_90'          => (-$shift_1 + $dy) . ',' . rand(-20, 20),
					'data-100_100'        => (-$shift + $dy) . ',' . rand(-20, 20),
				);
				break;
			
			case 'type4':
				$shift   = ($index % 2) ? 40 : 0;
				$shift_2 = $shift * 0.6;
				$shift_3 = $shift * 0.3;
				
				$attributes = array(
					'data-scroll-animate' => 'transform:translate3d($2%,$1%,0)',
					'data-0_0'            => $shift . ',' . rand(-150, 150),
					'data-20_20'          => $shift_2 . ',' . rand(-75, 75),
					'data-30_30'          => $shift_3 . ',' . rand(-50, 50),
					'data-40_40'          => '0,0',
					'data-60_60'          => '0,0',
					'data-70_70'          => -$shift_3 . ',' . rand(-50, 50),
					'data-80_80'          => -$shift_2 . ',' . rand(-75, 75),
					'data-100_100'        => -$shift . ',' . rand(-150, 150),
				);
				break;
			
			default:
				$attributes = array();
		}
		
		echo Writer::get_attributes_string($attributes);
	}
	
	/**
	 * @param \WP_Post $project
	 */
	public function the_grid_cell_styles($project) {
		$settings = $this->settings;
		
		if ($settings['border_on'] !== 'no') {
			$projectColor = ($settings['border_on'] === 'use') ? Options::get('project_color', $project->ID) : $settings['border_color'];
			
			echo ' style="border: ' . $settings['border'] . 'px solid ' . $projectColor . ';"';
		}
	}
	
	public function the_device_begin() {
		if ($this->is_devices) {
			$device_style = empty($this->item['device_color']) ? '' : ' style="background-color:' . esc_attr($this->item['device_color']) . '"';
			
			echo '<div class="device ', str_replace('_', ' ', esc_attr($this->item['device'])), '"', $device_style, '><div class="device-content">';
		}
	}
	
	public function the_device_end() {
		if ($this->is_devices) {
			echo '</div></div>';
		}
	}
	
	protected function get_item_image_sizes() {
		return $this->item['cols'][3] > 4 || $this->item['cols'][2] > 4 || $this->item['cols'][1] > 6
			? array('large-width', 'extra-large-width')
			: array('small-width', 'large-width', 'extra-large-width');
	}
	
	protected function get_item_image_attributes() {
		$attributes = array();
		
		if ($this->settings['layout'] === 'masonry') {
			$attributes['class'] = 'img-responsive';
		} else {
			$attributes['class'] = 'img-cover';
		}
		
		return $attributes;
	}
	
	protected function get_cols($col_counts = null) {
		if (is_numeric($col_counts)) {
			$col_counts = array($col_counts);
		}
		
		if (is_array($col_counts)) {
			$cols = array();
			
			for ($index = 0; $index < 4; $index++) {
				if (!isset($col_counts[$index]) || !is_numeric($col_counts[$index]) || $col_counts[$index] === 0) {
					$col_counts[$index] = $index == 0 ? 4 : max(1, min(12, $col_counts[$index - 1]));
				}
				
				$cols[$index] = round(12 / $col_counts[$index]);
			}
		} else {
			$cols = self::$cols_defaults[empty($this->settings['cols']) ? 4 : $this->settings['cols']];
		}
		
		return $cols;
	}
	
	private function get_item_rates($index, $image_id, $device) {
		if ($this->settings['layout'] === 'large_first' && $index == 0) {
			$rates = $this->get_item_double_rates();
		} elseif ($this->settings['layout'] === 'auto') {
			$rates = $this->get_item_auto_rates($image_id);
		} elseif ($this->settings['layout'] === 'devices_masonry') {
			$rates = $this->get_item_device_masonry_rates($device);
		} else {
			$rates = array('cols' => $this->cols, 'rows' => $this->rows);
		}
		
		return $rates;
	}
	
	private function render_classes($type, $rates = array()) {
		foreach ($rates as $index => $rate) {
			echo ' ', sanitize_html_class($type . self::$sizes[$index] . $rate);
		}
	}
	
	private function get_item_double_rates() {
		$cols = $this->cols;
		$rows = $this->rows;
		
		$indexMax = count($cols);
		for ($index = 0; $index < $indexMax; $index++) {
			$double_cols  = min(12, $cols[$index] * 2);
			$double_rows  = min(24, round($rows[$index] * $double_cols / $cols[$index]));
			$cols[$index] = $double_cols;
			$rows[$index] = $double_rows;
		}
		
		return array('cols' => $cols, 'rows' => $rows);
	}
	
	private function get_item_auto_rates($image_id) {
		$cols = $this->cols;
		$rows = $this->rows;
		
		if (!empty($image_id)) {
			$info = wp_get_attachment_image_src($image_id, 'full');
			
			if ($info) {
				$ratio = $info[1] / $info[2];
				
				foreach ($cols as $index => $col) {
					$row = $rows[$index];
					$new = array('col' => $col, 'row' => $row);
					
					$diff = abs($col / $row - $ratio);
					
					for ($current_col = $col * 2; $current_col <= 12; $current_col += $col) {
						$current = abs($current_col / $row - $ratio);
						if ($current < $diff) {
							$diff       = $current;
							$new['col'] = $current_col;
						}
					}
					
					if ($new['col'] == $col) {
						for ($current_row = $row * 2; $current_row <= 24; $current_row += $row) {
							$current = abs($col / $current_row - $ratio);
							if ($current < $diff) {
								$diff       = $current;
								$new['row'] = $current_row;
							}
						}
					}
					
					$cols[$index] = $new['col'];
					$rows[$index] = $new['row'];
				}
			}
		}
		
		return array('cols' => $cols, 'rows' => $rows);
	}
	
	private function get_item_device_masonry_rates($device) {
		$cols = $this->cols;
		$rows = $this->rows;
		
		$multiplier = ($device === 'phone' || $device === 'watch') ? 1 : ($device === 'tablet' ? 2 : 3);
		$max        = ($device === 'phone' || $device === 'watch') ? 4 : ($device === 'tablet' ? 8 : 12);
		
		foreach ($cols as &$col) {
			$col = min($max, $col * $multiplier);
		}
		
		return array('cols' => $cols, 'rows' => $rows);
	}
}