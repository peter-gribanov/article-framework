<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Response;

use Framework\Response\Response;

/**
 * Ответ от приложения направляемый в консоль
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Cli extends Response {

	/**
	 * Название ответа
	 *
	 * @var string
	 */
	const NAME = 'cli';


	/**
	 * Отправляет ответ клиенту
	 */
	public function transmit() {
		echo $this->getContent();
	}

}