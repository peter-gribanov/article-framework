<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Router;

use Framework\Request;
use Framework\Router;
use Framework\Router\Node;
use Framework\Exception;

/**
 * URL хелпер
 *
 * @package Framework\Router
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class URLHelper {

	/**
	 * Запрос
	 *
	 * @var \Framework\Request
	 */
	private $request;

	/**
	 * Роутер
	 *
	 * @var \Framework\Router
	 */
	private $router;


	/**
	 * Конструктор
	 *
	 * @param \Framework\Request $request Запрос
	 * @param \Framework\Router  $router  Роутер
	 */
	public function __construct(Request $request, Router $router) {
		$this->request = $request;
		$this->router  = $router;
	}

	/**
	 * Устанавливает запрос
	 *
	 * @param \Framework\Request $request Запрос
	 *
	 * @return \Framework\Router\URLHelper
	 */
	public function setRequest(Request $request) {
		$this->request = $request;
		return $this;
	}

	/**
	 * Возвращает относительный путь к узлу
	 *
	 * @throws \Framework\Exception
	 *
	 * @param string $alias  Алиас
	 * @param array  $params Параметры
	 *
	 * @return string
	 */
	public function getPath($alias, array $params = array()) {
		$node = $this->router->getNodeByAlias($alias);
		if (!($node instanceof Node)) {
			throw new Exception('Узел не найден');
		}
		return $node->getPattern().($params ? '?'.http_build_query($params) : '');
	}

	/**
	 * Возвращает абсолютный путь к узлу
	 *
	 * @throws \Framework\Exception
	 *
	 * @param string $alias  Алиас
	 * @param array  $params Параметры
	 *
	 * @return string
	 */
	public function getUrl($alias, array $params = array()) {
		$protocol = $this->request->isSecureRequest() ? 'https' : 'http';
		return $protocol.'://'.$this->request->server('HTTP_HOST').$this->getPath($alias, $params);
	}

}