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
	 * Главная
	 *
	 * @return array
	 */
	public function indexAction() {
		$items = array();

		if ($xml = @file_get_contents($this->getConfig('rss.channel'))) {
			$rss = simplexml_load_string($xml);
			foreach ($rss->children()->children()->item as $item) {
				$items[] = array(
					'date'  => strtotime((string)$item->pubDate),
					'link'  => (string)$item->link,
					'title' => trim(strip_tags((string)$item->title)),
					'text'  => trim(strip_tags((string)$item->description)),
				);
			}
		}

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

}