<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\Router;

use Framework\Response\Response;


/**
 * Нода роутинга запросов
 *
 * @package Framework\Router
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Node {

	/**
	 * Псевдоним ноды
	 *
	 * @var string
	 */
	private $alias = '';

	/**
	 * Паттерн
	 *
	 * @var string
	 */
	private $pattern = '/';

	/**
	 * Контроллер
	 *
	 * @var string
	 */
	private $controller = '';

	/**
	 * Экшен
	 *
	 * @var string
	 */
	private $action = '';

	/**
	 * Формат представления
	 *
	 * @var string
	 */
	private $present = '';

	/**
	 * Шаблон
	 *
	 * @var string
	 */
	private $template = '';


	/**
	 * Конструктор
	 *
	 * @param array  $node  Описание ноды
	 * @param string $alias Псевдоним ноды
	 */
	public function __construct(array $node = array(), $alias) {
		$this->alias   = $alias;
		$this->pattern = $node['pattern'];

		// формат представления
		if (!empty($node['present']) && Response::isSupported($node['present'])) {
			$this->present = $node['present'];
		} elseif (!empty($node['pattern']) && ($ext = pathinfo($node['pattern'], PATHINFO_EXTENSION)) && Response::isSupported($ext)) {
			$this->present = $ext;
		} else {
			$this->present = Response::getDefaultResponse();
		}

		// контроллер и экшен
		list($controller, $action) = explode('::', $node['controller']);
		$this->controller = '\Framework\Controller\\'.$controller;
		$this->action     = $action.'Action';

		// шаблон
		if (!empty($node['template'])) {
			$this->template = $node['template'];
		} else {
			$this->template = $controller.'/'.$action.'.'.$this->getPresent().'.tpl';
		}
	}

	/**
	 * Везвращает паттерн
	 *
	 * @return string
	 */
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * Везвращает контроллер
	 *
	 * @return string
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * Везвращает экшен
	 *
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Везвращает формат представления
	 *
	 * @return string
	 */
	public function getPresent() {
		return $this->present;
	}

	/**
	 * Везвращает шаблон для форматирования вывода
	 *
	 * @return string
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Везвращает псевдоним ноды
	 *
	 * @return string
	 */
	public function getAlias() {
		return $this->alias;
	}

}