<?php

abstract class C_Base extends C_Controller{
	protected $title;
	protected $content;
	protected $date;


	function __construct(){

	}

	protected function before(){
		$this->title="MyTitle";
		$this->content="MyContent";
	}

	public function render(){
		$params = array("title"=>$this->title, "main"=>$this->content);
		$page = $this->template("views/v_index.php", $params);

		echo $page;
	}
}