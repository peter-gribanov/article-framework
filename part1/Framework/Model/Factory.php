<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

namespace Framework\Model;

/**
 * фабрика моделей
 * 
 * @package Framework\Model
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Factory {

	/**
	 * Список загруженных моделей
	 * 
	 * @var array
	 */
	private $models = array();


	/**
	 * Получение модели по имени
	 * 
	 * @param string $name Название модели
	 */
	public function get($name) {
		if (!isset($this->models[$name])) {
			$this->models[$name] = new $name();
		}
		return $this->models[$name];
	}

	/**
	 * Модель пользователей
	 * 
	 * @return Framework\Model\Users
	 */
	public function Users() {
		return $this->get('Users');
	}

}