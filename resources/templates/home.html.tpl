<? 
/**
 * @param string  $name   Имя пользователя
 * @param string  $avatar Аватар пользователя
 * @param integer $balls  Баллы пользователя
 * @param string  $rank   Ранг пользователя
 * @param booelan $admin  Пользователь администратор
 */
?>
<div class="b-card">
	<div class="b-invite-link h-fright">
		<a href="?action=rating">Посмотрите, кто из друзей уже прошел тест</a>
	</div>
	<div class="b-name"><?=$name?></div>
	<div class="b-box-pic">
		<img src="<?=$avatar?>" alt="<?=$name?>">
	</div>
	<div class="b-box-txt">
		<div class="h-overflow">
			<div class="b-box-txt-lbl">
				Ваш ранг: 
			</div>
			<div class="h-overflow">
				<p>
					<?if($rank):?>
						<strong><?=$rank?></strong><br>
						Хотите повысить ранг?<br>
						Исправьте результат, пройдя тест ещё раз.
					<?else:?>
						Вы еще не проходили тест
					<?endif;?>
				</p>
			</div>
		</div>
		<div class="h-overflow">
			<div class="b-box-txt-lbl">
				Ваш рейтинг:
			</div>
			<div class="h-overflow">
				<p>
					<strong><?=$balls?></strong> <?=self::rusform($balls, 'балл', 'балла', 'баллов')?> из 1083
				</p>
				<ul>
					<li>
						<a href="?action=rating">Рейтинг среди Ваших друзей</a>
					</li>
					<?if($admin):?>
					<li>
						<a href="?action=users">Рейтинг</a>
					</li>
					<li>
						<a href="?action=log">Статистика</a>
					</li>
					<?endif;?>
				</ul>
			</div>
		</div>
	</div>
</div>
<a href="?action=poll" class="b-button h-bold"><?if($balls):?>Улучшить результат<?else:?>Начать тест<?endif;?></a>
<a href="?action=invite" class="b-button">Пригласить друзей</a>