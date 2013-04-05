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
 * Хелпер включающий другой шаблон
 *
 * @param string $template Шаблон
 * @param array  $vars     Параметры шаблона
 *
 * @return string
 */
return function ($template, array $vars = array()) use ($utility) {
	return $utility->render($template, $vars);
};