<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\Controller;


use Framework\Controller\Controller;
use Framework\Router\Node;
use Framework\Factory;
use Framework\Request;
use Microsoft\Api;
use Framework\Channel\Rss2;

/**
 * Главный контроллер
 *
 * @package Framework\Controller
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Home extends Controller {

	/**
	 * Количество записей на странице
	 *
	 * @var integer
	 */
	const PER_PAGE = 6;

	/**
	 * Время кэширования по умолчанию
	 *
	 * @var string
	 */
	const CACHE_DEFAULT = 60;

	/**
	 * Файл для кэша
	 *
	 * @var string
	 */
	const CACHE_FILE = 'news.php';

	/**
	 * Файл для кэша параметров доступа
	 *
	 * @var string
	 */
	const CACHE_ACCESS_FILE = 'news_access.php';


	/**
	 * API адаптор
	 *
	 * @var \Microsoft\Api
	 */
	private $api;


	/**
	 * Конструктор
	 *
	 * @param \Framework\Router\Node $node    Нода
	 * @param \Framework\Factory     $factory Фабрика
	 * @param \Framework\Request     $request Запрос
	 */
	public function __construct(Node $node, Factory $factory, Request $request) {
		parent::__construct($node, $factory, $request);
		$this->api = new Api(
			$factory->getConfig('api.code', ''),
			$factory->getConfig('api.secret', ''),
			$request->getRootUrl(),
			$factory->getConfig('api.can_authorize', false)
		);

		// авторезация приложения
		if ($code = $request->get('code')) {
			$this->api->authorize($code);
		}
	}

	/**
	 * Возвращает представление
	 *
	 * @return \Microsoft\Api
	 */
	private function getApi() {
		return $this->api;
	}

	/**
	 * Главная
	 *
	 * @return array
	 */
	public function indexAction() {
		// проверка авторезированности приложения
		$this->api->checkAuthoriz();

		$items = $this->getNews();

		// группируем по страницам и удаляем полузаполненные страницы
		$pages = array_chunk($items, self::PER_PAGE);
		$pages = array_slice($pages, 0, round(count($items)/self::PER_PAGE));

		return array(
			'pages' => $pages,
		);
	}

	/**
	 * Инвайты
	 *
	 * @return array
	 */
	public function inviteAction() {
		// проверка авторезированности приложения
		$this->api->checkAuthoriz();

		$items = array_fill(0, 10, 'item');

		$per_page = 4;
		$total = count($items);
		$page = 5;

		$start = $page-floor(($per_page-1)/2);
		$start = $start > 1 ? $start : 1;
		$start = $total - $start + 1 < $per_page && $total - $start >= 1 ? $total-$per_page+1 : $start;
		$list_page  = array_keys(array_fill($start, $per_page, ''));

		//p($page);
		//p($list_page);
		return array();
	}

	/**
	 * Обновление списка новостей
	 *
	 * @return array
	 */
	public function updateAction() {
		$news = $this->getNewItemsFromNews();

		// отправляем в ленту новые сообщения
		if ($news) {
			foreach ($news as $item) {
				try {
					$this->getApi()->fetch($api::METHOD_TAPE, array(
						'message' => $this->getView()->assign($item)->render('Home/update/message.html.tpl'),
						'type'    => 'app_subscribe',
					));
				} catch (\Exception $e) {
					$message = '['.date('r').'] '.$e->getMessage()."\n".$e->getTraceAsString()."\n";
					file_put_contents($this->factory->getDir().'/error.log', $message);
				}
			}
		}

		return $news;
	}

	/**
	 * Обновление списка новостей через консоль
	 *
	 * @return array
	 */
	public function updateCliAction() {
		$news = $this->updateAction();
		return "Update complete\nAdded ".count($news)." items\n";
	}

	/**
	 * Определет нужно ли обновлять ленту
	 *
	 * @return boolean
	 */
	public function needUpdateAction() {
		$file = $this->getFactory()->getDir().'/cache/'.self::CACHE_ACCESS_FILE;
		if (file_exists($file)) {
			$params = (array)include $file;
			return $params['access']+$params['ttl'] < time();
		}
		return false;
	}

	/**
	 * Возвращает список новых элементов в ленте новостей
	 *
	 * @return array
	 */
	private function getNewItemsFromNews() {
		$file = $this->getFactory()->getDir().'/cache/'.self::CACHE_FILE;
		// первый вызов
		if (!file_exists($file)) {
			return $this->getNews();
		}

		$old_items = (array)include $file;
		$items = $this->getNews();

		// группируем по id
		$keys = array_map(function ($item) {
			return $item['id'];
		}, $items);
		$items = array_combine($keys, $items);

		// удаляем те запеси которые у нас уже есть
		foreach ($old_items as $item) {
			if (isset($items[$item['id']])) {
				unset($items[$item['id']]);
			}
		}
		return array_values($items);
	}

	/**
	 * Возвращает список новостей
	 *
	 * @return array
	 */
	private function getNews() {
		$file = $this->getFactory()->getDir().'/cache/'.self::CACHE_FILE;

		if (!file_exists($file) || filemtime($file) < time()) {
			$file_access = $this->getFactory()->getDir().'/cache/'.self::CACHE_ACCESS_FILE;

			// читаем ленту
			$rss = new Rss2();
			// устанавливаем параметры доступа
			if (file_exists($file_access)) {
				$access = include $file_access;
				$rss->setETag($access['etag']);
				$rss->setLastModified($access['last_modified']); // канал плохо поддерживает этот параметр
			}
			$rss->setChannel($this->getFactory()->getConfig('channel.rss'));

			// получение списка новостей
			$feed = array();
			foreach ($rss->getResources() as $item) {
				/* @var $item \Framework\Channel\Rss2\Element\Item */
				$feed[] = array(
					'id'    => (string)$item->guid,
					'title' => trim($item->title),
					'link'  => $item->link,
					'text'  => trim(strip_tags($item->description, '<b><strong><u><i>')),
					'date'  => strtotime($item->pubDate),
				);
			}
			// кэшируем ленту
			file_put_contents($file, "<?php\nreturn ".var_export($feed, true).';');
			// время кэширования
			$ttl = $rss->getChannelInfo('ttl');
			$ttl = ($ttl ? $ttl*60 : self::CACHE_DEFAULT);
			touch($file, time()+$ttl);

			// кэшируем параметры доступа
			$access = array(
				'ttl'    => $ttl,
				'access' => time(),
				'etag'   => $rss->getETag(),
				'last_modified' => $rss->getLastModified(),
			);
			file_put_contents($file_access, "<?php\nreturn ".var_export($access, true).';');

			return $feed;
		}
		return (array)include $file;
	}
}