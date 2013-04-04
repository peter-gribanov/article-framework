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
 * XML ответ от вызова метода приложения
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Xml extends Http {

	/**
	 * Название ответа
	 *
	 * @var string
	 */
	const NAME = 'xml';


	/**
	 * Установить новый контент и заголовки
	 *
	 * @param string $data Новый контент
	 */
	public function __construct($data = '') {
		parent::__construct($data);
		$this->addHeader('Content-Type', 'application/xml');
	}

}