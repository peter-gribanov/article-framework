<?php

/**
 * Исключение API
 */
class Pro_Api_Exception extends Exception {

	/**
	 * Ошибка
	 *
	 * @var string
	 */
	private $error;

	/**
	 * Описание ошибки
	 *
	 * @var string
	 */
	private $description;


	/**
	 * Конструктор
	 *
	 * @param string  $error
	 * @param string  $description
	 * @param integer $code
	 */
	public function __construct($error, $description, $code) {
		$this->error = $error;
		$this->description = $description;
		parent::__construct($error, $code);
	}

	/**
	 * Получить ошибку
	 *
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * Получить описание
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

}