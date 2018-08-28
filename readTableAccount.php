<?php
	include "connection.php";
	
	$startDate=$_REQUEST['startDate'];
	$endDate=$_REQUEST['endDate'];

	$start = date("Y-m-d",strtotime($startDate));
	$end = date("Y-m-d",strtotime($endDate));


	$sql_read = "SELECT Accounts.title as 'GLAccountName' FROM gl_accounts Accounts";


	$data = $conn->query($sql_read);
	
	$result = array();
	
	
	while($row = $data->fetch(PDO::FETCH_OBJ)){
		
		$result[] = array(
			"GLAccountName"=>$row->GLAccountName, 
			);
	}
	
	header('Content-Type: application/json');

	$myJSON =   json_encode($result, JSON_NUMERIC_CHECK);

	echo $myJSON;
	$data = null;
	$conn = null;




?>