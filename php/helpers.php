<?php

/**
* Helpers
*/
class Helpers
{	
	static function strrpos_offset($needle, $haystack, $occurrence) {
		// explode the haystack
		$arr = array_reverse(explode($needle, $haystack));
		// check the needle is not out of bounds
		switch( $occurrence ) {
		case $occurrence == 0:
			return false;
		case $occurrence > max(array_keys($arr)):
			return false;
		default:
			$inverted = strlen(implode($needle, array_slice($arr, 0, $occurrence)));
			$actual = (strlen($haystack) - 1) - $inverted;
			return $actual;
		}
	}

	static function render($path, $variables) {
		extract($variables);
		ob_start();
		include $path;
		unset($path);
		return ob_get_clean();
	}
}