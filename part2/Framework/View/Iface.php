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
	 * Очистить добавленные данные
	 */
	public function clear();

	/**
	 * Возвращает список всех установленных переменных
	 */
	public function getVars();

	/**
	 * Присвоение переменных шаблону
	 *
	 * Позволяет установить значение к определенному ключу или передать массив пар ключ => значение
	 *
	 * @param string|array $spec  Ключ или массив пар ключ => значение
	 * @param mixed|null   $value Если присваивается значение одной переменной, то через него передается значение переменной
	 */
	public function assign($spec, $value = null);

	/**
	 * Выполнить трансфформацию тэмплэйта
	 *
	 * Или выполнить трансформацию тэмплэйта в нутри лэйаутов если передан массив тэмплэйтов.
	 *
	 * @param string|array $template Шаблон или список шаблонов
	 */
	public function render($template);

}