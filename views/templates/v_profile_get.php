<!--Профиль пользователя по id-->

<p>Профиль пользователя <?=$get_user['name']?></p>
<p>Статус: 
<? if($online) : ?>
	Online
<? else : ?>
	Offline
<? endif ;?>
</p>
<p><a href="index.php">Домой</a></p>

<img src="../../img/imgresize/<?=$get_user['img_profile']?>" title="Avatar" alt="Avatar">
<p><a href="<?=l('page/dialog/'.$get_user['id_user'])?>">Написать сообщение</a></p>


<form method="post">
	<input type="text" name="wall_msg">
	<input type="submit">
</form>

<ul>
<? 
if ($wall != null) {
	foreach ($wall as $notes){
		echo "<li><span>".$notes['name']."</span>";
		echo "<img width='100' src='../../img/imgresize/".$notes['img_profile']."' alt='profile photo'>";
		echo $notes['msg']." ";
		echo "<em>".$notes['date']."</em></li>";
	}
} else {
	echo "<p>Тут нет еще ни одной записи!</p>";
}
?>
</ul>
