<?php
	include "connection.php";
	


	$startDate=$_REQUEST['startDate'];
	$endDate=$_REQUEST['endDate'];

	$start = date("Y-m-d",strtotime($startDate));
	$end = date("Y-m-d",strtotime($endDate));

	/*$sql_read = "SELECT Accounts.title as 'GLAccountName', AVG(EntryItems.value) as 'AverageValue', MAX(EntryItems.value) as 'HighValue', SUM(EntryItems.value) as 'TotalValue' FROM entries Entries INNER JOIN entry_items EntryItems ON Entries.id = EntryItems.entry_id INNER JOIN gl_accounts Accounts ON EntryItems.gl_id = Accounts.id WHERE Entries.date > '2018-06-30' AND Entries.date < '2018-07-10' GROUP BY Accounts.title ORDER BY AverageValue DESC LIMIT 5";*/


	$sql_read = "SELECT Accounts.title as 'GLAccountName', ROUND(AVG(EntryItems.value),2) as 'AverageValue', ROUND(MAX(EntryItems.value),2) as 'HighValue', ROUND(SUM(EntryItems.value),2) as 'TotalValue' FROM entries Entries INNER JOIN entry_items EntryItems ON Entries.id = EntryItems.entry_id INNER JOIN gl_accounts Accounts ON EntryItems.gl_id = Accounts.id WHERE Entries.date >= '".$start ."'  AND Entries.date <= '".$end."'  GROUP BY Accounts.title ORDER BY AverageValue DESC LIMIT 5";
	

	$data = $conn->query($sql_read);
	
	$result = array();
	

	while($row = $data->fetch(PDO::FETCH_OBJ)){
		$result[] = array(
			"GLAccountName"=>$row->GLAccountName, 
			"AverageValue"=>$row->AverageValue,
			"HighValue"=>$row->HighValue,
			"TotalValue"=>$row->TotalValue,
			);
	}
	
	
	header('Content-Type: application/json');

	$myJSON =   json_encode($result, JSON_NUMERIC_CHECK);
	//$myJSON =   json_encode($result);

	echo $myJSON;
	$data = null;
	$conn = null;

?>