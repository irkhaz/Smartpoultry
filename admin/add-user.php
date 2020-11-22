<?php

session_start();
ob_start();

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

$title = 'Add User';

error_reporting(E_ALL);
// memasukan koneksi sql, header admin dan nav admin
include_once '../include/koneksi.php';
include_once '../include/header_admin.php';
include_once '../include/nav_admin.php';


// Define variables and initialize with empty values
$username = $password = $confirm_password = '';
$username_err = $password_err = $confirm_password_err = '';

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "username tidak boleh kosong!!";
    }else{
        // Prepare a select statement
        $sql = "SELECT id_user FROM user WHERE username = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows > 0){
                    $username_err = " username sudah ada";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Error";
            }
        }
         
        // Close statement
        $stmt->close();
    }

    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = " password kosong";     
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = " password minimal harus 6 karakter.";
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = ' confirm password kosong';     
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = ' Password tidak sesuai';
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($rfid_err)){
        
        // Prepare an insert statement
        // $sql = "INSERT INTO user (username, password, is_admin) ";
        // $sql .= "VALUES (?, ?, ?)";

        $sql = "INSERT INTO user (username, password, is_admin) VALUES (?, ?, ?)";
         
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_password, $param_isadmin);
            
            // Set parameters
            $param_username = $username;
            //$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_password = $password;
            $param_isadmin = $_POST['role'];

            // $param_email = $email;
            //echo $param_username;
            //echo $param_password;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: view-data-user.php?pesan=tambah");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }

}

// Close connection
$conn->close();
?>
<head>
<style>
.warning {color: #FF0000;}
</style>
</head>

<body>
    <div class="container">
        <h2>Add User</h2>
        <p>Silahkan Masukan Data User Baru</P>
        <form id="form-user" class="needs-validation" novalidate method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Name :</label>
                <input class="form-control" type="text" id="username" name="username" placeholder="Username" value="<?php echo $username; ?>" required >
                    <span class="invalid-feedback">Username wajib diisi</span>
            </div>
            
            <div class="form-group">
                <label for="password">Password :</label>
                <input class="form-control" type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required>
                    <span class="invalid-feedback">Password tidak boleh kosong</span>
            </div>
            <div class="form-group">  
                <label for="confirm_password">Confirm Password :</label>
                <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>" required>
                    <span class="invalid-feedback">Konfirmasi password harus diisi</span>
                    <span class="error" style="color: red"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group">  
                <label>Hak Akses :</label>
                <select class="dropdown-toggle" id="role" name="role" style="width:100%; height:40px; margin-bottom:20px;" placeholder="pilih hak akses" required>
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

            <input type="submit" name="sign-up" class="btn btn-primary" value="Register">
            <a value="Batal" class="btn btn-light" href="../admin/view-data-user.php">Batal</a>
        </form>
        <br>
    </div>

</body>

</html>
<?php 
    include_once '../include/footer.php';
?>