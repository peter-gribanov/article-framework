<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

if (version_compare(phpversion(), '5.3', '<') == true) {
	exit('Для работы требуется PHP 5.3.x');
}

/**
 * Функция автозагрузки классов и интерфейсов у нас анонимная
 *
 * Pегистрируем ее через SPL, чтобы избежать конфликта с другими
 * функциями автозагрузки. Что, является хорошим тоном.
 *
 * @param string $name Название класса
 *
 * @return boolean
 */
spl_autoload_register(function($name) {
	// аутолоадер используем только для Framework
	if (strpos($name, 'Framework') !== 0) {
		return false;
	}

	$paths = explode(PATH_SEPARATOR, get_include_path());
	$class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
	foreach ($paths as $path) {
		$filename = $path.DIRECTORY_SEPARATOR.$class_name.'.php';
		if (is_readable($filename)) {
			require_once $filename;
			return true;
		}
	}

	return false;
}, true, true);
