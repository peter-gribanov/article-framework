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

use Framework\Exception;

/**
 * Утилита по работе с масиивами
 *
 * @package Framework\Utility
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Arr {

	/**
	 * Получение из многомерного массива данных, значения по пути заданной строкой
	 * вида name[name1][name2] или name.name1.name2
	 *
	 * @param array       $from    Массив данных
	 * @param string|null $name    Путь
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	static public function getByPath(array $from, $name = null, $default = null) {
		if (is_null($name)) {
			// случай, когда нас попросили вернуть не какое то конкретное значение, а весь массив целиком
			return $from;
		}

		// приведем пути вида [name][name1][name2] к виду name.name1.name2
		$name = str_replace(array(']', '['), array('', '.'), $name);
		$parts = explode('.', $name);
		foreach ($parts as $part) {
			if (!is_array($from)) {
				throw new Exception('Нельзя получить значение из массива для пути '.$name);
			}
			if (!isset($from[$part])) {
				return $default;
			}
			$from = $from[$part];
		}
		return $from;
	}

	/**
	 * Получение из многомерного массива данных, значения по пути заданной строкой
	 * вида name[name1][name2] или name.name1.name2
	 *
	 * @param array       $from    Массив данных
	 * @param string|null $name    Путь
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	static public function &getByPathRef(array &$from, $name = null, $default = null) {
		if (is_null($name)) {
			// случай, когда нас попросили вернуть не какое то конкретное значение, а весь массив целиком
			return $from;
		}

		// приведем пути вида [name][name1][name2] к виду name.name1.name2
		$name = str_replace(array(']', '['), array('', '.'), $name);
		$is_set = true; // индикатор отсутствия значения
		$parts = explode('.', $name);
		foreach ($parts as $part) {
			if (!is_array($from)) {
				throw new Exception('Нельзя получить значение из массива для пути '.$name);
			}
			if (!isset($from[$part])) {
				$is_set      = false;   // очередного значения нет
				$from[$part] = array(); // создаем фейковый узел
			}
			// переключим текущий узел ("рекурсия")
			$from = &$from[$part];
		}

		if (!$is_set) {
			$from = $default;
		}
		return $from;
	}

	/**
	 * Извлекает элемент из массива если он есть, иначе возвращает значение по умолчанию
	 *
	 * @param array $array   Массив данных
	 * @param mixed $key     Ключ
	 * @param mixed $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	static public function get(array &$array, $key, $default) {
		if (empty($array)) {
			return $default;
		}

		if (!array_key_exists($key, $array)) {
			return $default;
		}

		return $array[$key];
	}

}