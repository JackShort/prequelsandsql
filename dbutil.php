<?php
class DbUtil{
	public static $loginUser = "jcs4ua"; 
	public static $loginPass = "copperhead12";
	public static $host = "cs4750.cs.virginia.edu"; // DB Host
	public static $schema = "jcs4ua"; // DB Schema
	
	public static function loginConnection(){
		$db = new mysqli(DbUtil::$host, DbUtil::$loginUser, DbUtil::$loginPass, DbUtil::$schema);
	
		if($db->connect_errno){
			echo("Could not connect to db");
			$db->close();
			exit();
		}
		
		return $db;
	}
	
}
?>