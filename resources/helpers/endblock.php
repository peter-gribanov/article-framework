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
 * Хелпер завершаюший создание блока и сохраняющий результат в переменную шаблона
 */
return function () use ($utility) {
	$utility->endBuffering();
};