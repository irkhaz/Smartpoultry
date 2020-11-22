<?php

session_start();
ob_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['is_admin']) || empty($_SESSION['is_admin'])){
  header("location: ../login.php");
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

error_reporting(E_ALL | E_WARNING | E_NOTICE);
ini_set('display_errors', TRUE);
include_once('../include/koneksi.php');
$title = 'Edit User';


$id = $_GET['id'];
$sql = "SELECT * FROM user WHERE id_user = '{$id}'";
$result = mysqli_query($conn, $sql);
if (!$result) die('Error: Data not Available');

$data = mysqli_fetch_assoc($result);

function is_select($var, $val) {
    if ($var == $val) return 'selected="selected"';
    return false;
}

include_once '../include/header_admin.php';
include_once '../include/nav_admin.php';
?>

<head>
<style>
.warning {color: #FF0000;}
</style>
</head>

<body>
 <div class="container">
	<h2>Edit Data User</h2>
    <p>Silahkan Edit Data User yang Dipilih</p>

    <form class="needs-validation" novalidate action="proses-edit-user.php" method="post" enctype="multipart/form-data">

        <!-- id user -->
        <div class="form-group">
           <!-- <label> ID User <b style="color: red">*</b></label> -->
            <input class="form-control" type="hidden" name="id" value="<?php echo $data['id_user']; ?>"  readonly="readonly">
        </div>

		<!-- username -->
		<div class="form-group">
 			<label> Username <b style="color: red">*</b></label>
 			<input class="form-control" type="text" name="username" value="<?php echo $data['username']; ?>" required>
 			<span class="invalid-feedback">Username Tidak Boleh Kosong.</span>
 		</div>

        <!-- password -->
		<div class="form-group">
 			<label> Password <b style="color: red">*</b></label>
 			<input class="form-control" type="text" name="password" value="<?php echo $data['password']; ?>" required>
 			<span class="invalid-feedback">Password tidak boleh kosong.</span>
 		</div>

 		<!-- is_admin -->
  		<div class="form-group">
 			<label> Hak akses <b style="color: red">*</b></label>
 			<select class="dropdown-toggle" name="is_admin" style="width:100%; height:40px; margin-bottom:20px;" value="<?php if($data['is_admin']==1){echo 'Admin';}else{echo 'User';}; ?>" >
                <option value="1">Admin</option>
                <option value="0">User</option>
			</select>
	 	</div> 

		 <script>
                // Example starter JavaScript for disabling form submissions if there are invalid fields
                (function() {
                    'use strict';
                    window.addEventListener('load', function() {
                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        var forms = document.getElementsByClassName('needs-validation');

                        // Loop over them and prevent submission
                        var validation = Array.prototype.filter.call(forms, function(form) {
                            form.addEventListener('submit', function(event) {
                                if (form.checkValidity() === false) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                }
                                form.classList.add('was-validated');
                            }, false);
                        });
                    }, false);
                })();
                
            </script>

	 	<div class="submit">
	 		<input type="submit" name="submit" value="Submit" class="btn btn-primary" />
          	<a value="Batal" class="btn btn-light" href="../admin/view-data-user.php">Batal</a>
	 	</div>
	</form>
    <br>
 </div>
</body>
 <?php 
 	include_once '../include/footer.php'; 
  ?>
