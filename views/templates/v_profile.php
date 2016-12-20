<!--Профиль пользователя-->

<p><?=$user['login']?>

<? if($online) : ?>
	<em>Online</em>
<? endif; ?>
<a href="<?=l('login')?>">Выход</a>

</p>
<img src="img/imgresize/<?=$user['img_profile']?>" title="Avatar" alt="Avatar">
<a href="<?=l('page/update_avatar')?>">Изменить фото профиля</a>
<a href="<?=l('page/list_users')?>">Список пользователей</a>
<a href="<?=l('page/dialog')?>">Диалоги</a>

<form method="post">
	<input type="text" name="wall_msg">
	<input type="submit">
</form>


<ul>
<? if ($wall != null) : ?>
<? foreach ($wall as $notes) : ?>
	<li>
		<span>
			<a href="#"><?=$notes['name']?></a>
		</span>
		<img width="100" src="img/imgresize/<?=$notes['img_profile']?>" alt="">
		<?=$notes['msg']?>
		<em>
			<?=$notes['date']?>
		</em> <a href="#">DELETE</a>
	</li>
<? endforeach ; ?>
<? else : ?>
	<p>Тут нет еще ни одной записи!</p>
<? endif ;?>

</ul>