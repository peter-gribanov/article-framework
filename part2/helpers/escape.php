<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

/**
 * Кодирования / экранирования спецсимволов
 *
 * @param string $string Экринируемая строка
 * 
 * @return string
 */
function helper_escape($string) {
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}
