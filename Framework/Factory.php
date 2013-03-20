<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework;

use Framework\API;
use Framework\View;
use Framework\Router;

/**
 * Райтинг
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Factory {

	/**
	 * Обертка для клиента API
	 *
	 * @var \Framework\API
	 */
	private $api;

	/**
	 * Представление
	 *
	 * @var \Framework\View
	 */
	private $view;

	/**
	 * Роутер
	 *
	 * @var \Framework\Router
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
	 * @return \Framework\API
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
	 * @return \Framework\View
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
	 * @return \Framework\Router
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