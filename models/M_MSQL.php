<?php
require("configs/config.php");

class M_MSQL {

	private static $instance;

	private function __construct(){
		// Connect to DB
		$this->link = mysqli_connect(HOST, USER, PASSWORD, DB)
			or die(mysqli_error($this->link));
	}

	public function __destruct(){
		mysqli_close($this->link);
	}

	public static function GetInstance(){
		if(self::$instance == null)
			self::$instance = new M_MSQL();

		return self::$instance;
	}

	public function NumRows($query) {
		$result = mysqli_query($this->link, $query);
		$n = mysqli_num_rows($result);
		return $n;
	}

	// SELECT A,C,B FROM T1 UNION SELECT D,C,E FROM T2
	public function Select($query){
		$result = mysqli_query($this->link, $query);

		if(!$result)
			die(mysqli_error($this->link));

		$n = mysqli_num_rows($result);

		$rows = array();
		for($i = 0; $i < $n; $i++){
			$rows[] = mysqli_fetch_assoc($result);
		}

		return $rows;
	}

	//INSERT INTO T1 (A,B,C) VALUES (VA,VB,VC)
	public function Insert($table, $fields){
		$columns = array();
		$values = array();

		$table = mysqli_real_escape_string($this->link, $table);
		foreach ($fields as $field => $value) {
			$field = mysqli_real_escape_string($this->link, $field);

			$columns[] = $field;
			if($value == null) {
				$values[] = "NULL";
			} else {
				$value = mysqli_real_escape_string($this->link, $value);
				$values[] = "'$value'";
			}
		}
		$columns_str = implode(",", $columns);
		$values_str = implode(",", $values);

		$query = "INSERT INTO `$table` ($columns_str) VALUES ($values_str)";
		$result = mysqli_query($this->link, $query);

		if(!$result){
			die(mysqli_error($this->link));
		}

		return mysqli_insert_id($this->link);
	}

	//DELETE FROM T1 WHERE id = (SELECT id FROM T2 WHERE name = "george")
	public function Delete($table, $where){
		$table = mysqli_real_escape_string($this->link, $table);
		$query = "DELETE FROM `$table` WHERE $where";
		$result = mysqli_query($this->link, $query);
		if(!$result){
			die(mysqli_error($this->link));
		}

		return mysqli_affected_rows($this->link);
	}

	//UPDATE T1 SET f1=v1, f2=v2, f3=v3 WHERE
	public function Update($table, $fields, $where){
		$sets = array();
		$table = mysqli_real_escape_string($this->link, $table);
		foreach ($fields as $field => $value) {
			$field = mysqli_real_escape_string($this->link, $field);
			if($value == null)
			{
				$sets[] = "$field=NULL";
			}else{
				$value = mysqli_real_escape_string($this->link, $value);
				$sets[] = "$field='$value'";
			}
		}

		$sets_str = implode(",", $sets);
		$query = "UPDATE `$table` SET $sets_str WHERE $where";
		$result = mysqli_query($this->link, $query);

		if(!$result)
		{
			die(mysqli_error($this->link));
		}

		return mysqli_affected_rows($this->link);
	}
}