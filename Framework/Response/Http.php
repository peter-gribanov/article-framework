<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Response;

use Framework\Response\Response;
use Framework\Http\Status;
/**
 * Ответ от вызова метода приложения отправляемый по HTTP(S) протоколу
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
abstract class Http extends Response {

	/**
	 * Название ответа
	 *
	 * @var string
	 */
	const NAME = 'http';


	/**
	 * Список заголовков
	 *
	 * @var array
	 */
	private $headers = array();

	/**
	 * Статус ответа
	 *
	 * @var \Framework\Http\Status
	 */
	private $status;


	/**
	 * Установить новый контент
	 *
	 * @param string $data Новый контент
	 */
	public function __construct($data = '') {
		parent::__construct($data);
		$this->setStatus(new Status());
		$this->addHeader('Content-Type', 'text/plain');
	}

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
	 * @param string                        $name  Название заголовка
	 * @param string|\Framework\Http\Status $value Значение
	 *
	 * @return \Framework\Response\Http
	 */
	public function addHeader($name, $value) {
		if ($value instanceof Status) {
			$this->setStatus($value);
		} elseif (is_string($name) && is_string($value)) {
			$this->headers[strtolower(str_replace('_', '-', $name))] = $name.': '.$value;
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
	 * Устанавливает статус ответа
	 *
	 * @param \Framework\Http\Status $status Статус
	 *
	 * @return \Framework\Response\Http
	 */
	public function setStatus(Status $status) {
		$this->status = $status;
		return $this;
	}

	/**
	 * Возвращает статус ответа
	 *
	 * @return \Framework\Http\Status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Отправляет ответ клиенту
	 */
	public function transmit() {
		if (!headers_sent()) {
			header($this->status->getStringStatus());
			foreach ($this->headers as $value) {
				header($value);
			}
		}
		echo $this->getContent();
	}

}