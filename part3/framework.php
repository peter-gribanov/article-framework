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
 * Второй параметр указывает что необходимо генерить исключени в случаи неуспеха
 * Третий что нашу функцию необходимо домавить в начала всех функций автозагрузок
 *
 * Внимание вызов у несуществующего класса константы, например Framework_Undefined::UNDEFINED
 * Вызовет PHP Fatal error:  Undefined class constant и невозможно будет перехватить
 * исключение
 * В тоже время Framework_Undefined::undefined() и Framework_Undefined::$undefined работать будут ожидаемо
 *
 * @package Framework\AutoLoad
 * @throws  Framework_AutoLoad_Exception
 *
 * @param string $name Название класса
 *
 * @return boolean
 */
spl_autoload_register(function($name) {
    // namespaced style
    if ('\\' == $name[0]) {
        $name = substr($name, 1);
    }

	// аутолоадер используем только для Framework
	if (strpos($name, 'Framework') !== 0) {
		return false;
	}
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $name);
	$file = str_replace('_', DIRECTORY_SEPARATOR, $file).'.php';
	$type = strpos($name, 'Iface') === false && strpos($name, 'Interface') === false ? 'class':'interface';
	$is = $type.'_exists';
	// ищем файл в include_path директориях
	$status = false;
	foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
		if (is_readable(realpath($path).DIRECTORY_SEPARATOR.$file)) {
			$the_path = $path;
			$status = true;
			break;
		}
	}

	// класс не найден
	if (!$status) {
		throw new Cms_AutoLoad_Exception(
			'Файл "'.$file.'" для '.($type=='class'?'класса':'интерфейса').' "'.$name.'" не найден'
		);
	}

	try {
		include_once($file);
	} catch (Exception $exeption) {
		// Костыль для php. При работе со статическими метада класса, без костыля
		// будет фатальная ошибка и исключение не сгенерируется
		throw new Cms_AutoLoad_Exception(
			'В файле "' . $file . '" ошибка, '.($type=='class'?'класс':'интерфейс').
			' "'.$name.'" не возможно определить: "'.$exeption->getMessage().'"'
		);
	}

	if (!$is($name, false)) {
		throw new Cms_AutoLoad_Exception(
			'В файле "'.$file.'" '.($type=='class'?'класс':'интерфейс').' "'.$name.'" не найден'
		);
	}

	return true;
}, true, true);

/**
 * Исключение для автозагрузки
 *
 * @package Framework\AutoLoad
 */
class Framework_AutoLoad_Exception extends Exception {
}
