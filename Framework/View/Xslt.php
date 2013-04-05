<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace Framework\View;

use Framework\View\Iface;
use Framework\View\Exception;

/**
 * Шаблонизатор XSLT
 *
 * @author  Peter Gribanov <gribanov@professionali.ru>
 * @package Framework\View
 */
class Xslt implements Iface {

	/**
	 * Данные для отрисовки в шаблонизаторе
	 *
	 * @var string
	 */
	private $xml = '';


	/**
	 * Присвоение переменных шаблону
	 *
	 * @param string $xml Данные
	 *
	 * @return \Framework\View\Xslt
	 */
	public function assign($xml) {
		$this->xml = $xml;
		return $this;
	}

	/**
	 * Возвращает список всех установленных переменных
	 *
	 * @return string
	 */
	public function getVars() {
		return $xml;
	}

	/**
	 * Очистить добавленные данные
	 *
	 * @return \Framework\View\Xslt
	 */
	public function clear() {
		$this->xml = '';
		return $this;
	}

	/**
	 * Возвращает отрисованный шаблон
	 *
	 * @throws \Framework\View\Exception
	 *
	 * @param string $xslt Шаблон
	 *
	 * @return string
	 */
	public function render($xslt) {
		if (extension_loaded('xslcache')) {
			$xslt = new \xsltCache();
			$xslt->importStyleSheet(TPL_DIR.'/'.$xslt);
		} else {
			$xslt = new \xsltProcessor();
			$xslt->importStyleSheet(DomDocument::load(TPL_DIR.'/'.$xslt));
		}

		$doc = new \DOMDocument();
		if (!$doc->loadXML($this->xml)) {
			throw new Exception('Некорректный формат XML данных');
		}
		return $xslt->transformToXML($doc);
	}

	/**
	 * Клонирование представления
	 */
	function __clone() {
		$this->clear();
	}

}