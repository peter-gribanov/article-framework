<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

namespace Framework\View\Php;

use Framework\View\Php;
use Framework\View\Exception;
use Framework\Router\URLHelper;

/**
 * Утилита для хелперов
 *
 * Скрывает некоторую внутреннюю реализацию работы шаблонизатора
 *
 * @author  Peter Gribanov <gribanov@professionali.ru>
 * @package Framework\View\Php
 */
class HelperUtility {

	/**
	 * Стек шаблонов
	 *
	 * Используется при выводе шаблонов
	 *
	 * @var array
	 */
	private $tpl_stack = array();

	/**
	 * Шаблонизатор
	 *
	 * @var \Framework\View\Php
	 */
	private $view;

	/**
	 * URL хелпер
	 *
	 * @var \Framework\Router\URLHelper
	 */
	private $url_helper;

	/**
	 * Список блоков которые не должны перезаписываться
	 *
	 * @var array
	 */
	private $not_overwrite = array();

	/**
	 * Имя сохраняемого блока
	 *
	 * @var string
	 */
	private $block = '';

	/**
	 * Буфер для хранения данных до блока
	 *
	 * @var string
	 */
	private $buffer = '';


	/**
	 * Конструктор
	 *
	 * @param \Framework\View\Php         $view       Шаблонизатор
	 * @param \Framework\Router\URLHelper $url_helper URL хелпер
	 */
	public function __construct(Php $view, URLHelper $url_helper) {
		$this->view       = $view;
		$this->url_helper = $url_helper;
	}

	/**
	 * Добавляет шаблон в стек вызовов
	 *
	 * @param string $template Шаблон
	 */
	public function addTemplate($template) {
		$this->tpl_stack[] = $template;
	}

	/**
	 * Возвращает шаблон в стек
	 *
	 * @return string
	 */
	public function getTemplate() {
		return array_shift($this->tpl_stack);
	}

	/**
	 * Проверяет пустой ли стек шаблонов
	 *
	 * @return boolean
	 */
	public function isEmptyStack() {
		return empty($this->tpl_stack);
	}

	/**
	 * Очищает стек шаблонов
	 *
	 * @return \Framework\View\Php\HelperUtility
	 */
	public function clear() {
		$this->tpl_stack = array();
		return $this;
	}

	/**
	 * Клонирование представления
	 */
	function __clone() {
		$this->clear();
	}

	/**
	 * Начинает буферизацию вывода
	 *
	 * @param string  $name      Имя блока
	 * @param boolean $overwrite Перезаписывать блок
	 */
	public function startBuffering($name, $overwrite = true) {
		if ($name) {
			if ($this->block) {
				throw new Exception('Буферизация уже начата в блоке '.$this->block);
			}
			if (!$overwrite) {
				$this->not_overwrite[] = $name;
			}
			$this->buffer = ob_get_clean();
			$this->block = $name;
			ob_start();
		}
	}

	/**
	 * Завершает буферизацию вывода и результат записывает в переменную шаблона
	 */
	public function endBuffering() {
		if ($this->block) {
			$buffer = ob_get_clean();
			ob_start();
			echo $this->buffer;
			// дописываем в конец предыдущего блока
			if (in_array($this->block, $this->not_overwrite)) {
				$buffer .= $this->view->getVar($this->block);
			}
			$this->view->assign(array($this->block => $buffer));
			// если это последний шаблон то выводим его содержимое
			if (!$this->tpl_stack) {
				echo $buffer;
			}
			$this->block = $this->buffer = '';
		}
	}

	/**
	 * Присвоение переменных шаблону
	 *
	 * @param mixed $vars Данные
	 */
	public function assign($vars) {
		$this->view->assign($vars);
	}

	/**
	 * Включение другого шаблона в поток обработки текущего
	 *
	 * @param string $template Шаблон
	 * @param array  $vars     Параметры шаблона
	 *
	 * @return string
	 */
	public function render($template, array $vars = array()) {
		// сохраняем стек шаблонов
		$tpl_stack = $this->tpl_stack;
		$this->tpl_stack = array();

		// на случай если вызов выполняется в буферезируемом блоке
		$buffer = $block_name = $block_buffer = '';
		if ($this->block) {
			$buffer = $this->buffer;
			$block_name = $this->block;
			$block_buffer = ob_get_clean();
			$this->block = $this->buffer = '';
		}

		// рендерим шаблон
		$content = $this->view->assign($vars)->render($template, true);

		// восстанавливаем буферизируемые данные
		if ($block_name) {
			$this->buffer = $buffer;
			$this->block = $block_name;
			$content = $block_buffer.$content;
			ob_start();
		}

		// восстанавливаем стек шаблонов
		$this->tpl_stack = $tpl_stack;

		return $content;
	}

	/**
	 * Возвращает URL хелпер
	 *
	 * @return \Framework\Router\URLHelper
	 */
	public function getURLHelper() {
		return $this->url_helper;
	}

}