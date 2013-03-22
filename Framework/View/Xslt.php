<?php

/**
 * Шаблонизатор XSLT
 *
 * @author  Peter Gribanov
 * @package View
 */
class View_Xslt implements View_Interface {

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
	 * @return View_Xslt
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
	 * @return View_Xslt
	 */
	public function clear() {
		$this->xml = '';
		return $this;
	}

	/**
	 * Возвращает отрисованный шаблон
	 *
	 * @param string $xslt Шаблон
	 *
	 * @return string
	 */
	public function render($xslt) {
		if (extension_loaded('xslcache')) {
			$xslt = new xsltCache();
			$xslt->importStyleSheet(TPL_DIR.'/'.$xslt);
		} else {
			$xslt = new xsltProcessor();
			$xslt->importStyleSheet(DomDocument::load(TPL_DIR.'/'.$xslt));
		}

		$doc = new DOMDocument();
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