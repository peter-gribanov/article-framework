<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework;

use Framework\Utility\Arr as ArrayUtility;

/**
 * Пользовательский запрос
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Request {

	/**
	 * Входные данные _POST, _GET, _ARG и т.д.
	 *
	 * @var array
	 */
	private $input = array();

	/**
	 * Путь к корневой дирректории
	 *
	 * @var string
	 */
	private $root_url = '';

	/**
	 * Запрос выполнен из консоли
	 *
	 * @var boolean
	 */
	private $is_cli = false;

	/**
	 * Путь запроса
	 *
	 * @var string
	 */
	private $path = '';


	/**
	 * Строит запрос из глобальных переменных
	 *
	 * @return \Framework\Request
	 */
	static public function buildFromGlobal() {
		$request = new self();

		if ($request->is_cli = PHP_SAPI == 'cli') {
			$arg = $request->arguments($_SERVER['argv']);
			$request->input = array(
				'server' =>  $_SERVER,
				'env'    =>  $_ENV,
				'arg'    =>  $arg,
			);
			$request->path = isset($arg['input'][1]) ? $arg['input'][1] : '';
		} else {
			$request->input = array(
				'get'    =>  $_GET,
				'post'   =>  $_POST,
				'files'  =>  $_FILES,
				'cookie' =>  $_COOKIE,
				'server' =>  $_SERVER,
				'env'    =>  $_ENV,
			);
			$path = parse_url($request->server('REQUEST_URI', '/'), PHP_URL_PATH);
			$request->path = $request->server('PATH_INFO', $path);
		}
		return $request;
	}

	/**
	 * Взять из установленных массивов переменную, если нет возращает значение
	 * по умолчанию.
	 *
	 * @param string      $from    Откуда
	 * @param string|null $name    Что брать
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	private function getInput($from, $name = null, $default = null) {
		if (!$this->isInputed($from)) {
			return $default;
		}
		return ArrayUtility::get($this->input[$from], $name, $default);
	}

	/**
	 * Установить новые входящие данные
	 *
	 * @param string $to    куда
	 * @param array  $value что
	 *
	 * @return Request
	 */
	private function setInput($to, array $value = array()) {
		$this->input[$to] = $value;
		return $this;
	}

	/**
	 * Проверяет перегруженна ли данная переменная
	 *
	 * @param string $var название переменной
	 *
	 * @return boolean
	 */
	private function isInputed($var) {
		return array_key_exists($var, $this->input);
	}

	/**
	 * Распарсить аргументы коммандной строки
	 *
	 * $php myscript.php arg1 -arg2=val2 --arg3=arg3 -arg4 --arg5 -arg6=false
	 * Результат:
	 * Array (
	 *     [input] => Array (
	 *             [0] => myscript.php
	 *             [1] => arg1
	 *             )
	 *     [arg2] => val2
	 *     [arg3] => arg3
	 *     [arg4] => true
	 *     [arg5] => true
	 *     [arg5] => false
	 *     )
	 *
	 * @param array $arguments Массив аргументов
	 *
	 * @return array
	 */
	private function arguments(array $arguments = array()) {
		$ret = array();
		foreach ($arguments as $arg) {
			if (preg_match('/^-{1,2}([^\s=]+)=?(.*)$/us', $arg, $matches)) {
				$key = $matches[1];
				switch ($matches[2]) {
					case '':
					case 'true':
						$arg = true;
						break;
					case 'false':
						$arg = false;
						break;
					default:
						$arg = trim($matches[2], '\'"');
				}
				$ret[$key] = $arg;
			} else {
				$ret['input'][] = $arg;
			}
		}
		return $ret;
	}

	/**
	 * Взять данные из post
	 *
	 * @param string|null $name    Название переменной
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	public function post($name = null, $default = null) {
		return $this->getInput('post', $name, $default);
	}

	/**
	 * Взять данные из get
	 *
	 * @param string|null $name    Название переменной
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	public function get($name = null, $default = null) {
		return $this->getInput('get', $name, $default);
	}

	/**
	 * Взять данные из files
	 *
	 * @param string|null $name    Название переменной
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	public function cookie($name = null, $default = null) {
		return $this->getInput('cookie', $name, $default);
	}

	/**
	 * Взять данные из files
	 *
	 * @param string|null $name    Название переменной
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	public function files($name = null, $default = null) {
		return $this->getInput('files', $name, $default);
	}

	/**
	 * Взять данные из server
	 *
	 * @param string|null $name    Название переменной
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	public function server($name = null, $default = null) {
		return $this->getInput('server', $name, $default);
	}

	/**
	 * Взять данные из env
	 *
	 * @param string|null $name    Название переменной
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	public function env($name = null, $default = null) {
		return $this->getInput('env', $name, $default);
	}

	/**
	 * Взять данные из env
	 *
	 * @param string|null $name    Название переменной
	 * @param mixed|null  $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	public function arg($name = null, $default = null) {
		return $this->getInput('arg', $name, $default);
	}

	/**
	 * Проверяем, был ли выполнен запрос по безопасному протоколу
	 *
	 * @return boolean
	 */
	public function isSecureRequest() {
		return $this->server('SERVER_PORT', 80) == 443;
	}

	/**
	 * Проверяем, был ли выполнен запрос через ajax
	 *
	 * @return boolean
	 */
	public function isAjaxRequest() {
		return $this->server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest' && $this->server('HTTP_REFERER');
	}

	/**
	 * Возвращает метод отправки запроса
	 *
	 * @return string
	 */
	public function getRequestMethod() {
		return $this->server('REQUEST_METHOD', 'GET');
	}

	/**
	 * Возвращает путь к корневой дирректории
	 *
	 * @return string
	 */
	public function getRootUrl() {
		if (!$this->root_url) {
			$this->root_url = ($this->isSecureRequest() ? 'https' : 'http').'://'.$this->server('HTTP_HOST', 'localhost');
		}
		return $this->root_url;
	}

	/**
	 * Запрос выполнен из консоли
	 *
	 * @return boolean
	 */
	public function isCli() {
		return $this->is_cli;
	}

	/**
	 * Возвращает путь запроса
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

}