<?self::extend('admin/layouts/html.tpl')?>

<?self::assign('page_title', 'Анализ текста статьи')?>
<?self::block('page_heads', false)?>
	<link rel="stylesheet" href="/admin_old/arteditor.css" type="text/css">
	<script type="text/javascript">
		$(function(){
			$('.b-arteditor-analyze .b-filter').change(function() {
				$('.b-arteditor-analyze .b-graph-block, .b-arteditor-analyze .b-words-list').hide();
				if ($(this).val() == 'informative') {
					$('.b-arteditor-analyze .b-graph-informative, .b-arteditor-analyze .b-words-list-informative').show();
				} else {
					$('.b-arteditor-analyze .b-graph-all, .b-arteditor-analyze .b-words-list-all').show();
				}
			});
		});
	</script>
<?self::endblock()?>

<div class="b-arteditor-analyze">
	<div class="b-graph">
		<h2>Частотность:</h2>
		<select name="filter" class="b-filter">
			<option value="all">Все</option>
			<option value="informative">Только информативные</option>
		</select>
		<?if($graph):?>
			<ul class="b-graph-block b-graph-all">
				<?foreach ($graph as $word => $value):?>
					<li>
						<div class="b-graph-word"><span title="<?=$word?>"><?=$word?></span></div>
						<div class="b-graph-freq"><?=$value[0]?></div>
						<div class="b-graph-percent">
							<div class="b-graph-progress" title="<?=$word?>: <?=$value[0]?> (<?=round($value[1], 2)?>%)" style="width: <?=round($value[1])?>%">&nbsp;</div>
						</div>
					</li>
				<?endforeach?>
			</ul>
		<?endif?>
		<?if($graph_filter):?>
			<ul class="b-graph-block b-graph-informative">
				<?foreach ($graph_filter as $word => $value):?>
					<li>
						<div class="b-graph-word"><span title="<?=$word?>"><?=$word?></span></div>
						<div class="b-graph-freq"><?=$value[0]?></div>
						<div class="b-graph-percent">
							<div class="b-graph-progress" title="<?=$word?>: <?=$value[0]?> (<?=round($value[1], 2)?>%)" style="width: <?=round($value[1])?>%">&nbsp;</div>
						</div>
					</li>
				<?endforeach?>
			</ul>
		<?endif?>
	</div>
	<div class="b-words-freq">
		<h2>Текст:</h2>
		<div class="b-words-list b-words-list-all">
			<?foreach($words as $word):?>
				<?if ($word[1]):?>
					<span class="b-word-freq-g b-word-freq-g<?=$word[1]?>" title="<?=$word[0]?>: <?=$word[2]?> (<?=round($word[3])?>%)"><?=$word[0]?></span>
				<?else:?>
					<span title="<?=$word[0]?>: <?=$word[2]?> (<?=round($word[3])?>%)"><?=$word[0]?></span>
				<?endif?> 
			<?endforeach;?>
		</div>
		<div class="b-words-list b-words-list-informative">
			<?foreach($words_filter as $word):?>
				<?if ($word[1]):?>
					<span class="b-word-freq-g b-word-freq-g<?=$word[1]?>" title="<?=$word[0]?>: <?=$word[2]?> (<?=round($word[3])?>%)"><?=$word[0]?></span>
				<?else:?>
					<span title="<?=$word[0]?>: <?=$word[2]?> (<?=round($word[3])?>%)"><?=$word[0]?></span>
				<?endif?> 
			<?endforeach;?>
		</div>
	</div>
</div>