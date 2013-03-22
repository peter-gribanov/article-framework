<?php

/**
 * Хелпер включающий другой шаблон
 *
 * @param string $template Шаблон
 * @param array  $vars     Параметры шаблона
 *
 * @return string
 */
return function ($template, array $vars = array()) use ($utility) {
	return $utility->render($template, $vars);
};