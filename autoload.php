<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */


// автолоадер
spl_autoload_register(function($name) {
	$type = strpos($name, 'Interface') === false ? 'class' : 'interface';
	$is = $type.'_exists';
	$file = __DIR__.'/'.str_replace(array('_', '\\'), '/', $name).'.php';
	if (is_readable($file)) {
		include_once $file;
		if (!$is($name, false)) {
			throw new Exception('В файле "'.$file.'" '.($type=='class' ? 'класс' : 'интерфейс').' "'.$name.'" не найден');
		}
	}
});
