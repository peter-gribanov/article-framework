<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework;

use Framework\Factory;
use Framework\Request;
use Framework\Http\NotFound;
use Framework\Exception;
use Framework\Router\Node;
use Framework\Http\Status;
use Framework\Response\Base as BaseResponse;

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
	 * Запрос
	 *
	 * @var \Framework\Request
	 */
	private $request;


	/**
	 * Конструктор
	 */
	public function __construct() {
		set_exception_handler($this->getExceptionHandler());
		set_error_handler($this->getErrorHandler());
		session_start();
		$this->factory = new Factory(
			dirname(__DIR__),
			require dirname(__DIR__).'/routing.php'
		);
	}

	/**
	 * Возвращает обработчик исключений
	 *
	 * @return \Closure
	 */
	private function getExceptionHandler() {
		$factory = $this->factory;
		return function (\Exception $e) use ($factory) {
			$message = $e->getMessage();
			if ($e instanceof \Framework\Http\NotFound) {
				$status = new Status(Status::NOT_FOUND);
			} elseif ($e instanceof \Pro_Api_Exception) {
				$status = new Status(Status::INTERNAL_SERVER_ERROR);
				$message = $e->getDescription();
			} else {
				$status = new Status(Status::INTERNAL_SERVER_ERROR);
			}

			try {
				header($status->getStringStatus());
				$error = Status::getString($status->getCode());
				$content = $factory->View()
					->render(array('html.html.tpl', 'error.html.tpl'), array(
						'page_title' => 'Ошибка: '.$error,
						'error'      => $error,
						'message'    => $message,
						'code'       => $e->getCode() ?: Status::INTERNAL_SERVER_ERROR
					));
			} catch (\Exception $e) {}

			echo $content ?: 'Error in template';
			exit(1);
		};
	}

	/**
	 * Возвращает обработчик ошибок
	 *
	 * @return \Closure
	 */
	private function getErrorHandler() {
		return function ($errno, $errstr, $errfile, $errline) {
			throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
		};
	}

	/**
	 * Устанавливает запрос
	 *
	 * @param \Framework\Request $request Запрос
	 *
	 * @return \Framework\App
	 */
	public function setRequest(Request $request) {
		$this->request = $request;
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
		if (!($this->request instanceof Request)) {
			throw new Exception('Не установлен запрос');
		}

		// авторезация приложения
		if ($code = $this->request->get('code')) {
			$this->factory->API()->authorize($code);
		}

		// проверка авторезированности приложения
		$this->factory->API()->checkAuthoriz();

		$node = $this->factory->Router()->getNodeByPattern($this->request->server('REQUEST_URI', '/'));

		if (!($node instanceof Node)) {
			throw new NotFound('Страница не найдена');
		}

		// инициализация контроллера и вызов экшена
		$controller = $node->getController();
		$controller = new $controller();

		if (!is_callable(array($controller, ($action = $node->getAction())))) {
			throw new NotFound('Страница не найдена');
		}
		$result = $controller->$action();

		// экшен вернул данные которые. надо отрендерить
		if (is_array($result)) {
			// TODO требуется реализация
			//$node->getPresent();
			//$node->getTemplates();
		}

		// экшен вернул отрендереный шаблон. надо сформировать ответ
		if (!($result instanceof BaseResponse)) {
			// TODO требуется реализация
		}

		return $result;
	}

}