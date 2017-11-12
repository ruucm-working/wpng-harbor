<?php

namespace Essentials\Core;

class Config {
	
	/**
	 * @var array
	 */
	public $assets;
	
	/**
	 * @var array
	 */
	public $common;
	
	/**
	 * @var array
	 */
	public $customizer;
	
	/**
	 * @var array
	 */
	public $layouts;
	
	/**
	 * @var array
	 */
	public $options;
	
	/**
	 * @var array
	 */
	public $post_meta;
	
	/**
	 * @var array Post types custom options list
	 */
	public $post_types;
	
	/**
	 * @var array Taxonomies custom options list
	 */
	public $taxonomies;
	
	/**
	 * Read theme JSON and PHP config file and write result into config
	 *
	 * @param string[] $sections Config property to update
	 *
	 * @return Config Configuration
	 */
	public function read(array $sections) {
		foreach ($sections as $section) {
			$name = str_replace('_', '-', $section);
			if ($file = locate_template("config/$name.json")) {
				$this->$section = json_decode(file_get_contents($file));
			} else if ($file = locate_template("config/$name.php")) {
				$this->$section = require $file;
				if (!is_array($this->$section)) {
					$this->$section = array();
				}
			}
		}
		
		return $this;
	}
}