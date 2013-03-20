<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework;

/**
 * Представление
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class View {

	/**
	 * Список хелперов
	 * 
	 * @var array
	 */
	private static $helpers = array();

	/**
	 * Корневая дирректория
	 *
	 * @var string
	 */
	private $dir = '';


	/**
	 * Конструктор
	 *
	 * @param string $dir Корневая дирректория
	 */
	public function __construct($dir) {
		$this->dir = $dir;

		self::setHelper('url', function ($path) {
			return '/'.$path;
		});

		$view = $this;
		self::setHelper('inc', function ($template, array $vars = array()) use ($view){
			return $view->render($template, (array)$vars);
		});

		self::setHelper('rusform', function ($num, $form_one = 'штука', $form_two = 'штуки', $form_five = 'штук') {
			$num = abs($num) % 100;
			$mod = $num % 10;
			if ($num > 10 && $num < 20) {
				return $form_five;
			}
			if ($mod > 1 && $mod < 5) {
				return $form_two;
			}
			if ($mod == 1) {
				return $form_one;
			}
			return $form_five;
		});
	}

	/**
	 * Добавляет хелпер
	 * 
	 * @param string   $name   Имя хелпера
	 * @param \Closure $helper Хелпер
	 */
	public static function setHelper($name, \Closure $helper) {
		self::$helpers[$name] = $helper;
	}

	/**
	 * Возвращает хелпер
	 * 
	 * @param string $name Имя хелпера
	 */
	public static function getHelper($name) {
		if (isset(self::$helpers[$name])) {
			return self::$helpers[$name];
		}
		return null;
	}

	/**
	 * Обработчик вызовов хелперов
	 * 
	 * @param string $name      Имя хелпера
	 * @param array  $arguments Параметры хелпера
	 * 
	 * @return mixed
	 */
	public static function __callstatic($name, array $arguments = array()) {
		if ($helper = self::getHelper($name)) {
			return call_user_func_array($helper, $arguments);
		}
	}

	/**
	 * Возвращает отрисованный шаблон
	 * 
	 * @param string|array $template Шаблон или список шаблонов
	 * @param array        $vars     Данные
	 * 
	 * @return string
	 */
	public function render($template, array $vars = array()) {
		$templates = array_reverse((array)$template);
		foreach ($templates as $template) {
			$vars['content'] = self::sandbox($this->dir.'/templates/'.$template, $vars);
		}
		return isset($vars['content']) ? $vars['content'] : '';
	}

	/**
	 * Песочница
	 *
	 * @param string $__file Название файла
	 * @param array  $__vars Переменные
	 *
	 * @return string
	 */
	protected static function sandbox($__file, array $__vars = array()) {
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

}