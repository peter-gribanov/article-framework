<?php

/**
 * Хелпер завершаюший создание блока и сохраняющий результат в переменную шаблона
 */
return function () use ($utility) {
	$utility->endBuffering();
};