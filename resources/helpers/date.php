<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

/**
 * Форматирование даты
 *
 * @param string  $format    Формат
 * @param mixed   $timestamp UTM время
 * 
 * @return string
 */
return function ($format, $timestamp = null) {
	if ($timestamp && !is_int($timestamp)) {
		$timestamp = strtotime($timestamp);
	}
	return date($format, ($timestamp ?: time()));
};