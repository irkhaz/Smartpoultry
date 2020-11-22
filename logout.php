<?php
    session_start();
    session_destroy();
    echo "<meta http-equiv='refresh' content='3; url=index.php'>";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Logging Out...</title>

	<link href="favicon.png" rel="icon">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
	<br><br>
	<br><br>
	<center>		
		<h1 style="float: center">Please Wait...</h1>
		<div class="spinner-grow text-muted"></div>
		<div class="spinner-grow text-primary"></div>
		<div class="spinner-grow text-success"></div>
		<div class="spinner-grow text-info"></div>
		<div class="spinner-grow text-warning"></div>
		<div class="spinner-grow text-danger"></div>
		<div class="spinner-grow text-secondary"></div>
		<div class="spinner-grow text-dark"></div>
		<div class="spinner-grow text-light"></div>	
	</center>
    </br>
</body>
</html>