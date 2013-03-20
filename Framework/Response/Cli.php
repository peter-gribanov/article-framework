<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Response;

use Framework\Response\Base as BaseResponse;

/**
 * Ответ от приложения направляемый в консоль
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Cli extends BaseResponse {

	/**
	 * Отправляет ответ клиенту
	 */
	public function transmit() {
		echo $this->getContent();
	}

}