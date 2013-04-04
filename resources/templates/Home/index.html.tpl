<? 
/**
 * @param array $pages Список страниц с новостями 
 */
?>
<?self::extend('layouts/default.html.tpl')?>
<section class="b-frame">
	<div class="b-scroll">
		<?foreach ($pages as $page):?>
			<div class="b-page">
				<?foreach ($page as $item):?>
					<article class="b-news-item">
						<h2>
							<a href="<?=$item['link']?>" target="_blank"><?=$item['title']?></a>
						</h2>
						<p><?=self::cut($item['text'], 300)?> <a href="<?=$item['link']?>" target="_blank">Дальше</a></p>
					</article>
				<?endforeach?>
			</div>
		<?endforeach?>
	</div>
</section>