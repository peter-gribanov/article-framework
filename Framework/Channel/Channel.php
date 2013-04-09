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

use Framework\Exception;

/**
 * Поставщик ресурсов
 *
 * @package Framework\Channel
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
abstract class Channel {

	/**
	 * Канал ресрсов
	 *
	 * @var string
	 */
	private $channel = '';

	/**
	 * Список HTTP заголовков запроса
	 *
	 * @var array
	 */
	private $request_headers = array();

	/**
	 * Список HTTP заголовков ответа
	 *
	 * @var array
	 */
	private $response_headers = array();


	/**
	 * Устанавливает канал
	 * 
	 * @param string $channel Канал ресрсов
	 * 
	 * @return \Framework\Channel\Channel
	 */
	public function setChannel($channel) {
		$this->clear()->channel = $channel;
		return $this;
	}

	/**
	 * Возвращает канал ресрсов
	 * 
	 * @return string
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * Очищает канал
	 * 
	 * @return \Framework\Channel\Channel
	 */
	public function clear() {
		$this->channel = '';
		$this->response_headers = array();
		$this->request_headers  = array();
		return $this;
	}

	/**
	 * Возвращает HTTP заголовки
	 *
	 * @return array
	 */
	protected function getHttpHeaders() {
		if (!$this->response_headers) {
			$this->response_headers = get_headers($this->channel, 1);
		}
		return $this->response_headers;
	}

	/**
	 * Возвращает список ресурсов
	 *
	 * @return array
	 */
	abstract public function getResources();

	/**
	 * Возвращает информацию о канале
	 *
	 * @param string|null $param   Название параметра
	 * @param string|null $default Значение по умолчанию
	 *
	 * @return array
	 */
	abstract public function getChannelInfo($param = null, $default = null);

	/**
	 * Возвращает контент с удаленного ресурса
	 *
	 * @return string
	 */
	protected function getRemoteContent() {
		// получаем 
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CUSTOMREQUEST  => 'GET',
			CURLOPT_URL => $this->channel,
			CURLOPT_HTTPHEADER => $this->request_headers,
		));
		$result = curl_exec($ch);

		// контент получен или не изменен и нет ошибки
		if ($result !== false && in_array(curl_getinfo($ch, CURLINFO_HTTP_CODE), array(200, 304))) {
			// обновляем путь к каналу если он изменился
			$this->channel = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
			curl_close($ch);
			// обновляем дату доступа
			$this->setETag($this->getETag());
			$this->setLastModified($this->getLastModified());

		} else { // произошла ошибка
			if ($result === false) {
				$e = new Exception(curl_error($ch), curl_errno($ch));
			} else {
				$e = new Exception('Не удалось получить данные', curl_getinfo($ch, CURLINFO_HTTP_CODE));
			}
			curl_close($ch);
			throw $e;
		}

		return $result;
	}

	/**
	 * Устанавливает ETag
	 *
	 * @param string $etag ETag
	 * 
	 * @return \Framework\Channel\Channel
	 */
	public function setETag($etag) {
		return $this->setHeader('If-None-Match', $etag);
	}

	/**
	 * Возвращает ETag
	 *
	 * @return string|null
	 */
	public function getETag() {
		$headers = $this->getHttpHeaders();
		return isset($headers['ETag']) ? $headers['ETag'] : null;
	}

	/**
	 * Устанавливает дату последнего изменения
	 *
	 * @param string $last_modified Дата последнего изменения
	 * 
	 * @return \Framework\Channel\Channel
	 */
	public function setLastModified($last_modified) {
		return $this->setHeader('If-Modified-Since', $last_modified);
	}

	/**
	 * Возвращает дату последнего изменения
	 *
	 * @return string|null
	 */
	public function getLastModified() {
		$headers = $this->getHttpHeaders();
		return isset($headers['Last-Modified']) ? $headers['Last-Modified'] : null;
	}

	/**
	 * Устанавливает заголовок HTTP запроса
	 *
	 * @param string $name  Имя заголовка
	 * @param string $value Значение
	 * 
	 * @return \Framework\Channel\Channel
	 */
	protected function setHeader($name, $value) {
		if ($name && $value) {
			$this->request_headers[$name] = $value;
		}
		return $this;
	}

}