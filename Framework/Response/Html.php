<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Response;

use Framework\Response\Http as HttpResponse;

/**
 * HTML ответ от вызова метода приложения
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Http extends HttpResponse {

	/**
	 * Установить новый контент и заголовки
	 *
	 * @param string $data Новый контент
	 */
	public function __construct($data = '') {
		parent::__construct($data);
		$this->addHeader('content-type', 'text/html');
	}

}