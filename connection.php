<?php

$servername = "mysql:host=localhost;dbname=myfr6168_dev003;charset=utf8"; 
$username = "root";
$password = "";

try{
	$conn = new PDO($servername, $username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//echo " Your IP address is => ".$IP;
	//echo " DB server is => ".$servername;
	
}catch(PDOException $error){
	echo "<h1>An error has ocurred while trying to connect to the DB!</h1>";
	echo $error ->getMessage();
}
?>