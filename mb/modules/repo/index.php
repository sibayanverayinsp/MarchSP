<form method = "post" name = "upload" action = "uploadproject.php" enctype="multipart/form-data">
	<label id="lab">Upload A Project:</label><br>
	Repo ID: <input type="text" name="repoid" id="repoid">
	Parent ID: <input type="text" name="parent" id="parent">
	<input type="file" name="proj" id="proj" size="50">
	<input type="submit" name="submit" value="Upload!">
</form>