<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

namespace Framework\View;

use Framework\Exception;
use Framework\Factory;

/**
 * Представление
 * 
 * @package Framework\View
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class NativePHP implements Iface {

	/**
	 * Пути к файлам темплэйтов
	 * 
	 * @var string
	 */
	private $__path;

	/**
	 * Переменные
	 *
	 * @var array
	 */
	private $__vars = array();

	/**
	 * Режим отладки
	 *
	 * @var boolean
	 */
	private $__debug = false;

	/**
	 * Список хелперов
	 * 
	 * @var array
	 */
	private static $__helpers = array();

	/**
	 * Путь к хелперам
	 *
	 * @var string
	 */
	private static $__helpers_path = null;


	/**
	 * Конструктор
	 * 
	 * @param string  $path  Путь к файлам темплэйтов
	 * @param boolean $debug Режим отладки
	 */
	public function __construct($path, $debug = false) {
		$this->__path  = $path;
		$this->__debug = $debug;
	}

	/**
	 * Клонирование представления
	 */
	function __clone() {
		$this->clear();
	}

	/**
	 * Очистить добавленные данные
	 *
	 * @return Framework\View\NativePHP
	 */
	public function clear() {
		$this->__vars = array();
		return $this;
	}

	/**
	 * Вызов установленных хелперов
	 *
	 * @param string|null $path Путь
	 */
	public static function setHelpersPath($path) {
		static::$__helpers_path = $path;
	}

	/**
	 * Добавляет хелпер
	 * 
	 * @param string  $name   Имя хелпера
	 * @param Closure $helper Хелпер
	 */
	public static function setHelper($name, \Closure $helper) {
		static::$__helpers[$name] = $helper;
	}

	/**
	 * Возвращает хелпер
	 * 
	 * @param string $name Имя хелпера
	 */
	public static function getHelper($name) {
		if (!isset(self::$__helpers[$name])) {
			$path = self::$__helpers_path;
			if (is_readable($path.DIRECTORY_SEPARATOR.$name.'.php')) {
				include $path.DIRECTORY_SEPARATOR.$name.'.php';
				if (function_exists('helper_'.$name)) {
					self::$__helpers[$name] = 'helper_'.$name;
				} else {
					throw new Exception('В файле '.$path.'/'.$name.'.php хелпер helper_'.$name.' не найден');
				}
			} else {
				throw new Exception('Хелпер "'.$name.'" не зарегистрирован');
			}
		}
		return self::$__helpers[$name];
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
		return call_user_func_array(static::getHelper($name), $arguments);
	}

	/**
	 * Присвоение переменных шаблону
	 *
	 * Позволяет установить значение к определенному ключу или передать массив пар ключ => значение
	 *
	 * @throws Framework\Exception
	 *
	 * @param string|array $spec  Ключ или массив пар ключ => значение
	 * @param mixed|null   $value Если присваивается значение одной переменной, то через него передается значение переменной
	 *
	 * @return Exception\View\NativePHP
	 */
	public function assign($spec, $value = null) {
		if (is_scalar($spec)) {
			$spec = array($spec => $value);
		}

		if (!is_array($spec)) {
			throw new Exception('Ожидалася скаляр или массив');
		}
		// записываем переменные
		foreach ($spec as $key => $val) {
			if (is_scalar($val) || is_array($val) || is_null($val)) {
				$this->__vars[$key] = $val;
			} else {
				throw new Exception('Ожидалася скаляр, массив или null, пришло: '.gettype($val));
			}
		}
		return $this;
	}

	/**
	 * Возвращает список всех установленных переменных
	 *
	 * @return array
	 */
	public function getVars() {
		return $this->__vars;
	}

	/**
	 * Выполнить трансфформацию тэмплэйта
	 *
	 * Или выполнить трансформацию тэмплэйта в нутри лэйаутов если передан массив тэмплэйтов.
	 * Лэйаут отличается от обычного тэмплэйта, только наличием вывода переменной content.
	 * В лэйауте должен быть вывод <?=$content;?>
	 *
	 * @throws Framework\Exception
	 *
	 * @param string|array $template       Шаблон или список шаблонов
	 * @param boolean      $is_add_comment Добавлять ли html коментарии с указанием шаблона
	 *
	 * @return string
	 */
	public function render($template, $is_add_comment = false) {
		if (!is_string($template) && !is_array($template)) {
			throw new Exception('Ожидалась строка или массив строк '.gettype($template));
		}
		$templates = array_reverse((array)$template);
		foreach ($templates as $template) {
			if (!is_readable($this->__path.DIRECTORY_SEPARATOR.$template)) {
				throw new Exception('Шаблон "'.$template.'" не найден в '.$this->__path);
			}
			$content = static::sandbox($this->__path.DIRECTORY_SEPARATOR.$template, $this->__vars);

			// Если вывести комментарий перед <!doctype html> то сломается верстка в IE6-7 или
			// Если добавить комментарии в шаблон XML то он сломается
			if (strpos($template, 'html.tpl') === false && $this->__debug && $is_add_comment) {
				$content = '<!-- template: '.$template.' -->'.$content.'<!-- /template: '.$template.' -->';
			}
			$this->__vars['content'] = $content;
		}
		return $this->__vars['content'];
	}

	/**
	 * Песочница
	 *
	 * @param string $__file Название файла
	 * @param array  $__vars Переменные
	 *
	 * @return string
	 */
	static function sandbox($__file, $__vars) {
		extract($__vars);
		ob_start();
		try {
			include ($__file);
		} catch (Exception $e) {
			ob_end_clean();
			throw $e;
		}
		return ob_get_clean();
	}

}