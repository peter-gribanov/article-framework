<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Http;

use Framework\Exception;
use Framework\Http\Status;

/**
 * Базовое HTTP исключение
 * 
 * @package Framework\Http
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
abstract class Http extends Exception {

	/**
	 * Конструктор
	 * 
	 * @param string|null  $message Сообщение
	 * @param integer|null $code    Код ошибки
	 */
	public function __construct($message = '', $code = null) {
		parent::__construct($message, $code ?: Status::INTERNAL_SERVER_ERROR);
	}

}