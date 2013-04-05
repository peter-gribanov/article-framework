<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */


// автолоадер
spl_autoload_register(function($name) {
	$type = (
		strpos($name, 'Interface') === false &&
		strpos($name, 'Iface') === false
	) ? 'class' : 'interface';
	$is = $type.'_exists';
	$file = __DIR__.'/'.str_replace(array('_', '\\'), '/', $name).'.php';
	if (is_readable($file)) {
		include_once $file;
		if (!$is($name, false)) {
			throw new Exception('В файле "'.$file.'" '.($type=='class' ? 'класс' : 'интерфейс').' "'.$name.'" не найден');
		}
	}
});
