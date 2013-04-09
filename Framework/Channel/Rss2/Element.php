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

use Framework\Channel\Rss2\Value;

/**
 * Элемент канала
 *
 * @package Framework\Channel\Rss2
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
abstract class Element {

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
	 * Возвращает параметр элемента
	 *
	 * @param string $name Название параметра
	 *
	 * @return \Framework\Channel\Rss2\Value|\Framework\Channel\Rss2\Element|string|null
	 */
	public function __get($name) {
		if (!isset($this->element->$name)) {
			return null;

		} elseif ($this->element->$name->attributes()->count()) {
			return new Value($this->element->$name);

		} elseif ($this->element->$name->children()->count()) {
			return new self($this->element->$name);

		} else {
			return (string)$this->element->$name;
		}
	}

}