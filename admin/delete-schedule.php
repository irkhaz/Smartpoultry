<?php 

// syarat memulai sesi harus login dulu
session_start();
ob_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['is_admin']) || empty($_SESSION['is_admin'])){
  header("location: ../index.php");
  exit;
}

if((time() - $_SESSION["last_login_time"]) > 60){
            
  // akan diarahkan kehalaman logout.php
  header("location: logout.php");
}

else {
  // jika ada aktivitas, maka update tambah waktu session
  $_SESSION["last_login_time"] = time();
}

include_once '../include/koneksi.php';

$id = $_GET['id'];
$sql = "DELETE FROM tbl_jadwal WHERE tbl_jadwal.id_jadwal = '{$id}'";

$result = mysqli_query($conn, $sql);

header('location: ../admin/view-data-schedule.php?pesan=hapus');
?>