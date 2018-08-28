<?php
	include "connection.php";
	
	$startDate=$_REQUEST['startDate'];
	$endDate=$_REQUEST['endDate'];

	$start = date("Y-m-d",strtotime($startDate));
	$end = date("Y-m-d",strtotime($endDate));






	$sql_read = "SELECT Locations.location as 'LocationName', Accounts.title as 'GLAccountName', Categories.title as 'GLCategoryName', CONCAT('$ ',ROUND(AVG(EntryItems.value),2)) as 'AverageValue', CONCAT('$ ',ROUND(MAX(EntryItems.value),2)) as 'HighValue',
	CONCAT('$ ',ROUND(SUM(EntryItems.value),2)) as 'TotalValue' FROM entries Entries INNER JOIN locations Locations ON Entries.location_id = Locations.id INNER JOIN entry_items EntryItems ON Entries.id = EntryItems.entry_id INNER JOIN gl_accounts Accounts ON EntryItems.gl_id = Accounts.id INNER JOIN gl_categories Categories ON Accounts.category_id = Categories.id WHERE Entries.date >= '".$start ."' AND Entries.date <= '".$end."' GROUP BY Locations.location, Categories.title, Accounts.title ORDER BY Locations.location, Accounts.title";


	$data = $conn->query($sql_read);
	
	$result = array();
	
	
	while($row = $data->fetch(PDO::FETCH_OBJ)){
		
		$result[] = array(
			"LocationName"=>$row->LocationName,
			"GLCategoryName"=>$row->GLCategoryName, 
			"GLAccountName"=>$row->GLAccountName, 
			"AverageValue"=>$row->AverageValue,
			"HighValue"=>$row->HighValue,
			"TotalValue"=>$row->TotalValue,
			);
	}
	
	header('Content-Type: application/json');

	$myJSON =   json_encode($result, JSON_NUMERIC_CHECK);

	echo $myJSON;
	$data = null;
	$conn = null;




?>