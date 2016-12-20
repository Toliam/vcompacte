<?
require_once("models/M_Users.php");

class C_Login extends C_Base {

	function __construct(){
		parent::__construct();
	}

	public function action_index() {
		$this->title = "Welcome";
		// Запуск сессии.
		session_start();

		// Менеджеры.
		$mUsers = M_Users::GetInstance();

		// Очистка старых сессий.
		$mUsers->ClearSessions();

		// Выход.
		$mUsers->Logout();

		// Обработка отправки формы.
		if ($this->IsPost()) {
			if ($mUsers->Login($_POST['login'], 
			                   $_POST['password'], 
							   isset($_POST['remember']))) {
				header('Location: /vcompacte/page');
				die();
			} else {
				echo "Пользователя не существует! Либо не правильно введены логин и пароль";
			}
		}
		$this->content = $this->template("views/templates/v_login.php", array());
	}

	public function action_registration() {
		$this->title = "Registration";
		// Запуск сессии.
		session_start();

		// Менеджеры.
		$mUsers = M_Users::GetInstance();

		// Выход.
		$mUsers->Logout();

		// Обработка отправки формы.
		if ($this->IsPost()) {
			if ($mUsers->Registration($_POST['r_name'], $_POST['r_login'], $_POST['r_password'], $_POST['r_confirm_password'])) {
				header('Location: /vcompacte/login');
				die();
			} else {
				echo "Ошибка! Что-то пошло не так! Придумайте другой логин либо правильно заполните подтверждение пароля";
			}
		}
		$this->content = $this->template("views/templates/v_registration.php", array());
	}


}