<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Microsoft\Http;

use Microsoft\Http\Status;

/**
 * Исключение NotFound
 * 
 * @package Microsoft\Http
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class NotFound extends \Microsoft\Exception {

	/**
	 * Конструктор
	 * 
	 * @param string|null $message Сообщение
	 */
	public function __construct($message = '') {
		parent::__construct($message, Status::NOT_FOUND);
	}

}