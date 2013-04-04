<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Response;

use Framework\Response\Http;

/**
 * HTML ответ от вызова метода приложения
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Html extends Http {

	/**
	 * Название ответа
	 *
	 * @var string
	 */
	const NAME = 'html';


	/**
	 * Установить новый контент и заголовки
	 *
	 * @param string $data Новый контент
	 */
	public function __construct($data = '') {
		parent::__construct($data);
		$this->addHeader('Content-Type', 'text/html');
	}

}