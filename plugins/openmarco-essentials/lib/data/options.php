<?php

namespace Essentials\Data;

use Essentials\Core\Config;
use Essentials\Core\Context;
use TitanFramework;

class Options {
	
	/**
	 * @var Context
	 */
	private $context;
	
	/**
	 * @var array
	 */
	private $settings;
	
	/**
	 * @var TitanFramework
	 */
	public static $titan;
	
	/**
	 * @param Context        $context
	 * @param array          $settings
	 * @param TitanFramework $titan
	 */
	public function __construct(Context $context, array $settings, TitanFramework $titan) {
		$this->context  = $context;
		$this->settings = $settings;
		
		if (empty($this->settings)) {
			return;
		}
		
		$panel = $titan->createAdminPanel(array(
			'name' => __('Openmarco Options', $this->context->theme->TextDomain),
		));
		
		foreach ($this->settings as $tab_name => $tab_options) {
			$tab = $panel->createTab(array(
				'name' => $tab_name,
			));
			
			foreach ($tab_options as $tab_option) {
				$tab->createOption($tab_option);
			}
			
			$tab->createOption(array(
				'type' => 'save'
			));
		}
	}
	
	/**
	 * @param $context Context
	 * @param $config  Config
	 * @param $titan   TitanFramework
	 */
	public static function init(Context $context, Config $config, TitanFramework $titan) {
		if (!self::$titan) {
			self::$titan = $titan;
		}
		
		if (TitanFramework::$initializing) {
			return;
		}
		
		if (is_array($config->options)) {
			$settings = apply_filters('om_options_settings', $config->options);
			
			new Options($context, $settings, $titan);
		}
		
		add_filter('admin_footer_text', '\Essentials\Data\Options::_footer_', 1);
	}
	
	/**
	 * @param string $content
	 *
	 * @return string
	 */
	public static function _footer_($content) {
		global $wp_filter;
		
		if (isset($wp_filter['admin_footer_text'][10])) {
			$filters = $wp_filter['admin_footer_text'][10];
			
			foreach ($filters as $filter) {
				if (isset($filter['function'][1]) && $filter['function'][1] === 'addTitanCreditText') {
					remove_filter('admin_footer_text', $filter['function'], 10);
					
					return "<em>Created by <a href=\"http://openmarco.com\">OpenMarco</a></em>";
				}
			}
		}
		
		return $content;
	}
	
	/**
	 * Retrieve option value.
	 *
	 * @param string   $name Option name.
	 * @param int|null $id   Optional, meta option post id.
	 *
	 * @return bool|mixed|null|string
	 */
	public static function get($name, $id = null) {
		$value = self::$titan->getOption($name, $id);
		
		if (($value === 'default' || $value === '') && preg_match('/_current$/', $name)) {
			$value = self::$titan->getOption(substr($name, 0, -8), $id);
		}
		
		if ($value === 'false') {
			$value = false;
		}
		
		if ($value === 'true') {
			$value = true;
		}
		
		return $value;
	}
	
	/**
	 * Retrieve an image url for image type options.
	 *
	 * @param string   $name Option name.
	 * @param string   $size Optional, default is 'full'. Size of image, either array or string.
	 * @param int|null $id   Optional, meta option post id.
	 *
	 * @return bool|array Returns url or false, if no image is available
	 */
	public static function get_image($name, $size = 'full', $id = null) {
		$attachment = wp_get_attachment_image_src(self::get($name, $id), $size);
		
		return $attachment ? $attachment[0] : false;
	}
	
	/**
	 * Update the value of an option.
	 *
	 * @param string   $name  Option name.
	 * @param mixed    $value New option value
	 * @param int|null $id    Optional, meta option post id.
	 *
	 * @return bool|int|mixed|string
	 */
	public static function set($name, $value, $id = null) {
		return self::$titan->setOption($name, $value, $id);
	}
	
	/**
	 * Print option value.
	 *
	 * @param string   $name Option name.
	 * @param int|null $id   Optional, meta option post id.
	 */
	public static function write($name, $id = null) {
		echo self::get($name, $id);
	}
	
	/**
	 * Checks if option specified
	 *
	 * @param string   $name Option name.
	 * @param int|null $id   Optional, meta option post id.
	 *
	 * @return bool
	 */
	public static function specified($name, $id = null) {
		$option = self::get($name, $id);
		
		return !empty($option);
	}
}