<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Microsoft\Router;


/**
 * Нода роутинга запросов
 *
 * @package Microsoft\Router
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Node {

	/**
	 * Описание ноды
	 *
	 * @var array
	 */
	private $node = array();


	/**
	 * Конструктор
	 *
	 * @param array $node Описание ноды
	 */
	public function __construct(array $node = array()) {
		$this->node = $node;
	}

	/**
	 * Везвращает паттерн
	 *
	 * @return string
	 */
	public function getPattern() {
		return $this->node['pattern'];
	}

	/**
	 * Везвращает контроллер
	 *
	 * @return string
	 */
	public function getController() {
		list($controller, ) = explode('::', $this->node['controller']);
		return '\Microsoft\Controller\\'.$controller;
	}

	/**
	 * Везвращает экшен
	 *
	 * @return string
	 */
	public function getAction() {
		list(, $action) = explode('::', $this->node['controller']);
		return $action.'Action';
	}

	/**
	 * Везвращает формат представления
	 *
	 * @return string
	 */
	public function getPresent() {
		return !empty($this->node['present']) ? $this->node['present'] : 'html';
	}

	/**
	 * Везвращает список шаблонов для форматирования вывода
	 *
	 * @return array
	 */
	public function getTemplates() {
		if (!empty($this->node['templates'])) {
			return (array)$this->node['templates'];
		}
		list($controller, $action) = explode('::', $this->node['controller']);
		return array(strtolower($controller).'/'.strtolower($action).'.'.$this->getPresent().'.tpl');
	}

}