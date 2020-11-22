<?php
include_once '../include/koneksi.php';


if (isset($_GET['auto-mode'])) {
	$am = $_GET['auto-mode'];

	if(mysqli_query($conn,"UPDATE kontrol SET status=$am WHERE device='auto_mode'")){
		echo "BERHASIL";
	}else{
		echo "GAGAL";
	}
}

if (isset($_GET['fan1'])) {
	$fan1 = $_GET['fan1'];

	if(mysqli_query($conn,"UPDATE kontrol SET status=$fan1 WHERE device='fan1'")){
		echo "BERHASIL";
	}else{
		echo "GAGAL";
	}
}

if (isset($_GET['fan2'])) {
	$fan2 = $_GET['fan2'];

	if(mysqli_query($conn,"UPDATE kontrol SET status=$fan2 WHERE device='fan2'")){
		echo "BERHASIL";
	}else{
		echo "GAGAL";
	}
}

if (isset($_GET['lamp'])) {
	$lamp = $_GET['lamp'];

	if(mysqli_query($conn,"UPDATE kontrol SET status=$lamp WHERE device='lamp'")){
		echo "BERHASIL";
	}else{
		echo "GAGAL";
	}
}

if (isset($_GET['feeder'])) {
	$feeder = $_GET['feeder'];

	if(mysqli_query($conn,"UPDATE kontrol SET status=$feeder WHERE device='feeder'")){
		echo "BERHASIL";
	}else{
		echo "GAGAL";
	}
}
?>