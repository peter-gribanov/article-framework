<?php
/**
 * Example PHP Framework
 *
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */


namespace Framework\Response;

/**
 * Какой-либо ответ от вызова метода приложения пригодный несущий часть данных
 *
 * @package Framework\Response
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
abstract class Base {

	/**
	 * Контент
	 *
	 * @var string
	 */
	private $content = null;

	/**
	 * Установить новый контент
	 *
	 * @param string $data Новый контент
	 */
	public function __construct($data = '') {
		$this->content = $data;
	}

	/**
	 * Установить новый контент
	 *
	 * @param string $new_content Новый контент
	 *
	 * @return \Framework\Response\Base
	 */
	public function setContent($new_content) {
		$this->content = $new_content;
		return $this;
	}

	/**
	 * Возвращает контент
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

}