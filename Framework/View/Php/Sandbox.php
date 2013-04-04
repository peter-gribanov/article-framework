<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\View\Php;

use Framework\View\Php;

/**
 * Песочница для рендера шаблона
 *
 * Песочница вынесена с отдельный класс для того что бы не было конфликтов с другими методами при вызове хелперов
 *
 * @author  Peter Gribanov <gribanov@professionali.ru>
 * @package Framework\View\Php
 */
class Sandbox {

	/**
	 * Запрещена инициализация
	 */
	private function __construct() {
	}

	/**
	 * Вызов шаблона
	 *
	 * @param string $__file Название файла
	 * @param array  $__vars Переменные
	 *
	 * @return string
	 */
	public static function __render($__file, $__vars) {
		extract($__vars);
		ob_start();
		try {
			include $__file;
		} catch (\Exception $e) {
			ob_end_clean();
			throw $e;
		}
		return ob_get_clean();
	}

	/**
	 * Обработчик вызовов хелперов
	 *
	 * @param string $name      Имя хелпера
	 * @param array  $arguments Параметры хелпера
	 *
	 * @return mixed
	 */
	public static function __callStatic($name, array $arguments = array()) {
		return call_user_func_array(Php::getHelper($name), $arguments);
	}

}