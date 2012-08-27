<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

namespace Framework;

/**
 * Представление
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Factory {

	/**
	 * Представление
	 * 
	 * @var Framework\View\Inface
	 */
	private $view;

	/**
	 * Фабрика моделей
	 * 
	 * @var Framework\Model\Factory
	 */
	private $model;

	/**
	 * Корневая папка проекта
	 * 
	 * @var string
	 */
	private $root;


	/**
	 * Конструктор
	 * 
	 * @param string $root Корневая папка проекта
	 */
	public function __construct($root) {
		$this->root = $root;
	}

	/**
	 * Представление
	 * 
	 * @return Framework\View\Inface
	 */
	public function View() {
		if (!($this->view instanceof Framework\View\Inface)) {
			$this->view = new Framework\View\View($factory);
		}
		return $this->view;
	}

	/**
	 * Корневая папка проекта
	 * 
	 * @return string
	 */
	public function getDir() {
		return $this->root;
	}

	/**
	 * Возращает модель или фабрику моделей
	 * 
	 * @param string|null $name Название модели
	 * 
	 * @return Framework\Model\Factory
	 */
	public function getModel($name = null) {
		if (!$this->model) {
			$this->model = new Framework\Model\Factory();
		}
		return $name ? $this->model->get($name) : $this->model;
	}

}