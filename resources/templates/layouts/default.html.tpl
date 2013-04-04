<?
/**
 * @param string $content Контент
 */
?>
<?self::extend('html.html.tpl')?>
<header>
	<a href="#" class="b-subscribe">Подписаться</a>
	<a href="<?=self::path('home_invite')?>" class="b-recommend">Рекомендовать</a>
</header>
<nav>
	<a href="#" class="b-previous">Предыдущие</a>
	<a href="#" class="b-next">Следующие</a>
</nav>
<?=$content?>