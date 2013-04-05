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
 * Хелпер сохраняющий значение
 *
 * @param string $name  Название
 * @param string $value Значение
 */
return function ($name, $value) use ($utility) {
	$utility->assign(array($name => $value));
};