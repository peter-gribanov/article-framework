<?php
/**
 * Example PHP Framework
 *
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

namespace Framework\Http;

/**
 * Исключение NotFound
 * 
 * @package Framework\Http
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class NotFound extends \Exception {

	/**
	 * Конструктор
	 * 
	 * @param string|null $message Сообщение
	 */
	public function __construct($message = '') {
		parent::__construct($message, 404);
	}

}