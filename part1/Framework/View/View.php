<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

namespace Framework\View;

use Framework\Factory;

/**
 * Представление
 * 
 * @package Framework\View
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class View implements Inface {

	/**
	 * Фабрика
	 * 
	 * @var Framework\Factory
	 */
	private $factory;


	/**
	 * Конструктор
	 * 
	 * @param Framework\Factory $factory Фабрика
	 */
	public function __construct(Factory $factory) {
		$this->factory = $factory;
	}

	/**
	 * Возвращает отрисованный шаблон
	 * 
	 * @param string|array $template Шаблон или список шаблонов
	 * @param array|null   $vars     Данные
	 * 
	 * @return string
	 */
	public function render($template, array $__vars = array()) {
		$templates = array_reverse((array)$template);
		foreach ($templates as $template) {
			extract($__vars, EXTR_SKIP | EXTR_REFS);
			ob_start();
			include $this->factory->getDir().'/ressources/templates/'.$template;
			$__vars['content'] = ob_get_clean();
		}
		return isset($__vars['content']) ? $__vars['content'] : '';
	}

}