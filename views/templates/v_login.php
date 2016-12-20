<!--Индексная (index) страница с формой входа-->
<a href="<?=l('login/registration')?>">Зарегестрироваться</a>
<form method="post">
	<input type="text" name="login" placeholder="Login">
	<input type="password" name="password" placeholder="Password">
	<input type="checkbox" name="remember"> Запомнить?
	<input type="submit">
</form>