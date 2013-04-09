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
use Framework\Channel\Rss2\Element\Image;
use Framework\Channel\Rss2\Element\TextInput;
use Framework\Channel\Rss2\Value\Category;
use Framework\Channel\Rss2\Value\Cloud;
use Framework\Channel\Rss2\Element\Item;

/**
 * Информация о канале
 *
 * @property string                                    $title          Название канала
 * @property string                                    $link           URL веб-сайта
 * @property string                                    $description    Описания канала
 * @property string                                    $language       Язык
 * @property string                                    $copyright      Авторские права
 * @property string                                    $managingEditor Менеджер
 * @property string                                    $webMaster      Администратор
 * @property string                                    $pubDate        Дата публикации
 * @property string                                    $lastBuildDate  Время последнего изменения
 * @property \Framework\Channel\Rss2\Value\Category    $category       Категории
 * @property string                                    $generator      Генератор ленты
 * @property string                                    $docs           Документация
 * @property \Framework\Channel\Rss2\Value\Cloud       $cloud          rssCloud
 * @property \Framework\Channel\Rss2\Element\Image     $image          Изображение
 * @property integer                                   $ttl            Время жизни
 * @property \Framework\Channel\Rss2\Element\TextInput $textInput      Рейтинг канала PICS
 * @property string                                    $skipHours      Какие часы можно пропустить
 * @property string                                    $skipDays       Какие дни  можно пропустить
 * @property array                                     $item           Список элементов ленты
 *
 * @package Framework\Channel\Rss2\Element
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Channel extends Element {

	/**
	 * Список элементов ленты
	 *
	 * @param array
	 */
	private $items = array();


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
			case 'image':
				return new Image($this->element->$name);
			case 'textInput':
				return new TextInput($this->element->$name);
			case 'category':
				return new Category($this->element->$name);
			case 'cloud':
				return new Cloud($this->element->$name);
			case 'item':
				return $this->getItems();
			default:
				return parent::__get($name);
		}
	}

	/**
	 * Возвращает список элементов ленты
	 *
	 * @ return array [\Framework\Channel\Rss2\Element\Item]
	 * @return multitype:\Framework\Channel\Rss2\Element\Item
	 */
	public function getItems() {
		if (!$this->items) {
			foreach ($this->element->children() as $item) {
				$this->items[] = new Item($item);
			}
		}
		return $this->items;
	}

}