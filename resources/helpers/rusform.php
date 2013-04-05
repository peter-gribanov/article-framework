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
 * Склонение существительных
 *
 * 1 — яблоко
 * 2 — яблока
 * 5 — яблок
 * 
 * <code>
 * self::rusform(6, 'яблоко', 'яблока', 'яблок') // Вернет "яблок"
 * </code>
 *
 * @param integer $number    Число
 * @param string  $form_one  Текст для одного
 * @param string  $form_two  Текст для двух
 * @param string  $form_five Текст для пяти
 * 
 * @return string
 */
return function ($number, $form_one = 'штука', $form_two = 'штуки', $form_five = 'штук') {
	$number = abs($number) % 100;
	$mod = $number % 10;
	if ($number > 10 && $number < 20) {
		return $form_five;
	}
	if ($mod > 1 && $mod < 5) {
		return $form_two;
	}
	if ($mod == 1) {
		return $form_one;
	}
	return $form_five;
};