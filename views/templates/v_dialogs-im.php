<h1>Личные сообщение</h1>
<a href="<?=l('page')?>">Профиль</a>
<ul>
	<? foreach ($messages as $message) : ?>
		<li>
			<span><?=$message['name']?></span>
			<img src="../../img/imgresize/<?=$message['img_profile']?>" width="50" alt="img"><br>
			<span><?=$message['msg']?></span><br>
			<em><?=$message['date']?></em>
		</li>
		<hr>
	<? endforeach ?>
</ul>
<form method="post">
	<textarea name="text_msg" placeholder="Message" cols="50" rows="4" autofocus maxlength="999"></textarea>
	<input type="submit">
</form>
 