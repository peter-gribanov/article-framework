<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Http;

use Framework\Http\Status;

/**
 * Исключение NotFound
 * 
 * @package Framework\Http
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class NotFound extends \Framework\Exception {

	/**
	 * Конструктор
	 * 
	 * @param string|null $message Сообщение
	 */
	public function __construct($message = '') {
		parent::__construct($message, Status::NOT_FOUND);
	}

}