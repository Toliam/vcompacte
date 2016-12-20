<h1>Все диалоги</h1>
<a href="<?=l('page/list_users')?>">Начать новый диалог</a>
<ul>
<? if($dialogs != null) : ?>
<? foreach($dialogs as $dialog) : ?>
<? if($name == $dialog['name']) : ?>
	<? continue ?>
<? endif ?>

	<li>
		<a href="<?=l('page/get_user/'.$dialog["id_user"])?>"><?=$dialog['name']?></a>
		<img src="../img/imgresize/<?=$dialog['img_profile']?>" width="50" alt="">
		<a href="<?=l('page/im/'.$dialog["hash"])?>">В ЛС с пользователем</a>
		<span>STATUS: <? if($dialog['status'] == null) echo"NEW";?></span>
	</li>

<? endforeach ; ?>
<? else : ?>
	<p>У вас еще нет диалогов, вы можете <a href="<?=l('page/list_users')?>">создать диалог</a></p>
<? endif ; ?>

</ul>