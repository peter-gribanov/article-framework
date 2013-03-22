<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title><?if(!empty($page_title)):?><?=$page_title?> - <?endif;?>StarHit</title>
	<link rel="shortcut icon" href="/favicon.ico">
	<?if(!empty($stylesheet)):?><?=$stylesheet?><?endif?>
	<?if(!empty($javascript)):?><?=$javascript?><?endif?>
	<?if(!empty($page_heads)):?><?=$page_heads?><?endif?>
</head>
<body>
<?=$content?>
</body>
</html>