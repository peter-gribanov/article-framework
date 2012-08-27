<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
namespace Framework\View;

/**
 * Представление
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class View {

	/**
	 * Список хелперов
	 * 
	 * @var array
	 */
	private static $helpers = array();

	/**
	 * Скини по умолчанию
	 * 
	 * @var string
	 */
	const DEFAULT_SKIN = '.default';


	/**
	 * Конструктор
	 */
	public function __construct() {
		$skin = &$this->skin;
		self::setHelper('path', function ($template) use ($skin) {
			return FORM_MANAGER_HTTP_PATH.'templates/'.$skin.'/'.$template;
		});

		self::setHelper('url', function ($path) {
			return FORM_MANAGER_HTTP_PATH.$path;
		});

		$view = $this;
		self::setHelper('inc', function ($template, array $vars = array()) use ($view){
			return $view->render((array)$vars, $template);
		});
	}

	/**
	 * Добавляет хелпер
	 * 
	 * @param string  $name   Имя хелпера
	 * @param Closure $helper Хелпер
	 */
	public static function setHelper($name, Closure $helper) {
		self::$helpers[$name] = $helper;
	}

	/**
	 * Возвращает хелпер
	 * 
	 * @param string $name Имя хелпера
	 */
	public static function getHelper($name) {
		if (isset(self::$helpers[$name])) {
			return self::$helpers[$name];
		}
		return null;
	}

	/**
	 * Обработчик вызовов хелперов
	 * 
	 * @param string $name      Имя хелпера
	 * @param array  $arguments Параметры хелпера
	 * 
	 * @return mixed
	 */
	public static function __callstatic($name, array $arguments = array()) {
		if ($helper = self::getHelper($name)) {
			return call_user_func_array($helper, $arguments);
		}
	}

	/**
	 * Устанавливает название используемого скина
	 * 
	 * @param string $skin Названия скина
	 * 
	 * @return boolaen
	 */
	public function setSkin($skin) {
		$this->skin = $skin;
	}

	/**
	 * Возвращает отрисованный шаблон
	 * 
	 * @param array        $vars     Данные
	 * @param string|array $template Шаблон или список шаблонов
	 * 
	 * @return string
	 */
	public function render(array $__vars, $template) {
		$templates = array_reverse((array)$template);
		foreach ($templates as $template) {
			extract($__vars, EXTR_SKIP | EXTR_REFS);
			ob_start();
			include FORM_MANAGER_PATH.'/templates/'.$this->skin.'/'.$template;
			$__vars['content'] = ob_get_clean();
		}
		return isset($__vars['content']) ? $__vars['content'] : '';
	}

}