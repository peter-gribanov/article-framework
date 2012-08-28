<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

namespace Framework\View;

/**
 * Интерфейс представления
 * 
 * @package Framework\View
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
interface Inface {

	/**
	 * Конструктор
	 * 
	 * @param Framework\Factory $factory Фабрика
	 */
	public function __construct(\Framework\Factory $factory);

	/**
	 * Возвращает отрисованный шаблон
	 * 
	 * @param string|array $template Шаблон или список шаблонов
	 * @param array|null   $vars     Данные
	 * 
	 * @return string
	 */
	public function render($template, array $__vars = array());

}