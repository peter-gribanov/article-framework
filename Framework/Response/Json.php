<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Response;

use Framework\Response\Http as HttpResponse;
use Framework\Exception;

/**
 * JSON ответ от вызова метода приложения
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Json extends HttpResponse {

	/**
	 * Установить новый контент и заголовки
	 *
	 * @param string $data Новый контент
	 */
	public function __construct($data = '') {
		parent::__construct($data);
		$this->addHeader('content-type', 'application/json');
	}

	/**
	 * Установить новый контент
	 *
	 * @param mixed $new_content Новый контент
	 *
	 * @return \Framework\Response\Base
	 */
	public function setContent($new_content) {
		if ($new_content && is_string($new_content) && json_decode($new_content, true) === null) {
			$new_content = json_encode($new_content);
			if ($new_content === false) {
				throw new Exception($this->getError());
			}
		}
		$this->content = $new_content;
		return $this;
	}

	/**
	 * Возвращает текст ошибки
	 *
	 * @return string
	 */
	private function getError() {
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return 'Ошибок нет';
			case JSON_ERROR_DEPTH:
				return 'Достигнута максимальная глубина стека';
			case JSON_ERROR_STATE_MISMATCH:
				return 'Некорректные разряды или не совпадение режимов';
			case JSON_ERROR_CTRL_CHAR:
				return 'Некорректный управляющий символ';
			case JSON_ERROR_SYNTAX:
				return 'Синтаксическая ошибка, не корректный JSON';
			case JSON_ERROR_UTF8:
				return 'Некорректные символы UTF-8, возможно неверная кодировка';
			default:
				return 'Неизвестная ошибка';
		}
	}

}