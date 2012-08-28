<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

namespace Framework\Controller;

use Framework\Model\Factory;

/**
 * Добашний пакет
 * 
 * @package Framework\Controller
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Home {

	/**
	 * Фабрика моделей
	 * 
	 * @var Framework\Model\Factory
	 */
	private $factory;


	/**
	 * Констуктор
	 * 
	 * @param Framework\Model\Factory $factory Фабрика моделей
	 */
	public function __construct(Factory $factory) {
		$this->factory = $factory;
	}

	/**
	 * Главная
	 * 
	 * @return array
	 */
	public function __index() {
		// получение данных о пользователе с id = 123
		return array(
			'user' => $this->factory->Users()->get(123)
		);
	}

}