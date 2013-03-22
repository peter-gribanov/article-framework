<?php

/**
 * Хелпер стартующий создание блока или сохраняющий значение
 *
 * @param string      $name  Название блока
 * @param string|null $value Значение блока
 */
return function ($name, $value = null) use ($utility) {
	if (is_null($value)) {
		$utility->startBuffering($name);
	} else {
		$utility->assign(array($name => $value));
	}
};