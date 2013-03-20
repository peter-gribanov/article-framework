<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Response;

use Framework\Response\Base as BaseResponse;

/**
 * Ответ от вызова метода приложения отправляемый по HTTP(S) протоколу
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
abstract class Http extends BaseResponse {

	/**
	 * Список заголовков
	 *
	 * @var array
	 */
	private $headers = array('content-type' => 'text/plain');


	/**
	 * Добавляет заголовоки
	 *
	 * @param array $headers Заголовки
	 *
	 * @return \Framework\Response\Http
	 */
	public function addHeaders(array $headers) {
		foreach ($headers as $name => $value) {
			$this->addHeader($name, $value);
		}
		return $this;
	}

	/**
	 * Добавляет заголовок
	 *
	 * @param string $name  Название заголовка
	 * @param string $value Значение
	 *
	 * @return \Framework\Response\Http
	 */
	public function addHeader($name, $value) {
		if (is_string($name) && is_string($value)) {
			$name = strtolower(str_replace('_', '-', $name));
			$this->headers[$name] = $value;
		}
		return $this;
	}

	/**
	 * Возвращает заголовки
	 *
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * Отправляет ответ клиенту
	 */
	public function transmit() {
		foreach ($this->headers as $name => $value) {
			header($name.': '.$value);
		}
		echo $this->getContent();
		exit;
	}

}