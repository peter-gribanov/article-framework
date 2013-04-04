<!doctype html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<title><?if(!empty($page_title)):?><?=$page_title?> &mdash; <?endif;?>Microsoft</title>
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="stylesheet" href="/main.css?1">
		<script type="text/javascript" src="/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="/main.js?85"></script>
		<?if(!empty($page_headers)):?><?=$page_headers?><?endif?>
	</head>
	<body>
		<?=$content?>
	</body>
</html>
