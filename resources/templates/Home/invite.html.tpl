<?
/**
 * @param array $users Список пользователей
 */
?>
<?self::extend('layouts/default.html.tpl')?>
<?if($users):?>
	<form action="" method="post">
		<section class="b-frame">
			<div class="b-scroll">
				<div class="b-page">
					<?foreach($users as $key => $user):?>
						<?if($key && $key%5 == 0):?>
							</div>
							<div class="b-page">
						<?endif?>
						<label class="b-user">
							<a href="<?=$user['link']?>" target="_blank" class="b-u-avatar"><img src="<?=$user['avatar_big']?>" alt="<?=$user['name']?>"></a>
							<a href="<?=$user['link']?>" target="_blank" class="b-u-name"><?=$user['name']?></a>
							<span class="b-control">
								<input type="checkbox" name="id[<?=$user['id']?>]">
							</span>
						</label>
					<?endforeach?>
				</div>
			</div>
			<div class="b-control">
				<div class="b-f-control">
					<a href="/" class="b-button">Отмена</a>
					<button type="submit">Отправить</button>
				</div>
				<div class="b-select-control">
					<a href="#" class="b-select-all">Все</a> /
					<a href="#" class="b-select-none">Нет</a>
				</div>
			</div>
		</section>
	</form>
<?else:?>
	<div class="b-no-news">Некого пригласить</div>
<?endif?>