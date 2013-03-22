<?php

/**
 * Кодирования / экранирования спецсимволов
 *
 * @param string $string Экринируемая строка
 * 
 * @return string
 */
return function ($string) {
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
};