<?php

/**
 * Шаблонизатор нативный PHP
 *
 * @author  Peter Gribanov
 * @package View
 */
class View_Php implements View_Interface {

	/**
	 * Список переменных шаблона
	 *
	 * @var array
	 */
	private $vars = array();

	/**
	 * Пути к файлам шаблонов
	 * 
	 * @var string
	 */
	private $path;

	/**
	 * Режим отладки
	 *
	 * @var boolean
	 */
	private $debug = false;

	/**
	 * Список хелперов
	 *
	 * @var array
	 */
	private static $helpers = array();

	/**
	 * Путь к хелперам
	 *
	 * @var string
	 */
	private static $helpers_path = '';

	/**
	 * Утилита для хелперов
	 *
	 * @var View_Php_HelperUtility
	 */
	private static $utility;


	/**
	 * Конструктор
	 *
	 * @param string  $path         Пути к файлам шаблонов
	 * @param string  $helpers_path Путь к хелперам
	 * @param boolean $debug        Режим отладки
	 */
	public function __construct($path = '', $helpers_path = '', $debug = true) {
		// TODO временные заглушки
		self::$helpers_path = $helpers_path ?: ROOT_DIR.'/resources/helpers';
		$this->path         = $path         ?: ROOT_DIR.'/resources/templates';
		$this->debug        = PRODUCTION    ? false : $debug;

		self::$utility = new View_Php_HelperUtility($this);
	}

	/**
	 * Присвоение переменных шаблону
	 *
	 * @param mixed $vars Данные
	 *
	 * @return View_Php
	 */
	public function assign($vars) {
		$vars = (array)$vars;
		$this->checkVars($vars);
		$this->vars = array_merge($this->vars, $vars);
		return $this;
	}

	/**
	 * Проверяет данные на корректность
	 *
	 * Шаблонизатор должен оперировать простыми данными такими как сколяр или массив
	 * Неправильно передавать в шаблон объект или ресурс
	 *
	 * @throws Exception
	 *
	 * @param array $vars Данные
	 */
	private function checkVars(array $vars) {
		$vars = array_values($vars);
		while ($var = array_shift($vars)) {
			if (is_array($var)) { // если массив то добавляем данные из него в конец
				$vars = array_merge($vars, array_values($var));
			} elseif (!is_scalar($var) && !is_null($var)) {
				throw new Exception('Ожидалася скаляр, массив или null, пришло: '.gettype($val));
			}
		}
		
	}

	/**
	 * Возвращает список всех установленных переменных
	 *
	 * @return mixed
	 */
	public function getVars() {
		return $this->vars;
	}

	/**
	 * Очистить добавленные данные
	 *
	 * @return View_Php
	 */
	public function clear() {
		$this->vars = array();
		return $this;
	}

	/**
	 * Клонирование представления
	 */
	function __clone() {
		$this->clear();
	}

	/**
	 * Выполнить трансфформацию тэмплэйта
	 *
	 * Лэйаут отличается от обычного тэмплэйта, только наличием вывода переменной content.
	 * В лэйауте должен быть вывод <?=$content;?>
	 *
	 * @throwsException
	 *
	 * @param string  $template       Шаблон
	 * @param boolean $is_add_comment Добавлять ли html коментарии с указанием шаблона
	 *
	 * @return string
	 */
	public function render($template, $is_add_comment = false) {
		if (!is_string($template)) {
			throw new Exception('Ожидалась строка '.gettype($template));
		}

		self::$utility->clear()->addTemplate($template);
		while ($template = self::$utility->getTemplate()) {
			if (!is_readable($this->path.DIRECTORY_SEPARATOR.$template)) {
				throw new Exception('Шаблон "'.$template.'" не найден в '.$this->path);
			}

			$content = self::sandbox($this->path.DIRECTORY_SEPARATOR.$template, $this->vars);

			// Если вывести комментарий перед <!doctype html> то сломается верстка в IE6-7 или
			// Если добавить комментарии в шаблон XML то он сломается
			if ((strpos($template, 'html.tpl') === false) && $this->debug && $is_add_comment) {
				$content = '<!-- template: '.$template.' -->'.$content.'<!-- /template: '.$template.' -->';
			}
			$this->vars['content'] = $content;
		}
		return $this->vars['content'];
	}

	/**
	 * Песочница
	 *
	 * @param string $__file Название файла
	 * @param array  $__vars Переменные
	 *
	 * @return string
	 */
	private static function sandbox($__file, $__vars) {
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

	/**
	 * Добавляет хелпер
	 *
	 * @param string  $name   Имя хелпера
	 * @param Closure $helper Хелпер
	 */
	public static function setHelper($name, Closure $helper) {
		self::$helpers[$name] = $helper;
	}

	/**
	 * Возвращает хелпер
	 *
	 * @param string $name Имя хелпера
	 */
	public static function getHelper($name) {
		if (!isset(self::$helpers[$name])) {
			$file = self::$helpers_path.DIRECTORY_SEPARATOR.$name.'.php';

			if (!is_readable($file)) {
				throw new Exception('Хелпер "'.$name.'" не зарегистрирован');
			}

			$utility = self::$utility;
			$helper = include $file;
			if (!($helper instanceof Closure)) {
				throw new Exception('В файле '.$file.' хелпер '.$name.' не найден');
			}

			self::$helpers[$name] = $helper;
		}
		return self::$helpers[$name];
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
		return call_user_func_array(array(self::getHelper($name), '__invoke'), $arguments);
	}

}