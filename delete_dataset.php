<?php
    include('header.php');
?>	

<?php

require "connection.php";


echo '<div class="containerTable">';

$sql_insert = "DELETE FROM `entries`";
try{
    $stmt =  $conn->prepare($sql_insert);
    
    $stmt->execute();
    
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}

$sql_insert = "DELETE FROM `entry_items`";
try{
    $stmt =  $conn->prepare($sql_insert);
    
    $stmt->execute();
    
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}

$sql_insert = "DELETE FROM `gl_accounts`";
try{
    $stmt =  $conn->prepare($sql_insert);
    
    $stmt->execute();
    
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}

$sql_insert = "DELETE FROM `gl_categories`";
try{
    $stmt =  $conn->prepare($sql_insert);
    
    $stmt->execute();
    
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}

$sql_insert = "DELETE FROM `locations`";
try{
    $stmt =  $conn->prepare($sql_insert);
    
    $stmt->execute();
    
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}

$data = null;

echo "<br><br><br><p><b>All data deleted.</b></p><br><br><br><br><br><br>";
$conn = null;

echo '</div>';

?>


<?php
	include('footer.php');
?>			