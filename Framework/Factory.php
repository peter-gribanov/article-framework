<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Microsoft;

use Microsoft\API;
use Microsoft\View;
use Microsoft\Router;

/**
 * Райтинг
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Factory {

	/**
	 * Обертка для клиента API
	 *
	 * @var \Microsoft\API
	 */
	private $api;

	/**
	 * Представление
	 *
	 * @var \Microsoft\View
	 */
	private $view;

	/**
	 * Роутер
	 *
	 * @var \Microsoft\Router
	 */
	private $router;

	/**
	 * Корневая дирректория
	 *
	 * @var string
	 */
	private $dir = '';


	/**
	 * Конструктор
	 *
	 * @param string $dir     Корневая дирректория
	 * @param array  $routing Роутинг
	 */
	public function __construct($dir, array $routing = array()) {
		$this->router = new Router($routing);
		$this->dir    = $dir;
	}

	/**
	 * Обертка для клиента API
	 *
	 * @return \Microsoft\API
	 */
	public function API() {
		if (!$this->api) {
			$this->api = new API();
		}
		return $this->api;
	}

	/**
	 * Представление
	 *
	 * @return \Microsoft\View
	 */
	public function View() {
		if (!$this->view) {
			$this->view = new View($this->getDir());
		}
		return $this->view;
	}

	/**
	 * Роутер
	 *
	 * @return \Microsoft\Router
	 */
	public function Router() {
		return $this->router;
	}

	/**
	 * Возвращает корневую дирректорию
	 *
	 * @return string
	 */
	public function getDir() {
		return $this->dir;
	}

}