<?php 
	include('auth.php');
	
	function openConnection(){
		$dbhost = $GLOBALS['dbhost'];
		$dbuser = $GLOBALS['dbuser'];;
		$dbpass = $GLOBALS['dbpass'];
		$dbname = $GLOBALS['dbname'];
		$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname) or die("Connect failed: %s\n". $connection -> error);
		return $connection;
	}
 
	function closeConnection($connection){
		$connection -> close();
	}