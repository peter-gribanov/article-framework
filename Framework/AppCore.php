<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework;

use Framework\Response\Json;

use Framework\Factory;
use Framework\Request;
use Framework\Http\Http;
use Framework\Http\NotFound;
use Framework\Exception;
use Framework\Router\Node;
use Framework\Http\Status;
use Framework\Response\Response;
use Framework\Response\Json as JsonResponse;

/**
 * Контроллер распределения запросов
 *
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class AppCore {

	/**
	 * Фабрика
	 *
	 * @var \Framework\Factory
	 */
	private $factory;

	/**
	 * Последняя загруженная нода
	 *
	 * @var \Framework\Router\Node
	 */
	private $last_node;


	/**
	 * Конструктор
	 */
	public function __construct() {
		$root = dirname(__DIR__);
		$this->factory = new Factory(
			$root,
			require $root.'/configs/routing.php',
			require $root.'/configs/global.php'
		);
		error_reporting($this->factory->getConfig('debug') ? E_ALL | E_STRICT : 0);
		set_exception_handler($this->getExceptionHandler());
		//set_error_handler($this->getErrorHandler());
		register_shutdown_function($this->getShutdownHandler());
		session_start();
	}

	/**
	 * Возвращает обработчик исключений
	 *
	 * @return \Closure
	 */
	private function getExceptionHandler() {
		$factory = $this->factory;
		$node    = $this->last_node;
		return function (\Exception $e) use ($factory, $node) {
			// определяем формат ответа
			$present = Response::getDefaultResponse();
			if ($node instanceof Node) { // есть нода
				$present = $node->getPresent();
			} elseif (PHP_SAPI != 'cli') {
				// пытаемся определить формат из запроса. иначе отдаем по умолчанию
				try {
					$url = $factory->getRequest()->server('REQUEST_URI', '/');
					if (($ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) && Response::isSupported($ext)) {
						$present = $ext;
					}
				} catch (\Exception $e) {}
			}

			$response = $factory->getResponse($present, $e->getMessage().($factory->getConfig('debug') ? "\n".$e->getTraceAsString() : ''));

			if (PHP_SAPI != 'cli') {
				if ($e instanceof NotFound) {
					$status = new Status($e->getCode());
				} else {
					$status = new Status(Status::INTERNAL_SERVER_ERROR);
				}

				// описание ошибки
				$data = array(
					'code'    => $e->getCode() ?: Status::INTERNAL_SERVER_ERROR,
					'error'   => Status::getString($status->getCode()),
					'message' => $e->getMessage(),
					'trace'   => $e->getTraceAsString(),
					'debug'   => $factory->getConfig('debug'),
				);

				if ($present == JsonResponse::NAME) {
					$content = json_encode($data);
				} else {
					// пытаемся отрендерить шаблон для ошибки
					try {
						$content = $factory->getView()->assign($data)->render('errors/default.'.$present.'.tpl', true);
					} catch (\Exception $e) {
						$content = $e->getMessage().($factory->getConfig('debug') ? "\n".$e->getTraceAsString() : '');
						$status  = new Status(Status::INTERNAL_SERVER_ERROR);
					}
				}
				$response->setContent($content)->setStatus($status);
			}

			$response->transmit();
			exit(1);
		};
	}

	/**
	 * Возвращает обработчик ошибок
	 *
	 * @return \Closure
	 */
	private function getErrorHandler() {
		$exception_handler = $this->getExceptionHandler();
		return function ($errno, $errstr, $errfile, $errline) {
			throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
		};
	}

	/**
	 * Возвращает обработчик завершения работы
	 *
	 * @return \Closure
	 */
	private function getShutdownHandler() {
		$exception_handler = $this->getExceptionHandler();
		return function () use ($exception_handler) {
			if ($error = error_get_last()) {
				$exception_handler(new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
			}
		};
	}

	/**
	 * Устанавливает запрос
	 *
	 * @param \Framework\Request $request Запрос
	 *
	 * @return \Framework\AppCore
	 */
	public function setRequest(Request $request) {
		$this->factory->setRequest($request);
		return $this;
	}

	/**
	 * Выполняет вызов экшена исходя из запроса
	 *
	 * @throws \Framework\Exception
	 *
	 * @return \Framework\Response\Base
	 */
	public function execute() {
		$request = $this->factory->getRequest();

		$node = $this->factory->getRouter()->getNodeByPattern($request->getPath());

		if (!($node instanceof Node)) {
			throw new NotFound('Страница для запроса "'.$request->getPath().'" не найдена');
		}
		$this->last_node = $node;

		// инициализация контроллера и вызов экшена
		$controller = $node->getController();
		$controller = new $controller($node, $this->factory, $request);

		if (!is_callable(array($controller, ($action = $node->getAction())))) {
			throw new NotFound('Невозможно выполнить действие "'.$action.'" для контроллера "'.get_class($controller).'"');
		}
		$result = $controller->$action();

		// экшен вернул данные которые надо отрендерить
		if (is_array($result) && !$request->isCli()) {
			if ($node->getPresent() == JsonResponse::NAME) {
				$result = json_encode($result);
			} else {
				$result = $this->factory->getView()->assign($result)->render($node->getTemplate(), true);
			}
		}

		// экшен вернул отрендереный шаблон. надо сформировать ответ
		if (is_string($result)) {
			$result = $this->factory->getResponse($node->getPresent(), $result);
		}

		if (!($result instanceof Response)) {
			throw new Exception('Не сформирован ответ');
		}

		return $result;
	}

}