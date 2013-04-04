<?
/**
 * @param string  $error   Ошибка
 * @param string  $message Сообщение
 * @param integer $code    Код ошибки
 * @param boolean $debug   Режим отладки
 */
?>
<?=json_encode(array(
	'code'    => $code,
	'error'   => $error,
	'message' => $message,
))?>