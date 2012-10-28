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
 * @param string              $format    Формат
 * @param string|integer|null $timestamp UTM время
 * 
 * @return string
 */
function helper_date($format, $timestamp = null) {
	return $timestamp ? date($format, (int)$timestamp) : date($format);
}

