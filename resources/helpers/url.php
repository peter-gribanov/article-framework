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
 * Возвращает абсолютный путь к узлу
 *
 * @param string $alias  Алиас
 * @param array  $params Параметры
 *
 * @return string
 */
return function ($alias, array $params = array()) use ($utility) {
	return $utility->getURLHelper()->getUrl($alias, $params);
};