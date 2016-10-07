<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<h2>Input Data</h2>
	<br>
	<br>
	<form action="./show.php" method="POST" enctype="multipart/form-data">
		<label for="file">Pilih File</label>
		<input type="file" name="file" id="file" accept=".csv" />
		<br>
		<br>
		<input type="submit" name="cluster" id="cluster" value="cluster">
	</form>
</body>
</html>