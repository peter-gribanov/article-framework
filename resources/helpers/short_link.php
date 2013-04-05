<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */


/**
 * Возвращает короткую ссылку
 *
 * @param string $link Ссылка
 *
 * @return string
 */
return function ($link) {
	// TODO ссылки можно хранить у себя или пользоваться goo.gl
	return @file_get_contents('http://clck.ru/--?url='.$link) ?: $link;
};
