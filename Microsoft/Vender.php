<?php
/**
 * Microsoft package
*
* @package Microsoft
* @author  Peter Gribanov <gribanov@professionali.ru>
*/

namespace Microsoft;

use Microsoft\Media\Processor;

/**
 * Поставщик
 *
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Vender {

	/**
	 * Инструмент для работы с конфигом
	 *
	 * @var \Microsoft\Config
	 */
	private $config = null;


	/**
	 * Конструктор
	 * 
	 * @param \Microsoft\Config $config Инструмент для работы с конфигом
	 */
	public function __construct(Config $config) {
		$this->config = $config;
	}

	/**
	 * Возвращает контент если он изменился
	 *
	 * @return string|boolean
	 */
	public function getNewRssNews() {
		// с последнего обновления прошло недостаточно времени
		if ($this->config->update+($this->config->ttl*60) > time()) {
			return array();
		}

		// получаем контент с удаленного ресурса
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CUSTOMREQUEST  => 'GET',
			CURLOPT_URL => $this->config->vender,
			CURLOPT_HTTPHEADER => array(
				'If-None-Match'     => $this->config->etag,
				//'If-Modified-Since' => $this->config->lest_mod, // не принимает почему-то
			),
		));
		$result = curl_exec($ch);

		// произошла ошибка или еще не пора обновлять
		if ($result === false) {
			curl_close($ch);
			throw new Exception(curl_error($ch), curl_errno($ch));
		}

		$news = array();
		// контент изменен и нет ошибки
		if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
			$vender = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

			$items = $this->parseContent($result);
			$items = $this->formatItems($items);

			if ($news = $this->findNew($items)) {
				// сохранение заголовков об изменениях
				$headers = get_headers($vender, 1);
				$this->config->etag     = $headers['ETag'];
				$this->config->lest_mod = $headers['Last-Modified'];
				$this->config->news     = $items;
			}
		}

		// контент изменин или нет. обновляем дату доступа
		if (in_array(curl_getinfo($ch, CURLINFO_HTTP_CODE), array(200, 304))) {
			$this->config->update = time();
			$this->config->save();
		}
		curl_close($ch);

		return $news;
	}

	/**
	 * Разбирает результат
	 *
	 * @param string $content Контент
	 *
	 * @return array
	 */
	private function parseContent($content) {
		$xml = simplexml_load_string($content);
		$result = array();
		foreach ($xml->channel->item as $item) {
			$result[] = array(
				'id'    => (string)$item->guid,
				'title' => (string)$item->title,
				'link'  => (string)$item->link,
				'text'  => (string)$item->description,
				'date'  => (string)$item->pubDate,
			);
		}
		$this->config->ttl = (string)$xml->channel->ttl;
		return $result;
	}

	/**
	 * Форматирование списка новостей
	 *
	 * @param array $items Список новостей
	 *
	 * @return array
	 */
	private function formatItems(array $items) {
		foreach ($items as $key => $item) {
			$items[$key]['text'] = strip_tags($item['text'], '<b><strong><u><i>');
			$items[$key]['date'] = strtotime($item['date']);
		}
		return $items;
	}

	/**
	 * Поиск новых записей
	 *
	 * @param array $items Список записей
	 *
	 * @return array
	 */
	private function findNew(array $items) {
		// группируем по id
		$keys = array_map(function ($item) {
			return $item['id'];
		}, $items);
		$items = array_combine($keys, $items);

		// удаляем те запеси которые у нас уже есть
		foreach ($this->config->news as $item) {
			if (isset($items[$item['id']])) {
				unset($items[$item['id']]);
			}
		}
		return array_values($items);
	}

}