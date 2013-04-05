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
 * Кодирования / экранирования спецсимволов
 *
 * @param string $string Экринируемая строка
 * 
 * @return string
 */
return function ($string) {
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
};