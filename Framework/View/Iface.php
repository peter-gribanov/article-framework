<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
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