<?
/**
 * @param string  $error   Ошибка
 * @param string  $message Сообщение
 * @param integer $code    Код ошибки
 * @param boolean $debug   Режим отладки
 */
?>
<?self::extend('html.html.tpl')?>
<?self::assign('page_title', 'Ошибка: '.$error)?>
<div class="b-fatal-error">
	<h1><?=$error?></h1>
	<p><?=$code?>: <?=$message?></p>
</div>