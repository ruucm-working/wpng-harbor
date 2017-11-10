<?php

namespace Essentials\Core;

use Essentials\Data;
use Essentials\Templates;

class Framework {

	/**
	 * @var Framework
	 */
	public static $instance;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @var \TitanFramework
	 */
	private $titan_instance;

	/**
	 * @param Context $context
	 * @param Config $config
	 */
	public function __construct(Context $context, Config $config) {
		$this->config  = $config;
		$this->context = $context;

		$GLOBALS['content_width'] = array_key_exists('content_width', $config->common) ? $config->common['content_width'] : 1140;

		Support::init($config);
		Data\Menu::init($config);

		add_action('after_setup_theme', array($this, '_init_'));
		add_action('tf_create_options', array($this, '_titan_'));
	}
	
	/**
	 * Init global entities
	 */
	public function _init_() {
		$context = $this->context;
		$config = $this->config;
		
		Data\Sidebar::init($context, $config);
		Data\Post_Type::init($context, $config);
		Data\Taxonomy::init($context, $config);

		if (!($context->is_admin || $context->is_login)) {
			// Frontend
			Templates\Layout::init($context, $config);
			Templates\Assets::init($context, $config);
		}
	}
	
	/**
	 * Init options and settings
	 */
	public function _titan_() {
		if (!$this->titan_instance) {
			$this->titan_instance = \TitanFramework::getInstance($this->context->theme->Template);
		}
		
		$context = $this->context;
		$config = $this->config;
		
		Data\Customizer::init($context, $config, $this->titan_instance);
		Data\Options::init($context, $config, $this->titan_instance);
		Data\Post_Meta::init($context, $config, $this->titan_instance);
	}

	/**
	 * @param $context Context
	 * @param $config  Config
	 */
	public static function create($context, $config) {
		Framework::$instance = new Framework($context, $config);
	}
}