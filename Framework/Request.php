<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Microsoft;


/**
 * Пользовательский запрос
 * 
 * @package Microsoft
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
	 * Строит запрос из глобальных переменных
	 *
	 * @return \Microsoft\Request
	 */
	static public function buildFromGlobal() {
		$request = new self();
		$request->input = array(
			'get'    =>  $_GET,
			'post'   =>  $_POST,
			'files'  =>  $_FILES,
			'cookie' =>  $_COOKIE,
			'server' =>  $_SERVER,
			'env'    =>  $_ENV,
		);
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
		if (is_null($name)) {
			return $this->input[$from];
		}
		return isset($this->input[$from][$name]) ? $this->input[$from][$name] : $default;
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

}