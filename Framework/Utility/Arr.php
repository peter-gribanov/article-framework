<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\Utility;

/**
 * Утилита по работе с масиивами
 *
 * @package Framework\Utility
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Arr {

	/**
	 * Возвращает элемент из массива
	 *
	 * Пример указания путь к элементу
	 * <code>
	 * path.to.some.record
	 * </code>
	 *
	 * @param array  $array Массив
	 * @param string $path  Путь к элементу массива
	 *
	 * @return mixed
	 */
	public static function getByPath(array $array, $path) {
		if (strpos($path, '.') != false) {
			$paths = explode('.', $path);
			foreach ($paths as $part) {
				if (isset($array[$part])) {
					$array = $array[$part];
				} else {
					return null;
				}
			}
			return $array;
		}
		return isset($array[$path]) ? $array[$path] : null;
	}

}