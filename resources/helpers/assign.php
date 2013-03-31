<?php

/**
 * Хелпер сохраняющий значение
 *
 * @param string $name  Название
 * @param string $value Значение
 */
return function ($name, $value) use ($utility) {
	$utility->assign(array($name => $value));
};