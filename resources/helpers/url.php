<?php

/**
 * Возвращает абсолютный путь к узлу
 *
 * @param string $alias  Алиас
 * @param array  $params Параметры
 *
 * @return string
 */
return function ($alias, array $params = array()) use ($utility) {
	return $utility->getURLHelper()->getUrl($alias, $params);
};