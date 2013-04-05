<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\Controller;


use Framework\Router\Node;
use Framework\Factory;
use Framework\Response\Response;
use Framework\Request;
use Framework\Router\URLHelper;

/**
 * Базовый контроллепр
 *
 * @package Framework\Controller
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Controller {

	/**
	 * Нода
	 *
	 * @var \Framework\Router\Node
	 */
	private $node;

	/**
	 * Фабрика
	 *
	 * @var \Framework\Factory
	 */
	private $factory;

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
	 * @param \Framework\Router\Node $node    Нода
	 * @param \Framework\Factory     $factory Фабрика
	 * @param \Framework\Request     $request Запрос
	 */
	public function __construct(Node $node, Factory $factory, Request $request) {
		$this->node    = $node;
		$this->factory = $factory;
		$this->request = $request;
	}

	/**
	 * Возвращает представление
	 *
	 * @return \Framework\View\Iface
	 */
	public function getView() {
		return $this->factory->getView();
	}

	/**
	 * Возвращает объект ответа
	 *
	 * @param mixed $content Контент
	 *
	 * @return \Framework\Response\Response
	 */
	public function getResponse($content = '') {
		return $this->factory->getResponse($this->node->getPresent(), $content);
	}

	/**
	 * Возвращает запрос
	 *
	 * @return \Framework\Request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Возвращает URL хелпер
	 *
	 * @return \Framework\Router\URLHelper
	 */
	public function getURLHelper() {
		return $this->factory->getURLHelper();
	}

	/**
	 * Возвращает параметр из конфигураций
	 *
	 * @param string $param Название параметра
	 *
	 * @return mixed
	 */
	public function getConfig($param) {
		return $this->factory->getConfig($param);
	}

}