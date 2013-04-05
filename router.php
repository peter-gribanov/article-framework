<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

$request = __DIR__.'/web'.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (is_file($request) || (is_dir($request) && is_file($request.'index.php'))) {
	return false; // сервер возвращает файлы напрямую

} else {
	// fix bug #64566
	if (($filename = ini_get('auto_prepend_file')) && $filename != 'none') {
		if (file_exists($filename)) { // absolute path
			include $filename;
		} else { // relative path
			foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
				if ($file = realpath($path.DIRECTORY_SEPARATOR.$filename)) {
					include $file;
					break;
				}
			}
		}
	}

	// фронт контроллер
	require 'web/index.php';
}