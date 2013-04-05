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
 * Хелпер стартующий создание блока
 *
 * @param string  $name      Название блока
 * @param boolean $overwrite Перезаписывать блок
 */
return function ($name, $overwrite = true) use ($utility) {
	$utility->startBuffering($name, $overwrite);
};