<?php

namespace Essentials\Data;

use Essentials\Core\Config;
use Essentials\Core\Context;
use Essentials\Utils\Condition;

class Sidebar {
	
	private static $collection = array();
	
	private static $defaults = array(
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
	);
	
	/**
	 * @var Context
	 */
	private $context;
	
	/**
	 * @var array
	 */
	public $args;
	
	/**
	 * @var int
	 */
	private $columns;
	
	/**
	 * @param array $options
	 * @param       $context Context
	 */
	private function __construct(array $options, Context $context) {
		$this->context = $context;
		$this->args    = array_merge(self::$defaults, $options);
		
		if (isset($options['columns']) && $options['columns'] > 0) {
			$this->columns = $options['columns'];
			
			if (isset($options['field'])) {
				$this->columns = (int)Options::get($options['field']);
			}
		}
		
		if (isset($this->args['name'])) {
			$this->args['name'] = __($this->args['name'], $context->theme->Text_Domain);
		}
		
		if (isset($this->args['description'])) {
			$this->args['description'] = __($this->args['description'], $context->theme->Text_Domain);
		}
		
		add_action('widgets_init', array($this, '_register_'));
	}
	
	/**
	 * Register widgets sidebars
	 */
	public function _register_() {
		if ($this->columns) {
			for ($number = 1; $number <= $this->columns; $number++) {
				$args = $this->args;
				
				$args['id'] .= "_{$number}";
				$args['name'] .= " {$number}";
				
				register_sidebar($args);
			}
		} else {
			register_sidebar($this->args);
		}
	}
	
	/**
	 * Render sidebar
	 */
	protected function render_sidebar() {
		if ($this->columns) {
			$class = 'col-sm-' . (12 / $this->columns);
			
			echo '<div class="row">';
			
			for ($number = 1; $number <= $this->columns; $number++) {
				echo "<div class=\"{$class}\">";
				dynamic_sidebar("{$this->args['id']}_{$number}");
				echo '</div>';
			}
			
			echo '</div>';
		} else {
			dynamic_sidebar($this->args['id']);
		}
	}
	
	/**
	 * @param $context Context
	 * @param $config  Config
	 */
	public static function init(Context $context, Config $config) {
		if (!array_key_exists('sidebars', $config->common) || !is_array($config->common['sidebars'])) {
			return;
		}
		
		/** @var array $sidebars */
		$sidebars = $config->common['sidebars'];
		
		foreach ($sidebars as $id => $options) {
			if (array_key_exists('dependency', $options) && !Condition::check($options['dependency']['type'], $options['dependency']['value'])) {
				continue;
			}
			
			$options['id'] = $id;
			
			self::$collection[] = new Sidebar($options, $context);
		}
	}
	
	/**
	 * Render sidebar by it's id
	 *
	 * @param string $id sidebar identifier
	 */
	public static function render($id) {
		$sidebar = from(self::$collection)
			->where('$v->args["id"] === "' . $id . '"')
			->singleOrDefault();
		
		if ($sidebar) {
			$sidebar->render_sidebar();
		}
	}
	
	/**
	 * @param string $type
	 *
	 * @return array
	 */
	public static function get_defaults($type) {
		global $wp_widget_factory;
		
		$defaults = self::$defaults;
		
		$widget = $wp_widget_factory->widgets[$type];
		if (is_a($widget, 'WP_Widget')) {
			$defaults['before_widget'] = sprintf($defaults['before_widget'], $widget->id, $widget->widget_options['classname']);
		}
		
		return $defaults;
	}
}