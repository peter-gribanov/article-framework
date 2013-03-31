<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\View;

use Framework\View\Iface;
use Framework\View\Php\HelperUtility;
use Framework\View\Php\Sandbox;
use Framework\View\Exception;

/**
 * Шаблонизатор нативный PHP
 *
 * @author  Peter Gribanov <gribanov@professionali.ru>
 * @package Framework\View
 */
class Php implements Iface {

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
	 * @var \Framework\View\Php\HelperUtility
	 */
	private static $utility;


	/**
	 * Конструктор
	 *
	 * @param string  $path         Пути к файлам шаблонов
	 * @param string  $helpers_path Путь к хелперам
	 * @param boolean $debug        Режим отладки
	 */
	public function __construct($path, $helpers_path, $debug = false) {
		self::$helpers_path = $helpers_path;
		$this->path         = $path;
		$this->debug        = $debug;

		self::$utility = new HelperUtility($this);
	}

	/**
	 * Присвоение переменных шаблону
	 *
	 * @param mixed $vars Данные
	 *
	 * @return \Framework\View\Php
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
	 * @throws \Framework\View\Exception
	 *
	 * @param array $vars Данные
	 */
	private function checkVars(array $vars) {
		foreach ($vars as $var) {
			if (is_array($var)) {
				$this->checkVars($var);
			} elseif (!is_scalar($var) && !is_null($var)) {
				throw new Exception('Ожидалася скаляр, массив или null, пришло: '.gettype($val));
			}
		}
	}

	/**
	 * Возвращает список всех установленных переменных
	 *
	 * @return array
	 */
	public function getVars() {
		return $this->vars;
	}

	/**
	 * Возвращает переменную
	 *
	 * @param string $name Название переменной
	 *
	 * @return mixed
	 */
	public function getVar($name) {
		return isset($this->vars[$name]) ? $this->vars[$name] : null;
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
	 * @throws \Framework\View\Exception
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

			$content = Sandbox::__render($this->path.DIRECTORY_SEPARATOR.$template, $this->vars);

			// Если вывести комментарий перед <!doctype html> то сломается верстка в IE6-7 или
			// Если добавить комментарии в шаблон XML то он сломается
			if ($this->debug && $is_add_comment && (strpos($template, 'html.html.tpl') === false)) {
				$content = '<!-- template: '.$template.' -->'.$content.'<!-- /template: '.$template.' -->';
			}
			$this->vars['content'] = $content;
		}
		return $this->vars['content'];
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
	 *
	 * @return \Closure
	 */
	public static function getHelper($name) {
		if (!isset(self::$helpers[$name])) {
			$file = self::$helpers_path.DIRECTORY_SEPARATOR.$name.'.php';

			if (!is_readable($file)) {
				throw new Exception('Хелпер "'.$name.'" не зарегистрирован');
			}

			$utility = self::$utility;
			$helper = include $file;
			if (!($helper instanceof \Closure)) {
				throw new Exception('В файле '.$file.' хелпер '.$name.' не найден');
			}

			self::$helpers[$name] = $helper;
		}
		return self::$helpers[$name];
	}

}