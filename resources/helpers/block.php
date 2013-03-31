<?php

/**
 * Хелпер стартующий создание блока
 *
 * @param string  $name      Название блока
 * @param boolean $overwrite Перезаписывать блок
 */
return function ($name, $overwrite = true) use ($utility) {
	$utility->startBuffering($name, $overwrite);
};