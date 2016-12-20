<?
require_once('configs/img_resize.php');
require_once('M_MSQL.php');

class M_Upload {
	private $msql;
	private static $instance;

	public static function GetInstance() {
		if (self::$instance == null)
			self::$instance = new M_Upload();
			
		return self::$instance;
	}

	public function __construct()
	{
		$this->msql = M_MSQL::GetInstance();
	}

	// $name = $_FILES['file']['name']
	private function CheckScripts($name){
		$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".php7");
		foreach ($blacklist as $item) {
		    if (preg_match("/$item\$/i", $name)) {
		        return false;
		    }
		}
		return true;
	}
	// $filesize = $_FILES['file']['size']
	private function CheckSize($filesize){
		if ($filesize > 600000) {
		    return false;
		}
		return true;
	}
	// $filetype = $_FILES['file']['type']
	private function CheckType($filetype){
		if ($filetype != "" && $filetype != 'image/jpeg' && $filetype != 'image/png' && $filetype != 'image/gif') {
		    return false;
		}
		return true;
	}

	// $fileloc = $_FILES['file']['tmp_name'];
	public function Upload($id, $name, $filesize, $filetype, $fileloc){

		$name = trim($name);
		$id = (int)$id;
		$filesize = trim($filesize);
		$filetype = trim($filetype);
		$fileloc = trim($fileloc);

		if(!$this->CheckScripts($name))
			return false;

		if(!$this->CheckSize($filesize))
			return false;

		if(!$this->CheckType($filetype))
			return false;

		$newfile = rand(1000, 100000)."-".$name;

    	$move = move_uploaded_file($fileloc, "img/".$newfile);

		if(!$move)
			return false;

        $resize = img_resize("img/" . $newfile, "img/imgresize/" . $newfile, 200, 150);

        if(!$resize)
        	return false;

        $fields = array("img_profile" => $newfile);
        $where = "id_user = '$id'";
        $result = $this->msql->Update("users", $fields, $where);

        if(!$result)
        	return false;

		return true;
	}


}