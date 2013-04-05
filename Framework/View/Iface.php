<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\View;

/**
 * Интерфейс шаблонизатеров
 *
 * @author  Peter Gribanov <gribanov@professionali.ru>
 * @package Framework\View
 */
interface Iface {

	/**
	 * Присвоение переменных шаблону
	 *
	 * @param mixed $vars Данные
	 *
	 * @return \Framework\View\Iface
	 */
	public function assign($vars);

	/**
	 * Возвращает список всех установленных переменных
	 *
	 * @return mixed
	 */
	public function getVars();

	/**
	 * Очистить добавленные данные
	 *
	 * @return \Framework\View\Iface
	 */
	public function clear();

	/**
	 * Возвращает отрисованный шаблон
	 *
	 * @param string $template Шаблон
	 *
	 * @return string
	 */
	public function render($template);

}