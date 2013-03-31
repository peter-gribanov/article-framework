<?php

/**
 * Возвращает русскоязычное представление даты
 *
 * @param integer $timestamp UTM время
 * 
 * @return string
 */
return function ($timestamp = null) {
	if (!$timestamp || date('dmY', $timestamp) == date('dmY')) {
		return 'Сегодня';
	} elseif (date('dmY', $timestamp) == date('dmY', time()-86400)) {
		return 'Вчера';
	} else {
		$months = array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентебря', 'Октября', 'Ноября', 'Декабря');
		return date('j ').$months[date('n')-1].date(' Y');
	}
};