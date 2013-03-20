<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Microsoft\Response;

use Microsoft\Response\Base as BaseResponse;

/**
 * Ответ от приложения направляемый в консоль
 *
 * @package Microsoft
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