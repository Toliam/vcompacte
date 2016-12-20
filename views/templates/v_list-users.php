<p>Вы вошли как <?=$user['name']?></p>

<ul>

<? foreach ($all_users as $u) : ?>
	<? if ($u['login'] == $user['login']) : ?>
		<? continue ?>
	<? endif ?>
	<? if ($u['login'] == $user['login']) : ?>
		<? continue ?>
	<? endif ?>
<li>
	<span>LOGIN:<?=$u['login']?></span>
	<span>NAME:<?=$u['name']?></span>
	<a href="<?=l('page/get_user/'.$u["id_user"])?>">Посмотреть страницу</a>
	<a href="<?=l('page/dialog/'.$u["id_user"])?>">Создать диалог с пользователем</a>
	<hr>
</li>


<? endforeach ?>

</ul>
