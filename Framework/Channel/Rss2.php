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
use Framework\Channel\Rss2\Element\Channel as ChannelElement;

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
	 * @var \Framework\Channel\Rss2\Element\Channel
	 */
	private $resource;


	/**
	 * Возвращает список ресурсов
	 *
	 * @return array [\Framework\Channel\Rss2\Element\Item]
	 */
	public function getResources() {
		return $this->getChannelInfo()->getItems();
	}


	/**
	 * Возвращает информацию о канале
	 *
	 * @param string|null $param   Название параметра
	 * @param string|null $default Значение по умолчанию
	 *
	 * @return \Framework\Channel\Rss2\Element\Channel
	 */
	public function getChannelInfo($param = null, $default = null) {
		// читает и парсит канал
		if (!($this->resource instanceof ChannelElement)) {
			// если контента нет возвращаем пустой массив
			if (!($content = $this->getRemoteContent())) {
				return array();
			}
			$xml = simplexml_load_string($content);
			if (!($xml instanceof \SimpleXMLElement)) {
				throw new Exception('Не удалось разобрать ответ от канала '.$this->getChannel());
			}
			$this->resource = new ChannelElement($xml->channel);
		}

		// возвращаем весь список параметров
		if (!$param) {
			return $this->resource;
		}
		return isset($this->resource->$param) ? $this->resource->$param : $default;
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