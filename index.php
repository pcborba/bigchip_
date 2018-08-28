<?php
	include('header.php');
?>	

<div>
	<h1 class="borba">UPLOAD DATASET</h1>
	<form class="wrapperContent" action="upload_dataset.php" method="post" enctype="multipart/form-data">
		<input type="file" name="fileToUpload" id="fileToUpload">
		<button class="button" type="submit" name="submit">"Upload"</button>
	</form>
	<h1 class="borba">DELETE DATASET</h1>
	<form class="wrapperContent" action="delete_dataset.php" method="post" enctype="multipart/form-data">
		<button class="button" type="submit" name="deletecontent">"Delete"</button>
	</form>


</div>

<?php
	include('footer.php');
?>			