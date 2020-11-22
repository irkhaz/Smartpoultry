
<?php 
   // mengaktifkan session pada php
   session_start();
    
   // menghubungkan php dengan koneksi database
   include 'include/koneksi.php';
    
   // menangkap data yang dikirim dari form login
   $username = $_POST['username'];
   $password = $_POST['password'];

   // waktu model UNIX time() -> mengambil waktu dalam bentuk string time
   $_SESSION["last_login_time"] = time(); 

    
    
   // menyeleksi data user dengan username dan password yang sesuai
   $login = mysqli_query($conn,"select * from user where username='$username' and password='$password'");
   // menghitung jumlah data yang ditemukan
   $cek = mysqli_num_rows($login);

   
   // cek apakah username dan password di temukan pada database
   if($cek > 0){
      $data = mysqli_fetch_assoc($login);
       
      // cek jika user login sebagai admin
      if($data['is_admin']=="1"){
         // buat session login dan username
         $_SESSION['username'] = $username;
         $_SESSION['is_admin'] = "1";
         // alihkan ke halaman utama admin
         header("location: admin/index.php");
       
      // cek jika user login sebagai user
      }elseif($data['is_admin']=="0"){
         // buat session login dan username
         $_SESSION['username'] = $username;
         $_SESSION['is_admin'] = "0";
         // alihkan ke halaman utama user
         header("location: user/index.php");
      
      }
   }else{
      header("location:login.php?pesan=gagal");
      session_destroy();
   }
?>