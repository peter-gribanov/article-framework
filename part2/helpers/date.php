<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

/**
 * Форматирование даты
 *
 * @param string  $format    Формат
 * @param mixed   $timestamp UTM время
 * 
 * @return string
 */
function helper_date($format, $timestamp = null) {
	if ($timestamp && !is_int($timestamp)) {
		$timestamp = strtotime($timestamp);
	}
	return date($format, ($timestamp ?: time()));
}

