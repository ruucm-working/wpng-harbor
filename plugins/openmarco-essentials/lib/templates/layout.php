<?php

namespace Essentials\Templates;

use Essentials\Core\Config;
use Essentials\Core\Context;

class Layout {
	
	/**
	 * @var Layout Current layout object
	 */
	public static $current;
	
	/**
	 * @var array[] Layout settings
	 */
	public static $settings;
	
	/**
	 * @var Context
	 */
	public static $context;
	
	/**
	 * @var array Dynamic template data
	 */
	public static $data = array();
	
	/**
	 * @var string
	 */
	private $body;
	
	/**
	 * @var string
	 */
	private $layout;
	
	/**
	 * @var array
	 */
	private $sections;
	
	/**
	 * @var bool Any mobile device
	 */
	public $is_mobile;
	
	/**
	 * @var bool Any phone device
	 */
	public $is_phone;
	
	/**
	 * @var bool Any tablet device
	 */
	public $is_tablet;
	
	/**
	 * @param string $body
	 */
	public function __construct($body) {
		$detect = new \Mobile_Detect();
		
		$this->is_mobile = $detect->isMobile();
		$this->is_tablet = $this->is_mobile && $detect->isTablet();
		$this->is_phone  = $this->is_mobile && !$this->is_tablet;
		
		// get template name
		$template = basename($body, '.php');
		
		$this->body = $template;
		
		// search settings for current template
		foreach (self::$settings as $setting) {
			if (!array_key_exists('templates', $setting)) {
				$this->layout   = $setting['layout'];
				$this->sections = $setting['sections'];
			} else if (in_array($template, $setting['templates'], true)) {
				if (array_key_exists('layout', $setting)) {
					$this->layout = $setting['layout'];
				}
				
				$this->sections = array_merge($this->sections, $setting['sections']);
			}
		}
	}
	
	private function locate($file) {
		if ($this->is_mobile) {
			if ($this->is_phone) {
				$template = locate_template($file . '.phone.php');
			} else if ($this->is_tablet) {
				$template = locate_template($file . '.tablet.php');
			}
			
			if (empty($template)) {
				$template = locate_template($file . '.mobile.php');
			}
		}
		
		if (empty($template)) {
			$template = locate_template($file . '.php');
		}
		
		return $template;
	}
	
	/**
	 * Load template file
	 *
	 * @param string $file Template file path
	 */
	private function load($file) {
		$template = $this->locate($file);
		
		load_template($template, true);
	}
	
	/**
	 * Initialize layout system
	 *
	 * @param Context $context
	 * @param Config  $config
	 */
	public static function init(Context $context, Config $config) {
		self::$settings =  $config->layouts;
		self::$context  = $context;
		
		add_filter('template_include', array('Essentials\Templates\Layout', 'wrap'), 99);
	}
	
	public static function wrap($body) {
		$layout = new Layout($body);
		
		self::$current = $layout;
		
		return $layout->locate($layout->layout);
	}
	
	public static function render($name = 'body') {
		$layout = self::$current;
		
		if ($name === 'body') {
			$layout->load($layout->body);
		} else {
			if (isset($layout->sections[$name])) {
				$layout->load($layout->sections[$name]);
			}
		}
	}
	
	public static function get_template_name() {
		return self::$current->body;
	}
}