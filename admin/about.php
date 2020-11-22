<?php
  // session_start();
  // ob_start();
  
  // include "include/koneksi.php";

  // if(empty($_SESSION['username']) or empty($_SESSION['password'])){
  //    echo "<br><br><br><br><br><br><br><br><h1 align='center'> Anda harus login terlebih dahulu!</h1></br>?";
  //    echo "<meta http-equiv='refresh' content='3; url=login.php'>";
  // }else{
  //   define('INDEX', true);
session_start();
ob_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['is_admin']) || empty($_SESSION['is_admin'])){
  header("location: ../index.php");
  exit;
}

if((time() - $_SESSION["last_login_time"]) > 360){
            
  // akan diarahkan kehalaman logout.php
  header("location: logout.php");
}

else {
  // jika ada aktivitas, maka update tambah waktu session
  $_SESSION["last_login_time"] = time();
}

$title = 'About';
include_once('../include/header_admin.php');
include_once('../include/nav_admin.php');
?>
<div class="container cursive">
	<br>
	<h1 class="h1">TENTANG KAMI</h1><br>
  <img src = "../favicon.png" alt="" width="100px"><br><br>
	<p>SmartPoultry adalah sistem peternakan ayam berbasis Internet of Things yang memungkinkan pengguna untuk dapat memonitor dan mengontrol keadaan kandang ayam kapanpun dan dimanapun.</p>		
	<br>
</div>
<div class="clear"></div>
<?php
// require('include/sidebar.php'); 
include_once('../include/footer.php'); 
 ?>