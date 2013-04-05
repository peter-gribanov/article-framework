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
 * Хелпер вклучающий родительский шаблон
 *
 * @param string $template Шаблон
 */
return function ($template) use ($utility) {
	$utility->addTemplate($template);
};