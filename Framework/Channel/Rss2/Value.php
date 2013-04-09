<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\Channel\Rss2;

/**
 * Значение элемента канала
 *
 * @package Framework\Channel\Rss2
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Value {

	/**
	 * Элемент
	 *
	 * @var \SimpleXMLElement
	 */
	protected $element;


	/**
	 * Конструктор
	 *
	 * @param \SimpleXMLElement $element Элемент
	 */
	public function __construct(\SimpleXMLElement $element) {
		$this->element = $element;
	}

	/**
	 * Возвращает атрибут
	 *
	 * @param string $name Название атрибута
	 *
	 * @return string|null
	 */
	public function __get($name) {
		if (!isset($this->element->attributes()->$name)) {
			return null;
		} else {
			return (string)$this->element->attributes()->$name;
		}
	}

	/**
	 * Возвращает значение элемента
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->element->__toString();
	}

}