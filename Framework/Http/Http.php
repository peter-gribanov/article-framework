<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
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