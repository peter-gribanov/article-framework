<?
/**
 * @param string $link  Ссылка на новость
 * @param string $title Заголовок новости
 * @param string $text  Текст новости
 */
$link = self::short_link($link);
?>
<a href="<?=$link?>" target="_blank" style="color:#f96800;margin-bottom:10px"><?=$title?></a>
<p><?=$text?> <a href="<?=$link?>" target="_blank" style="color:#f96800">Дальше</a></p>