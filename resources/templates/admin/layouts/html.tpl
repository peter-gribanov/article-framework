<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title><?if(!empty($page_title)):?><?=$page_title?> - <?endif;?>StarHit</title>
	<link rel="shortcut icon" href="/favicon.ico">
	<?if(!empty($page_heads)):?><?=$page_heads?><?endif?>
	<?/*self::block('page_heads') // альтернативный метод вывода блока с возможностью добавить к нему контент в другом шаблоне?>
		<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
	<?self::endblock()*/?>
</head>
<body>
<?=$content?>
</body>
</html>