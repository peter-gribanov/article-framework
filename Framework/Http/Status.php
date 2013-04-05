<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\Http;


/**
 * Http статус
 *
 * @package Framework\Http
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Status {

	/**
	 * Перемещено окончательно
	 *
	 * @var integer
	 */
	const MOVED_PERMAMENTLY = 301;

	/**
	 * Найдено
	 *
	 * @var integer
	 */
	const FOUND = 302;

	/**
	 * Смотреть другое
	 *
	 * @var integer
	 */
	const SEE_OTHER = 303;

	/**
	 * Не изменялось
	 *
	 * @var integer
	 */
	const NOT_MODIFIED = 304;

	/**
	 * Плохой запрос
	 *
	 * @var integer
	 */
	const BAD_REQUEST = 400;

	/**
	 * Не авторизован
	 *
	 * @var integer
	 */
	const UNAUTHORIZED = 401;

	/**
	 * Необходима оплата
	 *
	 * @var integer
	 */
	const PAYMENT_REQUIRED = 402;

	/**
	 * Запрещено
	 *
	 * @var integer
	 */
	const FORBIDDEN = 403;

	/**
	 * Не найдено
	 *
	 * @var integer
	 */
	const NOT_FOUND = 404;

	/**
	 * Метод не применим
	 *
	 * @var integer
	 */
	const METHOD_NOT_ALLOWED = 405;

	/**
	 * Не приемлемо
	 *
	 * @var integer
	 */
	const NOT_ACCEPTABLE = 406;

	/**
	 * Неподдерживаемый тип данных
	 *
	 * @var integer
	 */
	const UNSUPPORTED_MEDIA_TYPE = 415;

	/**
	 * Просмотр этой страницы запрещен органами власти
	 *
	 * @var integer
	 */
	const UNAVAILABLE_FOR_LEGAL_REASONS = 451;

	/**
	 * Внутренняя ошибка сервера
	 *
	 * @var integer
	 */
	const INTERNAL_SERVER_ERROR = 500;

	/**
	 * Сервис недоступен
	 *
	 * @var integer
	 */
	const SERVICE_UNAVAILABLE = 503;

	/**
	 * Нет контента
	 *
	 * @var integer
	 */
	const NO_CONTENT = 204;


	/**
	 * Возможные http статусы
	 *
	 * @var array
	 */
	private static $status = array(
		100 => '100 Continue',
		101 => '101 Switching Protocols',
		102 => '102 Processing',
		200 => '200 OK',
		201 => '201 Created',
		202 => '202 Accepted',
		203 => '203 Non-Authoritative Information',
		204 => '204 No Content',
		205 => '205 Reset Content',
		206 => '206 Partial Content',
		207 => '207 Multi Status',
		226 => '226 IM Used',
		300 => '300 Multiple Choices',
		301 => '301 Moved Permanently',
		302 => '302 Found',
		303 => '303 See Other',
		304 => '304 Not Modified',
		305 => '305 Use Proxy',
		306 => '306 (Unused)',
		307 => '307 Temporary Redirect',
		400 => '400 Bad Request',
		401 => '401 Unauthorized',
		402 => '402 Payment Required',
		403 => '403 Forbidden',
		404 => '404 Not Found',
		405 => '405 Method Not Allowed',
		406 => '406 Not Acceptable',
		407 => '407 Proxy Authentication Required',
		408 => '408 Request Timeout',
		409 => '409 Conflict',
		410 => '410 Gone',
		411 => '411 Length Required',
		412 => '412 Precondition Failed',
		413 => '413 Request Entity Too Large',
		414 => '414 Request-URI Too Long',
		415 => '415 Unsupported Media Type',
		416 => '416 Requested Range Not Satisfiable',
		417 => '417 Expectation Failed',
		420 => '420 Policy Not Fulfilled',
		421 => '421 Bad Mapping',
		422 => '422 Unprocessable Entity',
		423 => '423 Locked',
		424 => '424 Failed Dependency',
		426 => '426 Upgrade Required',
		449 => '449 Retry With',
		451 => '451 Unavailable For Legal Reasons',
		500 => '500 Internal Server Error',
		501 => '501 Not Implemented',
		502 => '502 Bad Gateway',
		503 => '503 Service Unavailable',
		504 => '504 Gateway Timeout',
		505 => '505 HTTP Version Not Supported',
		506 => '506 Variant Also Varies',
		507 => '507 Insufficient Storage',
		509 => '509 Bandwidth Limit Exceeded',
		510 => '510 Not Extended'
	);

	/**
	 * Текущий статус
	 *
	 * @var integer
	 */
	private $code = 200;


	/**
	 * Конструтор
	 *
	 * @throws \ErrorException
	 *
	 * @param integer $code код статуса
	 */
	public function __construct($code = 200) {
		if (isset(self::$status[$code])) {
			$this->code = $code;
		} else {
			throw new \ErrorException('Unknown status code!');
		}
	}

	/**
	 * Получить строку статуса
	 *
	 * @param integer $code код статуса
	 *
	 * @return string
	 */
	static public function getString($code) {
		return self::$status[$code];
	}

	/**
	 * Получить код статуса
	 *
	 * @return integer
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * Получить строку статуса
	 *
	 * @return string
	 */
	public function getStringStatus() {
		$header = (php_sapi_name() != 'cgi') ? 'HTTP/1.0 ' : 'Status: ';
		return $header.self::$status[$this->code];
	}

}