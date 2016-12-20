<?
require_once("M_MSQL.php");

class M_Models {
	private $msql;

	public function __construct(){
		$this->msql = M_MSQL::GetInstance();
	}

	
	//
	// START Users WALL
	//

	public function getNotesTo($user_id){
// No tested
		$user_id = (int)$user_id;
		$q = "SELECT `users`.`name`, `users`.`img_profile`, `wall`.`msg`, `wall`.`date` FROM `users` JOIN `wall` ON `wall`.`id_user_from`=`users`.`id_user` WHERE `wall`.`id_user_to`='%d' ORDER BY wall.date DESC";
		$query = sprintf($q, $user_id);
		$result = $this->msql->Select($query);

		if(!$result)
			return false;

		return $result;
	}
	public function addNote($user_to, $user_from, $msg){
// No tested
		$user_to = (int)$user_to;
		$user_from = (int)$user_from;
		$msg = trim($msg);
		$msg = htmlspecialchars($msg);
		$date = date('Y-m-d H:i:s');

		$fields = array("id_user_to"=>$user_to, "id_user_from"=>$user_from, "msg"=>$msg, "date"=>$date);
		$result = $this->msql->Insert("wall", $fields);

		if(!$result)
			return false;

		return true;
	}


	public function deleteNote($id){
// No tested
		$id = (int)$id;
		$where = "id='$id'";
		$result = $this->msql->Delete("wall", $where);

		if(!$result)
			return false;

		return true;
	}

	//
	// END Users wall
	//





	//
	// START Messages
	//

	private function createDialog($author_id, $receiver_id){
		$author_id = (int)$author_id;
		$receiver_id = (int)$receiver_id;

		$hash = $this->randomHash();
		$hash = md5($hash);

		$fields = array("id_author"=>$author_id, "id_receiver"=>$receiver_id, "hash"=>$hash);
		$result = $this->msql->Insert("dialogs", $fields);

		if(!$result)
			return false;
		
		return true;
	}

	public function selectDialog($author_id, $receiver_id){
		// OR id_author == reciever_id AND reciever_id == id_author
		$q = "SELECT * FROM dialogs WHERE id_author='%d' AND id_receiver='%d' OR id_receiver='%d' AND id_author='%d'";
		$query = sprintf($q, $author_id, $receiver_id, $author_id, $receiver_id);

		$result = $this->msql->Select($query);

		if(!$result){
			$create = $this->createDialog($author_id, $receiver_id);
			if(!$create)
				return false;
		}

		return $result[0];
	}


	public function selectAllDialogs($user_id){
		$q = "SELECT users.id_user, dialogs.hash, dialogs.status, users.name, users.img_profile 
			FROM dialogs
			JOIN users ON users.id_user = dialogs.id_receiver OR users.id_user = dialogs.id_author
			WHERE dialogs.id_author='%d' OR dialogs.id_receiver='%d'";
		$query = sprintf($q, $user_id, $user_id);
		$result = $this->msql->Select($query);

		if(!$result)
			return false;

		return $result;
	}

	public function selectAllMessages($hash){
		$q = "SELECT messages.msg, messages.date, users.name, users.img_profile 
			FROM dialogs 
			JOIN messages ON dialogs.id = messages.dialog_id
			JOIN users ON messages.id_author = users.id_user WHERE dialogs.hash = '%s'";
		$query = sprintf($q, $hash);
		$result = $this->msql->Select($query);

		return $result;
	}


	public function sendMessage($author_id, $msg, $hash) {

		$author_id = (int)$author_id;
		$msg = substr( nl2br(htmlspecialchars(trim($msg))) ,0,1000);
		$date = date('Y-m-d H:i:s');
		// for GET hash we know id dialog
		$id = $this->checkDialID($hash);


		$fields = array("dialog_id" => $id, "id_author" => $author_id, "msg" => $msg, "date" => $date);
		$result = $this->msql->Insert("messages", $fields);

		if(!$result)
			return false;

		return true;
	}

	private function checkDialID($hash){
		$q = "SELECT id FROM dialogs WHERE hash = '%s'";
		$query = sprintf($q, $hash);
		$row = $this->msql->Select($query);

		return $row[0]['id'];
	}

	public function checkHash($id_author, $hash){
		$q = "SELECT hash FROM dialogs WHERE hash = '%s' AND id_author='%d' OR id_receiver='%d'";
		$query = sprintf($q, $hash, $id_author, $id_author);
		$result = $this->msql->Select($query);

		if(!$result)
			return false;

		return true;
	}

	private function randomHash($length = 15){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  

		while (strlen($code) < $length) 
            $code .= $chars[mt_rand(0, $clen)];  

		return $code;
	}

	//
	// END Messages
	//
}