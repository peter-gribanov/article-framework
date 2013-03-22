<?php

/**
 * Хелпер вклучающий родительский шаблон
 *
 * @param string $template Шаблон
 */
return function ($template) use ($utility) {
	$utility->addTemplate($template);
};