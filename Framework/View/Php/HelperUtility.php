<?php

/**
 * Утилита для хелперов
 *
 * Скрывает некоторую внутреннюю реализацию работы шаблонизатора
 *
 * @author Peter Gribanov
 * @package View\Php
 */
class View_Php_HelperUtility {

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
	 * @var View_Php
	 */
	private $view;

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
	 * @param View_Php $view Шаблонизатор
	 */
	public function __construct(View_Php $view) {
		$this->view = $view;
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
	 * @return View_Php_HelperUtility
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
	 * @param string $name Имя блока
	 */
	public function startBuffering($name) {
		if ($name) {
			if ($this->block) {
				throw new Exception('Буферизация уже начата в блоке '.$this->block);
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
			$this->view->assign(array($this->block => ob_get_clean()));
			echo $this->buffer;
			$this->buffer = $this->name = '';
			ob_start();
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
	 * Включение шаблона
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
}