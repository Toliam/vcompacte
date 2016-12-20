<?

require_once('config.php');

function __autoload($classname) {
	require_once("controllers/$classname.php");
}


$info = array();
if(isset($_GET['q']))
	$info = explode('/', $_GET['q']); // для index.php?q=$0

$params = array();
foreach ($info as $v) {
	if ($v != '')
		$params[] = $v;
}


$action = "action_";
$action .= isset($params[1])?$params[1]:"index";

$c = "page";
if(isset($params[0])){
	$c = $params[0];
}


switch($c){
	case "page": $controller = new C_Page();break;
	case "login": $controller = new C_Login();break;
	default: $controller = new C_Page();
}

//$controller = new $c();

$controller->request($action, $params);