<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\Channel\Rss2\Value;

use Framework\Channel\Rss2\Value;

/**
 * Уникальный идентификатор статьи
 *
 * @property boolean $isPermaLink Постоянная ссылка
 *
 * @package Framework\Channel\Rss2\Value
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Guid extends Value {

	/**
	 * Возвращает атрибут
	 *
	 * @param string $name Название атрибута
	 *
	 * @return string|null
	 */
	public function __get($name) {
		if ($name == 'isPermaLink') {
			return (bool)parent::__get($name);
		} else {
			return parent::__get($name);
		}
	}

}