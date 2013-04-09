<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Microsoft;

/**
 * Обертка для клиента API
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Api {

	/**
	 * Какую версию API использовать
	 *
	 * @var integer
	 */
	const USE_API_VERSION = 5;

	/**
	 * Методы Api
	 *
	 * @var string
	 */
	const METHOD_GET_CURRENT = \Pro_Api_Client::POINT_GET_CURRENT;

	const METHOD_USER_GET = '/users/get.json?fields[]=id&fields[]=name&fields[]=link&fields[]=avatar_big';

	const METHOD_TAPE = '/tape/new.json';

	const METHOD_IS_ADMIN = '/users/isAdmin.json';

	const METHOD_CAN_INVITE = '/invites/getCanAppInvite.json';

	const METHOD_INVITE_NEW = '/invites/new.json';

	/**
	 * Время жизни токена по умоляанию
	 *
	 * @var integer
	 */
	const EXPIRES_DEFAULT = 3600;


	/**
	 * Список используемых методов API
	 *
	 * @var array
	 */
	public $methods = array(
		self::METHOD_GET_CURRENT,
		self::METHOD_USER_GET,
		self::METHOD_TAPE,
		self::METHOD_IS_ADMIN,
		self::METHOD_CAN_INVITE,
	);

	/**
	 * Список методов API которые отпроавляются методом POST
	 *
	 * @var array
	 */
	public $method_as_post = array(
		self::METHOD_TAPE,
		self::METHOD_INVITE_NEW,
	);

	/**
	 * API клиент
	 *
	 * @var \Pro_Api_Client
	 */
	private $client = null;

	/**
	 * URL к корню проекта
	 *
	 * @var string
	 */
	private $root_url = '';

	/**
	 * Приложение может самостоятельно авторезироваться
	 *
	 * @var string
	 */
	private $can_authorize = false;


	/**
	 * Конструктор
	 *
	 * @param string  $app_code      Код приложения
	 * @param string  $app_secret    Секретный код проложения
	 * @param string  $root_url      URL к корню проекта
	 * @param boolean $can_authorize Приложение может самостоятельно авторезироваться
	 */
	public function __construct($app_code, $app_secret, $root_url = 'http://localhost/', $can_authorize = false) {
		$this->root_url      = $root_url;
		$this->can_authorize = $can_authorize;

		$token   = null;
		$expires = time()+self::EXPIRES_DEFAULT;
		if (PHP_SAPI != 'cli') {
			if (!empty($_SESSION['token'])) {
				$token = &$_SESSION['token'];
			}
			if (!empty($_SESSION['expires'])) {
				$expires = &$_SESSION['expires'];
			}
		}
		$this->client = new \Pro_Api_Client($app_code, $app_secret, $token, $expires);
	}

	/**
	 * Проверить авторезировано ли приложение
	 */
	public function checkAuthoriz() {
		if (!$this->client->getAccessToken()) {
			if ($this->can_authorize && PHP_SAPI != 'cli') {
				// редирект на страницу авторизации
				header('Location: '.$this->client->getAuthenticationUrl($this->root_url), true, 301);
				exit;
			} else {
				throw new \Exception('Приложение не авторезированно');
			}
		} elseif ($this->client->isExpiresAccessToken()) {
			$this->client->refreshAccessToken();
		}
	}

	/**
	 * Авторезирование приложения
	 */
	public function authorize($code) {
		$this->client->getAccessTokenFromCode($code, $this->root_url);
		$this->save();
		// Редиректим на себя же, чтоб убрать код из GET параметра
		header('Location: /', true, 301);
		exit();
	}

	/**
	 * Выполнение запроса
	 * 
	 * @param string     $method Метод
	 * @param array|null $params Параметры запроса
	 * 
	 * @return array
	 */
	public function fetch($method, $params = array()) {
		if (!$this->client->getAccessToken()) {
			throw new \Exception('Необходимо авторезироваться');
		}

		try {
			$response = $this->client->fetch(
				$this->buildPath($method),
				$params,
				in_array($method, $this->method_as_post) ? \Pro_Api_Client::HTTP_POST : \Pro_Api_Client::HTTP_GET
			);
		} catch (\Pro_Api_Exception $e) {
			// если неверный токен пытаемся авторезироваться повторно если можно
			if ($e->getError() == 'invalid_token' || $e->getError() == 'undefined_token') {
				$_SESSION['token'] = $_SESSION['expires'] = null;
				$this->checkAuthoriz();
			} else {
				throw $e;
			}
		}

		return $response['result'];
	}

	/**
	 * Построение пути к методу API
	 * 
	 * @param string $method Метод
	 * 
	 * @return string
	 */
	private function buildPath($method) {
		if (!in_array($method, $this->methods)) {
			throw new \Exception('Неизвестный метод');
		}
		return \Pro_Api_Client::API_HOST.'/v'.self::USE_API_VERSION.$method;
	}

	/**
	 * Сохранение информации о токене
	 *
	 * @return \Microsoft\API
	 */
	public function save() {
		if (PHP_SAPI != 'cli') {
			$_SESSION['token']   = $this->client->getAccessToken();
			$_SESSION['expires'] = $this->client->getExpires();
		}
		return $this;
	}

	/**
	 * Возвращает API клиент
	 *
	 * @return \Pro_Api_Client
	 */
	public function getCliet() {
		return $this->client;
	}

	/**
	 * Деструктор
	 */
	public function __destruct() {
		$this->save();
	}

}