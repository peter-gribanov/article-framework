<?php

/**
 * Интерфейс шаблонизатеров
 *
 * @author Peter Gribanov
 * @package View
 */
interface View_Interface {

	/**
	 * Присвоение переменных шаблону
	 *
	 * @param mixed $vars Данные
	 *
	 * @return View_Interfase
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
	 * @return View_Interfase
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