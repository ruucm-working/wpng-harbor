<?php

namespace Essentials\Html;

class Css {
	
	/**
	 * @var array List of selectors with parameters
	 */
	private $selectors = array();
	
	/**
	 * Create new instance of Css class
	 * @return $this
	 */
	public static function init() {
		return new Css();
	}
	
	/**
	 * Add parameter to selector
	 *
	 * @param $selector  string|array selector name or list of names
	 * @param $parameter string|array parameter name or list of parameters values with parameters names as keys
	 * @param $value     string if $parameter is string, than set as value
	 *
	 * @return $this
	 */
	public function set($selector, $parameter, $value = null) {
		
		if (is_string($selector)) {
			$selector = array($selector);
		}
		
		if (is_string($parameter)) {
			$parameter = array($parameter => $value);
		}
		
		if (is_array($selector) && is_array($parameter)) {
			foreach ($selector as $name) {
				$this->selectors[$name] = array_merge(empty($this->selectors[$name]) ? array() : $this->selectors[$name], $parameter);
			}
		}
		
		return $this;
	}
	
	/**
	 * Clear all parameters
	 * @return $this
	 */
	public function clear() {
		$this->selectors = array();
		
		return $this;
	}
	
	/**
	 * Return finalized CSS string
	 * @deprecated
	 * @return string
	 */
	public function to_string() {
		return (string)$this;
	}
	
	/**
	 * Return finalized CSS string
	 * @return string
	 */
	public function __toString() {
		$css = '';
		
		foreach ($this->selectors as $selector => $parameters) {
			$params = from($parameters)->where('!empty($v)||$v==="0"||$v===0')->select('$k . ":" . $v')->toString(';');
			
			if (!empty($params)) {
				$css .= "{$selector}{{$params}}";
			}
		}
		
		return $css;
	}
	
	/**
	 * @param string $svg
	 *
	 * @return string
	 */
	public static function getSvgUrl($svg) {
		return 'data:image/svg+xml;base64,' . base64_encode($svg);
	}
}
