<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\Response;

use Framework\Response\Cli;
use Framework\Response\Html;
use Framework\Response\Http;
use Framework\Response\Json;
use Framework\Response\Xml;

/**
 * Какой-либо ответ от вызова метода приложения пригодный несущий часть данных
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
abstract class Response {

	/**
	 * Название ответа
	 *
	 * @var string
	 */
	const NAME = 'response';

	/**
	 * Ответ по умолчанию для HTTP(S) запроса
	 *
	 * @var string
	 */
	const DEFAULT_HTTP = Html::NAME;

	/**
	 * Ответ по умолчанию для CLI запроса
	 *
	 * @var string
	 */
	const DEFAULT_CLI = Cli::NAME;


	/**
	 * Контент
	 *
	 * @var string
	 */
	private $content = null;

	/**
	 * Список поддерживаемых форматов ответа для CLI запроса
	 *
	 * @var array
	 */
	private static $cli_responses = array(
		Cli::NAME,
	);

	/**
	 * Список поддерживаемых форматов ответа для HTTP(S) запроса
	 *
	 * @var array
	 */
	private static $http_responses = array(
		Html::NAME,
		Json::NAME,
		Xml::NAME,
	);


	/**
	 * Установить новый контент
	 *
	 * @param string $data Новый контент
	 */
	public function __construct($data = '') {
		$this->setContent($data);
	}

	/**
	 * Установить новый контент
	 *
	 * @param string $new_content Новый контент
	 *
	 * @return \Framework\Response\Response
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

	/**
	 * Отправляет ответ клиенту
	 */
	abstract public function transmit();

	/**
	 * Проверяет поддерживается ли формат ответа
	 * 
	 * @param string $name Формат ответа
	 *
	 * @return boolean
	 */
	public static function isSupported($name) {
		return in_array($name, (PHP_SAPI == 'cli' ? self::$cli_responses : self::$http_responses));
	}

	/**
	 * Возвращает формат ответа по умолчанию
	 *
	 * @return string
	 */
	public static function getDefaultResponse() {
		return (PHP_SAPI == 'cli' ? self::DEFAULT_CLI : self::DEFAULT_HTTP);
	}

}