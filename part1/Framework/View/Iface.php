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
interface Iface {

	/**
	 * Конструктор
	 * 
	 * @param string $path Путь к файлам темплэйтов
	 */
	public function __construct($path);

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