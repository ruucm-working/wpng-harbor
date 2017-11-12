<?php
namespace Essentials\Utils;

class Condition {

	/**
	 * @param string $type  Condition type
	 * @param mixed  $value Value to check
	 *
	 * @return bool
	 */
	public static function check($type, $value) {
		if ($type === 'class_exists' && $value) {
			return class_exists($value);
		}
		
		if ($type === 'defined' && $value) {
			return defined($value);
		}
		
		if (function_exists($type)) {
			return (bool)(!$value ? call_user_func($type) : call_user_func($type, $value));
		}

		return false;
	}
}