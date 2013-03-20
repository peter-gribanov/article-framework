<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Microsoft;

use Microsoft\Router\Node;

/**
 * Роутинг запросов
 *
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Router {

	/**
	 * Список роутев
	 *
	 * @var array
	 */
	private $routing = array();


	/**
	 * Конструктор
	 *
	 * @param array $routing Список роутев
	 */
	public function __construct(array $routing = array()) {
		$this->routing = $routing;
	}

	/**
	 * Везвращает ноду по паттерну
	 *
	 * @param string $pattern Паттерн
	 *
	 * @return \Microsoft\Router\Node|null
	 */
	public function getNodeByPattern($pattern) {
		return $this->findNode('pattern', $pattern);
		// TODO добавить поддержку регулярных выражений
	}

	/**
	 * Везвращает ноду по контроллеру
	 *
	 * @param string $controller Контроллер
	 *
	 * @return \Microsoft\Router\Node|null
	 */
	public function getNodeByController($controller) {
		return $this->findNode('controller', $controller);
	}

	/**
	 * Найти ноду по параметру
	 *
	 * @param string $field Поле
	 * @param mixed  $value Значение
	 *
	 * @return \Microsoft\Router\Node|null
	 */
	private function findNode($field, $value) {
		foreach ($this->routing as $node) {
			if ($node[$field] == $value) {
				return new Node($node);
			}
		}
		return null;
	}

}