<?php
	include "connection.php";
	


	$startDate=$_REQUEST['startDate'];
	$endDate=$_REQUEST['endDate'];

	$start = date("Y-m-d",strtotime($startDate));
	$end = date("Y-m-d",strtotime($endDate));

	$sql_read = "SELECT Accounts.title as 'GLAccountName', SUM(EntryItems.value) as 'TotalValue' FROM entries Entries INNER JOIN entry_items EntryItems ON Entries.id = EntryItems.entry_id INNER JOIN gl_accounts Accounts ON EntryItems.gl_id = Accounts.id WHERE Entries.date >= '".$start ."'  AND Entries.date <= '".$end."' GROUP BY Accounts.title ORDER BY 'TotalValue' DESC LIMIT 5";
	
	$data = $conn->query($sql_read);
	
	$result = array();
	

	while($row = $data->fetch(PDO::FETCH_OBJ)){
		$result[] = array(
			"GLAccountName"=>$row->GLAccountName, 
			"TotalValue"=>$row->TotalValue,
			);
	}
	
	
	header('Content-Type: application/json');

	$myJSON =   json_encode($result, JSON_NUMERIC_CHECK);

	echo $myJSON;
	$data = null;
	$conn = null;

?>