<?php
$server = 'irkhaz.my.id';
$user = 'irkhazmy_irhas';
$pass = 'irkhazt676';
$db = 'irkhazmy_smartpoultry';

$conn = mysqli_connect($server,$user,$pass,$db);
if ($conn==false) {
	echo "koneksi server gagal";
	die();
}
?>