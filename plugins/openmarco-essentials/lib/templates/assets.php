<?php

namespace Essentials\Templates;

use Essentials\Core\Config;
use Essentials\Core\Context;
use Essentials\Utils\Condition;
use Essentials\Utils\Once;

class Assets {
	
	/**
	 * @var Context
	 */
	private $context;
	
	/**
	 * @var array[] List of styles assets
	 */
	private $styles;
	
	/**
	 * @var array[] List of scripts assets
	 */
	private $scripts;
	
	/**
	 * @var array CDN links dictionary
	 */
	private $cdn = array();
	
	/**
	 * Enqueue assets
	 *
	 * @param array   $assets Assets to enqueue
	 * @param Context $context
	 */
	public function __construct(array $assets, Context $context) {
		$this->context = $context;
		$this->styles  = array_key_exists('css', $assets) ? (array)$assets['css'] : array();
		$this->scripts = array_key_exists('js', $assets) ? (array)$assets['js'] : array();
		
		if ($context->is_admin) {
			$tag = 'admin';
		} else {
			$tag = $context->is_login ? 'login' : 'wp';
		}
		$tag .= '_enqueue_scripts';
		
		add_action($tag, array($this, 'enqueue_scripts'), 5);
		
		if (!$context->is_admin && !$context->is_login) {
			add_action('wp_enqueue_scripts', array($this, 'enqueue_comment_reply'));
		}
	}
	
	/**
	 * Enqueue assets hook
	 */
	public function enqueue_scripts() {
		$context = $this->context;
		
		foreach ($this->styles as $style) {
			if (array_key_exists('dependency', $style) && !Condition::check($style['dependency']['type'], $style['dependency']['value'])) {
				continue;
			}
			
			wp_enqueue_style(
				$style['handle'],
				$context->theme_uri . $style['src'],
				array_key_exists('deps', $style) ? $style['deps'] : null,
				$this->get_version($style));
		}
		
		foreach ($this->scripts as $script) {
			if (array_key_exists('dependency', $script) && !Condition::check($script['dependency']['type'], $script['dependency']['value'])) {
				continue;
			}
			
			wp_register_script(
				$script['handle'],
				(array_key_exists('child', $script) && $script['child'] ? $context->child_theme_uri : $context->theme_uri) . $script['src'],
				array_key_exists('deps', $script) ? $script['deps'] : null,
				$this->get_version($script),
				array_key_exists('footer', $script) ? $script['footer'] : true);
			
			wp_enqueue_script($script['handle']);
			
			if (array_key_exists('cdn', $script) && array_key_exists('test', $script)) {
				$this->cdn[$script['handle']] = array($script['cdn'], $script['test']);
				
				Once::add_filter('script_loader_src', array($this, 'cdn'), 10, 2);
				Once::add_action('wp_head', array($this, 'cdn'), 10, 2);
			}
		}
	}
	
	/**
	 * Allow full JavaScript functionality with the comment features
	 */
	public function enqueue_comment_reply() {
		if (is_singular()) {
			wp_enqueue_script('comment-reply');
		}
	}
	
	/**
	 * Replace local script with CDN and local fallback
	 *
	 * @param string $src    local script link
	 * @param string $handle script name
	 *
	 * @return string|bool False if process CDN script, otherwise do nothing and return original src
	 */
	public function cdn($src, $handle = null) {
		if (array_key_exists($handle, $this->cdn)) {
			$cdn = $this->cdn[$handle];
			
			echo "<script src=\"{$cdn[0]}\"></script><script>{$cdn[1]}||document.write('<script src=\"{$src}\"></' + 'script>')</script>";
			
			return false;
		}
		
		return $src;
	}
	
	/**
	 * @param array $asset
	 *
	 * @return string
	 */
	private function get_version(array $asset) {
		if (array_key_exists('version', $asset) && $version = $asset['version']) {
			$version = is_string($version) || is_numeric($version) ? (string)$version : $this->context->theme->Version;
		} else {
			$version = '';
		}
		
		return $version;
	}
	
	/**
	 * Init assets enqueue
	 *
	 * @param Context $context
	 * @param Config  $config
	 */
	public static function init(Context $context, Config $config) {
		new Assets($config->assets, $context);
	}
}