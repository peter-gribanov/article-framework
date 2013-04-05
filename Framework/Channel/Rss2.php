<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\Channel;

use Framework\Channel\Channel;
use Framework\Exception;

/**
 * Поставщик новостей RSS 2.0
 *
 * @package Framework\Channel
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Rss2 extends Channel {

	/**
	 * Загруженные ресурсы
	 *
	 * @var SimpleXMLElement
	 */
	private $resource;

	/**
	 * Список ресурсов
	 *
	 * @var array
	 */
	private $items = array();


	/**
	 * Возвращает список ресурсов
	 *
	 * @return array
	 */
	public function getResources() {
		if (!$this->items) {
			// читаем контент
			if (!($this->resource instanceof SimpleXMLElement)) {
				// если контента нет возвращаем пустой массив
				if (!($content = $this->getRemoteContent())) {
					return array();
				}
				$this->resource = simplexml_load_string($content);
				if (!($this->resource instanceof SimpleXMLElement)) {
					throw new Exception('Не удалось разобрать ответ от канала '.$this->getChannel());
				}
			}
			foreach ($this->resource->channel->item as $item) {
				$this->items[] = array(
					'guid'        => (string)$item->guid,
					'title'       => (string)$item->title,
					'link'        => (string)$item->link,
					'description' => (string)$item->description,
					'pubDate'     => (string)$item->pubDate,
				);
			}
		}
		return $this->items;
	}

	/**
	 * Возвращает интервал доступа к каналу
	 *
	 * @return integer
	 */
	public function getTtl() {
		return $this->resource && isset($this->resource->channel->ttl) ? (string)$this->resource->channel->ttl : 0;
	}

	/**
	 * Очищает канал
	 *
	 * @return \Framework\Channel\Rss2
	 */
	public function clear() {
		$this->resource = null;
		$this->items = array();
		return parent::clear();
	}

}