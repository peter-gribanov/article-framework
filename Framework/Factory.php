<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework;

use Framework\View\Php as View;
use Framework\Router;
use Framework\Request;
use Framework\Router\URLHelper;
use Framework\Exception;
use Framework\Utility\Arr as ArrayUtility;

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
	 * Конфигурации
	 *
	 * @var array
	 */
	private $config = array();

	/**
	 * Запрос
	 *
	 * @var \Framework\Request
	 */
	private $request;

	/**
	 * URL хелпер
	 *
	 * @var \Framework\Router\URLHelper
	 */
	private $url_helper;


	/**
	 * Конструктор
	 *
	 * @param string $dir     Корневая дирректория
	 * @param array  $routing Роутинг
	 * @param array  $config  Конфигурации
	 */
	public function __construct($dir, array $routing = array(), array $config = array()) {
		$this->router = new Router($routing);
		$this->dir    = $dir;
		$this->config = $config;
	}

	/**
	 * Представление
	 *
	 * @return \Framework\View\Iface
	 */
	public function getView() {
		if (!$this->view) {
			$this->view = new View(
				$this->getDir().'/resources/templates',
				$this->getDir().'/resources/helpers',
				$this->getURLHelper(),
				$this->getConfig('debug')
			);
		}
		return $this->view;
	}

	/**
	 * Роутер
	 *
	 * @return \Framework\Router
	 */
	public function getRouter() {
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

	/**
	 * Возвращает объект ответа
	 *
	 * @param string $present Формат ответа
	 * @param mixed  $content Контент
	 *
	 * @return \Framework\Response\Response
	 */
	public function getResponse($present, $content = '') {
		$classname = '\Framework\Response\\'.ucwords($present);
		return new $classname($content);
	}

	/**
	 * Возвращает параметр из конфигураций
	 *
	 * @param string     $param   Название параметра
	 * @param mixed|null $default Значение по умолчанию
	 *
	 * @return mixed
	 */
	public function getConfig($param, $default = null) {
		return ArrayUtility::getByPath($this->config, $param, $default);
	}

	/**
	 * Устанавливает запрос
	 *
	 * @param \Framework\Request $request Запрос
	 *
	 * @return \Framework\Factory
	 */
	public function setRequest(Request $request) {
		$this->request = $request;
		return $this;
	}

	/**
	 * Возвращает запрос
	 *
	 * @return \Framework\Request
	 */
	public function getRequest() {
		if (!($this->request instanceof Request)) {
			throw new Exception('Не установлен запрос');
		}
		return $this->request;
	}

	/**
	 * Возвращает URL хелпер
	 *
	 * @return \Framework\Router\URLHelper
	 */
	public function getURLHelper() {
		if (!($this->url_helper instanceof URLHelper)) {
			$this->url_helper = new URLHelper($this->getRequest(), $this->getRouter());
		}
		return $this->url_helper;
	}

}