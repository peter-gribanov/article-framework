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


use Framework\Controller\Controller;
use Framework\Router\Node;
use Framework\Factory;
use Framework\Request;
use Microsoft\Api;

/**
 * Базовый контроллепр для приложения Microsoft
 *
 * @package Framework\Controller
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
abstract class Microsoft extends Controller {

	/**
	 * API адаптор
	 *
	 * @var \Microsoft\Api
	 */
	private $api;


	/**
	 * Конструктор
	 *
	 * @param \Framework\Router\Node $node    Нода
	 * @param \Framework\Factory     $factory Фабрика
	 * @param \Framework\Request     $request Запрос
	 */
	public function __construct(Node $node, Factory $factory, Request $request) {
		parent::__construct($node, $factory, $request);
		$this->api = new Api(
			$factory->getConfig('api.code', ''),
			$factory->getConfig('api.secret', ''),
			$request->getRootUrl(),
			$factory->getConfig('api.can_authorize', false)
		);

		// авторезация приложения
		if ($code = $request->get('code')) {
			$this->api->authorize($code);
		}
		// проверка авторезированности приложения
		$this->api->checkAuthoriz();
	}

	/**
	 * Возвращает представление
	 *
	 * @return \Microsoft\Api
	 */
	public function getApi() {
		return $this->api;
	}

}