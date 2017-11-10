<?php

namespace Essentials\Data;

use Essentials\Utils\Once;

abstract class Base_Entity {
	
	/**
	 * @var string Entity name
	 */
	protected $name;
	
	/**
	 * @var string Singular name for UI
	 */
	protected $singular;
	
	/**
	 * @var string Plural name for UI
	 */
	protected $plural;
	
	/**
	 * @var string Text domain for translations
	 */
	protected $text_domain;
	
	/**
	 * @var array Settings for registering
	 */
	protected $settings;
	
	/**
	 * Base entity constructor
	 *
	 * @param array  $options     Custom entity options
	 * @param string $text_domain Text domain. Unique identifier for retrieving translated strings
	 */
	public function __construct(array $options, $text_domain) {
		# Check post type name
		if (array_key_exists('name', $options)) {
			$options['name'] = strtolower($options['name']);
		} else {
			return;
		}
		
		$this->name        = $options['name'];
		$this->text_domain = $text_domain;
		$this->singular    = array_key_exists('singular', $options) ? $options['singular'] : ucwords(str_replace('_', ' ', $this->name));
		$this->plural      = array_key_exists('plural', $options) ? $options['plural'] : "{$this->singular}s";
	}
	
	/**
	 * Translate string using text domain
	 *
	 * @param string $string String to translate
	 *
	 * @return string
	 */
	protected function translate($string) {
		return _x($string, $this->name, $this->text_domain);
	}
	
	/**
	 * Compose common settings for register
	 *
	 * @param array $defaults Default settings
	 * @param array $options  Custom entity options
	 * @param array $labels   An array of labels for this entity
	 *
	 * @return array
	 */
	protected function parse_common_settings(array $defaults, array $options, array $labels) {
		$settings = array_merge($defaults, array_key_exists('args', $options) ? $options['args'] : array());
		
		# Labels
		$settings['label'] = array_key_exists('label', $settings) ? $settings['label'] : $this->plural;
		
		if (array_key_exists('labels', $options)) {
			$labels = array_merge($labels, $options['labels']);
		}
		
		foreach ($labels as &$label) {
			$label = $this->translate($label);
		}
		
		unset($label);
		
		$settings['labels'] = $labels;
		
		# Rewrite
		if (array_key_exists('rewrite', $settings)) {
			Once::add_action('after_switch_theme', 'flush_rewrite_rules');
		}
		
		return $settings;
	}
}