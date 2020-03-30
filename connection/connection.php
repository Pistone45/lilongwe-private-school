<?php
//connect to db
class Connection{
	public function dbConnection(){
		try{
			$connect = new PDO("mysql:host=localhost; dbname=lilongwe_private;port=3306",'root','');
			$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $connect;
		}catch(PDOException $e){
			echo 'ERROR: '.$e->getMessage();
		}
	}
}
?>