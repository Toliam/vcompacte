<?

require_once("models/M_Models.php");
require_once("models/M_Users.php");
require_once("models/M_Upload.php");

class C_Page extends C_Base {

	function __construct(){
		parent::__construct();
	}

	function action_index() {
		session_start();
		$this->title = "Home";

		$models = new M_Models();
		$mUsers = M_Users::GetInstance();
		// Очистка старых сессий.
		$mUsers->ClearSessions();
		$user = $mUsers->Get();

		if ($user == NULL)
			header("Location: /vcompacte/login");


		if($this->IsPost()){
			$models->addNote($user['id_user'], $user['id_user'], $_POST['wall_msg']);
			header("Refresh:0");
		}

		$walls = $models->getNotesTo($user['id_user']);
		//Online
		$isOnLine = $mUsers->IsOnline();

		//Display index page with all articles from variable $articles
		$this->content = $this->template("views/templates/v_profile.php", array("user"=>$user, "online"=>$isOnLine, "wall"=>$walls));
	}

	function action_list_users() {
		$this->title = "List Users";
		// Запуск сессии.
		session_start();

		$mUsers = M_Users::GetInstance();
		$mUsers->ClearSessions();
		$user = $mUsers->Get();

		if ($user == NULL)
			header("Location: /vcompacte/login");


		$all_users = $mUsers->listUsers();


		$this->content = $this->template("views/templates/v_list-users.php", array("user"=>$user, "all_users"=>$all_users));
	}

	function action_dialog() {
		$this->title = "Dialogs";
		session_start();
			

		$models = new M_Models();
		$mUsers = M_Users::GetInstance();
		$mUsers->ClearSessions();
		$user = $mUsers->Get();
		$username = $user['name'];

		if ($user == null)
			header("Location: /vcompacte/login");

		$id = $this->params[2];
		if($id != null){
			$check_id = $mUsers->checkUserID($id);
			if($check_id){
				$models->selectDialog($user['id_user'], $id);
			} else {
				echo "Невозможно создать диалог! Пользователь не существует!";
			}
		}


		// Вывод всех диалогов
		$all_dialogs = $models->selectAllDialogs($user['id_user']);

		$this->content = $this->template("views/templates/v_dialogs.php", array("name"=>$username,"dialogs"=>$all_dialogs));
	}

	function action_im() {
		$this->title = "Private messages";
		session_start();

		$models = new M_Models();
		$mUsers = M_Users::GetInstance();
		$mUsers->ClearSessions();
		$user = $mUsers->Get();
		

		$hash = $this->params[2];
		if($hash != NULL){
			$check_hash = $models->checkHash($user['id_user'], $hash);
			if(!$check_hash)
				header("Location: /vcompacte/page");
		}


		if($hash == NULL)
			header("Location: /vcompacte/page");

		if($this->IsPost()){
			if($_POST['text_msg'] != ''){
				$models->sendMessage($user['id_user'], $_POST['text_msg'], $hash);
				header("Location: /vcompacte/page/im/$hash");
			}

		}

		$all_messages = $models->selectAllMessages($hash);


		$this->content = $this->template("views/templates/v_dialogs-im.php", array("messages"=>$all_messages));
	}

	function action_update_avatar() {
		session_start();
		$this->title = "Update avatar";
		$name = $_FILES['file']['name'];
		$filesize = $_FILES['file']['size'];
		$filetype = $_FILES['file']['type'];
		$fileloc = $_FILES['file']['tmp_name'];

		$mUsers = M_Users::GetInstance();
		$user = $mUsers->Get();

		if ($user == NULL)
			header("Location: /vcompacte/login");

		if($this->IsPost()){
			$mUpload = M_Upload::GetInstance();
			//Upload($id, $name, $filesize, $filetype, $fileloc)
			$result = $mUpload->Upload($user['id_user'], $name, $filesize, $filetype, $fileloc);

			if($result){
				header("Location: /vcompacte/page");
			} else {
				die("Ошибка! <a href=\"/vcompacte/page/update_avatar\">Назад</a>");
			}
			
		}
		$this->content = $this->template("views/templates/v_upload_avatar.php", array());
	}

	function action_get_user() {
		session_start();
		$this->title = "Profile User";
		$id = (int)$id;
		// http://localhost/page/edit/2
		// там где 2 автоматически подставляется ключь id
		// а params[2] значит что это третий параметр в адресной строке =>
		// первый (controller) = page, второй (action) = edit, третий (id) = 2
		$id = $this->params[2];

		$mUsers = M_Users::GetInstance();
		// Дали текущего пользователя
		$user = $mUsers->Get();

		// Дали пользователя по айди
		$get_user = $mUsers->Get($id);

		if($get_user == null)
			header("Location: /vcompacte/page");
		
		
		$model = new M_Models();
		$walls = $model->getNotesTo($id);

		if ($user == NULL)
			header("Location: /vcompacte/login");

		//Online
		$isOnLine = $mUsers->IsOnline($id);

		// Add Note to wall
		if($this->IsPost()){
			$model->addNote($get_user['id_user'], $user['id_user'], $_POST['wall_msg']);
			header("Refresh:0");
		}

		$this->content = $this->template("views/templates/v_profile_get.php", array("user"=>$user,"get_user"=>$get_user, "wall"=>$walls, "online"=>$isOnLine));
	}

}