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
spl_autoload_register(function($class_name) {
	$file_name = str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class_name).'.php';

	if (!is_readable(__DIR__.DIRECTORY_SEPARATOR.$file_name)) {
		throw new Exception(sprintf('Файл "%s" для "%s" не найден', $file_name, $class_name));
	}

	$result = require $file_name;
	if ($result === false) {
		throw new Exception(sprintf('Не удалось подключить файл "%s" для класса "%s"', $file_name, $class_name));
	}

	if (!class_exists($class_name, false) && !interface_exists($class_name, false)) {
		throw new Exception(sprintf('В файле "%s" не найден "%s"', $file_name, $class_name));
	}
}, true, true);
