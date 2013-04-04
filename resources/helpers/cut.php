<?php

/**
 * Возвращает строку усеченную до указанного размера
 *
 * @param string  $string Входная строка
 * @param integer $width  Допустимая ширина строки
 * @param string  $break  Текст дописываемый в конце обрезаемого
 *
 * @return string
 */
return function ($string, $width, $break = '...') {
	if (strlen($string) > $width) {
		$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $width-strlen($break))).$break;
	}
	return $string;
};