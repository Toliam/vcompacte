<?php
include_once('M_MSQL.php');

//
// Менеджер пользователей
//
class M_Users {	
	private static $instance;	// экземпляр класса
	private $msql;				// драйвер БД
	private $sid;				// идентификатор текущей сессии
	private $uid;				// идентификатор текущего пользователя
	private $mlink;

	//
	// Получение экземпляра класса
	// результат	- экземпляр класса MSQL
	//
	public static function GetInstance()
	{
		if (self::$instance == null)
			self::$instance = new M_Users();
			
		return self::$instance;
	}

	//
	// Конструктор
	//
	public function __construct()
	{
		$this->msql = M_MSQL::GetInstance();
		$this->sid = null;
		$this->uid = null;
		$this->mlink = M_MSQL::GetInstance()->link;
	}
	
	//
	// Очистка неиспользуемых сессий
	// 
	public function ClearSessions() {
		$min = date('Y-m-d H:i:s', time() - 60 * 20); 			
		$t = "time_last < '%s'";
		$where = sprintf($t, $min);
		$this->msql->Delete('sessions', $where);
	}

	//
	// Авторизация
	// $login 		- логин
	// $password 	- пароль
	// $remember 	- нужно ли запомнить в куках
	// результат	- true или false
	//
	public function Login($login, $password, $remember = true)
	{
		// вытаскиваем пользователя из БД 
		$user = $this->GetByLogin($login);

		if ($user == null)
			return false;
		
		$id_user = $user['id_user'];
				
		// проверяем пароль
		if ($user['password'] != md5($password))
			return false;
				
		// запоминаем имя и md5(пароль)
		if ($remember)
		{
			$expire = time() + 3600 * 24 * 100;
			setcookie('login', $login, $expire);
			setcookie('password', md5($password), $expire);
		}		
				
		// открываем сессию и запоминаем SID
		$this->sid = $this->OpenSession($id_user);
		
		return true;
	}
	
	//
	// Выход
	//
	public function Logout()
	{
		setcookie('login', '', time() - 1);
		setcookie('password', '', time() - 1);
		unset($_COOKIE['login']);
		unset($_COOKIE['password']);
		unset($_SESSION['sid']);		
		$this->sid = null;
		$this->uid = null;
	}
						
	//
	// Получение пользователя
	// $id_user		- если не указан, брать текущего
	// результат	- объект пользователя
	//
	public function Get($id_user = null)
	{	
		// Если id_user не указан, берем его по текущей сессии.
		if ($id_user == null)
			$id_user = $this->GetUid();
			
		if ($id_user == null)
			return null;
			
		// А теперь просто возвращаем пользователя по id_user.
		$t = "SELECT * FROM users WHERE id_user = '%d'";
		$query = sprintf($t, $id_user);
		$result = $this->msql->Select($query);
		return $result[0];		
	}
	
	//
	// Получает пользователя по логину
	//
	public function GetByLogin($login)
	{	
		$t = "SELECT * FROM users WHERE login = '%s'";
		$query = sprintf($t, mysqli_real_escape_string($this->mlink, $login));
		$result = $this->msql->Select($query);
		return $result[0];
	}
			
	//
	// Проверка наличия привилегии
	// $priv 		- имя привилегии
	// $id_user		- если не указан, значит, для текущего
	// результат	- true или false
	//
	public function Can($priv, $id_user = null) {

		// Если значение пустое
		if ($id_user == null)
			$id_user = $this->GetUid();

		$q = "SELECT `privs`.`name` FROM `privs` JOIN `privs2roles` ON `privs2roles`.`id_priv`=`privs`.`id_priv` JOIN `users` ON `users`.`id_role`=`privs2roles`.`id_role` WHERE `users`.`id_user`='%d'";
		$query = sprintf($q, $id_user);
		$result = $this->msql->Select($query);

		// Получаем количество строк в бд для перебора всех прав пользователя
		$n = $this->msql->NumRows($query);

		//Перебираем все права которые есть у этого пользователя
		for($i=0;$i<$n;$i++){
			// Если совпадает даем права
			if($result[$i]['name'] == $priv){
				return true;
			}
		}

		// У вас нет прав
		return false;
	}

	//
	// Проверка активности пользователя
	// $id_user		- идентификатор
	// результат	- true если online
	//
	public function IsOnline($id_user = null) {

		// Если значение не указано
		if ($id_user == null)
			$id_user = $this->GetUid();


		$min = date('Y-m-d H:i:s', time() - 60 * 3);
		// Прикрепляем sid потому что у пользователя могло быть несколько сессий
		$t = "SELECT time_last FROM sessions WHERE id_user='%d'";
		$query = sprintf($t, $id_user);
		$result = $this->msql->Select($query);
		// Если несколько сессий берём последнюю
		$result_last = array_pop($result);

		// Проверка на оффлайн
		if($result_last['time_last'] < $min){
			return false;
		}
		// Если вы онлайн
		return true;
	}
	
	//
	// Получение id текущего пользователя
	// результат	- UID
	//
	public function GetUid()
	{	
		// Проверка кеша.
		if ($this->uid != null)
			return $this->uid;	

		// Берем по текущей сессии.
		$sid = $this->GetSid();
				
		if ($sid == null)
			return null;
			
		$t = "SELECT id_user FROM sessions WHERE sid = '%s'";
		$query = sprintf($t, mysqli_real_escape_string($this->mlink, $sid));
		$result = $this->msql->Select($query);
				
		// Если сессию не нашли - значит пользователь не авторизован.
		if (count($result) == 0)
			return null;
			
		// Если нашли - запоминм ее.
		$this->uid = $result[0]['id_user'];
		return $this->uid;
	}

	//
	// Функция возвращает идентификатор текущей сессии
	// результат	- SID
	//
	private function GetSid()
	{
		// Проверка кеша.
		if ($this->sid != null)
			return $this->sid;
	
		// Ищем SID в сессии.
		$sid = $_SESSION['sid'];
								
		// Если нашли, попробуем обновить time_last в базе. 
		// Заодно и проверим, есть ли сессия там.
		if ($sid != null)
		{
			$session = array();
			$session['time_last'] = date('Y-m-d H:i:s'); 			
			$t = "sid = '%s'";
			$where = sprintf($t, mysqli_real_escape_string($this->mlink, $sid));
			$affected_rows = $this->msql->Update('sessions', $session, $where);

			if ($affected_rows == 0)
			{
				$t = "SELECT count(*) FROM sessions WHERE sid = '%s'";		
				$query = sprintf($t, mysqli_real_escape_string($this->mlink, $sid));
				$result = $this->msql->Select($query);
				
				if ($result[0]['count(*)'] == 0)
					$sid = null;			
			}			
		}		
		
		// Нет сессии? Ищем логин и md5(пароль) в куках.
		// Т.е. пробуем переподключиться.
		if ($sid == null && isset($_COOKIE['login']))
		{
			$user = $this->GetByLogin($_COOKIE['login']);
			
			if ($user != null && $user['password'] == $_COOKIE['password'])
				$sid = $this->OpenSession($user['id_user']);
		}
		
		// Запоминаем в кеш.
		if ($sid != null)
			$this->sid = $sid;
		
		// Возвращаем, наконец, SID.
		return $sid;		
	}
	
	//
	// Открытие новой сессии
	// результат	- SID
	//
	private function OpenSession($id_user)
	{
		// генерируем SID
		$sid = $this->GenerateStr(10);
				
		// вставляем SID в БД
		$now = date('Y-m-d H:i:s'); 
		$session = array();
		$session['id_user'] = $id_user;
		$session['sid'] = $sid;
		$session['time_start'] = $now;
		$session['time_last'] = $now;				
		$this->msql->Insert('sessions', $session); 
				
		// регистрируем сессию в PHP сессии
		$_SESSION['sid'] = $sid;				
				
		// возвращаем SID
		return $sid;	
	}

	//
	// Генерация случайной последовательности
	// $length 		- ее длина
	// результат	- случайная строка
	//
	private function GenerateStr($length = 10) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  

		while (strlen($code) < $length) 
            $code .= $chars[mt_rand(0, $clen)];  

		return $code;
	}
	public function Registration($name, $login, $password, $confirm, $id_role = 3) {
		// Проверяем входящие данные
		$name = trim($name);
		$login = trim($login);
		$password = trim($password);
		// password confirmation
		$confirm = trim($confirm);
		$img = "20420-profile.jpg";

		// Переводим пароль в md5 кодировку
		$password = md5($password);
		$confirm = md5($confirm);

		// Подтверждение пароля
		if ($password != $confirm)
			return false;

		// Вытаскиваем пользователя из БД 
		$user = $this->GetByLogin($login);

		// Проверка на существования пользователя в бд
		if (!is_null($user))
			return false;

		$table = "users";
		$object = array("name" => $name, "login" => $login, "password" => $password, "img_profile" => $img, "id_role" => $id_role);

		$this->msql->Insert($table, $object);
		
		return true;
	}
	public function listUsers() {
		$query = "SELECT id_user, login, name FROM users";
		$users = $this->msql->Select($query);

		return $users;
	}
	public function checkUserID($id_user){
		$q = "SELECT id_user FROM users WHERE id_user = '%d'";
		$query = sprintf($q, $id_user);
		$result = $this->msql->Select($query);

		if(!$result)
			return false;

		return true;
	}
	// Нужно как-то по айди вычислить какие данные дать пользователя

}