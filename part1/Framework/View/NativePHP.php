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
class NativePHP implements Iface {

	/**
	 * Пути к файлам темплэйтов
	 * 
	 * @var string
	 */
	private $__path;


	/**
	 * Конструктор
	 * 
	 * @param string $path Путь к файлам темплэйтов
	 */
	public function __construct($path) {
		$this->__path = $path;
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
			extract($__vars, EXTR_SKIP);
			ob_start();
			include $this->__path.DIRECTORY_SEPARATOR.$template;
			$__vars['content'] = ob_get_clean();
		}
		return isset($__vars['content']) ? $__vars['content'] : '';
	}

}