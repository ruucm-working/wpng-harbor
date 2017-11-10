<?php

namespace Essentials\Html;

use Essentials\Utils\Once;

abstract class Base_Shortcode {

	/**
	 * @var string Tag name
	 */
	public $tag;

	/**
	 * @var array Shortcode instance settings
	 */
	public $settings;

	/**
	 * @var array Shortcode parameters
	 */
	public $parameters;

	/**
	 * @var string shortcode instance hash
	 */
	public $hash;

	/**
	 * @var string current edited post id
	 */
	private static $edit_post_id;

	/**
	 * @var array current edited post shortcodes
	 */
	private static $edit_post_shortcodes;

	/**
	 * @var string Shortcode content
	 */
	public $content;

	public function __construct() {
		$this->tag = strtolower(get_class($this));

		Once::add_action('edit_post', '\Essentials\Html\Base_Shortcode::post_edit', 1);

		// Run constructor after theme setup
		add_action('after_setup_theme', array($this, 'construct'));
		add_filter('vc_base_build_shortcodes_custom_css', array($this, 'custom_css'));
	}

	/**
	 * General constructor
	 */
	public function construct() {
		$this->init();

		add_shortcode($this->tag, array($this, 'hook'));
		add_action('vc_before_init', array($this, 'integrate'));
	}

	/**
	 * Specific shortcode initiation
	 */
	abstract public function init();

	/**
	 * Hook to run when shortcode is found
	 *
	 * @param      $attributes array
	 * @param null $content    string
	 *
	 * @return string
	 */
	public function hook($attributes, $content = null) {
		$this->settings = $this->parse_settings($attributes);
		$this->hash     = $this->get_hash($this->settings);
		$this->content  = do_shortcode(shortcode_unautop(wpautop(preg_replace('/<\/?p\>/', "\n", $content) . "\n")));

		return $this->render();
	}

	/**
	 * Parse shortcode attributes
	 *
	 * @param $attributes array List of shortcode attributes
	 *
	 * @return array Entire list of supported attributes and their values
	 */
	private function parse_settings($attributes) {
		if (!isset($this->parameters['params'])) {
			return array();
		}

		$defaults = from($this->parameters['params'])->toDictionary('$v["param_name"]', array(
			$this,
			'get_shortcode_param_default'
		))->toArray();

		return shortcode_atts($defaults, $attributes);
	}

	private static $values_params = array('single_checkbox');

	public function get_shortcode_param_default($value, $key) {

		if (isset($value['std'])) {
			return $value['std'];
		} else {
			return (isset($value['value']) && in_array($value['type'], self::$values_params)) ? $value['value'] : '';
		}
	}

	/**
	 * Get unique shortcode hash using parameters
	 *
	 * @param $tag    string Shortcode tag name
	 * @param $params array List of parameters to calculate hash
	 *
	 * @return string hash
	 */
	public static function hash($tag, $params) {
		if (empty($params)) {
			return '';
		}

		return $tag . '_' . hash('crc32', implode('', $params));
	}

	/**
	 * Get unique shortcode hash using parameters for current instance
	 *
	 * @param $params array List of parameters to calculate hash
	 *
	 * @return string hash
	 */
	private function get_hash($params) {
		return self::hash($this->tag, $params);
	}

	/**
	 * Render shortcode content
	 * @return string HTML string
	 */
	private function render() {
		ob_start();
		include($this->get_template());
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Get current class file directory
	 */
	protected function get_dir() {
		$reflector = new \ReflectionClass(get_class($this));

		return dirname($reflector->getFileName());
	}

	/**
	 * Get current shortcode template
	 */
	public function get_template() {
		return $this->get_dir() . '/template.php';
	}

	/**
	 * Add shortcode to Visual Composer if exists
	 */
	public function integrate() {
		vc_map(array_merge(array(
			'base' => $this->tag,
		), $this->parameters));
	}

	/**
	 * Remember edit post id for future use
	 *
	 * @param null $id Current edit post id
	 */
	public static function post_edit($id = null) {
		self::$edit_post_id = $id;
	}

	/**
	 * Extend Visual Composer shortcodes CSS styles
	 *
	 * @param $css string Current styles
	 *
	 * @return string updated styles
	 */
	public function custom_css($css) {
		$css .= $this->get_styles();

		return $css;
	}

	/**
	 * Get additional CSS styles for shortcodes
	 * @return string additional styles
	 */
	protected function get_styles() {
		return '';
	}

	/**
	 * Get list of shortcodes from page content where tag name is equal to $this->tag
	 * @return array List of shortcodes with parsed settings and hash
	 */
	protected function get_shortcodes() {
		$shortcodes = self::get_all_shortcodes($this->tag);

		foreach ($shortcodes as &$shortcode) {
			$settings              = $this->parse_settings($shortcode['attributes']);
			$shortcode['hash']     = $this->get_hash($settings);
			$shortcode['settings'] = $settings;
		}

		return $shortcodes;
	}

	public static function get_edit_post_id() {
		return self::$edit_post_id;
	}

	/**
	 * Get list of all shortcodes from page content
	 *
	 * @param null|string $tag - tag name
	 *
	 * @return array List of shortcodes with attributes
	 */
	public static function get_all_shortcodes($tag = null) {
		if (!isset(self::$edit_post_shortcodes)) {
			$post = get_post(self::$edit_post_id);

			self::$edit_post_shortcodes = array();

			if ($post) {
				preg_match_all('/\[[\w]+[^\]]+/', $post->post_content, $strings);

				foreach ($strings[0] as $string) {
					$string = explode(' ', $string, 2);

					self::$edit_post_shortcodes[] = array(
						'tag'        => str_replace('[', '', $string[0]),
						'attributes' => isset($string[1]) ? shortcode_parse_atts($string[1]) : array()
					);
				}
			}
		}

		return empty($tag)
			? self::$edit_post_shortcodes
			: from(self::$edit_post_shortcodes)->where("\$v['tag']=='$tag'")->toValues()->toArray();
	}
}
