<?self::extend('admin/layouts/html.tpl')?>

<?self::block('page_title', 'Анализ текста статьи')?>
<?self::block('stylesheet')?>
	<link rel="stylesheet" href="/admin_old/arteditor.css" type="text/css">
<?self::endblock()?>

<div class="b-words-freq">
	<?foreach($words as $word):?>
		<span class="b-word-freq-g<?=$word[1]?>"><?=$word[0]?></span> 
	<?endforeach;?>
</div>