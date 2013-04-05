<?php
/**
 * Microsoft package
*
* @package Microsoft
* @author  Peter Gribanov <gribanov@professionali.ru>
*/

namespace Microsoft;

/**
 * Инструмент для работы с конфигом
 *
 * @property string  $vender   Постовщик новостей
 * @property integer $ttl      Частота обновления канала в минутах
 * @property integer $update   Дата последнего обновления
 * @property string  $token    Токен
 * @property string  $etag     ETag
 * @property string  $lest_mod Последние изменения (Last-Modified)
 * @property array   $news     Новости
 *
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */
class Config {

	/**
	 * Файл конфигураций
	 *
	 * @var string
	 */
	const FILE = 'config.php';
	
	/**
	 * Шаблон файла конфигураций
	 *
	 * @var string
	 */
	const SIMPLE = 'config.simple.php';


	/**
	 * Конфигурации
	 *
	 * @var array
	 */
	private $config = array();

	/**
	 * Изменено
	 *
	 * @var boolean
	 */
	private $is_changed = false;

	/**
	 * Корневая диреутория
	 *
	 * @var string
	 */
	private $dir;


	/**
	 * Конструктор
	 *
	 * @param string $dir Корневая диреутория
	 */
	public function __construct($dir) {
		$this->dir = $dir;
	}

	/**
	 * Устанавливает значение конфига
	 *
	 * @param string $var   Название переменной
	 * @param mixed  $value Значение
	 */
	public function __set($var, $value) {
		$this->loadIfNeed();
		if ($this->config[$var] != $value) {
			$this->config[$var] = $value;
			$this->is_changed = true;
		}
	}

	/**
	 * Возвращает значение конфига
	 *
	 * @param string $var Название переменной
	 *
	 * @return mixed
	 */
	public function __get($var) {
		$this->loadIfNeed();
		return $this->config[$var];
	}

	/**
	 * Сохранить конфиг
	 *
	 * @return \Microsoft\Config
	 */
	public function save() {
		if ($this->is_changed) {
			$tpl = file_get_contents($this->dir.'/'.self::SIMPLE);
			foreach ($this->config as $var => $value) {
				$tpl = str_replace('%'.$var.'%', var_export($value, true), $tpl);
			}
			file_put_contents($this->dir.'/'.self::FILE, $tpl);
			$this->is_changed = false;
		}
		return $this;
	}

	/**
	 * Загружает конфиг при необходимости
	 */
	private function loadIfNeed() {
		if (!$this->config) {
			$this->config = require $this->dir.'/'.self::FILE;
		}
	}

	/**
	 * Деструктор
	 */
	public function __destruct() {
		$this->save();
	}

}