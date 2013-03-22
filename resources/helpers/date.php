<?php

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