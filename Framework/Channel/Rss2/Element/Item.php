<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\Channel\Rss2\Element;

use Framework\Channel\Rss2\Element;
use Framework\Channel\Rss2\Value;
use Framework\Channel\Rss2\Value\Category;
use Framework\Channel\Rss2\Value\Guid;
use Framework\Channel\Rss2\Value\Enclosure;
use Framework\Channel\Rss2\Value\Source;

/**
 * Элемент ленты
 *
 * @property string                                  $title       Заголовок сообщения
 * @property string                                  $link        URL сообщения
 * @property string                                  $description Краткий обзор сообщения
 * @property string                                  $author      Автор
 * @property \Framework\Channel\Rss2\Value\Category  $category    Категории
 * @property string                                  $comments    URL страницы для комментариев
 * @property \Framework\Channel\Rss2\Value\Enclosure $enclosure   Медиа-объект
 * @property \Framework\Channel\Rss2\Value\Guid      $guid        Идентификатор сообщение
 * @property string                                  $pubDate      Дата публикации
 * @property \Framework\Channel\Rss2\Value\Source    $source       RSS-канал, из которого получено сообщение
 *
 * @package Framework\Channel\Rss2\Element
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Item extends Element {

	/**
	 * Возвращает параметр элемента
	 *
	 * @see \Framework\Channel\Rss2\Element::__get()
	 *
	 * @param string $name Название параметра
	 *
	 * @return \Framework\Channel\Rss2\Value|\Framework\Channel\Rss2\Element|string|null
	 */
	public function __get($name) {
		switch ($name) {
			case 'category':
				return new Category($this->element->$name);
			case 'enclosure':
				return new Enclosure($this->element->$name);
			case 'guid':
				return new Guid($this->element->$name);
			case 'source':
				return new Source($this->element->$name);
			default:
				return parent::__get($name);
		}
	}

}