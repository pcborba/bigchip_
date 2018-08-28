<?php
    include('header.php');
?>	

<?php


require 'lib/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$target_dir = "file/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


echo '<div class="containerTable">';
// Check if file already exists
if (file_exists($target_file)) {
    echo "<p> Sorry, file already exists. </p>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "xlsx") {
    echo "<p>Sorry, only XLSX files are allowed.</p>";
    die();
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<p>Sorry, your file was not uploaded.</p>";
// if everything is ok, try to upload file
} else {
    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<p>Sorry, there was an error uploading your file.</p>";
    } else {
        echo "<br><p>The file <b>". basename( $_FILES["fileToUpload"]["name"]). "</b> has been uploaded.</p><br>";
        echo "<p>Starting the importing process.</p><br><p>Uploading the sheets from your spreadsheet file <b>". basename( $_FILES["fileToUpload"]["name"]). "</b></p><br>";
        importData();
    }
}

function importData(){
    require "connection.php";

    $inputFile = "file/".basename( $_FILES["fileToUpload"]["name"]);
    $tablesCreated = 0;
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

    $spreadsheet = $reader->load($inputFile);
    $sheetData =  $spreadsheet->getActiveSheet()->toArray(null,true);
    $sheetEntries = $spreadsheet->getSheetByName('Entries');
    $sheetEntry_Items = $spreadsheet->getSheetByName('Entry_Items');
    $sheetGL_Accounts = $spreadsheet->getSheetByName('GL_Accounts');
    $sheetGL_Categories = $spreadsheet->getSheetByName('GL_Categories');
    $sheetLocations = $spreadsheet->getSheetByName('Locations');

    $formatDate = 'Y-m-d';
    $formatDateTime = 'Y-m-d H:i';

    foreach($sheetLocations->getRowIterator(2) as $row){
        $id=$sheetLocations->getCellByColumnAndRow(1, $row->getRowIndex());
        $location=$sheetLocations->getCellByColumnAndRow(2, $row->getRowIndex());

        $sql_insert = "INSERT INTO locations (id, location) VALUES (:ID, :LOCATION)";

        try{
            $stmt =  $conn->prepare($sql_insert);
            $stmt->bindParam(':ID', $id);
            $stmt->bindParam(':LOCATION', $location);
            $stmt->execute();
            $idLast = $conn->lastInsertId();
            $data = array("CREATE"=>"OK", "ID"=>$idLast);
        }catch(PDOException $e){
            echo "Error: ".$e->getMessage();
            $data = array("CREATE"=>"ERRO", "ID"=>$idLast);
        }
        $data = null;
    }
    $tablesCreated += 1;

    foreach($sheetGL_Categories->getRowIterator(2) as $row){
        $id=$sheetGL_Categories->getCellByColumnAndRow(1, $row->getRowIndex());
        $title=$sheetGL_Categories->getCellByColumnAndRow(2, $row->getRowIndex());
        
        $sql_insert = "INSERT INTO gl_categories (id, title) VALUES (:ID, :TITLE)";

        try{
            $stmt =  $conn->prepare($sql_insert);
            $stmt->bindParam(':ID', $id);
            $stmt->bindParam(':TITLE', $title);
            $stmt->execute();
            $idLast = $conn->lastInsertId();
            $data = array("CREATE"=>"OK", "ID"=>$idLast);
        }catch(PDOException $e){
            echo "Error: ".$e->getMessage();
            $data = array("CREATE"=>"ERRO", "ID"=>$idLast);
        }
        $data = null;
    }
    $tablesCreated += 1;

    foreach($sheetGL_Accounts->getRowIterator(2) as $row){
        $id=$sheetGL_Accounts->getCellByColumnAndRow(1, $row->getRowIndex());
        $category_id=$sheetGL_Accounts->getCellByColumnAndRow(2, $row->getRowIndex());
        $number=$sheetGL_Accounts->getCellByColumnAndRow(3, $row->getRowIndex());
        $title=$sheetGL_Accounts->getCellByColumnAndRow(4, $row->getRowIndex());
        
        $sql_insert = "INSERT INTO gl_accounts (id, category_id, number, title) VALUES (:ID, :CATEGORY_ID, :NUMBER, :TITLE)";

        try{
            $stmt =  $conn->prepare($sql_insert);
            $stmt->bindParam(':ID', $id);
            $stmt->bindParam(':CATEGORY_ID', $category_id);
            $stmt->bindParam(':NUMBER', $number);
            $stmt->bindParam(':TITLE', $title);
            $stmt->execute();
            $idLast = $conn->lastInsertId();
            $data = array("CREATE"=>"OK", "ID"=>$idLast);
        }catch(PDOException $e){
            echo "Error: ".$e->getMessage();
            $data = array("CREATE"=>"ERRO", "ID"=>$idLast);
        }
        $data = null;
    }
    $tablesCreated += 1;

    foreach($sheetEntries->getRowIterator(2) as $row){
        $id=$sheetEntries->getCellByColumnAndRow(1, $row->getRowIndex());
        $location_id=$sheetEntries->getCellByColumnAndRow(2, $row->getRowIndex());
        $dateInput=$sheetEntries->getCellByColumnAndRow(3, $row->getRowIndex())->getValue();
        $date = date($formatDate, \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($dateInput));
        $createdInput=$sheetEntries->getCellByColumnAndRow(4, $row->getRowIndex())->getValue();
        $created = date($formatDateTime, \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($createdInput));
        $updatedInput=$sheetEntries->getCellByColumnAndRow(5, $row->getRowIndex())->getValue();
        $updated = date($formatDateTime, \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($updatedInput));
        $type=$sheetEntries->getCellByColumnAndRow(6, $row->getRowIndex());
        $status=$sheetEntries->getCellByColumnAndRow(7, $row->getRowIndex());

        $sql_insert = "INSERT INTO entries (id, location_id, date, created, updated, type, status) VALUES (:ID, :LOCATION_ID, :DATE, :CREATED, :UPDATED, :TYPE, :STATUS)";

        try{
            $stmt =  $conn->prepare($sql_insert);
            $stmt->bindParam(':ID', $id);
            $stmt->bindParam(':LOCATION_ID', $location_id);
            $stmt->bindParam(':DATE', $date);
            $stmt->bindParam(':CREATED', $created);
            $stmt->bindParam(':UPDATED', $updated);
            $stmt->bindParam(':TYPE', $type);
            $stmt->bindParam(':STATUS', $status);
            
            $stmt->execute();
            $idLast = $conn->lastInsertId();
            $data = array("CREATE"=>"OK", "ID"=>$idLast);
        }catch(PDOException $e){
            echo "Error: ".$e->getMessage();
            $data = array("CREATE"=>"ERRO", "ID"=>$idLast);
        }
        $data = null;
    }
    $tablesCreated += 1;

    foreach($sheetEntry_Items->getRowIterator(2) as $row){
        $id=$sheetEntry_Items->getCellByColumnAndRow(1, $row->getRowIndex());
        $entry_id=$sheetEntry_Items->getCellByColumnAndRow(2, $row->getRowIndex());
        $gl_id=$sheetEntry_Items->getCellByColumnAndRow(3, $row->getRowIndex());
        $value=$sheetEntry_Items->getCellByColumnAndRow(4, $row->getRowIndex());
        $note=$sheetEntry_Items->getCellByColumnAndRow(5, $row->getRowIndex());
        
        $sql_insert = "INSERT INTO entry_items (id, entry_id, gl_id, value, note) VALUES (:ID, :ENTRY_ID, :GL_ID, :VALUE, :NOTE)";

        try{
            $stmt =  $conn->prepare($sql_insert);
            $stmt->bindParam(':ID', $id);
            $stmt->bindParam(':ENTRY_ID', $entry_id);
            $stmt->bindParam(':GL_ID', $gl_id);
            $stmt->bindParam(':VALUE', $value);
            $stmt->bindParam(':NOTE', $note);
            $stmt->execute();
            $idLast = $conn->lastInsertId();
            $data = array("CREATE"=>"OK", "ID"=>$idLast);
        }catch(PDOException $e){
            echo "Error: ".$e->getMessage();
            $data = array("CREATE"=>"ERRO", "ID"=>$idLast);
        }
        $data = null;
    }
    $tablesCreated += 1;

    

    echo "<p>The importing process was successfully finished. </p><br><p>We insert all the registers from ". $tablesCreated." sheets  from you spreadsheet file <b>". basename( $_FILES["fileToUpload"]["name"]). "</b></p><br>";
    $conn = null;
}
echo '</div>';

?>


<?php
	include('footer.php');
?>			